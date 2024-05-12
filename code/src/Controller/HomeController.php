<?php

namespace App\Controller;

use App\Service\CookieJarService;
use App\Service\PzRconService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PzRconService $pzRcon, CookieJarService $cookieJarService, Request $request): Response
    {
        //TODO: get steam_user from cookie
        $steamUser = $request->cookies->get('steam_user', '');

        $playerStats = $pzRcon->getPlayerInfo();
        $playerCount = $playerStats['playerCount'];
        $players = $playerStats['players'];

        $cookieJar = $cookieJarService->getCookieJar();
        $isCookieJarAvailable = $cookieJarService->isCookieJarAvailable();

        $isoDate = $cookieJarService->nextCookieAvailable()->format('c'); // Converts to ISO 8601 format
        $nextCookieJarAvailable = $isoDate;

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'playerCount' => $playerCount,
            'players' => $players,
            'cookieJar' => $cookieJar,
            'isCookieJarAvailable' => $isCookieJarAvailable,
            'nextCookieJarAvailable' => $nextCookieJarAvailable,
            'steam_user' => $steamUser,
        ]);
    }
}
