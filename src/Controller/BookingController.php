<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\MessageType;
use App\Helper\MailingHelper;
use App\Form\ClientType;
use App\Entity\AppointmentType;
use App\Entity\AppointmentTime;
use App\Entity\Client;
use App\Entity\Appointment;
use DateTime;
use Symfony\Component\HttpFoundation\Response;

class BookingController extends AbstractController
{

    /**
     * @Route("/reservation", name="book")
     */
    public function displayPage(Request $request)
    {
        setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
        $year = date('Y');
        $monthFr = (strftime("%B"));
        $month = date('m');
        $today = date("d");
        $grid = $this->createGrid(intval(date('Y')), intval(date('m')), intval(date("d")));
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
        return $this->render('book.html.twig', [
            'offset' => 0,
            'year' => $year,
            'month' => $monthFr,
            'monthNumber' => $month,
            'today' => $today,
            'grid' => $grid,
            'contactForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/reservation/suivant/{offset}", requirements={"offset"="\d+"})
     */
    public function displayNextMonth($offset, Request $request)
    {
        setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
        $year = intval(date('Y'));
        $month = intval(date('m'));
        $displayedMonth = $month + $offset;
        if ($displayedMonth > 12) {
            $year += intval($offset/12);
            $displayedMonth = $displayedMonth % 12;
            if ($displayedMonth === 0) {
                $displayedMonth = 12;
            }
        }
        $monthFr = strftime("%B", mktime(0, 0, 0, $displayedMonth, 1, $year));
        $grid = $this->createGrid($year, $displayedMonth, -1);
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
        return $this->render('book.html.twig', [
            'offset' => $offset,
            'year' => $year,
            'month' => $monthFr,
            'monthNumber' => $displayedMonth,
            'grid' => $grid,
            'today' => -1,
            'contactForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/reservation/{year}/{month}/{day}", requirements={"day"="\d+", "month"="\d+", "year"="\d+"})
     */
    public function displayAppointmentForm(Request $request, int $year, int $month, int $day)
    {
        $contactForm = $this->createForm(MessageType::class);
        $contactForm->handleRequest($request);
        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            //objet AdminUser contenant les infos entrées dans le formulaire
            $messageInfo = $contactForm->getData();
            $sent = MailingHelper::sendMail($messageInfo['Nom'], $messageInfo['Adresse_mail'], $messageInfo['Sujet'], $messageInfo['Message']);
            if($sent) {
                return $this->render('validation.html.twig', ['validationMessage' => 'Le message a bien été envoyé.', 'contactForm' => $contactForm->createView()]);
            }
            return $this->render('validation.html.twig', ['validationMessage' => 'Une erreur s\'est produite, veuillez réessayer.', 'contactForm' => $contactForm->createView()]);
        }

        $repository = $this->getDoctrine()->getRepository(AppointmentType::class);
        $appointmentTypes = $repository->findAll();
        $appointmentTimes = array();
        foreach($appointmentTypes as $type) {
            $repository = $this->getDoctrine()->getRepository(AppointmentTime::class);
            $times = $repository->findBy([
                'AppointmentType' => $type->getId()
            ]);
            foreach($times as $time) {
                $appointmentTimes[date_format($time->getTime(), 'H:i')] = date_format($time->getTime(), 'H:i');
            }
        }

        $bookingForm = $this->createForm(ClientType::class, null, [
            'appointment_types' => $appointmentTypes,
            'available_times' => $appointmentTimes
        ]);

        $bookingForm->handleRequest($request);
        if ($bookingForm->isSubmitted() && $bookingForm->isValid()) {

            $formData = $bookingForm->getData();
            $em = $this->getDoctrine()->getManager();

            $client = new Client();
            $client->setLastName($formData['lastName']);
            $client->setFirstName($formData['firstName']);
            $client->setMail($formData['mail']);
            $client->setPhone($formData['phone']);
            $client->setBirthDate($formData['birthDate']);
            $em->persist($client);
            $em->flush();

            $appointment = new Appointment();
            $appointment->setClient($client);
            $repository = $this->getDoctrine()->getRepository(AppointmentType::class);
            $appointmentType = $repository->find($formData['appointmentType']);
            $appointment->setType($appointmentType);
            $date = new DateTime();
            $date->setDate($year, $month, $day);
            $appointment->setDate($date);
            $time = new DateTime();
            $time->setTime(intval(substr($formData['appointmentTime'], 0, 2)), intval(substr($formData['appointmentTime'], -2)));
            $appointment->setTime($time);
            $appointment->setConfirmed(false);
            $em->persist($appointment);
            $em->flush();

            return new Response('Saved new client with id '.$client->getId().'<br>Saved new appoitment with id '.$appointment->getId());
        }

        return $this->render('bookingForm.html.twig', [
            'contactForm' => $contactForm->createView(),
            'bookingForm' => $bookingForm->createView()
        ]);
    }

    public function createGrid(int $currentYear, int $currentMonth, int $start)
    {
        $dayValues = [
            "Monday" => 0,
            "Tuesday" => 1,
            "Wednesday" => 2,
            "Thursday" => 3,
            "Friday" => 4,
            "Saturday" => 5,
            "Sunday" => 6
        ];
        $numberOfDaysInMonth = [
            1 => 31,
            2 => 28,
            3 => 31,
            4 => 30,
            5 => 31,
            6 => 30,
            7 => 31,
            8 => 31,
            9 => 30,
            10 => 31,
            11 => 30,
            12 => 31,
        ];

        //$currentDay = intval(date('d'));
        $firstDayOfMonth = date('l', mktime(0, 0, 0, $currentMonth, 1, $currentYear));
        $intFirstDay = $dayValues[$firstDayOfMonth];
        $max = $numberOfDaysInMonth[$currentMonth];

        $count = 1;
        $grid = array();
        for ($i = 0; $i < 5; $i++) {
            $grid[$i] = array();
            for ($j = 0; $j < 7; $j++) {
                if (($i === 0 && $j < $intFirstDay) || $count > $max) {
                    $grid[$i][$j]['number'] = null;
                    $grid[$i][$j]['class'] = "table-secondary";
                } else {
                    if ($count <= $start) {
                        $grid[$i][$j]['number'] = $count;
                        $grid[$i][$j]['class'] = "table-secondary";
                    } else {
                        $grid[$i][$j]['number'] = $count;
                        $grid[$i][$j]['class'] = "";
                    }
                    $count++;
                }
            }
        }

        return $grid;
    }

    public function checkAvailability(/*string*/$date)
    {
        // $entityManager = new EntityManager()
        // $repository = $this->getDoctrine()->getRepository(AdminUser::class);
        // $res = $repository->findBy(
        //     ['userName' => $adminInfo->getUserName()]
        // );
    }
}
