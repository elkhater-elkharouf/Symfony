<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Form\PublicationType;
use App\Entity\Comment;
use App\Entity\User;
use App\Repository\PublicationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;
use App\Notifications\NouveauPublicationNotification;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Swift_Mailer;
use Symfony\Component\Mime\Email;
use Swift_SmtpTransport;
use Swift_Message;

use Knp\Component\Pager\PaginatorInterface;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPaginationInterface ;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
/**
 * @Route("/publication")
 */
class PublicationController extends AbstractController
{


    /**
      * @Route("/addpublication", name="add_publication")
      * @Method("POST")
      */

      public function addpublication(Request $request)
      {
          $publication = new Publication();
          $title = $request->query->get("title");
          $content = $request->query->get("content");
          $em = $this->getDoctrine()->getManager();
 
          $publication->setTitle($title);
          $publication->setContent($content);
          
          $em->persist($publication);
          $em->flush();
          $serializer = new Serializer([new ObjectNormalizer()]);
          $formatted = $serializer->normalize("publication ajouter");
          return new JsonResponse($formatted);
 
      }


      /**
      * @Route("/deletepublication", name="delete_publication")
      * @Method("DELETE")
      */

     public function deletePublicationAction(Request $request) {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $publication = $em->getRepository(Publication::class)->find($id);
        if($publication!=null ) {
            $em->remove($publication);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("publication a ete supprimee avec success.");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id reclamation invalide.");


    }

    /**
     * @Route("/updatepublication", name="update_publication")
     * @Method("PUT")
     */
    public function modifierPublicationAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $publication = $this->getDoctrine()->getManager()
                        ->getRepository(Publication::class)
                        ->find($request->get("id"));

        $publication->setTitle($request->get("title"));
        $publication->setContent($request->get("content"));

        $em->persist($publication);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($publication);
        return new JsonResponse("Publication a ete modifiee avec success.");

    }


     /**
      * @Route("/displaypublication", name="display_publication")
      */
     public function allRecAction()
     {

         $publication = $this->getDoctrine()->getManager()->getRepository(Publication::class)->findAll();
         $serializer = new Serializer([new ObjectNormalizer()]);
         $formatted = $serializer->normalize($publication);

         return new JsonResponse($formatted);

     }
    /**
     * @Route("/listo", name="listo")
     */
    public function listo(PublicationRepository $publicationRepository)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('publication/mypdf.html.twig', [
            'publications' => $publicationRepository->findAll()
        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);
    }
    /**
     * @Route("/", name="publication_index", methods={"GET"})
     */
    public function index(PublicationRepository $publicationRepository): Response
    {
        return $this->render('publication/index.html.twig', [
            'publications' => $publicationRepository->findAll(),
        ]);
    }


    /**
     * @Route("/pubs", name="pubs")
     */
    public function lespub(Request $request, PaginatorInterface $paginator): Response
    {
        $donnees = $this->getDoctrine()->getRepository(Publication::class)->findAll();

        $publication = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            1 // Nombre de résultats par page
        );
        return $this->render('publication/lespub.html.twig', [
            'publications' => $publication,
        ]);
    }
    /**
     * @Route("/new", name="publication_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,UserRepository $userrepo,MailerInterface  $mailer): Response
    {
        $user = new User();

        //$value = $userrepo->find(1);
        $publication = new Publication();
        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = (new Email())
                ->from(('GETAWAY <mohamedaziz.azaiez@esprit.tn>'))
                ->to('walid.aissaoui@esprit.tn')
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('Nouvelle Publication  Ajouté  !!')

                ->html('
            
           
            
            ');
            $mailer->send($email);
            $uploadedFile = $form['image']->getData();
            $filename = md5(uniqid()).'.'.$uploadedFile->guessExtension();
            $uploadedFile->move($this->getParameter('upload_directory'),$filename);
            $publication->setImage($filename);
            //$publication->setIduser($value) ;
            $entityManager->persist($publication);
            $entityManager->flush();


            return $this->redirectToRoute('pubs', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('publication/new.html.twig', [
            'publication' => $publication,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="publication_show", methods={"GET"})
     */
    public function show(Publication $publication): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $value = $publication->getAbn();
        $value = $value + 1 ;
        $publication->setAbn($value);
        $entityManager->flush();
        return $this->render('publication/show.html.twig', [
            'publication' => $publication,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="publication_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('pubs');
        }

        return $this->render('publication/edit.html.twig', [
            'publication' => $publication,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="publication_delete", methods={"POST"})
     */
    public function delete(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publication->getId(), $request->request->get('_token'))) {
            $entityManager->remove($publication);
            $entityManager->flush();
        }

        return $this->redirectToRoute('publication_index', [], Response::HTTP_SEE_OTHER);
    }
        /**
     * @Route("/publication_delete_user/{id}", name="publication_delete_user", methods={"POST"})
     */
    public function deleteuser(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publication->getId(), $request->request->get('_token'))) {
            $entityManager->remove($publication);
            $entityManager->flush();
        }

        return $this->redirectToRoute('pubs', [], Response::HTTP_SEE_OTHER);
    }

 /**
     * @Route("/publication/searchpublication", name="searchpublication")
     */
    public function searchpublication(Request $request ,PublicationRepository $publicationRepository){
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $posts =  $publicationRepository->findEntitiesByString($requestString);
        if(!$posts) {
            $result['posts']['error'] = "Pas de publication ! 🙁 ";
        } else {
            $result['posts'] = $this->getRealEntities($posts);
        }
        return new Response(json_encode($result));
    }
    public function getRealEntities($posts){
        foreach ($posts as $posts){
            $realEntities[$posts->getId()] = [$posts->getImage(),$posts->getTitle(),$posts->getContent()];

        }
        return $realEntities;
    }
}
