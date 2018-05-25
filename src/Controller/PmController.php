<?php

namespace App\Controller;

use App\Entity\PrivateMessages;
use App\Entity\Travel;
use App\Entity\User;
use App\Form\PrivateMessagesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PmController extends Controller
{
    /**
     * @Route("/pm/{id}", name="pm")
     */
    public function addPm($id,
                          Request $request,
                          EntityManagerInterface $em)
    {
        // $id = $id de trajet

        //TO DO : récupérer le user à partir de l'id
        $userRepo = $this->getDoctrine()->getRepository(User::class);
//        var_dump($userRepo);
        $userTarget = $userRepo->find($id);

        //Récupère le User du trajet
//        $userTarget = $user->getUserid();
//        var_dump($userTarget);
        $pm = new PrivateMessages();
        //crée le formulaire en lui associant notre review vide
        $pmForm = $this->createForm(PrivateMessagesType::class, $pm);

        $pmForm->handleRequest($request);

        //si le formulaire est soumis && si il est valide
        if ($pmForm->isSubmitted() && $pmForm->isValid()) {
            $pm->setDateCreated(new \DateTime());
            $pm->setUsertargetid($userTarget);
            $pm->setUserlauncherid($this->getUser());
            //Prend les données envoyées et les injecte dans $review
            //on sauvegarde l'entité en BDD
            $em->persist($pm);
            $em->flush();
            //Stocke un message et envoie un message
            $this->addFlash("success", "Your message has been send !");
            return $this->redirectToRoute('profil_user', ["id" => $this->getUser()->getId()]);
        }

        return $this->render('pm/add-pm.html.twig', [
            'target_username' => $userTarget->getUsername(),
            "pmForm" => $pmForm->createView(),
        ]);
    }


    /**
     * @Route("/pm/watch/{id}", name="pmwatch")
     */
    public function watchPm($id,
                            Request $request,
                            EntityManagerInterface $em)
    {
        $userPM = $this->getDoctrine()->getRepository(PrivateMessages::class)->findBy(["userlauncherid" => $id]);
        $userPMTarget = $this->getDoctrine()->getRepository(PrivateMessages::class)->findBy(["usertargetid" => $id]);
//                        var_dump($userPM);
        return $this->render('pm/watchpm.html.twig', [
            'userpmlauncher' => $userPM,
            'userpmtarget' => $userPMTarget,
            "userInfos" => $this->getUser()
        ]);
    }

}