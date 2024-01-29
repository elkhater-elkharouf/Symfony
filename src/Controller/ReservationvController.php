<?php

namespace App\Controller;

use App\Entity\Reservationv;
use App\Repository\VoitureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\VoitureType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
use App\Form\ReservationvType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Swift_Mailer;
use Symfony\Component\Mime\Email;
use function mysql_xdevapi\getSession;


class ReservationvController extends AbstractController
{
    /**
     * @Route("/reservationv", name="reservationv")
     */
    public function index(): Response
    {
        return $this->render('reservationv/index.html.twig', [
            'controller_name' => 'ReservationvController',
        ]);
    }

    /**
     * @Route("/getaway/reservationv/list", name="listrr")
     */
    public function list(): Response
    {
        $rep=$this->getDoctrine()->getRepository(Reservationv::class);
        $reservations=$rep->findAll();
        return $this->render('back-office/reservationv/list.html.twig', [
            'reservation' => $reservations,
        ]);

    }
    /**
     * @Route("/getaway/reservationv/delete/{id}", name="deleterr")
     */
    public function delete($id): Response
    {
        $rep = $this->getDoctrine()->getRepository(Reservation::class);
        $em = $this->getDoctrine()->getManager();
        $reservation = $rep->find($id);
        $em->remove($reservation);
        $em->flush();
        return $this->redirectToRoute('listrr');

    }
    /**
     * @Route("/getaway/reservationv/update/{id}", name="updaterr")
     */
    public function update(Request $request,$id): Response
    {
        $rep=$this->getDoctrine()->getRepository(Reservationv::class);

        $reservation=$rep->find($id);
        $form=$this->createForm(ReservationvType::class,$reservation);
        $form=$form->handleRequest($request);
        if ($form->isSubmitted())
        {

            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listrr');

        }

        return $this->render('back-office/reservationv/update.html.twig', [
            'formA' => $form->createView(),
        ]);
    }

    /**
     * @Route("/getaway/reservationv/{id}", name="addrr")
     */
    public function add( $id , Request $request , VoitureRepository $rep , UserRepository $repository,MailerInterface  $mailer): Response
    {
        $reservation=new Reservationv();
        $voiture = $rep->find($id);
        $user = $repository->find(getSession()->generateUUID());
        $nbjr = abs(strtotime($request->get('datefin')) - strtotime($request->get('datedebut')));
        //$nbjr= $request->get('datefin') - $request->get('datedebut');
        //dump($nbjr);
        $form=$this->createForm(ReservationvType::class,$reservation);
        $form=$form->handleRequest($request);
        if ($form->isSubmitted())
        {
            $reservation = $form->getData();

            $email = (new Email())
                ->from(('GETAWAY <mohamedaziz.azaiez@esprit.tn>'))
                ->to('walid.aissaoui@esprit.tn')
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('Nouvelle Location voiture Ajouté  !!')

                ->html('
             ');
            $mailer->send($email);

            $reservation->setVoiture($voiture);
            $reservation->setUser($user);
            $reservation->setPrixreservation($nbjr * $voiture->getPrix());
            $reservation=$form->getData();
            $em=$this->getDoctrine()->getManager();
            $em->persist($reservation);
            $em->flush();
            return $this->redirectToRoute('voiture');

        }
        return $this->render('front-office/reservationv.html.twig', [
            'formA' => $form->createView(),
        ]);
    }
}