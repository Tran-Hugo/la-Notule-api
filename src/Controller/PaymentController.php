<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Manager\CartManager;
use App\Repository\CartItemRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentController extends AbstractController
{   
    #[Route('/payment/{id}', name:'payment', methods:['POST','GET'])]
    public function payment($id,CartManager $cartManager, Cart $cart, UserRepository $repo)
    {
        $user = $repo->find($id);
        $intentSecret = $cartManager->intentSecret($cart);
        $data = [
            'user'=>$user,
            'intentSecret'=>$intentSecret,
            'cart'=>$cart
        ];
        return $this->json(
            $data
        );
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
                // $cartManager->create_subscription($cart,$user);
                $cartManager->create_subscription($cart,$user,$products);
                return $this->json($data,201);
            }
        }
    }
}