<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrderRepository;
use App\Controller\GetOrderListByUser;
use App\Controller\GetByUserController;
use App\Controller\SearchOrderController;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
#[ApiResource(
    normalizationContext:['groups'=>['read:Order']],
    itemOperations:[
        'delete'=>[
            'security'=>'is_granted("ROLE_ADMIN")',
            'openapi_context' => [
                'security' => [['bearerAuth'=>[]],
            ]
        ],
    ],
        'getByUser'=>[
            'method'=>'get',
            'path'=>'/user/orders/{id}',
            'controller'=>GetByUserController::class,
            'security'=>'is_granted("IS_AUTHENTICATED_FULLY")',
            'openapi_context' => [
                'summary'=>'Permet de récuperer une commande en vérifiant l\'identité de la personne',
                'security' => [['bearerAuth'=>[]],
            ]
        ],
        ]
        ],
    collectionOperations:[
        'get'=>[
            "order" => ["id" => "DESC"]
        ],
        'getListByUser'=>[
            'method'=>'GET',
            'path'=>'/user/orders',
            'controller'=>GetOrderListByUser::class,
            'security'=>'is_granted("IS_AUTHENTICATED_FULLY")',
            'openapi_context' => [
                'summary'=>'Permet de récuperer les commandes d\'un utilisateur',
                'security' => [['bearerAuth'=>[]],
            ]
        ],
        ],
        'searchOrder'=>[
            'method'=>'POST',
            'path'=>'/search/order',
            'controller'=>SearchOrderController::class,
            'security'=>'is_granted("ROLE_ADMIN")',
            'openapi_context' => [
                'summary'=>'Permet de chercher les commandes par id email ou produits',
                'security' => [['bearerAuth'=>[]],
                ]
            ],
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
