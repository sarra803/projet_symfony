<?php
namespace App\Controller;

use App\Entity\Librairie;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\Livre;
use App\Entity\PropertySearch;
use App\Form\LivreType;
use App\Form\LibrairieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
Use Symfony\Component\Routing\Annotation\Route;
use App\Entity\CategorySearch;
use App\Entity\PriceSearch;
use App\Form\CategorySearchType;
use App\Form\PropertySearchType;

class IndexController extends AbstractController
{
/**
 *@Route("/",name="livre_list")
 */
public function home(Request $request)
{
   $propertySearch = new PropertySearch();
   $form = $this->createForm(PropertySearchType::class,$propertySearch);
   $form->handleRequest($request);
   //initialement le tableau des livres est vide,
   //c.a.d on affiche les livres que lorsque l'utilisateur
   //clique sur le bouton rechercher
   $livres= [];
   
   if($form->isSubmitted() && $form->isValid()) {
   //on récupère le titre du livre tapé dans le formulaire
   $titre = $propertySearch->getTitre(); 
   if ($titre!="")
   //si on a fourni un nom d'article on affiche tous les articles ayant ce nom
   $livres= $this->getDoctrine()->getRepository(Livre::class)->findBy(['titre' => $titre] );
   else 
   //si si aucun titre n'est fourni on affiche tous les livres
   $livres= $this->getDoctrine()->getRepository(Livre::class)->findAll();
   }
   return $this->render('articles/index.html.twig',[ 'form' =>$form->createView(), 'livres' => $livres]); 
   }
  


/**
 * @Route("/livre/save")              
 */
 public function save() {
 $entityManager = $this->getDoctrine()->getManager();
 $repo= $this->getDoctrine()->getRepository(Librairie::class);
 
 $livre = new Livre();
 $livre->setTitre('Le tour du monde en 80 jours');
 $livre->setNbPages(320);
 $livre->setAuteur("Jules Vernes");
 $Library=$repo->find(1);
 $livre->setLibrairie($Library);
 
 $entityManager->persist($livre);
 $entityManager->flush();
 return new Response('livre enregisté avec id '.$livre->getId());
 }
/**
 * @Route("/livre/new", name="new_livre")
 * Method({"GET", "POST"})
 */

 public function new(Request $request) {
 $livre = new Livre();
 $form = $this->createForm(LivreType::class,$livre);
 $form->handleRequest($request);
 if($form->isSubmitted() && $form->isValid()) {
 $livre = $form->getData();
 $entityManager = $this->getDoctrine()->getManager();
 $entityManager->persist($livre);
 $entityManager->flush();
 return $this->redirectToRoute('livre_list');
 }
 return $this->render('articles/new.html.twig',['form' => $form->createView()]);
 }
    /**
 * @Route("/livre/{id}", name="livre_show")
 */
 public function show($id) {
    $livre = $this->getDoctrine()->getRepository(Livre::class)
    ->find($id);
    return $this->render('articles/show.html.twig',
    array('Livre' => $livre));
     }
   /**
 * @Route("/livre/edit/{id}", name="edit_livre")
 * Method({"GET", "POST"})
 */
 public function edit(Request $request, $id) {
    $livre = new Livre();
    $livres = $this->getDoctrine()->getRepository(Livre::class)->find($id);
                        
    $form = $this->createFormBuilder($livres)
    ->add('auteur', TextType::class)
    ->add('titre', TextType::class)
    ->add('save', SubmitType::class, array('label' => 'Modifier'))->getForm();
    
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
    
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->flush();
    
    return $this->redirectToRoute('livre_list');
    }
    
    return $this->render('articles/edit.html.twig', ['form' => $form->createView()]);
    }
    /**
 * @Route("/livre/delete/{id}",name="delete_livre")
 * @Method({"DELETE"})
 */
 public function delete(Request $request, $id) {
    $article = $this->getDoctrine()->getRepository(Livre::class)->find($id);
    
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($article);
    $entityManager->flush();
    
    $response = new Response();
    $response->send();
    return $this->redirectToRoute('livre_list');
    }
 /**
 * @Route("/newLibrairie", name="new_librairie")
 * Method({"GET", "POST"})
 */
public function newLibrairie(Request $request) {
   $Librairie = new Librairie();
   $form = $this->createForm(LibrairieType::class,$Librairie);
   $form->handleRequest($request);
   if($form->isSubmitted() && $form->isValid()) {
   $article = $form->getData();
   $entityManager = $this->getDoctrine()->getManager();
   $entityManager->persist($Librairie);
   $entityManager->flush();
   }
  return $this->render('articles/newLibrairie.html.twig',['form'=>$form->createView()]);
   }
   
/**
 * @Route("/livre_cat/", name="livre_par_cat")
 * Method({"GET", "POST"})
 */
 public function livresParCategorie(Request $request) {
 $categorySearch = new CategorySearch();
 $form = $this->createForm(CategorySearchType::class,$categorySearch);
 $form->handleRequest($request);
 $livres= [];
 if($form->isSubmitted() && $form->isValid()) {
   $category = $categorySearch->getCategory();
   
   if ($category!="")
  $livres= $category->getRelationships();
   else 
   $livres= $this->getDoctrine()->getRepository(Livre::class)->findAll();
   }
   
   return $this->render('articles/articlesParCategorie.html.twig',['form' => $form->createView(),'livres' => $livres]);
   } 
   /**
 * @Route("/livre_prix/", name="livre_par_prix")
 * Method({"GET"})
 */
 public function livresParPrix(Request $request)
 {
 
 $priceSearch = new PriceSearch();
 $form = $this->createForm(PriceSearchType::class,$priceSearch);
 $form->handleRequest($request);
 $livres= [];
 if($form->isSubmitted() && $form->isValid()) {
 $minPrice = $priceSearch->getMinPrice();
 $maxPrice = $priceSearch->getMaxPrice();
 $livres = $this->getDoctrine()->getRepository(Livre::class)->findByPriceRange($minPrice,$maxPrice);

 //>findByPriceRange($minPrice,$maxPrice);
 }
 return $this->render('articles/LivresParPrix.html.twig',[ 'form' =>$form->createView(), 'livres' => $livres]); }
}