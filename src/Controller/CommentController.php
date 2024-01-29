<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Publication;
use App\Repository\PublicationRepository;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; 
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
/**
 * @Route("/comment")
 */
class CommentController extends AbstractController
{


    /**
      * @Route("/addcommentaire", name="add_commentaire")
      * @Method("POST")
      */

      public function addcommentaire(Request $request)
      {
          $comment = new Comment();
          $rep= $this->getDoctrine()->getRepository(Publication::class);
          $publication=$rep->find($request->get('idpub'));
          $authorName = $request->query->get("authorName");
          $content = $request->query->get("content");
          $id=$publication->getid();
          $em = $this->getDoctrine()->getManager();
          $comment->setAuthorName($authorName);
          $comment->setContent($content);
          $comment->setPublication($publication);
          $comment->setIdpub($id);
          $em->persist($comment);
          $em->flush();
          $serializer = new Serializer([new ObjectNormalizer()]);
          $formatted = $serializer->normalize("commentaire ajouter");
          return new JsonResponse($formatted);
 
      }


      /**
      * @Route("/deletecommentaire", name="delete_commentaire")
      * @Method("DELETE")
      */

     public function deleteCommentaireAction(Request $request) {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $comment = $em->getRepository(Comment::class)->find($id);
        if($comment!=null ) {
            $em->remove($comment);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("commentaire a ete supprimee avec success.");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id commentaire invalide.");


    }

    /**
     * @Route("/updatecommentaire", name="update_commentaire")
     * @Method("PUT")
     */
    public function modifierCommentaireAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $comment = $this->getDoctrine()->getManager()
                        ->getRepository(Comment::class)
                        ->find($request->get("id"));

        $comment->setAuthorName($request->get("authorName"));
        $comment->setContent($request->get("content"));

        $em->persist($comment);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($comment);
        return new JsonResponse("commentaire a ete modifiee avec success.");

    }


     /**
      * @Route("/displaycommentaire", name="display_commentaire")
      */
     public function allRecAction()
     {

         $comment = $this->getDoctrine()->getManager()->getRepository(Comment::class)->findAll();
         $serializer = new Serializer([new ObjectNormalizer()]);
         $formatted = $serializer->normalize($comment);

         return new JsonResponse($formatted);

     }
    /**
     * @Route("/", name="comment_index", methods={"GET"})
     */
    public function index(CommentRepository $commentRepository): Response
    {
        return $this->render('comment/index.html.twig', [
            'comments' => $commentRepository->findAll(),
        ]);
    }
    /**
     * @Route("/lescom/{id}", name="lescom", methods={"GET"})
     */
    public function lescom(CommentRepository $commentRepository,$id): Response
    {
        $commentRepository = $commentRepository->findBy(['publication' => $id]);
        return $this->render('comment/lescom.html.twig', [
            'comments' => $commentRepository,
        ]);
    }
    /**
     * @Route("/new/{id}", name="comment_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,$id,Publication $publication,PublicationRepository $pubrepo): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $publication = $pubrepo->find($id) ;
            $comment->setPublication($publication) ;
            $comment->setCreatedAt(new \DateTime());
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('pubs');
        }

        return $this->render('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="comment_show", methods={"GET"})
     */
    public function show(Comment $comment): Response
    {
        return $this->render('comment/show.html.twig', [
            'comment' => $comment,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="comment_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('pubs', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="comment_delete", methods={"POST"})
     */
    public function delete(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('comment_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/{id}", name="comment_delete_comment", methods={"POST"})
     */
    public function delete1(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('pubs', [], Response::HTTP_SEE_OTHER);
    }
}
