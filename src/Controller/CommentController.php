<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Offense;
use App\Entity\Reclamation;
use App\Entity\Travel;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\OffenseType;
use App\Form\ReclamationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CommentController extends Controller
{
     /**
     * @Route("/comment/user/{id_trajet}/{id_cible}", name="commentUserOnTravel")
     */
    public function commentUserOnTravel(EntityManagerInterface $em, Request $request, $id_trajet, $id_cible)
    {

        if ($this->getUser()){
            if ($this->getUser()->getRoles()[0] == "ROLE_USER" || $this->getUser()->getRoles()[0] == "ROLE_ADMIN"){

                $commentRepo = $this->getDoctrine()->getRepository(Comment::class);
                $comment = $commentRepo->findOneBy(['userlauncher'=>$this->getUser()->getId(), 'usertarget' => $id_cible, 'travel' => $id_trajet]);

                $userRepo = $this->getDoctrine()->getRepository(User::class);
                $user = $userRepo->findOneBy(['id'=>$id_cible]);

                if ($comment != null){
                    $flash = 'Vous avez déjà évaluer '.$user->getUsername().' sur ce trajet';

                    $this->addFlash(
                        'warning',
                        $flash
                    );

                    return $this->redirectToRoute('home');
                }
                else{
                    $comment = new Comment();
                    $form = $this->createForm(CommentType::class, $comment);
                    $form->handleRequest($request);



                    if ($form->isSubmitted() && $form->isValid()) {

                        $travelRepo = $this->getDoctrine()->getRepository(Travel::class);
                        $travel = $travelRepo->findOneBy(['id'=>$id_trajet]);

                        $comment->setDatecreated(new \DateTime());

                        $comment->setTravel($travel);
                        $comment->setUserlauncher($this->getUser());
                        $comment->setUsertarget($user);
                        $comment->setDisplay(true);

                        $em->persist($comment);
                        $em->flush();

                        $flash = "Commentaire effectué";

                        $this->addFlash(
                            'success',
                            $flash
                        );
                        // ... persist the $user variable or any other work

                        return $this->redirectToRoute('home'); // a changer
                    }

                    return $this->render('comment/comment.html.twig', [
                        'form' => $form->createView(),
                        'cible' => $user
                    ]);
                }
            }
            else{
                $flash = "Veuillez d'abords confirmer votre compte";

                $this->addFlash(
                    'warning',
                    $flash
                );

                return $this->redirectToRoute("home");//a changer
            }
        }
        else{
            $flash = "Veuillez d'abords vous connecter";

            $this->addFlash(
                'warning',
                $flash
            );

            return $this->redirectToRoute("login");
        }

    }

    /**
     * @Route("/comment/admin/show/{id_comment}", name="commentAdminShow")
     */
    public function commentShow($id_comment){
        if ($this->getUser() && $this->getUser()->getRoles()[0] == "ROLE_ADMIN") {
            $commentRepo = $this->getDoctrine()->getRepository(Comment::class);
            $comment = $commentRepo->findOneBy(['id'=>$id_comment]);

            if ($comment == null){
                $flash = "Ce commentaire n'existe pas";

                $this->addFlash('warning', $flash);

                return $this->redirectToRoute('home');
            }
            else{
                return $this->render('comment/commentAdmin.html.twig',
                    [
                        'comment' => $comment
                    ]
                );
            }

        }
        else{
            return $this->redirectToRoute('home');
        }
    }



    /**
     * @Route("/comment/admin/del", name="commentAdminDelete")
     */
    public function commentDelete(EntityManagerInterface $em){
        if ($this->getUser() && $this->getUser()->getRoles()[0] == "ROLE_ADMIN") {
            $id_comment = $_GET['comment_id'];

            $commentRepo = $this->getDoctrine()->getRepository(Comment::class);
            $comment = $commentRepo->findOneBy(['id'=>$id_comment]);

            if ($comment == null){
                $flash = "Ce commentaire n'existe pas";

                $this->addFlash('warning', $flash);

                return $this->redirectToRoute('home');
            }
            else{
                $comment->setDisplay(false);
                $em->flush();

                $flash = "Commentaire archivé";

                $this->addFlash('success', $flash);

                return $this->redirectToRoute('home');
            }

        }
        else{
            return $this->redirectToRoute('home');
        }
    }


    /**
     * @Route("/comment/admin/offense/{id_comment}/{id_offenser}", name="commentOffense")
     */
    public function commentOffense(EntityManagerInterface $em, Request $request, $id_comment, $id_offenser, \Swift_Mailer $mailer)
    {

        if ($this->getUser()){
            if ($this->getUser()->getRoles()[0] == "ROLE_ADMIN") {

                $commentRepo = $this->getDoctrine()->getRepository(Comment::class);
                $comment = $commentRepo->findOneBy(['id'=> $id_comment ]);

                $userRepo = $this->getDoctrine()->getRepository(User::class);
                $user = $userRepo->findOneBy(['id'=>$id_offenser]);

                if ($comment == null){
                    $flash = 'Ce commentaire n\'existe pas';

                    $this->addFlash(
                        'warning',
                        $flash
                    );

                    return $this->redirectToRoute('home');
                }
                elseif ($user == null){
                    $flash = 'Cette utilisateur n\'existe pas';

                    $this->addFlash(
                        'warning',
                        $flash
                    );

                    return $this->redirectToRoute('home');
                }
                else{
                    $offense = new Offense();
                    $form = $this->createForm(OffenseType::class, $offense);
                    $form->handleRequest($request);

                    if ($form->isSubmitted() && $form->isValid()) {

                        $offense->setDate(new \DateTime());
                        $offense->setAdmin($this->getUser());
                        $offense->setUser($user);

                        $em->persist($offense);
                        $em->flush();


                        $message = (new \Swift_Message('Alerte Commentaire supprimé'))
                            ->setFrom('dave.lopper0@gmail.com')
                            ->setTo($user->getEmail());

                        $data['image_src'] = $message->embed(\Swift_Image::fromPath('/var/www/html/papoteCar/public/pictures/default/logo.png'));

                        $message->setBody(
                            $this->renderView(
                            // templates/emails/registration.html.twig
                                'comment/alerteOffense.html.twig',
                                array('name' => $user->getUsername(),
                                    'image' => $data['image_src'],
                                    'comment' => $comment,
                                    'offense' => $offense
                                )
                            ),
                            'text/html'
                        )
            ;

            $mailer->send($message);


                        $flash = "Utilisateur averti";

                        $this->addFlash(
                            'success',
                            $flash
                        );

                        return $this->redirectToRoute('commentAdminDelete', array('comment_id' => $comment->getId()));
                    }

                    return $this->render('comment/offense.html.twig', [
                        'form' => $form->createView(),
                        'offenser' => $user,
                        'comment' => $comment
                    ]);
                }
            }
            else{
                return $this->redirectToRoute('home');
            }
        }
        else{
            $flash = "Veuillez d'abords vous connecter";

            $this->addFlash(
                'warning',
                $flash
            );

            return $this->redirectToRoute("login");
        }

    }

    /**
     * @Route("/comment/reclamation/{id}/{userId}", name="commentAlert", defaults={"userId":"1"})
     */

    public function commentAlert(Request $request, EntityManagerInterface $em, $id, $userId)
    {

        if ($this->getUser() && ($this->getUser()->getRoles()[0] == "ROLE_USER" || $this->getUser()->getRoles()[0] == "ROLE_ADMIN")) {


            $commentRepo = $this->getDoctrine()->getRepository(Comment::class);

            $comment = $commentRepo->findOneBy(['id' => $id]);
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $userId]);


            $userRepo = $this->getDoctrine()->getRepository(User::class);

            $user = $userRepo->findOneBy(['id' => $comment->getUserlauncher()]);


            if ($comment == null) {

                $flash = "Ce commentaire n'existe pas";


                return new Response(

                    $flash

                );

            } else {


                $reclamation = new Reclamation();

                $form = $this->createForm(ReclamationType::class, $reclamation);

                $form->handleRequest($request);


                if ($form->isSubmitted() && $form->isValid()) {

                    $reclamation->setDate(new \DateTime());

                    $reclamation->setUserlauncher($this->getUser());

                    $reclamation->setUsertarget($user);


                    $em->persist($reclamation);

                    $em->flush();


                    $flash = "Reclamation postée";


                    /*return new Response(

                        $flash

                    );*/

                }

                return $this->render('comment/reclamation.html.twig', [

                    'form' => $form->createView(),
                    'comment' => $comment,
                    'user' => $user

                ]);


            }


        } // mettre un message flash sinon

    }
}
