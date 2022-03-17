<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\CommerceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PortefeuilleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api")
 */
class PortefeuilleController extends AbstractController
{
    /**
     * @Route("/portefeuille/{nom}/{nomCommerce}", name="app_portefeuille")
     * @Entity("user", expr="repository.findOneByNom(nom)")
     */
    public function showPortefeuille(User $user,PortefeuilleRepository $portefeuilleRepo,
        $nomCommerce = null, CommerceRepository $commerceRepo): JsonResponse
    {
        session_start();
        if($nomCommerce != null){
            $commerce = $commerceRepo->findOneByNom($nomCommerce);
            $portefeuille = $portefeuilleRepo->findByUserAndCommerce($user, $commerce);
            $data =
            [
                'client' => $portefeuille->getUser()->getNom(),
                'commerce' => $portefeuille->getCommerce()->getNom(),
                'solde' => $portefeuille->getSolde(),
            ];
        }else{
            $portefeuille = $portefeuilleRepo->findByUser($user);

            foreach ($portefeuille as $element) {
                $data[] =
                [
                    'client' => $element->getUser()->getNom(),
                    'commerce' => $element->getCommerce()->getNom(),
                    'solde' => $element->getSolde(),
                ];
            }
            
        }
        // dd($portefeuille);
        

        $response = new JsonResponse($data);        
        // Use the JSON_PRETTY_PRINT 
        $response->setEncodingOptions( $response->getEncodingOptions() | JSON_PRETTY_PRINT );
        
        return $response;
    }

    /**
     * @Route("/debit/portefeuille/{nom}/{nomCommerce}/{prix}", name="app_debit_portefeuille")
     * @Route("/credit/portefeuille/{nom}/{nomCommerce}/{prix}", name="app_credit_portefeuille")
     * @Entity("user", expr="repository.findOneByNom(nom)")
     */
    public function debitCreditPortefeuille(User $user,PortefeuilleRepository $portefeuilleRepo,CommerceRepository $commerceRepo,
        EntityManagerInterface $em, $nomCommerce, $prix, $_route): JsonResponse
    {
        session_start();
        $commerce = $commerceRepo->findOneByNom($nomCommerce);
        $portefeuille = $portefeuilleRepo->findByUserAndCommerce($user, $commerce);
        if($_route =="app_debit_portefeuille"){
            $newSolde = $portefeuille->getSolde() - $prix;

        }else{
            if($prix == 20){
                $newSolde = $portefeuille->getSolde() + $prix + 1;
            }elseif($prix == 50){
                $newSolde = $portefeuille->getSolde() + $prix + 2.5;
            }elseif($prix == 100){
                $newSolde = $portefeuille->getSolde() + $prix + 10;
            } 
        }

        if($newSolde >= 0){
            $portefeuille->setSolde($newSolde);
            $em->persist($portefeuille);
            $em->flush();
            $data =
            [
                'client' => $portefeuille->getUser()->getNom(),
                'commerce' => $portefeuille->getCommerce()->getNom(),
                'solde' => $portefeuille->getSolde(),
            ];

        }else{
            $data =[
                'error'=>'Votre solde est insuffisant pour effectuer cet achat'
            ];
        }

        $response = new JsonResponse($data);        
        // Use the JSON_PRETTY_PRINT 
        $response->setEncodingOptions( $response->getEncodingOptions() | JSON_PRETTY_PRINT );
        
        return $response;
    }
}
