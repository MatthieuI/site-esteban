<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\MessageType;

class BlogController extends AbstractController {

    /**
     * @Route("/articles", name="articlesList")
     */
    public function displayPage()
    {
        $form = $this->createForm(MessageType::class);
        return $this->render('blog.html.twig', ['contactForm' => $form->createView()]);
    }
    
    /**
     * @Route("/articles/{id}", name="articleDisplay", requirements={"id"="\d+"}))
     */
    public function displayArticle(int $id)
    {
        $form = $this->createForm(MessageType::class);
        return $this->render('article.html.twig', ['contactForm' => $form->createView()]);
    }

}