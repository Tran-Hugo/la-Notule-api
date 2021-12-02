<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class GetByUserController extends AbstractController
{
    public function __construct(private Security $security)
    {
    }
    public function __invoke(Request $request)
    {
        $userId = $this->security->getUser()->getId();
        $role = $this->security->getUser()->getRoles()[0];
        $order = $request->get('data');
        $orderOwnerId = $order->getUser()->getId();

        // On vérifie l'id de l'utilisateur grace à token reçu puis on le compare avec l'id du propriétaire de la commande

        if (($userId === $orderOwnerId) || $role === "ROLE_ADMIN") {
            return $order;
        } else {
            $response = new Response();
            $response->setContent(json_encode([
                'message' => 'Ceci n\'est pas votre commande'
            ]));
            $response->setStatusCode(403);
            return $response;
        }
    }
}
