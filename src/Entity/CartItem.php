<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Controller\PostItemController;
use App\Repository\CartItemRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CartItemRepository::class)
 */
#[ApiResource(
    collectionOperations:[
        'get',
        'post'=>[
            // 'controller'=>PostItemController::class,
            'openapi_context'=>[
                'summary'=>'Permet d\'ajouter un livre au panier',
                'requestBody'=>[
                    'content'=>[
                        'application/json'=>[
                            'schema'=>[
                                '$ref' => '#/components/schemas/cartItem'
                            ]
                        ]
                    ]
                ]
            ]
        ]
                            ],
        normalizationContext:['groups'=>['read:cartItem']]
)]
class CartItem
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['read:cart','read:cartItem'])]
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Cart::class, inversedBy="cartItems")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cart;

    /**
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="cartItems", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Groups(['read:cart','read:cartItem'])]
    private $book;

    /**
     * @ORM\Column(type="integer")
     */
    #[Groups(['read:cart','read:cartItem'])]
    private $quantity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
