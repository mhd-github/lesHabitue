<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Repository\UserRepository;
use App\Repository\CommerceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PortefeuilleRepository;
use Symfony\Component\HttpFoundation\Request;
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
     * Consulter un portefeuille
     * 
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

        $response = new JsonResponse($data);        
        // Use the JSON_PRETTY_PRINT 
        $response->setEncodingOptions( $response->getEncodingOptions() | JSON_PRETTY_PRINT );
        
        return $response;
    }

    /**
     * Crediter et debiter un portefeuille
     * 
     * 
     * @Route("/debit/portefeuille", methods={"POST"}, name="app_debit_portefeuille")
     * @Route("/credit/portefeuille", methods={"POST"}, name="app_credit_portefeuille")
     */
    public function debitCreditPortefeuille(PortefeuilleRepository $portefeuilleRepo, UserRepository $userRepo, CommerceRepository $commerceRepo,
        EntityManagerInterface $em,Request $request, $_route): Response
    {
        $transaction = new Transaction();
        $form=$this->createForm(TransactionType::class,$transaction);
        $data=json_decode($request->getContent(),true);
        
        
        $commerce = $commerceRepo->findOneByNom($data["commerce"]);
        $user = $userRepo->findOneByNom($data["client"]);
        $portefeuille = $portefeuilleRepo->findByUserAndCommerce($user, $commerce);
        $form->submit($data); 
        $transaction->setClient($user);
        $transaction->setCommerce($commerce);

        if($_route =="app_debit_portefeuille"){
            $newSolde = $portefeuille->getSolde() - $data["prix"];

        }else{
            if($data["prix"] == 20){
                $newSolde = $portefeuille->getSolde() + $data["prix"] + 1;
            }elseif($data["prix"] == 50){
                $newSolde = $portefeuille->getSolde() + $data["prix"] + 2.5;
            }elseif($data["prix"] == 100){
                $newSolde = $portefeuille->getSolde() + $data["prix"] + 10;
            }else{
                $newSolde = $portefeuille->getSolde() + $data["prix"];
            }
        }

        if($newSolde >= 0){
            $em->persist($transaction);
            $em->persist($portefeuille->setSolde($newSolde));
            $em->flush();
            $response = new Response(json_encode($data), 200);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        $response = new Response(json_encode($data), 403);
            $response->headers->set('Content-Type', 'application/json');
            return $response;       
        
    }
}
