<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Form\VoitureType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Evenement;
use App\Repository\EvenementRepository;
use App\Form\EvenementType;

class EvenementController extends AbstractController
{
    /**
     * @Route("/evenement", name="evenement")
     */
    public function index(): Response
    {
        return $this->render('evenement/index.html.twig', [
            'controller_name' => 'EvenementController',
        ]);
    }
    /**
     * @Route("/evenement/list", name="liste")
     */
    public function list(): Response
    {
        $rep=$this->getDoctrine()->getRepository(Evenement::class);
        $evenements=$rep->findAll();
        return $this->render('back-office/evenement/list.html.twig', [
            'evenement' => $evenements,
        ]);

    }

    /**
     * @Route("/evenement/delete/{id}", name="deletee")
     */
    public function delete($id): Response
    {
        $rep = $this->getDoctrine()->getRepository(Evenement::class);
        $em = $this->getDoctrine()->getManager();
        $evenement = $rep->find($id);
        $em->remove($evenement);
        $em->flush();
        return $this->redirectToRoute('liste');

    }
    /**
     * @Route("/evenement/add", name="adde")
     */
    public function add(Request $request): Response
    {
        $evenement=new Evenement();
        $form=$this->createForm(EvenementType::class,$evenement);
        $form=$form->handleRequest($request);
        if ($form->isSubmitted())
        {
            $evenement=$form->getData();
            $em=$this->getDoctrine()->getManager();
            $em->persist($evenement);
            $em->flush();
            return $this->redirectToRoute('list');

        }
        return $this->render('back-office/evenement/add.html.twig', [
            'formA' => $form->createView(),

        ]);
    }
    /**
     * @Route("/evenement/update/{id}", name="updatee")
     */
    public function update(Request $request,$id): Response
    {
        $rep=$this->getDoctrine()->getRepository(Evenement::class);

        $evenement=$rep->find($id);
        $form=$this->createForm(EvenementType::class,evenement);
        $form=$form->handleRequest($request);
        if ($form->isSubmitted())
        {

            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('liste');

        }

        return $this->render('back-office/evenement/update.html.twig', [
            'formA' => $form->createView(),
        ]);
    }
}
