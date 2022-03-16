<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Commerce;
use App\Form\CommerceType;
use App\Form\PortefeuilleType;
use App\Entity\Portefeuille;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ActionController extends AbstractController
{
    /**
     * @Route("/addUser", name="add_user")
     */
    public function addUser(Request $request, EntityManagerInterface $em): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            $em->persist($user);
            $em->flush();
        }

        return $this->render('action/user.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/addCommerce", name="add_commerce")
     */
    public function addCommerce(Request $request, EntityManagerInterface $em): Response
    {
        $commerce = new Commerce();
        $form = $this->createForm(CommerceType::class, $commerce);
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            $em->persist($commerce);
            $em->flush();
        }

        return $this->render('action/commerce.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/addPortefeuille", name="add_portefeuille")
     */
    public function addPortefeuille(Request $request, EntityManagerInterface $em): Response
    {
        $portefeuille = new Portefeuille();
        $form = $this->createForm(PortefeuilleType::class, $portefeuille);
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            $em->persist($portefeuille);
            $em->flush();
        }

        return $this->render('action/portefeuille.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
