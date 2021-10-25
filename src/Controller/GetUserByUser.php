<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetUserByUser extends AbstractController
{
    public function __construct(private Security $security){}

    public function __invoke($id, UserRepository $repo)
    {
        $userId = $this->security->getUser()->getId(); //$userId est l'id retrouvé grâce au token et $id est l'id demandé dans la requête
        $role = $this->security->getUser()->getRoles()[0];

        //on compare les deux afin de vérifier si l'utilisateur a le droit d'avoir accès aux données demandées
        if(($userId == $id) || $role == "ROLE_ADMIN"){
            $user = $repo->find($id);
            return $user;
        } else {
            $response = new Response();
            $response->setContent(json_encode([
                'message'=>'Vous n\'avez pas le droit d\accèder à ces données'
            ]));
            $response->setStatusCode(403);
            return $response;
        }
    }
}