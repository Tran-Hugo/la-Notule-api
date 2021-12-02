<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EditUserController extends AbstractController
{
    public function __construct(private Security $security)
    {
    }
    #[Route('api/user/edit', name: 'editUser', methods: ['patch'])]
    public function editUser(Request $request, UserRepository $repo, EntityManagerInterface $em)
    {
        $userId = $this->security->getUser()->getId();
        $user = $repo->find($userId);
        $data = json_decode($request->getContent(), true);
        $data['firstname'] ? $user->setFirstname($data['firstname']) : null;
        $data['lastname'] ? $user->setLastname($data['lastname']) : null;
        $data['adress'] ? $user->setAdress($data['adress']) : null;

        $em->persist($user);
        $em->flush();
        return new Response("edited", 200);
    }
}
