<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SearchFormv;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Voiture;
use App\Repository\VoitureRepository;
use App\Form\VoitureType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use App\Entity\Urlizer;
use App\Data\SearchData;


class VoitureController extends AbstractController
{
    /**
     * @Route("/getaway/voiture", name="voiture")
     */
    public function index(): Response
    {
        return $this->render('voiture/index.html.twig', [
            'controller_name' => 'VoitureController',
        ]);
    }
    /**
     * @Route("/getaway/voiture/list", name="listv")
     */
    public function list(): Response
    {
        $rep=$this->getDoctrine()->getRepository(Voiture::class);
        $voitures=$rep->findAll();
        return $this->render('back-office/voiture/list.html.twig', [
            'voiture' => $voitures,
        ]);

    }

    /**
     * @Route("/getaway/voiture/delete/{id}", name="deletev")
     */
    public function delete($id): Response
    {
        $rep = $this->getDoctrine()->getRepository(Voiture::class);
        $em = $this->getDoctrine()->getManager();
        $voiture = $rep->find($id);
        $em->remove($voiture);
        $em->flush();
        return $this->redirectToRoute('listv');

    }
    /**
     * @Route("/getaway/voiture/add", name="addv")
     */
    public function add(Request $request): Response
    {
        $voiture=new Voiture();
        $form=$this->createForm(VoitureType::class,$voiture);
        $form=$form->handleRequest($request);
        if ($form->isSubmitted())
        {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['image']->getData();
            $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );
            $voiture->setImage($newFilename);
            $voiture=$form->getData();
            $em=$this->getDoctrine()->getManager();
            $em->persist($voiture);
            $em->flush();
            return $this->redirectToRoute('listv');

        }
        return $this->render('back-office/voiture/add.html.twig', [
            'formA' => $form->createView(),

        ]);
    }
    /**
     * @Route("/getaway/voiture/update/{id}", name="updatev")
     */
    public function update(Request $request,$id): Response
    {
        $rep=$this->getDoctrine()->getRepository(Voiture::class);

        $voiture=$rep->find($id);
        $form=$this->createForm(VoitureType::class,$voiture);
        $form=$form->handleRequest($request);
        if ($form->isSubmitted())
        {

            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listv');

        }

        return $this->render('back-office/voiture/update.html.twig', [
            'formA' => $form->createView(),
        ]);
    }
    /**
     * @Route("/getaway/voiture", name="voiture")
     */
    public function showvoiture(VoitureRepository $rep,Request $request): Response
    {
        $data=new SearchData();
        $data->page=$request->get('page',1);
        $form =$this->createForm(SearchFormv::class,$data);
        $form->handleRequest($request);
        $voiture = $rep->findSearch($data);
        return $this->render('front-office/voiture.html.twig', [
            'controller_name' => 'VoitureController',
            'voiture'=>$voiture,
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/getaway/voiture/detail/{id}", name="detailv")
     */
    public function detail($id , Request $request , VoitureRepository $rep): Response
    {

        $voitures = $rep->find($id);
        return $this->render('front-office/detail.html.twig', [
            'voiture' => $voitures,

        ]);

    }

}