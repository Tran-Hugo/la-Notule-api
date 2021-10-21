<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use DateTimeImmutable;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;

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
        $supprImg = $request->request->get('supprImg');
        // throw new Exception($supprImg);
        // dd($categories);

        $previousCat = $book->getCategory()->getSnapshot();
        $newCat= $repo->findBy(['id'=>str_split($categories)]);
        // dd($newCat,$oldCat);
        
        $file = $request->files->get('file');
        $book->setTitle($title);
        $book->setAuthor($author);
        $book->setDescription($description);
        $book->setPrice($price);
        $book->setQuantity($quantity);
        foreach($previousCat as $cat){
            $book->removeCategory($cat);
        }
        foreach($newCat as $cat){
           $book->addCategory($cat); 
        }
        
        if($supprImg == "false"){
            $book->setFile($file);
        } else {
            $fileSystem = new Filesystem;
            $fileSystem->remove($this->getParameter('public')."/images/books/".$book->getFilePath());
            
            $book->setFilePath(null);
            $book->setFile(null);
        }
        
        
        $book->setUpdatedAt(new DateTimeImmutable());
        


        return $book;

    }
}