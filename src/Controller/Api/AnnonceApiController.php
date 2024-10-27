<?php

namespace App\Controller\Api;

use App\Entity\Annonce;
use App\Entity\Entreprise;
use App\Entity\Metier;
use App\Entity\Ville;
use App\Entity\Contrat;
use App\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

#[Route('/api/annonce', name:'app_api')]
class AnnonceApiController extends AbstractController
{
    #[Route('/add', name: 'add_annonce', methods:'POST')]
    public function addAnnonce(Request $request, EntityManagerInterface $entityManager, HttpClientInterface $client) : JsonResponse
    {
        $json = $request->getContent();
        $data = json_decode($json, true);

        //dd($data);

        if( !isset($data['title']) ||
            !isset($data['description']) ||
            !isset($data['reference']) ||
            !isset($data['entreprise']) ||
            !isset($data['ville']) ||
            !isset($data['metier']) ||
            !isset($data['contrat'])){
                throw new Exception("Missing field !");             
        }        

        $entityManager->getConnection()->beginTransaction();
        try{
            if($entityManager->getRepository(Entreprise::class)->findByName($data['entreprise']) == null)
            {
                $entreprise = new Entreprise();
                $entreprise->setName($data['entreprise']);
                $entityManager->persist($entreprise);
            }
            else{
                $entreprise = $entityManager->getRepository(Entreprise::class)->findByName($data['entreprise']);
            }
    
            if($entityManager->getRepository(Ville::class)->findByName($data['ville']) == null)
            {
                $ville = new Ville();
                $ville->setName($data['ville']);
                $entityManager->persist($ville);
            }
            else{
                $ville = $entityManager->getRepository(Ville::class)->findByName($data['ville']);
            }
    
            if($entityManager->getRepository(Metier::class)->findByName($data['metier']) == null)
            {
                $metier = new Metier();
                $metier->setJobName($data['metier']);
                $entityManager->persist($metier);
            }
            else{
                $metier = $entityManager->getRepository(Metier::class)->findByName($data['metier']);
            }
            if($entityManager->getRepository(Contrat::class)->findByName($data['contrat']) == null)
            {
                $contrat = new Contrat();
                $contrat->setTypeContrat($data['contrat']);
                $entityManager->persist($contrat);
            }
            else{
                $contrat = $entityManager->getRepository(Contrat::class)->findByName($data['contrat']);
            }

            if(!isset($data['image']))
            {
                $image = new Image();

                $response = $client->request(
                    'GET',
                    'https://some-random-api.com/animal/cat'
                );
                $content = $response->toArray();

                $image = new Image();
                $image->setUrl($content['image']);
                $entityManager->persist($image);
            }
            else{

                $image = new Image();
                $image->setUrl($data['image']);
                $entityManager->persist($image);
            }
        
            $annonce = new Annonce();
            $annonce
                ->setTitle($data['title'])
                ->setDescription($data['description'])
                ->setReference($data['reference'])
                ->setEntreprise($entreprise)
                ->setVille($ville)
                ->setMetier($metier)
                ->setContrat($contrat)
                ->setImage($image);
    
            $entityManager->persist($annonce);

            $entityManager->flush();
            $entityManager->getConnection()->commit();

        } catch (Exception $e) {
            $entityManager->getConnection()->rollBack();
            throw $e;
        }       


        return new JsonResponse($data, Response::HTTP_CREATED);
    }

    #[Route('/editByReference/{reference}', name: 'edit_annonce', methods:'PUT')]
    public function edit(Request $request, EntityManagerInterface $entityManager, $reference) : JsonResponse
    {
        $json = $request->getContent();
        $data = json_decode($json, true);

        if ($entityManager->getRepository(Annonce::class)->findByReference($reference) == null)
        {
            throw new Exception("Annonce with reference : " . $reference . " doesnt exist !");          
        }

        $annonce = $entityManager->getRepository(Annonce::class)->findByReference($reference);

        $entityManager->getConnection()->beginTransaction();
        try{
            if(isset($data['title'])){
                $annonce->setTitle($data['title']);
            }
    
            if(isset($data['description'])){
                $annonce->setTitle($data['description']);
            }
    
            if(isset($data['entreprise'])){
                if($entityManager->getRepository(Entreprise::class)->findByName($data['entreprise']) == null){
                    $entreprise = new Entreprise();
                    $entreprise->setName($data['entreprise']);
                    $entityManager->persist($entreprise);
    
                }
                else{
                    $entreprise = $entityManager->getRepository(Entreprise::class)->findByName($data['entreprise']);
                }
                $annonce->setEntreprise($entreprise);
                
            }
    
            if(isset($data['ville'])){
                if($entityManager->getRepository(Ville::class)->findByName($data['ville']) == null){
                    $ville = new Ville();
                    $ville->setName($data['entreprise']);
                    $entityManager->persist($ville);
    
                }
                else{
                    $ville = $entityManager->getRepository(Ville::class)->findByName($data['ville']);
                }
                $annonce->setVille($ville);
                
            }
    
            if(isset($data['contrat'])){
                if($entityManager->getRepository(Contrat::class)->findByName($data['contrat']) == null){
                    $contrat = new Contrat();
                    $contrat->setTypeContrat($data['contrat']);
                    $entityManager->persist($contrat);
    
                }
                else{
                    $contrat = $entityManager->getRepository(Contrat::class)->findByName($data['contrat']);
                }
                $annonce->setContrat($contrat);
            }
    
            if(isset($data['metier'])){
                if($entityManager->getRepository(Metier::class)->findByName($data['metier']) == null){
                    $metier = new Metier();
                    $metier->setJobName($data['metier']);
                    $entityManager->persist($metier);
    
                }
                else{
                    $metier = $entityManager->getRepository(Metier::class)->findByName($data['metier']);
                }
                $annonce->setContrat($metier);
            }

            $entityManager->persist($annonce);
            $entityManager->flush();
            $entityManager->getConnection()->commit();

        }
        catch (Exception $e) {
            $entityManager->getConnection()->rollBack();
            throw $e;
        }             

        return new JsonResponse($data, Response::HTTP_CREATED);         
    }

    #[Route('/deleteByReference/{reference}', name: 'delete_annonce', methods:'DELETE')]
    public function deleteAnnonce(Request $request, EntityManagerInterface $entityManager, $reference) : JsonResponse
    {
        $json = $request->getContent();
        $data = json_decode($json, true);

        if ($entityManager->getRepository(Annonce::class)->findByReference($reference) == null)
        {
            throw new Exception("Annonce with reference : " . $reference . " doesnt exist !");          
        }

        $annonce = $entityManager->getRepository(Annonce::class)->findByReference($reference);

        $entityManager->getConnection()->beginTransaction();
        try{ 
            $entityManager->remove($annonce);
            $entityManager->flush();
            $entityManager->getConnection()->commit();

        }
        catch (Exception $e) {
            $entityManager->getConnection()->rollBack();
            throw $e;
        }   

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);         
    }


}