<?php

namespace App\Controller;

use App\Service\CookieJarService;
use App\Service\PzRconService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    private PzRconService $pzRcon;
    private CookieJarService $cookieJarService;

    public function __construct(PzRconService $pzRcon, CookieJarService $cookieJarService)
    {
        $this->pzRcon = $pzRcon;
        $this->cookieJarService = $cookieJarService;
    }

    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
        $steamUser = $request->cookies->get('steam_user', '');

        $playerStats = $this->pzRcon->getPlayerInfo();
        $playerCount = $playerStats['playerCount'];
        $players = $playerStats['players'];

        $cookieJars = $this->cookieJarService->getCookieJarHistory();
        $isCookieJarAvailable = $this->cookieJarService->isCookieJarAvailable();

        $isoDate = $this->cookieJarService->nextCookieAvailable()->format('c'); // Converts to ISO 8601 format
        $nextCookieJarAvailable = $isoDate;

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'playerCount' => $playerCount,
            'players' => $players,
            'cookieJars' => $cookieJars,
            'isCookieJarAvailable' => $isCookieJarAvailable,
            'nextCookieJarAvailable' => $nextCookieJarAvailable,
            'steam_user' => $steamUser,
        ]);
    }


    #[Route('/api/players', name: 'api_players')]
    public function getPlayers()
    {
        $playerStats = $this->pzRcon->getPlayerInfo();
        // Get player data from your database or service
        $playerCount = $playerStats['playerCount'];
        $players = $playerStats['players'];

        return new JsonResponse([
            'playerCount' => $playerCount,
            'players' => $players
        ]);
    }
}
