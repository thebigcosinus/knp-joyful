<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Genus;
use AppBundle\Entity\GenusNote;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GenusController extends Controller
{
    /**
     * @Route("/genus" , name="homepagegenus")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $genuses = $em->getRepository('AppBundle:Genus')->findAllPublishedOrderedByRecentlyActive();

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
        $em = $this->getDoctrine()->getManager();
        $genus = new Genus();
        $genus->setName("octopus".mt_rand(1,100));
        $sub = $em->getRepository('AppBundle:SubFamily')->findOneBy(['name'=>'Adams']);
        $genus->setSubFamily($sub);
        $genus->setSpeciesCount(mt_rand(100,99999));
        $genus->setFirstDiscoveredAt(  new \DateTime());

        $genusNote = new GenusNote();
        $genusNote->setCreatedAt(new \Datetime());
        $genusNote->setNote("Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
        proident, sunt in culpa qui officia deserunt mollit anim id est laborum.");
        $genusNote->setUserAvatarFilename('lenna.jpg');
        $genusNote->setUsername('fabien');
        $genusNote ->setGenus($genus);

        
        $em = $this->getDoctrine()->getManager();
        
        $user = $em->getRepository('AppBundle:User')
            ->findOneBy(['email' => 'weaverryan+10@gmail.com']);
        
        $genus->addGenusScientists($user);
        
        $em->persist($genus);
        $em->persist($genusNote);
        $em->flush();

        return new Response('<html><body>Genus Created</body></html>');

    }
    /**
     * @Route("/genus/{slug}", name="genus_show")
     */
    public function showAction(Genus $genus)
    {
        $em = $this->getDoctrine()->getManager();
        //$genus = $em->getRepository('AppBundle:Genus')->findOneBy(['slug' => $genusName]);

       /* if (!$genus) {
             throw $this->createNotFoundException('No genus found');
        }*/
        $this->get('logger')
            ->info('Showing genus:'.$genus->getName());

        /*$recentNotes = $genus->getNotes()->filter(function(GenusNote $note) {
                return $note->getCreatedAt() > new \DateTime('-3 months');
        });*/
        $recentNotes = $em->getRepository('AppBundle:GenusNote')->findAllRecentNotesForGenus($genus);

        //$transformer =  new MarkdownTransformer($this->get('markdown.parser'));
        $transformer = $this->get('app.markdown_transformer');
        $funFact =  $transformer->parse($genus->getFunFact());


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
            'funFact' => $funFact,
            'recentNoteCount' =>count($recentNotes)
        ));
    }

    /**
     * @Route("/genus/{slug}/notes", name="genus_show_notes")
     * @Method("GET")
     */
    public function getNotesAction(Genus $genus)
    {
        $notes = [];
        foreach ($genus->getNotes() as $note) {
            $notes[] = [
                'id' => $note->getId(),
                'username' => $note->getUsername(),
                'avatarUri' => '/images/'.$note->getUserAvatarFilename(),
                'note' => $note->getNote(),
                'date' => $note->getCreatedAt()->format('M d, Y')

            ];
        }

        $data = [
            'notes' => $notes
        ];
        return new JsonResponse($data);
    }

    /**
     * @Route("/genus/{genusId}/scientist/{userId}", name="genus_scientist_remove")
     * @Method("DELETE")
     */
    public function removeGenusScientistAction($genusId, $userId)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Genus $genus */
        $genus = $em->getRepository('AppBundle:Genus')
            ->find($genusId);

        if ($genus) {
            throw $this->createNotFoundException('genus not found');
        }

        /** @var User $genusScientist */
        $genusScientist = $em->getRepository('AppBundle:User')
            ->find($userId);

        if ($genusScientist) {
            throw $this->createNotFoundException('genusScientist not found');
        }
        $genus->removeGenusScientist($genusScientist);
        $em->persist($genus);
        $em->flush();

        return new Response(null, 204);
    }
}
