<?php

namespace App\Controller;

use App\Entity\AdminUser;
use App\Form\AdminType;
use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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
    public function displayHome(Request $request)
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
}
