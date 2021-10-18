<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BookRepository;
use App\Controller\AddBookController;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
#[ApiResource(
    denormalizationContext:['groups'=>['write:book']],
    collectionOperations:[
        'get',
        'post',
        'addBook'=>[
            'method'=>'POST',
            'path'=>'/books/new',
            'controller'=>AddBookController::class,
            'deserialize'=>false,
        ]
    ]
)]
class Book
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['write:book','read:cart'])]
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['write:book'])]
    private $author;

    /**
     * @ORM\Column(type="text")
     */
    #[Groups(['write:book'])]
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    #[Groups(['write:book'])]
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    #[Groups(['write:book'])]
    private $quantity;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="books", cascade={"persist"})
     */
    #[Groups(['write:book'])]
    private $category;


    /**
     * @ORM\OneToMany(targetEntity=CartItem::class, mappedBy="book", orphanRemoval=true)
     */
    private $cartItems;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->carts = new ArrayCollection();
        $this->cartItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

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

    /**
     * @return Collection|Category[]
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->category->removeElement($category);

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
            $cartItem->setBook($this);
        }

        return $this;
    }

    public function removeCartItem(CartItem $cartItem): self
    {
        if ($this->cartItems->removeElement($cartItem)) {
            // set the owning side to null (unless already changed)
            if ($cartItem->getBook() === $this) {
                $cartItem->setBook(null);
            }
        }

        return $this;
    }
}
