<?php

namespace App\Controller;



use App\Entity\Participation;
//use App\Entity\Events;

use App\Form\ParticipationFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Swift_Mailer;

use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
 use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


class ParticipationController extends AbstractController
{



   /******************Ajouter Reclamation*****************************************/
     /**
      * @Route("/addPart", name="addPart")
      * @Method("POST")
      */

      public function addPart(Request $request)
      {
          
        $participation = new Participation();
        //$id = $request->query->get("id");

          $nom = $request->query->get("nom");
          $prenom = $request->query->get("prenom");
          $numtel = $request->query->get("numtel");
          $adresse = $request->query->get("adresse");
          //$eventid = $request->query->get("eventid");

          $idevent = $request->query->get("idevent");

          $em = $this->getDoctrine()->getManager();
          $datanes = new \DateTime('now');

          $participation->setNom($nom);
          $participation->setPrenom($prenom);
          $participation->setNumtel($numtel);
          $participation->setDatanes($datanes);
          $participation->setAdresse($adresse);
          $participation->setIdevent($idevent);

         // $participation->setEventid($eventid);


          $em->persist($participation);
          $em->flush();
          $serializer = new Serializer([new ObjectNormalizer()]);
          $formatted = $serializer->normalize("publication ajouter");
          return new JsonResponse($formatted);
        
      }
   // ...

    /**
     * @Route("/add-participation", name="add_participation")
     */
    public function addParticipation(Request $request,MailerInterface  $mailer): Response
    {
        $participation = new Participation();

        $form = $this->createForm(ParticipationFormType::class , $participation);
        $form = $form->handleRequest($request);

        if ($form->isSubmitted()){
            //$form->get('adresse')->getData();
            $participation = $form->getData();

            $email = (new Email())
            ->from(('GETAWAY <mohamedaziz.azaiez@esprit.tn>') )
            ->to('z3yzztn4@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Nouveau Participant Ajouté !!')
            ->text($participation->getNom())
            ->html('
            <center><h1>Hello '.$participation->getNom().',</h1></center>
            <p>These are your credentials</p>
            <ul>
            <li>Prenom: '.$participation->getPrenom().'</li>
            <li>Numero: '.$participation->getNumtel().'</li>
            </ul>
            
            ');

        $mailer->send($email);

            $em = $this->getDoctrine()->getManager();
            $em->persist($participation);
            $em->flush();
            



        // ...
        }
        return $this->render("participation/participation-form.html.twig", [
            "form_title" => "Register",
            "form_participation" => $form->createView(),
        ]);
    }
    /**
 * @Route("/participations", name="participations")
 */
public function participations(): Response
{
    $participation = $this->getDoctrine()->getRepository(participation::class)->findAll();

    return $this->render('participation/participations.html.twig', [
        "participation" => $participation,
    ]);
}
/**
 * @Route("/modify-participation/{id}", name="modify_participation")
 */
public function modifyParticipation(Request $request, int $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();

    $participation = $entityManager->getRepository(participation::class)->find($id);
    $form = $this->createForm(ParticipationFormType::class, $participation);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid())
    {
        $entityManager->flush();
    }

    return $this->render("participation/modifypartipation.html.twig", [
        "form_title" => "Modifier un participation",
        "form_participation" => $form->createView(),
        
    ]);
    
}
/**
 * @Route("/delete-participations/{id}", name="delete_participations")
 */
public function deleteParticipation(int $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $participation = $entityManager->getRepository(participation::class)->find($id);
    $entityManager->remove($participation);
    $entityManager->flush();

    return $this->redirectToRoute("participations");
}


/**
     * @Route("/displayParticipation", name="displayParticipation")
     * Method ({"GET","POST"})
     */
    public function displayParticipation(NormalizerInterface $normalizer): Response
    {

        $em=$this->getDoctrine()->getManager();
        $blogs=$em->getRepository(Participation::class)->findAll();
        $datas=array();
        foreach($blogs as $key=>$blog) {
            $datas[$key]['id']=$blog->getId();
            $datas[$key]['nom']=$blog->getNom();
            $datas[$key]['prenom']=$blog->getPrenom();
            $datas[$key]['datanes']=$blog->getDatanes();
            $datas[$key]['numtel']=$blog->getNumtel();
            $datas[$key]['adresse']=$blog->getAdresse();
            $datas[$key]['idevent']=$blog->getIdevent();


        }
        return new JsonResponse($datas);

    }
    
    /**
     * @Route("/deletePart", name="deletePart")
     */
    public function deletePart(Request $request) {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $participation = $em->getRepository(Participation::class)->find($id);
        if($participation!=null ) {
            $em->remove($participation);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("participation a ete supprimee avec success.");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id produit invalide.");


    }
    /******************Modifier Reclamation*****************************************/
    /**
     * @Route("/updatePart", name="updatePart")
     * @Method("PUT")
     */
    public function updatePart(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $participation = $this->getDoctrine()->getManager()
                        ->getRepository(Events::class)
                        ->find($request->get("id"));

        $participation->setNom($request->get("nom"));
        $participation->setPrenom($request->get("prenom"));
        $participation->setDatanes($request->get("datanes"));
        $participation->setNumtel($request->get("numtel"));
        $participation->setAdresse($request->get("adresse"));
        $participation->setIdevent($request->get("Idevent"));

        $em->persist($participation);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($participation);
        return new JsonResponse("participation a ete modifiee avec success.");

    }
    
}
