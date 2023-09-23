<?php

namespace App\Controller\Faq;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/faq/tag")
 */
class TagController extends AbstractController
{
    //! mettre un em global avec request
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * @Route("/list", name="app_faq_tag_list", methods="GET")
     */
    public function list(TagRepository $tagRepository): Response
    {
        return $this->render('faq/tag/list.html.twig', ['tags' => $tagRepository->findAll()]);
    }

    /**
     * @Route("/new", name="app_faq_tag_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        
            $this->em->persist($tag);
            $this->em->flush();

            $this->addFlash('success', 'Tag ajouté.');

            return $this->redirectToRoute('app_faq_tag_list');
        }

        return $this->render('faq/tag/new.html.twig', [
            'tag'  => $tag,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_faq_tag_show", methods="GET")
     */
    public function show(Tag $tag): Response
    {
        return $this->render('faq/tag/show.html.twig', ['tag' => $tag]);
    }

    /**
     * @Route("/{id}/edit", name="app_faq_tag_edit", methods="GET|POST")
     */
    public function edit(Request $request, Tag $tag): Response
    {
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            $this->addFlash('success', 'Tag modifié.');

            return $this->redirectToRoute('app_faq_tag_edit', ['id' => $tag->getId()]);
        }

        return $this->render('faq/tag/edit.html.twig', [
            'tag'  => $tag,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_faq_tag_delete", methods="POST")
     */
    public function delete(Request $request, Tag $tag): Response
    {
        if ($this->isCsrfTokenValid('delete' . $tag->getId(), $request->request->get('_token'))) {
          
            $this->em->remove($tag);
            $this->em->flush();

            $this->addFlash('success', 'Tag supprimé.');

        }

        return $this->redirectToRoute('app_faq_tag_list');
    }
}