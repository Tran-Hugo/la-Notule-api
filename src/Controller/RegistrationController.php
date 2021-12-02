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
        if (strlen($user->getPassword()) > 0) {
            $user->setPassword($this->hasher->hashPassword($user, $user->getPassword()));
            $data = $user;
            return $data;
        } else {
            throw new Exception('not blank');
        }
    }
}
