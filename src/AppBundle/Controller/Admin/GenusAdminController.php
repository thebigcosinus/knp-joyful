<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Genus;
use AppBundle\Form\GenusFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin")
 * @Security("is_granted('ROLE_MANAGE_GENUS')")
 */
class GenusAdminController extends Controller
{
    /**
     * @Route("/genus", name="admin_genus_list")
     * @Security("is_granted('ROLE_MANAGE_GENUS')")
     */
    public function indexAction()
    {
//        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
//            throw  $this->createAccessDeniedException('GET OUT');
//        }
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');
        $genuses = $this->getDoctrine()
            ->getRepository('AppBundle:Genus')
            ->findAll();

        return $this->render('admin/genus/list.html.twig', array(
            'genuses' => $genuses
        ));
    }

    /**
     * @Route("/genus/new", name="admin_genus_new")
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm(GenusFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $genus = $form->getData();
            $em = $this->getDoctrine()->getManager();

            $em->persist($genus);
            $em->flush();

            $this->addFlash('success', sprintf('Genus created %s', $this->getUser()->getEmail())

            );

            return $this->redirectToRoute("admin_genus_list");

        }
        return $this->render('admin/genus/new.html.twig', array(
            'genusForm' =>$form->createView()
        ));
    }

    /**
     * @Route("/genus/{id}/new", name="admin_genus_edit")
     */
    public function editAction(Request $request, Genus $genus)
    {
        $form = $this->createForm(GenusFormType::class, $genus);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $genus = $form->getData();
            $em = $this->getDoctrine()->getManager();

            $em->persist($genus);
            $em->flush();

            $this->addFlash('success', 'Genus updated');

            return $this->redirectToRoute("admin_genus_list");

        }
        return $this->render('admin/genus/edit.html.twig', array(
            'genusForm' =>$form->createView()
        ));
    }
}
