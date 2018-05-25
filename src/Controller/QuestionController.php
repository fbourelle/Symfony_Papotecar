<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Travel;
use App\Form\QuestionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class QuestionController extends Controller
{
    /**
     * @Route("/question/{id}", name="question")
     */
    public function questionTravel($id, Request $request, EntityManagerInterface $em)
    {
        if ($this->getUser() && ($this->getUser()->getRoles()[0] == "ROLE_USER" || $this->getUser()->getRoles()[0] == "ROLE_ADMIN")){
            $question = new Question();
            $form = $this->createForm(QuestionType::class, $question);
            $form->handleRequest($request);
var_dump($id);
            if ($form->isSubmitted() && $form->isValid()) {

                $travelRepo = $this->getDoctrine()->getRepository(Travel::class);
                $travel = $travelRepo->findOneBy(['id' => $id]);

                $question->setDatecreated(new \DateTime());
                $question->setTravel($travel);
                $question->setUser($this->getUser());


                $em->persist($question);

                var_dump($question);
                $em->flush();

                $flash = "Envoi effectué";

                $this->addFlash(
                    'success',
                    $flash
                );
                // ... persist the $user variable or any other work

                return $this->redirectToRoute('travel-list');
            }

            return $this->render('question/question.html.twig', [
                'form' => $form->createView(),
            ]);
        }
        else{
            return $this->redirectToRoute('home');
        }
    }
}
