<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DiscordDefaultController extends AbstractController
{
    public function index()
    {
        return $this->render('discord_default/index.html.twig', [
            'controller_name' => 'DiscordDefaultController',
        ]);
    }
}
