<?php

namespace App\Controller;

use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetCartController extends AbstractController
{
    public function __construct(private Security $security){}

    public function __invoke($id,CartRepository $repo, EntityManagerInterface $em)
    {
        $userId = $this->security->getUser()->getId();

        if($userId == $id){
            $cart = $repo->find($id);
            $cartItems = $cart->getCartItems();
            $total = 0; // Ici on vérifie le total du panier en prenant en compte tout éventuel changement de prix puis on l'envoie en base de données
            foreach($cartItems as $cartitem){
               $total += $cartitem->getBook()->getPrice()*$cartitem->getQuantity();
            };
            $cart->setTotal($total);
            $em->persist($cart);
            $em->flush();
            
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