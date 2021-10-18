<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BookRepository;
use App\Controller\AddBookController;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 * @Vich\Uploadable()
 */
#[ApiResource(
    normalizationContext:['groups'=>['read:collection']],
    denormalizationContext:['groups'=>['write:book']],
    collectionOperations:[
        'get',
        'post',
        'addBook'=>[
            'method'=>'POST',
            'path'=>'/books/new',
            'controller'=>AddBookController::class,
            'deserialize'=>false,
            'security'=>'is_granted("ROLE_ADMIN")'
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
    #[Groups(['write:book','read:cart','read:collection','read:category'])]
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['write:book','read:collection'])]
    private $author;

    /**
     * @ORM\Column(type="text")
     */
    #[Groups(['write:book','read:collection'])]
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    #[Groups(['write:book','read:collection'])]
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    #[Groups(['write:book','read:collection'])]
    private $quantity;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="books", cascade={"persist"})
     */
    #[Groups(['write:book','read:collection'])]
    private $category;


    /**
     * @ORM\OneToMany(targetEntity=CartItem::class, mappedBy="book", orphanRemoval=true)
     */
    private $cartItems;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="post_image", fileNameProperty="filePath")
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filePath;
    
    /**
     * @var string|null
     */
    #[Groups('read:collection')]
    private $fileUrl;

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

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): self
    {
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * Get the value of file
     *
     * @return  File|null
     */ 
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set the value of file
     *
     * @param  File|null  $file
     *
     * @return  self
     */ 
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get the value of fileUrl
     */ 
    public function getFileUrl()
    {
        return $this->fileUrl;
    }

    /**
     * Set the value of fileUrl
     *
     * @return  self
     */ 
    public function setFileUrl($fileUrl)
    {
        $this->fileUrl = $fileUrl;

        return $this;
    }
}
