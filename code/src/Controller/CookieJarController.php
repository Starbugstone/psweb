<?php

namespace App\Controller;

use App\Entity\CookieJar;
use App\Enum\CookieJarItemsEnum;
use App\Service\CookieJarService;
use App\Service\PzRconService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Pusher\Pusher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CookieJarController extends AbstractController
{

    private PzRconService $pzRcon;
    private CookieJarService $cookieJarService;
    private EntityManagerInterface $em;
    private ParameterBagInterface $params;

    public function __construct(PzRconService $pzRcon, CookieJarService $cookieJarService, EntityManagerInterface $em, ParameterBagInterface $params)
    {
        $this->pzRcon = $pzRcon;
        $this->cookieJarService = $cookieJarService;
        $this->em = $em;
        $this->params = $params;
    }

    #[Route('/cookie/jar/claim', name: 'app_cookie_jar_claim', methods: ['POST'])]
    public function claimCookie(Request $request): Response
    {
        $response = $this->redirectToRoute('app_home');

        $steamUser = $request->request->get('steam_user');
        if (empty($steamUser)) {
            $this->addFlash('error', 'You must enter your steam user to claim a cookie!');
            return $response;
        }

        //setting the cookie for future use
        $cookie = new Cookie(
            'steam_user',        // Cookie name
            $steamUser,                 // Cookie value
            '+5 days',            // Expire (5 days from now)
        );

        $response->headers->setCookie($cookie);

        $players = $this->pzRcon->getPlayerInfo()['players'];
        if (!in_array($steamUser, $players)) {
            $this->addFlash('error', 'You must be connected to the server to claim a cookie!');
            return $response;
        }

        if (!$this->cookieJarService->isCookieJarAvailable()) {
            $this->addFlash('error', 'The cookie jar is not available yet! Somebody probably snagged it before you!');
            return $response;
        }

        $reward = CookieJarItemsEnum::randomItem();

        try {
            $this->pzRcon->rewardItem($steamUser, $reward);
        } catch (Exception $e) {
            $this->addFlash('error', 'An error occurred while adding the cookie to your inventory!');
            return $response;
        }

        try {
            $cookie = new CookieJar();
            $cookie->setLastCookieWinner($steamUser)
                ->setLastCookieItem($reward)
                ->setLastCookieDateTime(new DateTime());
            $this->em->persist($cookie);
            $this->em->flush();
        } catch (Exception $e) {
            $this->addFlash('error', 'An error occurred while adding the cookie to the jar, but your item should still be rewarded in game!');
            return $response;
        }

        $this->addFlash('success', 'You have claimed a cookie!');

        $this->addPusherMessage($steamUser);

        return $response;
    }

    private function addPusherMessage(string $user): void
    {
        $options = array(
            'cluster' => $this->params->get('app.pusher_cluster'),
            'useTLS' => true
        );
        $pusher = new Pusher(
            $this->params->get('app.pusher_key'),
            $this->params->get('app.pusher_secret'),
            $this->params->get('app.pusher_app_id'),
            $options
        );

        $data['message'] = "$user has claimed a cookie!";
        $data['nextDate'] = $this->cookieJarService->nextCookieAvailable()->format('c');
        $pusher->trigger('pz-channel', 'cookie-claimed', $data);
    }
}
