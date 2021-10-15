<?php

namespace App\Controller;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
        
    }

    public function __invoke(Request $request)
    {
        $user = $request->get('data');
        $user->setPassword($this->hasher->hashPassword($user,$user->getPassword()));
        // $password = $user->getPassword();
        // $confirmPassword = $user->getConfirmPassword();
        // if ($password == $confirmPassword) {
            // $user->setConfirmPassword($user->getPassword());
        // } else {
        //     // $user->setPassword($this->hasher->hashPassword($user,$user->getPassword()));
        //     // $user->setConfirmPassword($confirmPassword);
        //     throw new Exception('les mots de passes ne correspondent pas');
        // }
        
        $data = $user;
        return $data;
        
    }
}