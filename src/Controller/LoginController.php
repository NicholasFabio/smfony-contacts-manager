<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Login;
use App\Repository\LoginRepository;

class LoginController extends AbstractController
{
    public function login(): Response
    {
        return $this->render('login.html.twig');
    }

    public function register(): Response
    {
        return $this->render('register.html.twig');
    }


}