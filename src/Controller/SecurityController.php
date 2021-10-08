<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SecurityController extends AbstractController
{
    #[Route('/api/login', name:'api_login', methods:['POST'])] /*ajouter path si ça fonctionne pas*/
    public function login(){
    }
}