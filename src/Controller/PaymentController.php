<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Manager\CartManager;
use App\Repository\UserRepository;
use App\Repository\CartItemRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsController]
class PaymentController extends AbstractController
{   
    public function __construct(private Security $security){}

    #[Route('/payment/{id}', name:'payment', methods:['GET']),Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function payment(CartManager $cartManager, Cart $cart)
    {
        $userId = $this->security->getUser()->getId();
        $user = $cart->getUser();
        $cartOwnerId = $user->getId();
        if($userId==$cartOwnerId){ //empêche de créer l'intention de paiement d'un panier qui nous appartient pas
            $intentSecret = $cartManager->intentSecret($cart);
            $data = [
                'user'=>$user,
                'intentSecret'=>$intentSecret,
                'cart'=>$cart
            ];
            return $this->json(
                $data
            );
        } else {
            $response = new Response();
            $response->setContent(json_encode([
                'message'=>'Votre identifiant ne correspond pas à celui du propriétaire du panier'
            ]));
            $response->setStatusCode(403);
            return $response;
        }
        
    }

    #[Route('/payment/{id}/subscription', name:'subscription', methods:['POST','GET'])]
    public function subscription($id,Cart $cart, Request $request, CartManager $cartManager, UserRepository $repo,CartItemRepository $cartItemRepo)
    {   
        $user = $repo->find($id);
        $products = $cartItemRepo->findBy(['cart'=>$id]);
        $data = ['cart'=>$cart];
        if($request->getMethod()==="POST") {
            $resource = $cartManager->stripe(json_decode($request->getContent(), true), $cart);
            if($resource !== null){
                $cartManager->create_subscription($cart,$user,$products);
                return $this->json($data,201);
            }
        }
    }
}