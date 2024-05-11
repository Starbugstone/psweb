<?php

namespace App\Controller;

use App\Service\PzRconService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PzRconService $pzRcon): Response
    {

        $playerStats = $pzRcon->getPlayerInfo();

        $playerCount = $playerStats['playerCount'];
        $players = $playerStats['players'];


        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'playerCount' => $playerCount,
            'players' => $players,
        ]);
    }
}
