<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrderRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
#[ApiResource(
    normalizationContext:['groups'=>['read:Order']],
    itemOperations:[
        'get',
        'delete'=>[
            'security'=>'is_granted("ROLE_ADMIN")',
            'openapi_context' => [
                'security' => [['bearerAuth'=>[]],
            ]
        ],
    ],
    ],
    collectionOperations:[
        'get'=>[
            "order" => ["id" => "DESC"]
        ]
    ]
)]
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['read:Order'])]
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Groups(['read:Order'])]
    private $User;


    /**
     * @ORM\Column(type="float")
     */
    #[Groups(['read:Order'])]
    private $price;

    /**
     * @ORM\Column(type="array")
     */
    #[Groups(['read:Order'])]
    private $products = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

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

    public function getProducts(): ?array
    {
        return $this->products;
    }

    public function setProducts(array $products): self
    {
        $this->products = $products;

        return $this;
    }
}
