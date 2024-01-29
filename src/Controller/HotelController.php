<?php

namespace App\Controller;


use App\Data\SearchData;
use App\Form\SearchForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Hotel;
use App\Repository\HotelRepository;
use App\Form\HotelType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use App\Entity\Urlizer;


class HotelController extends AbstractController
{

    /**
     * @Route("/getaway/hotel/list", name="listh")
     */
    public function list(): Response
    {
        $rep=$this->getDoctrine()->getRepository(Hotel::class);
        $hotels=$rep->findAll();
        return $this->render('back-office/hotel/list.html.twig', [
            'hotel' => $hotels,
        ]);

    }

    /**
     * @Route("/getaway/hotel/delete/{id}", name="deleteh")
     */
    public function delete($id): Response
    {
        $rep = $this->getDoctrine()->getRepository(Hotel::class);
        $em = $this->getDoctrine()->getManager();
        $hotel = $rep->find($id);
        $em->remove($hotel);
        $em->flush();
        return $this->redirectToRoute('listh');

    }


    /**
     * @Route("/getaway/hotel/update/{id}", name="updateh")
     */
    public function update(Request $request,$id): Response
    {
        $rep=$this->getDoctrine()->getRepository(Hotel::class);

        $hotel=$rep->find($id);
        $form=$this->createForm(HotelType::class,$hotel);
        $form=$form->handleRequest($request);
        if ($form->isSubmitted())
        {

            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listh');

        }

        return $this->render('back-office/hotel/update.html.twig', [
            'formA' => $form->createView(),
        ]);
    }
    /**
     * @Route("/getaway/hotel/add", name="addh")
     */
    public function add(Request $request): Response
    {
        $hotel=new Hotel();
        $form=$this->createForm(HotelType::class,$hotel);
        $form=$form->handleRequest($request);
        if ($form->isSubmitted())
        {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['img']->getData();
            $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );
            $hotel->setImg($newFilename);
            $hotel=$form->getData();
            $em=$this->getDoctrine()->getManager();
            $em->persist($hotel);
            $em->flush();
            return $this->redirectToRoute('listh');

        }
        return $this->render('back-office/hotel/add.html.twig', [
            'formA' => $form->createView(),

        ]);
    }
    /**
     * @Route("/getaway/hotel", name="hotel")
     */
    public function showhotel(HotelRepository $rep,Request $request ): Response
    {
        $data=new SearchData();
        $data->page=$request->get('page',1);
        $form =$this->createForm(SearchForm::class,$data);
        $form->handleRequest($request);
        $hotel = $rep->findSearch($data);

        return $this->render('front-office/hotel.html.twig', [
            'controller_name' => 'HotelController',
            'hotel'=>$hotel,
            'form'=>$form->createView()
        ]);
    }





}
