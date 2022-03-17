<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\CommerceRepository;
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

        }else{
            $portefeuille = $portefeuilleRepo->findByUser($user);
            
        }

        foreach ($portefeuille as $element) {
            $data[] =
            [
                'client' => $element->getUser()->getNom(),
                'commerce' => $element->getCommerce()->getNom(),
                'solde' => $element->getSolde(),
            ];
        }

        $response = new JsonResponse($data);        
        // Use the JSON_PRETTY_PRINT 
        $response->setEncodingOptions( $response->getEncodingOptions() | JSON_PRETTY_PRINT );
        
        return $response;
    }
}
