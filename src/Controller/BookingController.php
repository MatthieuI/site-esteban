<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{

    /**
     * @Route("/reservation", name="book")
     */
    public function displayPage()
    {
        setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
        $year = date('Y');
        $monthFr = (strftime("%B"));
        $grid = $this->createGrid(intval(date('Y')), intval(date('m')));
        return $this->render('book.html.twig', [
            'offset' => 0,
            'year' => $year,
            'month' => $monthFr,
            'grid' => $grid
        ]);
    }

    public function createGrid(int $currentYear, int $currentMonth)
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
                    $grid[$i][$j] = null;
                } else {
                    $grid[$i][$j] = $count;
                    $count++;
                }
            }
        }

        return $grid;
    }

    /**
     * @Route("/reservation/suivant/{offset}", requirements={"offset"="\d+"})
     */
    public function displayNextMonth($offset)
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
        $grid = $this->createGrid($year, $displayedMonth);
        return $this->render('book.html.twig', [
            'offset' => $offset,
            'year' => $year,
            'month' => $monthFr,
            'grid' => $grid
        ]);
    }

}