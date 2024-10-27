<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnoncesFormType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;



class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->redirectToRoute('jobboard');

    }

    #[Route('/JobBoard', name: 'jobboard', methods: 'GET|POST')]
    public function displayJobBoard(Request $request,EntityManagerInterface $entityManager, PaginatorInterface $paginator) : Response
    {

        $form = $this->createForm(AnnoncesFormType::class);

        $form->handleRequest($request);
        
        if($form->isSubmitted())
        {
            $data = $form->getData();
            
            $query = $entityManager->getRepository(Annonce::class)->createQueryBuilder('ann');

           // FILTRES
            if($data["title"] != null){
                $query                    
                    ->andWhere('ann.title = :item')
                    ->setParameter('item', $data["title"]);
            }
            if($data["createdAt"] != null){
                $query                    
                    ->andWhere('ann.createdAt = :item2')
                    ->setParameter('item2', $data["createdAt"]);
            }
            if($data["metier"] != null){
                $query
                    ->addSelect('metier')
                    ->leftJoin('ann.metier', 'metier')                   
                    ->andWhere('metier.jobName = :item3')
                    ->setParameter('item3', $data["metier"]);
            }
            if($data["contrat"] != null){
                $query
                    ->addSelect('contrat')
                    ->leftJoin('ann.contrat', 'contrat')                   
                    ->andWhere('contrat.typeContrat = :item4')
                    ->setParameter('item4', $data["contrat"]);
            }
            if($data["ville"] != null){
                $query
                    ->addSelect('ville')
                    ->leftJoin('ann.ville', 'ville')                   
                    ->andWhere('ville.name = :item5')
                    ->setParameter('item5', $data["ville"]);
            }
        }
        else{

            $query = $entityManager->getRepository(Annonce::class)->createQueryBuilder('ann')
            ->addSelect('ville')
            ->leftJoin('ann.ville', 'ville')
            ->addSelect('metier')
            ->leftJoin('ann.metier', 'metier')
            ->addSelect('contrat')
            ->leftJoin('ann.contrat', 'contrat')
            ->addSelect('entreprise')
            ->leftJoin('ann.entreprise', 'entreprise')
            ->addOrderBy('ann.createdAt', 'DESC');
        }        


        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );



        return $this->render('home/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView(),
        ]);
    }
}
