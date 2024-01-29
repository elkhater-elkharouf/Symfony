<?php

namespace App\Controller;
use App\Entity\Category;
use App\Form\CategoryFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Annotation\Groups;
class CategoryController extends AbstractController
{
    /**
     * @Route("/add-category", name="addcategory")
     */
    public function addcategory(Request $request): Response
    {
        $category = new category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);
    
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirectToRoute('category');
        }
    
        return $this->render("category/category-form.html.twig", [
            "form_title" => "Ajouter une categorie",
            "form_category" => $form->createView(),
        ]);
    }
    /**
 * @Route("/category", name="category")
 */
public function category()
{
    $category = $this->getDoctrine()->getRepository(category::class)->findAll();

    return $this->render('category/category.html.twig', [
        "category" => $category,
    ]);
}
/**
 * @Route("/modify-category/{id}", name="modify_category")
 */
public function modifycategory(Request $request, int $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();

    $category = $entityManager->getRepository(category::class)->find($id);
    $form = $this->createForm(CategoryFormType::class, $category);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid())
    {
        $entityManager->flush();
        return $this->redirectToRoute('category');
    }

    return $this->render("category/category-form.html.twig", [
        "form_title" => "Modifier une categorie",
        "form_category" => $form->createView(),
    ]);
}
/**
 * @Route("/delete-category/{id}", name="delete_category")
 */
public function deletecategory(int $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $category = $entityManager->getRepository(category::class)->find($id);
    $entityManager->remove($category);
    $entityManager->flush();

    return $this->redirectToRoute("category");
}
/**
      * @Route("/affichercategory", name="affichcategory")
      */
      public function affichercategoryy()
      {
 
         
        $category = $this->getDoctrine()->getManager()->getRepository(category::class)->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($category);


             return new JsonResponse($formatted);
      }
      /**
     * @Route("/updatecategory", name="updatecategory")
     * @Method("PUT")
     */
    public function modifiercategoryy(Request $request) {
        $id = $request->get("id");
        $nom = $request->query->get("nom");
        $type = $request->query->get("type");
        $em = $this->getDoctrine()->getManager();
        $category = $this->getDoctrine()->getManager()->getRepository(category::class)->find($id);
        $category->setNom($nom);
        $category->setType($type);
        $em->persist($category);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($category);
        return new JsonResponse("caegorie modifiee avec success.");

    }
    /**
      * @Route("/deletecategory", name="deletecategory")
      * @Method("DELETE")
      */

      public function deletecategoryy(Request $request) {
        
        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository(category::class)->find($id);
        if($category!=null ) {
            $em->remove($category);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("categorie supprime avec succes.");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id categorie invalide.");


    }
    /**
      * @Route("/addcategory", name="addcategory")
      * @Method("POST")
      */

      public function addcategoryy(Request $request)
      {
        $category = new category();
          $nom = $request->query->get("nom");
          $type = $request->query->get("type");
          $category->setNom($nom);
          $category->setType($type);
          $em = $this->getDoctrine()->getManager();
          $em->persist($category);
          $em->flush();
          $serializer = new Serializer([new ObjectNormalizer()]);
          $formatted = $serializer->normalize("categorie ajoute avec succes.");
          return new JsonResponse($formatted);
 
      }
}