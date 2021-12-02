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
        $categories = $request->request->get('categories');
        foreach ($categories as  $category) {
            $category = $repo->findOneBy(['id' => $category]);
            $book->addCategory($category);
        }

        $file = $request->files->get('file');
        $book->setTitle($title);
        $book->setAuthor($author);
        $book->setDescription($description);
        $book->setPrice($price);
        $book->setQuantity($quantity);
        $book->setFile($file);


        return $book;
    }
}
