<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\MessageType;
use App\Helper\MailingHelper;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends AbstractController {

    /**
     * @Route("/articles", name="articlesList")
     */
    public function displayPage(Request $request)
    {
        $form = $this->createForm(MessageType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //objet AdminUser contenant les infos entrées dans le formulaire
            $messageInfo = $form->getData();
            $sent = MailingHelper::sendMail($messageInfo['Nom'], $messageInfo['Adresse_mail'], $messageInfo['Sujet'], $messageInfo['Message']);
            if($sent) {
                return $this->render('validation.html.twig', ['validationMessage' => 'Le message a bien été envoyé.', 'contactForm' => $form->createView()]);
            }
            return $this->render('validation.html.twig', ['validationMessage' => 'Une erreur s\'est produite, veuillez réessayer.', 'contactForm' => $form->createView()]);
        }
        return $this->render('blog.html.twig', ['contactForm' => $form->createView()]);
    }
    
    /**
     * @Route("/articles/{id}", name="articleDisplay", requirements={"id"="\d+"}))
     */
    public function displayArticle(int $id, Request $request)
    {
        $form = $this->createForm(MessageType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //objet AdminUser contenant les infos entrées dans le formulaire
            $messageInfo = $form->getData();
            $sent = MailingHelper::sendMail($messageInfo['Nom'], $messageInfo['Adresse_mail'], $messageInfo['Sujet'], $messageInfo['Message']);
            if($sent) {
                return $this->render('validation.html.twig', ['validationMessage' => 'Le message a bien été envoyé.', 'contactForm' => $form->createView()]);
            }
            return $this->render('validation.html.twig', ['validationMessage' => 'Une erreur s\'est produite, veuillez réessayer.', 'contactForm' => $form->createView()]);
        }
        return $this->render('article.html.twig', ['contactForm' => $form->createView()]);
    }

}