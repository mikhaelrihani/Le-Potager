<?php

namespace App\Controller\Faq;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Tag;
use App\Form\AnswerType;
use App\Form\QuestionType;
use App\Repository\AnswerRepository;
use App\Repository\QuestionRepository;
use App\Repository\UserRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/faq/question")
 */
class QuestionController extends AbstractController
{
    //! faire les routes api 
    //! menu deroulant de la navbar pour chaque partie 
    //! personnaliser les 404
    //! completer les wireframes
    //! mettre a jour insomnia faq
    //! regler l histoire des routes / en url
    //! csrf token ?
    //! slugify?
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     *! @Route("/list", name="app_faq_question_list")
     * List all questions
     * 
     *! @Route("/tag/{name}", name="app_faq_question_list_by_tag")
     * List all questions by tag
     */
    public function list(Request $request, QuestionRepository $questionRepository, Tag $tag = null)
    {

        // on traite le cas ou le tag n'existe pas  
        if ($request->attributes->get('_route') == 'question_list_by_tag' && $tag === null) {

            $params = $request->attributes->get('_route_params');
            $selectedTag = $params[ 'name' ];
            $this->addFlash('success', 'Le mot-clé "' . $selectedTag . '" n\'existe pas !');

            return $this->redirectToRoute('app_faq_question_list');
        }

        // on traite le cas ou le tag existe 
        if ($tag) {
            $questions = $questionRepository->findByTag($tag->getName());
            $selectedTag = $tag->getName();

            // on traite le cas ou il n'a pas de tag renseigné et on recupere les questions qui n'ont pas été blocké
        } else {
            $questions = $questionRepository->findBy(['isBlocked' => false], ['updatedAt' => 'DESC']);
            $selectedTag = null;
        }

        // on recupere tous les tags pour le rendu twig
        $tags = $this->em->getRepository(Tag::class)->findBy([], ['name' => 'ASC']);

        return $this->render('faq/question/list.html.twig', [

            'questions'   => $questions,
            'tags'        => $tags,
            'selectedTag' => $selectedTag,
        ]);
    }

    /**
     *! @Route("/{id}", name="app_faq_question_show", requirements={"id": "\d+"})
     * On affiche la question choisie et verifie si pas bloqué,
     * et traite la reponse de l'utilisateur connecté 
     */
    public function show(Question $question, Request $request, AnswerRepository $answerRepository)
    {

        // si l'utilisateur essaie d'afficher une question par son url et que la question est bloqué on ne l'affiche  
        if ($question->getIsBlocked()) {
            throw $this->createAccessDeniedException('La question a été bloquée');
        }
        //! afficher la question si admin et gerer un template twig
        $answer = new Answer();
        $form = $this->createForm(AnswerType::class, $answer);

        // si l'utilisateur envoie une reponse, 
        // et si la question est toujours active(d'actualité),
        // on verifie la reponse 
        $form->handleRequest($request);
        if ($question->getIsActive()) {
            if ($form->isSubmitted() && $form->isValid()) {

                // On associe la réponse avec la question et l'utilisateur
                $answer->setQuestion($question);
                $answer->getQuestion()->setUpdatedAt(new \DateTimeImmutable);
                $answer->setUser($this->getUser());

                $this->em->persist($answer);
                $this->em->flush();

                $this->addFlash('success', 'Réponse ajoutée');
                //! penser a retourner un email a celui qui a envoye la questions avec mailer
                return $this->redirectToRoute('app_faq_question_show', ['id' => $question->getId()]);
            }
        }

        // on recupere toutes les reponses non bloquées pour le rendu twig
        $answersNonBlocked = $answerRepository->findBy([
            'question'  => $question,
            'isBlocked' => false,
        ]);

        return $this->renderForm('faq/question/show.html.twig', [
            'form'              => $form,
            'question'          => $question,
            'answersNonBlocked' => $answersNonBlocked,
        ]);
    }
    /**
     *! @Route("/add", name="app_faq_question_add")
     * on ajoute une question
     */
    public function add(Request $request)
    {
        $question = new Question();

        $form = $this->createForm(QuestionType::class, $question);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $question->setUser($this->getUser());

            $this->em->persist($question);
            $this->em->flush();

            $this->addFlash('success', 'Question ajoutée');

            return $this->redirectToRoute('app_faq_question_show', ['id' => $question->getId()]);
        }

        return $this->renderForm('faq/question/add.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     *! @Route("/edit/{id}", name="app_faq_question_edit")
     * on update une question
     */
    public function edit(Question $question, Request $request)
    {

        $this->denyAccessUnlessGranted("QUESTION_EDIT", $question);

        $form = $this->createForm(QuestionType::class, $question);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $question->setUpdatedAt(new \DateTimeImmutable);
            $this->em->persist($question);
            $this->em->flush();

            $this->addFlash('success', 'Question modifiée');

            return $this->redirectToRoute('app_faq_question_show', ['id' => $question->getId()]);
        }

        return $this->renderForm('faq/question/add.html.twig', [
            'form' => $form,
        ]);
    }


    /**
     * @Route("/toggle/{id}", name="app_faq_question_toggle")
     */
    public function adminToggle(Question $question = null)
    {
        if ($question === null) {
            throw $this->createNotFoundException('Question non trouvée.');
        }
        //! penser a gerer un template error
        // Inverse the boolean value via not (!)
        $question->setIsBlocked(!$question->getIsBlocked());

        $this->em->persist($question);
        $this->em->flush();

        $this->addFlash('success', 'Question modérée.');

        return $this->redirectToRoute('app_faq_question_show', ['id' => $question->getId()]);
    }

}