<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController {

    /**
     * @Route("/articles", name="articlesList")
     */
    public function displayPage()
    {
        return $this->render('blog.html.twig');
    }
    
    /**
     * @Route("/articles/{id}", name="articleDisplay", requirements={"id"="\d+"}))
     */
    public function displayArticle(int $id)
    {
        return $this->render('article.html.twig');
    }

}