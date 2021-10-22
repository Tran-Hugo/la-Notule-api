<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchBookController extends AbstractController
{
    #[Route('/api/books/search', name:'searchBook',methods:["POST"])]
    public function __invoke(Request $request, BookRepository $repo)
    {
        $search = json_decode($request->getContent(),true)['search'];
        $books = $repo->searchedBook($search);
        
        return $this->json($books,200);
    }
}