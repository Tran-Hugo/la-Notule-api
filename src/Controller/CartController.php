<?php

namespace App\Controller;

use App\Entity\CartItem;
use App\Repository\BookRepository;
use App\Repository\CartItemRepository;
use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
        
    }

    #[Route('/cartItems/add', name:"addCartItem", methods:['POST','GET'])]
    public function addCartItem(Request $request, BookRepository $bookRepo, CartRepository $cartRepo,CartItemRepository $cartItemRepo)
    {
        // Permet de récuperer les infos POST
        // dd(json_decode($request->getContent(),true)['book']); 
        $book = $bookRepo->find(json_decode($request->getContent(),true)['book']);
        $cart = $cartRepo->find(json_decode($request->getContent(),true)['cart']);
        $quantity = json_decode($request->getContent(),true)['quantity'];
        $cartItemExist = $cartItemRepo->findBy(["book"=>$book,"cart"=>$cart]);
        
        if (count($cartItemExist)==0){
        $cartItem = new CartItem();
        $cartItem->setBook($book);
        $cartItem->setCart($cart);
        $cartItem->setQuantity($quantity);
        $cart->setTotal($cartItem->getBook()->getPrice()*$cartItem->getQuantity()+$cart->getTotal());
        $this->em->persist($cartItem);
        $this->em->flush();
        } else {
            $cartItem=$cartItemExist[0];
            $cart->setTotal($cartItem->getBook()->getPrice()*$cartItem->getQuantity()+$cart->getTotal());
            $cartItem->setQuantity($cartItem->getQuantity()+$quantity);
            $this->em->persist($cartItem);
            $this->em->flush();
        }

        $this->em->persist($cart);
        $this->em->flush();

        return $this->json($cartItem,201);
    }

    #[Route('/cartItems/minus', name:"minusCartItem", methods:["POST"])]
    public function minusCartItem(Request $request, CartItemRepository $cartItemRepo)
    {
        $cartItem = $cartItemRepo->find(json_decode($request->getContent(),true)['cartItemId']);
        $cart = $cartItem->getCart();

        if ($cartItem->getQuantity()>1){
            $cartItem->setQuantity($cartItem->getQuantity()-1);
            $cart->setTotal($cart->getTotal()-$cartItem->getBook()->getPrice());
            $this->em->persist($cartItem);
            $this->em->flush();
        } else if ($cartItem->getQuantity()==1) {
            $cart->setTotal($cart->getTotal()-$cartItem->getBook()->getPrice());
            $this->em->remove($cartItem);
            $this->em->flush();
        }

        $this->em->persist($cart);
        $this->em->flush();

        return $this->json($cartItem);
    }

    #[Route('/cartItems/plus', name:"plusCartItem", methods:["POST"])]
    public function plusCartItem(Request $request, CartItemRepository $cartItemRepo)
    {
        $cartItem = $cartItemRepo->find(json_decode($request->getContent(),true)['cartItemId']);
        $cart = $cartItem->getCart();

        $cartItem->setQuantity($cartItem->getQuantity()+1);
        $cart->setTotal($cart->getTotal()+$cartItem->getBook()->getPrice());
        $this->em->persist($cart);
        $this->em->persist($cartItem);
        $this->em->flush();
        
    }

    #[Route('/cartItems/delete/{id}', name:'deleteCartItem', methods:["DELETE"])]
    public function deleteCartItem($id, CartItemRepository $cartItemRepo)
    {
        $cartItem = $cartItemRepo->find($id);
        $cart = $cartItem->getCart();
        $cart->setTotal($cart->getTotal()-$cartItem->getBook()->getPrice()*$cartItem->getQuantity());
        
        $this->em->persist($cart);
        $this->em->remove($cartItem);
        $this->em->flush();

        return $this->json($cart,200);
    }
}