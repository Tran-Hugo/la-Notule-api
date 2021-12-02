<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetCategoriesLimited extends AbstractController
{
    public function __invoke(CategoryRepository $repo)
    {
        $categories = $repo->findAll();
        $cat = array();
        $limit = 6; //cette variable permet de limiter les résultats par catégorie dans la page d'accueil
        foreach ($categories as $category) {
            $cat[] = array(
                'id' => $category->getId(),
                'name' => $category->getName(),
                'books' => array_slice($category->getBooks()->toArray(), 0, $limit),
            );
        };
        return $cat;
    }
}
