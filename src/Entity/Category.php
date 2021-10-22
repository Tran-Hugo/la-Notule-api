<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
#[ApiResource(
    // normalizationContext:['groups'=>['read:category']],
    collectionOperations:[
        'get'=>['normalization_context' => ['groups' => 'read:category']],
        'post'=>[
            'security'=>'is_granted("ROLE_ADMIN")',
            'openapi_context' => [
                'security' => [['bearerAuth'=>[]],
            ]
        ],
    ],
    ],
    itemOperations:[
        'get'=>['normalization_context' => ['groups' => 'read:category']],
        'put'=>[
            'security'=>'is_granted("ROLE_ADMIN")',
            'openapi_context' => [
                'security' => [['bearerAuth'=>[]]]
            ]
        ],
        'delete'=>[
            'security'=>'is_granted("ROLE_ADMIN")',
            'openapi_context' => [
                'security' => [['bearerAuth'=>[]]]
            ]
        ],
        'patch'=>[
            'security'=>'is_granted("ROLE_ADMIN")',
            'openapi_context' => [
                'security' => [['bearerAuth'=>[]]]
            ]
        ]
    ]
)]
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['read:category','read:collection'])]
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['write:book','read:category','read:collection'])]
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Book::class, mappedBy="category")
     */
    #[Groups(['read:category'])]
    private $books;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Book[]
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books[] = $book;
            $book->addCategory($this);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->books->removeElement($book)) {
            $book->removeCategory($this);
        }

        return $this;
    }
}
