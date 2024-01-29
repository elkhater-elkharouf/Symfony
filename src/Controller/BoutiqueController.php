<?php

namespace App\Controller;

use App\Entity\Produit;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ProduitRepository;
use App\Form\ProduitFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\CategorySearchType;
use App\Entity\CategorySearch;
use App\Entity\TriiparPrix;
use App\Entity\TriiiparPrix;
use App\Form\TriiparPrixType;
use App\Form\TriiiparPrixType;
class BoutiqueController extends AbstractController
{
    /**
     * @Route("/shop", name="shop")
     */
    

    public function index(Request $request, PaginatorInterface  $paginator)
{
    $donnees = $this->getDoctrine()->getRepository(produit::class)->findAll();
     $produit = $paginator->paginate(
         $donnees,
         $request->query->getInt('page', 1),
         1
     );

    return $this->render('produit/boutique.html.twig', [
        "produit" => $produit,
    ]);
}

 /**
     * @Route("/rech/", name="rech")
     * Method({"GET", "POST"})
     */
   
    public function produitParCategorie(Request $request) {
        
        $categorySearch = new CategorySearch();
        $form = $this->createForm(CategorySearchType::class,$categorySearch);
        $form->handleRequest($request);
  
        $produit= [];
  
        if($form->isSubmitted() && $form->isValid()) {
          $category = $categorySearch->getCategory();
         
          if ($category!="") 
          {
            
            
            
           $produit= $this->getDoctrine()->getRepository(produit::class)->findBy(['category' => $category] );
          }
          else   
            $produit= $this->getDoctrine()->getRepository(produit::class)->findAll();
          }
        
        return $this->render('produit/produitParCategorie.html.twig',['form' => $form->createView(),'produit' => $produit]);
    }
    /**
     * @Route("/trii/", name="trii")
     * Method({"GET", "POST"})
     */
   
    public function TriiparPrix(Request $request) {
        
        $TriiparPrix = new TriiparPrix ();
        $form = $this->createForm(TriiparPrixType::class,$TriiparPrix);
        $form->handleRequest($request);
  
        $produit= [];
        $prix = $TriiparPrix->getPrix();
        $produit= $this->getDoctrine()->getRepository(produit::class)->findBy(array(),array('prix' => 'DESC'));

       if($form->isSubmitted() && $form->isValid()) {
    
        $prix = $TriiparPrix->getPrix();
        
    }
    
        
    return $this->render('produit/TriiparPrix.html.twig',['form' => $form->createView(),'produit' => $produit]);
}
 /**
     * @Route("/triii/", name="triii")
     * Method({"GET", "POST"})
     */
   
    public function TriiiparPrix(Request $request) {
        
        $TriiiparPrix = new TriiiparPrix ();
        $form = $this->createForm(TriiiparPrixType::class,$TriiiparPrix);
        $form->handleRequest($request);
  
        $produit= [];
        $prix = $TriiiparPrix->getPrix();
        $produit= $this->getDoctrine()->getRepository(produit::class)->findBy(array(),array('prix' => 'ASC'));

       if($form->isSubmitted() && $form->isValid()) {
    
        $prix = $TriiiparPrix->getPrix();
        
    }
    
        
    return $this->render('produit/TriiiparPrix.html.twig',['form' => $form->createView(),'produit' => $produit]);
}
}