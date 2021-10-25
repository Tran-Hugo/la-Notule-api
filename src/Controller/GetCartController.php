<?php

namespace App\Controller;

use App\Repository\CartRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetCartController extends AbstractController
{
    public function __construct(private Security $security){}

    public function __invoke($id,CartRepository $repo)
    {
        $userId = $this->security->getUser()->getId();

        if($userId == $id){
            $cart = $repo->find($id);
            return $cart;
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