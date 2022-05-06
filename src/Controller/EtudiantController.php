<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Form\EtudiantType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


//Route par défaut pour éviter la réecriture
#[Route('/etudiant')]

class EtudiantController extends AbstractController
{
    #[Route('/etudiant', name: 'app_etudiant')]
    public function index(): Response
    {
        return $this->render('etudiant/index.html.twig', [
            'controller_name' => 'EtudiantController',
        ]);
    }

//pour la pagination dans l'affichage
//une route permettant d'afficher les étudiants avec une pagination automatique
    #[Route('/{page<\d+>?1}/{nbre<\d+>?10}',
        name: 'etudiant.list')]
    public function indexAll(ManagerRegistry $doctrine, $page, $nbre): Response
    {
        $repository = $doctrine->getRepository(Etudiant::class);
        $nbPersonne = $repository->count([]);
        $nbrePage = ceil($nbPersonne / $nbre);
        $etudiants = $repository->findBy([], [], $nbre, ($page - 1) * $nbre);

        return $this->render('etudiant/index.html.twig', [
            'etudiants' => $etudiants,
            'isPaginated' => true,
            'nbrePage' => $nbrePage,
            'page' => $page,
            'nbre' => $nbre
        ]);
    }
    #[Route('/edit/{id?0}', name: 'etudiant.edit')]
    public function addPersonne(Etudiant $etudiant = null, ManagerRegistry $doctrine, Request $request): Response
    {
        $new = false;
        //$this->getDoctrine() : Version Sf <= 5
        if (!$etudiant) {
            $new = true;
            $etudiant = new Etudiant();
        }

        // $etudiant est l'image de notre formulaire
        $form = $this->createForm(EtudiantType::class, $etudiant);
        // On va aller traiter la requete
        $form->handleRequest($request);
        //Si le formulaire a été soumis
        if($form->isSubmitted()) {
            // si oui,
            // on va appender l'etudiant dans la bd
            $manager = $doctrine->getManager();
            $manager->persist($etudiant);

            $manager->flush();
            // Afficher un mssage de succès
            if($new) {
                $message = " a été ajouté avec succès !";
            } else {
                $message = " a été mis à jour avec succès !";
            }
            $this->addFlash('success',$etudiant->getNom(). $message );
            // Rediriger vers la liste des etudiant
            return $this->redirectToRoute('etudiant.list');
        }

        //Sinon on affiche notre formulaire
        return $this->render('etudiant/add.html.twig', [
            'form' => $form->createView()
        ]);

    }

    //route pour deleter une personne
    #[Route('/delete/{id}', name: 'etudiant.delete')]
    public function deletePersonne(Etudiant $etudiant = null, ManagerRegistry $doctrine): RedirectResponse {
        // Récupérer la etudiant
        if ($etudiant) {
            // Si la etudiant existe => le supprimer et retourner un flashMessage de success
            $manager = $doctrine->getManager();
            // Ajoute la fonction de suppression dans la transaction
            $manager->remove($etudiant);
            // Exécuter la transacition
            $manager->flush();
            $this->addFlash('success', "L'étudiant a été supprimé avec succès");
        } else {
            //Sinon  retourner un flashMessage d'erreur
            $this->addFlash('error', "etudiant n'existe pas !");
        }
        return $this->redirectToRoute('etudiant.list');
    }








}
