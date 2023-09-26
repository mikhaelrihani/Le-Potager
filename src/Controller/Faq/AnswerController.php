<?php

namespace App\Controller\Faq;

use App\Entity\Answer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/faq/answer")
 */
class AnswerController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * @Route("validate/{id}", name="app_faq_answer_validate")
     */
    public function validate(Answer $answer)
    {
        //! ajouter issolved dans entity
        $this->denyAccessUnlessGranted("QUESTION_ANSWER_VALIDATE", $answer->getQuestion());

        $answer->setIsValidated(true);

        $answer->getQuestion()->setIsSolved(true);

        $this->em->flush();

        $this->addFlash('success', 'Réponse acceptée');

        return $this->redirectToRoute('app_faq_question_show', ['id' => $answer->getQuestion()->getId()]);
    }

    /**
     * @Route("toggle/{id}", name="app_faq_answer_toggle")
     */

    //! creer un service toggle pour eviter la repetition
    public function adminToggle(Answer $answer = null)
    {
        if (null === $answer) {
            throw $this->createNotFoundException('Réponse non trouvée.');
        }

        $answer->setIsBlocked(!$answer->getIsBlocked());

        $this->em->flush();

        $this->addFlash('success', 'Réponse modérée.');

        return $this->redirectToRoute('app_faq_question_show', ['id' => $answer->getQuestion()->getId()]);
    }

}