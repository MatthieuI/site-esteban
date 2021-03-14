<?php

namespace App\Controller;

use App\Entity\AdminUser;
use App\Entity\Appointment;
use App\Entity\Article;
use App\Form\AdminType;
use App\Form\ArticleType;
use App\Form\ArticleModifierType;
use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Form\ChangePasswordType;
use App\Form\ArticlePreviewType;
use Symfony\Component\HttpFoundation\Response;

class BackOfficeController extends AbstractController
{

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("/back-office", name="backLogin")
     */
    public function displayLoginForm(Request $request)
    {
        //verification de la présence d'une session --> si session retour accueil
        if ($this->session->get('userName')) {
            return $this->redirectToRoute('backLandingPage');
        }

        $admin = new AdminUser();
        $form = $this->createForm(AdminType::class, $admin);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //objet AdminUser contenant les infos entrées dans le formulaire
            $adminInfo = $form->getData();

            $repository = $this->getDoctrine()->getRepository(AdminUser::class);
            $res = $repository->findBy(
                ['userName' => $adminInfo->getUserName()]
            );
            if ($res) {
                if ($res[0]->getPassword() === $adminInfo->getPassword()) {
                    $this->session->start();
                    $this->session->set('userName', $res[0]->getUserName());
                    return $this->redirectToRoute('backLandingPage');
                }
            }
            return $this->render('backLogin.html.twig', [
                'adminForm' => $form->createView()
            ]);
        }

        return $this->render('backLogin.html.twig', [
            'adminForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/back-office/accueil", name="backLandingPage")
     */
    public function displayHome()
    {
        //verification de la présence d'une session --> retour au formulaire si pas de session
        if (!$this->session->get('userName')) {
            return $this->redirectToRoute('backLogin');
        }
        return $this->render('backHome.html.twig', ['userName' => $this->session->get('userName')]);
    }

    /**
     * @Route("/back-office/logout", name="backLogout")
     */
    public function logout()
    {
        $this->session->clear();
        return $this->redirectToRoute('backLogin');
    }

    /**
     * @Route("/back-office/clients", name="backClientList")
     */
    public function displayClientList()
    {
        //verification de la présence d'une session --> retour au formulaire si pas de session
        if (!$this->session->get('userName')) {
            return $this->redirectToRoute('backLogin');
        }

        $repository = $this->getDoctrine()->getRepository(Client::class);
        $clients = $repository->findAll();

        return $this->render('backClientList.html.twig', ['clients' => $clients]);
    }

    /**
     * @Route("/back-office/articles/new", name="backArticleEditor")
     */
    public function displayArticleEditor(Request $request)
    {
        //verification de la présence d'une session --> retour au formulaire si pas de session
        if (!$this->session->get('userName')) {
            return $this->redirectToRoute('backLogin');
        }

        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $articleInfo = $form->getData();
            if ($form->get('Preview')->isClicked()) {
                $articleContent = $articleInfo->getHtmlBody();
                $article->setHtmlBody(str_replace("playlist", "embed/videoseries", $articleContent));
                $previewForm = $this->createForm(ArticlePreviewType::class, $article, [
                    'title' => $article->getTitle(),
                    'body' => $article->getHtmlBody(),
                    'abstract' => $article->getAbstract()
                ]);
                return $this->render('test.html.twig', [
                    'article' => $articleInfo->getHtmlBody(),
                    //'previewForm' => $previewForm->createView()
                ]);
            }
            if ($form->get('Save')->isClicked()) {
                $articleContent = $article->getHtmlBody();
                $article->setHtmlBody(str_replace("playlist", "embed/videoseries", $articleContent));
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($article);
                $entityManager->flush();
                return new Response('Saved new article with id ' . $article->getId());
            }
        }

        return $this->render('backArticleEditor.html.twig', ['articleForm' => $form->createView()]);
    }

    /**
     * @Route("/back-office/articles", name="backArticleList")
     */
    public function displayBackArticleList(Request $request)
    {
        //verification de la présence d'une session --> retour au formulaire si pas de session
        if (!$this->session->get('userName')) {
            return $this->redirectToRoute('backLogin');
        }

        $repository = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repository->findAll();

        return $this->render('backArticleList.html.twig', ['articles' => $articles]);
    }

    /**
     * @Route("/back-office/articles/modify/{id}", name="backArticleModifier", requirements={"id"="\d+"})
     */
    public function displayBackArticleModifier(int $id, Request $request)
    {
        //verification de la présence d'une session --> retour au formulaire si pas de session
        if (!$this->session->get('userName')) {
            return $this->redirectToRoute('backLogin');
        }

        $repository = $this->getDoctrine()->getRepository(Article::class);
        $article = $repository->find($id);
        $form = $this->createForm(ArticleModifierType::class, $article, [
            'title' => $article->getTitle(),
            'body' => $article->getHtmlBody(),
            'abstract' => $article->getAbstract()
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $articleInfo = $form->getData();
            if ($form->get('Preview')->isClicked()) {
                $articleContent = $articleInfo->getHtmlBody();
                $article->setHtmlBody(str_replace("playlist", "embed/videoseries", $articleContent));
                $previewForm = $this->createForm(ArticlePreviewType::class, $article, [
                    'title' => $article->getTitle(),
                    'body' => $article->getHtmlBody(),
                    'abstract' => $article->getAbstract()
                ]);
                return $this->render('test.html.twig', [
                    'article' => $articleInfo->getHtmlBody(),
                    //'previewForm' => $previewForm->createView()
                ]);
            }
            if ($form->get('Save')->isClicked()) {
                $articleContent = $article->getHtmlBody();
                $article->setHtmlBody(str_replace("playlist", "embed/videoseries", $articleContent));
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();
                return new Response('Updated article with id ' . $article->getId());
            }
        }

        return $this->render('backArticleModifier.html.twig', ['articleForm' => $form->createView()]);
    }

    /**
     * @Route("/back-office/articles/delete", name="deleteArticle", methods="post")
     */
    public function deleteArticle(Request $request)
    {
        $articles = $request->request->get('delete');
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $message = '<p>Deleted articles with ids :</p><ul>';
        foreach ($articles as $article) {
            $item = $repository->find(intval($article));
            $entityManager->remove($item);
            $entityManager->flush();
            $message .= "<li>$article</li>";
        }
        $message .= "</ul>";
        return $this->redirectToRoute('backArticleList');
    }

    // /**
    //  * @Route("/back-office/articles/preview", name="previewArticle")
    //  */
    // //, methods="post"
    // public function previewArticle(Request $request)
    // {
    //     // $articles = $request->request->get('delete');
    //     // $entityManager = $this->getDoctrine()->getManager();
    //     // $repository = $this->getDoctrine()->getRepository(Article::class);
    //     // $message = '<p>Deleted articles with ids :</p><ul>';
    //     // foreach ($articles as $article) {
    //     //     $item = $repository->find(intval($article));
    //     //     $entityManager->remove($item);
    //     //     $entityManager->flush();
    //     //     $message .= "<li>$article</li>";
    //     // }
    //     // $message .= "</ul>";
    //     // return $this->redirectToRoute('backArticleList');
    //     $previewForm = $this->createForm(ArticlePreviewType::class/*, $article, [
    //         'title' => $article->getTitle(),
    //         'body' => $article->getHtmlBody(),
    //         'abstract' => $article->getAbstract()
    //     ]*/);
    //     return $this->render('test.html.twig', [
    //         'article' => $request->request->get('htmlBody'),
    //         'previewForm' => $previewForm->createView()
    //     ]);
    // }

    /**
     * @Route("/back-office/parametres", name="backSettings")
     */
    public function displaySettings(Request $request)
    {
        //verification de la présence d'une session --> retour au formulaire si pas de session
        if (!$this->session->get('userName')) {
            return $this->redirectToRoute('backLogin');
        }

        $form = $this->createForm(ChangePasswordType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            if ($formData['password'] === $formData['password2']) {
                $entityManager = $this->getDoctrine()->getManager();
                $repository = $this->getDoctrine()->getRepository(AdminUser::class);
                $res = $repository->findBy(
                    ['userName' => $this->session->get('userName')]
                );
                $res[0]->setPassword($formData['password']);
                $entityManager->flush();
                return $this->redirectToRoute('backSettings');
            }
        }

        return $this->render('backSettings.html.twig', [
            'userName' => $this->session->get('userName'),
            'passwordForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/back-office/reservations", name="backAppointments")
     */
    public function displayAppointmentManagement()
    {
        $repository = $this->getDoctrine()->getRepository(Appointment::class);
        $unconfirmed = $repository->findBy(
            ['confirmed' => 0]
        );
        return $this->render('backAppointments.html.twig', [
            'unconfirmed' => $unconfirmed
        ]);
    }
}
