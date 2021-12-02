<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetOrderListByUser extends AbstractController
{
    public function __construct(private Security $security)
    {
    }


    public function __invoke(OrderRepository $repo)
    {
        $user = $this->security->getUser();
        $data = $repo->findBy(['User' => $user], ['id' => 'DESC']);

        return $data;
    }
}
