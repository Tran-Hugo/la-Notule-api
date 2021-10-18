<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddBookController extends AbstractController
{
    public function __invoke(Request $request, CategoryRepository $repo)
    {
        $book = new Book;
        $title = $request->request->get('title');
        $author = $request->request->get('author');
        $description = $request->request->get('description');
        $price = $request->request->get('price');
        $quantity = $request->request->get('quantity');
        $category = $request->request->get('category');
        $cat= $repo->findOneBy(['id'=>$category]);
        $file = $request->files->get('file');

        $book->setTitle($title);
        $book->setAuthor($author);
        $book->setDescription($description);
        $book->setPrice($price);
        $book->setQuantity($quantity);
        $book->addCategory($cat);
        $book->setFile($file);

        return $book;

    }
}