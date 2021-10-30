<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GetBookByCatController extends AbstractController
{
    #[Route('/api/bookByCat/{id}',name:'bookByCat',methods:['GET'])]
    public function getBook($id,BookRepository $bookRepo)
    {
        $books=$bookRepo->getBookByCat($id);
        return $this->json($books,200);
    }
}