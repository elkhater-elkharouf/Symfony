<?php

namespace App\Controller;
use App\Entity\Produit;
use App\Form\ProduitFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\Image;
use App\Entity\Category;
use Dompdf\Dompdf;
use Dompdf\options;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Annotation\Groups;


class ProduitController extends AbstractController
{
     /**
 * @Route("/produits", name="produits")
 */
public function produits()
{
    
    $produit = $this->getDoctrine()->getRepository(produit::class)->findAll();
     
    return $this->render('produit/produit.html.twig', [
        "produit" => $produit,
        
    ]);
}
    /**
     * @Route("/add-produit", name="add_produit")
     */
    public function addProduit(Request $request): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitFormType::class, $produit);
        $form->handleRequest($request);
    
        if($form->isSubmitted() && $form->isValid())
        {
            

           $path=$this->getParameter('kernel.project_dir') .'/public/imgs';
           $produit=$form->getData();
            
            $image=$produit->getImage();
            $file=$image->getFile();
            $nom = md5(uniqid()). '.'.$file->guessExtension();
            $file->move($path,$nom);
            $image->setNom($nom);
            

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();
            return $this->redirectToRoute('produits');
        }
    
        return $this->render("produit/produit-form.html.twig", [
            "form_title" => "Ajouter un produit",
            "form_produit" => $form->createView(),
        ]);
    }
   
 


/**
 * @Route("/modify-produit/{id}", name="modify_produit")
 */
public function modifyProduit(Request $request, int $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();

    $produit = $entityManager->getRepository(Produit::class)->find($id);
    $form = $this->createForm(ProduitFormType::class, $produit);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid())
    {
        $entityManager->flush();
        return $this->redirectToRoute('produits');
    }

    return $this->render("produit/produit-form.html.twig", [
        "form_title" => "Modifier un produit",
        "form_produit" => $form->createView(),
    ]);
}
/**
 * @Route("/delete-produit/{id}", name="delete_produit")
 */
public function deleteProduit(int $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $produit = $entityManager->getRepository(Produit::class)->find($id);
    $entityManager->remove($produit);
    $entityManager->flush();

    return $this->redirectToRoute("produits");
}
/**
      * @Route("/afficherproduit", name="affichproduit")
      */
      public function afficherproduitt()
      {
 
         
        $produit = $this->getDoctrine()->getManager()->getRepository(produit::class)->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($produit);


             return new JsonResponse($formatted);
      }
      /**
     * @Route("/updateproduit", name="updateproduit")
     * @Method("PUT")
     */
    public function modifierproduitt(Request $request) {
        $id = $request->get("id");
        $nom = $request->query->get("nom");
        $couleur = $request->query->get("couleur");
        $prix = $request->query->get("prix");
        $quantite = $request->query->get("quantite");
        $em = $this->getDoctrine()->getManager();
        $produit = $this->getDoctrine()->getManager()->getRepository(produit::class)->find($id);
        $produit->setNom($nom);
        $produit->setCouleur($couleur);
        $produit->setPrix($prix);
        $produit->setQuantite($quantite);
        $em->persist($produit);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($produit);
        return new JsonResponse("produit modifiee avec success.");

    }
    /**
      * @Route("/deleteproduit", name="deleteproduit")
      * @Method("DELETE")
      */

      public function deleteproduitt(Request $request) {
        
        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository(produit::class)->find($id);
        if($produit!=null ) {
            $em->remove($produit);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("produit supprime avec succes.");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id produit invalide.");


    }
    /**
      * @Route("/addproduit", name="addproduit")
      * @Method("POST")
      */

      public function addproduitt(Request $request)
      {
        $produit = new produit();
          $nom = $request->query->get("nom");
          $couleur = $request->query->get("couleur");
          $prix = $request->query->get("prix");
          $quantite = $request->query->get("quantite");
          $produit->setNom($nom);
          $produit->setCouleur($couleur);
          $produit->setPrix($prix);
          $produit->setQuantite($quantite);
         
          $em = $this->getDoctrine()->getManager();
          $em->persist($produit);
          $em->flush();
          $serializer = new Serializer([new ObjectNormalizer()]);
          $formatted = $serializer->normalize("produit ajoute avec succes.");
          return new JsonResponse($formatted);
 
      }

}