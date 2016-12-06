<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Genus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GenusController extends Controller
{
    /**
     * @Route("/genus")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $genuses = $em->getRepository('AppBundle:Genus')->findAllPublishedOrderedBySize();

        return $this->render(
            'genus/list.html.twig',
            [
                'genuses' => $genuses,
            ]
        );

    }
    /**
     * @Route("/genus/new")
     */
    public function newAtion()
    {
        $genus = new Genus();
        $genus->setName("octopus".mt_rand(1,100));
        $genus->setSubFamily('Octopidaine');
        $genus->setSpeciesCount(mt_rand(100,99999));

        $em = $this->getDoctrine()->getManager();
        $em->persist($genus);
        $em->flush();

        return new Response('<html><body>Genus Created</body></html>');

    }
    /**
     * @Route("/genus/{genusName}", name="genus_show")
     */
    public function showAction($genusName)
    {
        $em = $this->getDoctrine()->getManager();
        $genus = $em->getRepository('AppBundle:Genus')->findOneBy(['name' => $genusName]);

        if (!$genus) {
             throw $this->createNotFoundException('No genus found');
        }
       

//        $markown = $this->get('markdown.parser');
//        $funFact = 'une petite phrases en **gras**';
//        $cache  = $this->get('doctrine_cache.providers.my_markown_cache');
//
//        $key = md5($funFact);
//        if ($cache->contains($key)) {
//            $funFact = $cache->fetch($key);
//        } else {
//            sleep(1);
//            $funFact = $markown->transform($funFact);
//            $cache->save($key, $funFact);
//        }

        
        return $this->render('genus/show.html.twig', array(
            'genus' => $genus,
        ));
    }

    /**
     * @Route("/genus/{genusName}/notes", name="genus_show_notes")
     * @Method("GET")
     */
    public function getNotesAction($genusName)
    {
        $notes = [
            ['id' => 1, 'username' => 'AquaPelham', 'avatarUri' => '/images/leanna.jpeg', 'note' => 'Octopus asked me a riddle, outsmarted me', 'date' => 'Dec. 10, 2015'],
            ['id' => 2, 'username' => 'AquaWeaver', 'avatarUri' => '/images/ryan.jpeg', 'note' => 'I counted 8 legs... as they wrapped around me', 'date' => 'Dec. 1, 2015'],
            ['id' => 3, 'username' => 'AquaPelham', 'avatarUri' => '/images/leanna.jpeg', 'note' => 'Inked!', 'date' => 'Aug. 20, 2015'],
        ];
        $data = [
            'notes' => $notes
        ];

        return new JsonResponse($data);
    }
}
