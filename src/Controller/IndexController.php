<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use App\Entity\Hotel;
use App\Repository\HotelRepository;
use App\Form\HotelType;

use App\Entity\Reservation;
use App\Repository\UserRepository;
use App\Form\ReservationType;
use Symfony\Component\HttpFoundation\JsonResponse;


class IndexController extends AbstractController
{

    private $session;


    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }


    /**
     * @Route("/index", name="index")
     */
    public function index(): Response
    {

        if($this->session->get('role'))
        {
            $rr = $this->session->get('role');
            if($rr == '3')
            {
                $em = $this->getDoctrine()->getManager();
                $rep=$em->getRepository(Hotel::class);
                $totalHotels = $rep->createQueryBuilder('a')

                ->select('count(a.id)')
                ->getQuery()
                ->getSingleScalarResult();

                $rep=$em->getRepository(User::class);
                $totalUsers = $rep->createQueryBuilder('a')

                ->select('count(a.id)')
                ->where('a.role = 2')
                ->getQuery()
                ->getSingleScalarResult();

                $rep=$em->getRepository(User::class);
                $totalHoteliers = $rep->createQueryBuilder('a')

                ->select('count(a.id)')
                ->where('a.role = 1')
                ->getQuery()
                ->getSingleScalarResult();

                $rep=$em->getRepository(Reservation::class);
                $totalReservations = $rep->createQueryBuilder('a')

                ->select('count(a.id)')
                ->getQuery()
                ->getSingleScalarResult();



                return $this->render('back-office/index/index.html.twig', [
                    'hotel' => $totalHotels, 'users' => $totalUsers, 'reservations' => $totalReservations, 'hoteliers' => $totalHoteliers,
                ]);

            }
            else return $this->redirectToRoute('login');
        }
        else return $this->redirectToRoute('login');
    



        
    }


        /**
     * @Route("/test", name="test")
     */
    public function test(): Response
    {
        return $this->render('front-office/index/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
