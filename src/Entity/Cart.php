<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CartRepository;
use App\Controller\GetCartController;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CartRepository::class)
 */
#[ApiResource(
    itemOperations:[
        'get'=>[
            'controller'=>GetCartController::class,
            'security'=>'is_granted("IS_AUTHENTICATED_FULLY")',
            'openapi_context' => [
                'summary'=>'Permet d\'avoir accès au panier d\'un utilisateur',
                'security' => [['bearerAuth'=>[]],
            ]
        ],
        ],
        'patch'
    ],
    collectionOperations:[
        'get'
    ],
    normalizationContext:['groups'=>['read:cart']]
)]
class Cart
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups('read:cart')]
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="cart", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['write:User','read:cart','read:User'])]
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity=CartItem::class, mappedBy="cart", orphanRemoval=true)
     */
    #[Groups('read:cart')]
    private $cartItems;

    /**
     * @ORM\Column(type="float", options={"default" : 0})
     */
    #[Groups(['read:cart','write:User'])]
    private $total;

    public function __construct()
    {
        $this->cartItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        // set the owning side of the relation if necessary
        if ($user->getCart() !== $this) {
            $user->setCart($this);
        }

        $this->user = $user;

        return $this;
    }


    public function getOwner(): ?string
    {
        return $this->owner;
    }

    public function setOwner(string $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|CartItem[]
     */
    public function getCartItems(): Collection
    {
        return $this->cartItems;
    }

    public function addCartItem(CartItem $cartItem): self
    {
        if (!$this->cartItems->contains($cartItem)) {
            $this->cartItems[] = $cartItem;
            $cartItem->setCart($this);
        }

        return $this;
    }

    public function removeCartItem(CartItem $cartItem): self
    {
        if ($this->cartItems->removeElement($cartItem)) {
            // set the owning side to null (unless already changed)
            if ($cartItem->getCart() === $this) {
                $cartItem->setCart(null);
            }
        }

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(?float $total): self
    {
        $this->total = $total;

        return $this;
    }
}
