<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EditBookController extends AbstractController
{
    public function __invoke(Request $request, CategoryRepository $repo)
    {   
        $book = $request->get('data');
        $title = $request->request->get('title');
        $author = $request->request->get('author');
        $description = $request->request->get('description');
        $price = $request->request->get('price');
        $quantity = $request->request->get('quantity');
        $categories = $request->request->get('categories');

        $previousCat = $book->getCategory()->getSnapshot();
        $previousCategories = [];
        foreach($previousCat as $cat){
            array_push($previousCategories,$cat->getId());
        };
        $oldCat = $repo->findBy(['id'=>$previousCategories]);
        
        $newCat= $repo->findBy(['id'=>str_split($categories)]);
        // dd($newCat,$oldCat);
        
        $file = $request->files->get('file');
        $book->setTitle($title);
        $book->setAuthor($author);
        $book->setDescription($description);
        $book->setPrice($price);
        $book->setQuantity($quantity);
        foreach($oldCat as $cat){
            $book->removeCategory($cat);
        }
        foreach($newCat as $cat){
           $book->addCategory($cat); 
        }
        $book->setFile($file);


        return $book;

    }
}