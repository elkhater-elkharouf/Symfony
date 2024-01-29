<?php

namespace App\Controller;

use App\Repository\CalendarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\HotelRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ReservationRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Swift_Mailer;
use Symfony\Component\Mime\Email;
use App\Entity\Calendar;

class ReservationController extends AbstractController
{
    /**
     * @Route("/reservation", name="reservation")
     */
    public function index(): Response
    {
        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }
    /**
     * @Route("/getaway/reservation/list", name="listr")
     */
    public function list(ReservationRepository $rep,CalendarRepository $calendar): Response
    {
        $find=$rep->findAll();
        $events=$calendar->findByReservations($find);
        $reser=[];
        foreach ($events as $res) {
            $reser[] = [
                'id' => $res->getId(),
                'title' => $res->getTitle(),
                'start' => $res->getStart(),
                'end' => $res->getEnd(),
            ];
        }
        $data=json_encode($reser);

        $rep=$this->getDoctrine()->getRepository(Reservation::class);
        $reservations=$rep->findAll();
        return $this->render('back-office/reservation/list.html.twig',[
            'reservation' => $reservations,
        ]);
    }

    /**
     * @Route("/getaway/reservation/delete/{id}", name="deleter")
     */
    public function delete($id): Response
    {
        $rep = $this->getDoctrine()->getRepository(Reservation::class);
        $em = $this->getDoctrine()->getManager();
        $reservation = $rep->find($id);
        $em->remove($reservation);
        $em->flush();
        return $this->redirectToRoute('listr');

    }
    /**
     * @Route("/getaway/reservation/{id}", name="addr")
     */
    public function add( $id , Request $request , HotelRepository $rep , UserRepository $repository,MailerInterface  $mailer): Response
    {
        $reservation=new Reservation();
        $hotel = $rep->find($id);

        $user = $repository->find(35);
        //$nbjr= $request->get('date_fin') - $request->get('date_debut');
        $nbjr = abs(strtotime($request->get('date_fin')) - strtotime($request->get('date_debut')));


        //dump($nbjr);
        $form=$this->createForm(ReservationType::class,$reservation);
        $form=$form->handleRequest($request);
        if ($form->isSubmitted())
        {


            $email = (new Email())
                ->from(('GETAWAY <mohamedaziz.azaiez@esprit.tn>'))
                ->to('walid.aissaoui@esprit.tn')
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('Nouvelle Reservation hotel Ajouté  !!')

                ->html('
            
           
            
            ');

            $mailer->send($email);

            $reservation->setHotel($hotel);
            $reservation->setUser($user);
            $reservation->setPrixreservation($nbjr * $hotel->getPrix());
            $reservation=$form->getData();
            $em=$this->getDoctrine()->getManager();
            $em->persist($reservation);
            $em->flush();
            return $this->redirectToRoute('hotel');

        }
        return $this->render('front-office/reservation.html.twig', [
            'formA' => $form->createView(),
            'data' =>$nbjr
        ]);
    }
    /**
     * @Route("/getaway/reservation/update/{id}", name="updater")
     */
    public function update(Request $request,$id): Response
    {
        $rep=$this->getDoctrine()->getRepository(Reservation::class);

        $reservation=$rep->find($id);
        $form=$this->createForm(ReservationType::class,$reservation);
        $form=$form->handleRequest($request);
        if ($form->isSubmitted())
        {

            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listr');

        }

        return $this->render('back-office/reservation/update.html.twig', [
            'formA' => $form->createView(),
        ]);
    }

}
