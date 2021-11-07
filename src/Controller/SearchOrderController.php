<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchOrderController extends AbstractController
{
    public function __invoke(Request $request,OrderRepository $repo)
    {
        $search = json_decode($request->getContent(),true)['search'];
        $orders = $repo->searchedOrders($search);
        return $orders;
    }
}