<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Travel;
use App\Entity\User;
use App\Form\PrivateMessagesType;
use App\Form\RegisterUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends Controller
{
    /**
     * @Route("/registerUser", name="registerUser")
     */
    public function registerUser(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer)
    {
        $user = new User();

        //create Form
        $registerUserForm = $this->createForm(RegisterUserType::class, $user);
        $registerUserForm->handleRequest($request);

        //check if form is valid
        if ($registerUserForm->isSubmitted() && $registerUserForm->isValid()) {
            //hasch password
            $encoded = $encoder->encodePassword($user, $user->getPassword());
            //set password, date and role to user
            $user->setPassword($encoded);
            $user->setDateCreated(new \DateTime());
            $user->setRoles(["ROLE_NOTHING"]);

            $file = $registerUserForm->get('profilePicture')->getData();

            if ($file === NULL) {
                $user->setProfilepicture(NULL);
            } elseif ($file) {
                if (($file->getClientOriginalExtension() === 'jpg') OR ($file->getClientOriginalExtension() === 'jpeg') OR ($file->getClientOriginalExtension() === 'png')) {

                    $fileName = md5(uniqid()) . '.' . $file->guessExtension();

                    // moves the file to the directory where brochures are stored
                    $file->move(
                        $this->getParameter('profilesPics_directory'),
                        $fileName
                    );
                    $user->setProfilepicture($fileName);
                } else {

                    $this->addFlash('danger', 'File must be .pnj or .jpeg');
                    return $this->render('user/registerUser.html.twig', [
                        'registerUserForm' => $registerUserForm->createView()
                    ]);
                }
            }

            //Create a "unique" token.
            $token = bin2hex(openssl_random_pseudo_bytes(16));

            $user->setToken($token);


            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Your account has been successfully created, you must have received a confirmation mail');


            //Get the unique user ID of the user that has just registered.
            $userId = $user->getId();

            //Construct the URL.
            $url = "http://localhost/papoteCar/public/tokenCheck?token=$token&user=$userId";

            // set the message in confirmation mail
            $message = (new \Swift_Message('Your recent inscription on our website'))
                ->setFrom('dave.lopper0@gmail.com')
                ->setTo($user->getEmail());

            $data['image_src'] = $message->embed(\Swift_Image::fromPath('/var/www/html/papoteCar/public/pictures/default/logo.png'));

            $message->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'user/emailRegister.html.twig',
                    array('name' => $user->getUsername(),
                        'image' => $data['image_src'],
                        'url' => $url
                    )
                ),
                'text/html'
            )/*
                 * If you also want to include a plaintext version of the message
                ->addPart(
                    $this->renderView(
                        'emails/registration.txt.twig',
                        array('name' => $name)
                    ),
                    'text/plain'
                )
                */
            ;


            $mailer->send($message);
            return $this->redirectToRoute('login');

        }


        return $this->render('user/registerUser.html.twig', [
            'registerUserForm' => $registerUserForm->createView()
        ]);
    }


    /**
     * @Route("/tokenCheck", name="tokenCheck")
     */
    public function tokenCheck(EntityManagerInterface $em)
    {
//Make sure that our query string parameters exist.
        $token = trim($_GET['token']);
        $userId = trim($_GET['user']);

        if (isset($_GET['token']) && isset($_GET['user'])) {

            $userRepo = $this->getDoctrine()->getRepository(User::class);
            $results = $userRepo->findOneBy([
                'id' => $userId,
                'token' => $token,
            ]);

            if ($results) {
                $user = $userRepo->findOneBy([
                    'id' => $userId,
                    'token' => $token,
                ]);

                $user->setRoles(['ROLE_USER']);
                $user->setToken(Null);
                $em->persist($user);
                $em->flush();
                return $this->render('security/login.html.twig', [
                    'id' => $userId,
                    'token' => $token,
                    'results' => $results

                ]);
            }

        }
        return $this->redirectToRoute("login");
    }


    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();


        //on bloque l'accès si deja connecté
        if ($this->getUser()) {
            return $this->redirectToRoute("home");
        }

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {

    }

    /**
     * @Route("/profil/{id}", name="profil_user")
     */
    public function profilUser(EntityManagerInterface $em, $id)
    {
        $user = $this->getUser();
        $travel = $this->getDoctrine()->getRepository(Travel::class)->findBy(["user" => $id]);
        $userInfos = $this->getDoctrine()->getRepository(User::class)->findOneBy(["id" => $id]);
        // voir pourquoi il n'y a que la table travel qui contient un lien vers car/user pour savoir a qui
        // appartient la voiture...
//        $userCarInfos = $this->getDoctrine()->getRepository(Travel::class)->findOneBy(["car" => $id]);
        $userComment = $this->getDoctrine()->getRepository(Comment::class)->findBy(["usertarget" => $id]);
//        $count = 0;
//        foreach ($travel as $nb) {
//            $count++;
//        }

        return $this->render('user/profilUser.twig', [
            "userInfos" => $userInfos,
            "travel" => $travel,
            "usercomment" => $userComment
//            "count" => $count,
//            "car" => $userCarInfos,
        ]);
    }

    /**
     * @Route("/updateUser/{id}", name="updateUser")
     */
    public function updateUser(EntityManagerInterface $em,
                               Request $request,
                               UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer,
                               $id)
    {
        $user = $this->getUser();
        $updateUserForm = $this->createForm(RegisterUserType::class, $user);

        $updateUserForm->handleRequest($request);

        //check if form is valid
        if ($updateUserForm->isSubmitted() && $updateUserForm->isValid()) {
            //hasch password
            $encoded = $encoder->encodePassword($user, $user->getPassword());
            //set password, date and role to user
            $user->setPassword($encoded);
//            $user->setDateCreated($this->getUser()->getDatecreated);
//            $user->setRoles(["ROLE_NOTHING"]);
//            $user->setId($id);
            $file = $updateUserForm->get('profilePicture')->getData();

            if ($file === NULL) {
                $user->setProfilepicture(NULL);
            } elseif ($file) {
                if (($file->getClientOriginalExtension() === 'jpg') OR ($file->getClientOriginalExtension() === 'jpeg') OR ($file->getClientOriginalExtension() === 'png')) {

                    $fileName = md5(uniqid()) . '.' . $file->guessExtension();

                    // moves the file to the directory where brochures are stored
                    $file->move(
                        $this->getParameter('profilesPics_directory'),
                        $fileName
                    );
                    $user->setProfilepicture($fileName);
                } else {

                    $this->addFlash('danger', 'File must be .pnj or .jpeg');
                    return $this->render('user/registerUser.html.twig', [
                        'registerUserForm' => $updateUserForm->createView()
                    ]);
                }
            }

            //Create a "unique" token.
            $token = bin2hex(openssl_random_pseudo_bytes(16));

            $user->setToken($token);
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Your account has been successfully updated, you must have received a confirmation mail');


            //Get the unique user ID of the user that has just registered.
//            $userId = $user->getId();

            //Construct the URL.
            $url = "http://localhost/papoteCar/public/tokenCheck?token=$token&user=$id";

            // set the message in confirmation mail
            $message = (new \Swift_Message('Your recent inscription on our website'))
                ->setFrom('dave.lopper0@gmail.com')
                ->setTo($user->getEmail());

            $data['image_src'] = $message->embed(\Swift_Image::fromPath('/var/www/html/papoteCar/public/pictures/default/logo.png'));

            $message->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'user/emailRegister.html.twig',
                    array('name' => $user->getUsername(),
                        'image' => $data['image_src'],
                        'url' => $url
                    )
                ),
                'text/html'
            )/*
                 * If you also want to include a plaintext version of the message
                ->addPart(
                    $this->renderView(
                        'emails/registration.txt.twig',
                        array('name' => $name)
                    ),
                    'text/plain'
                )
                */
            ;


            $mailer->send($message);
            return $this->redirectToRoute('login');

        }

        return $this->render('user/updateUser.html.twig', [
            "user" => $this->getUser(),
            "updateUserForm" => $updateUserForm->createView()
//            "count" => $count,
//            "car" => $userCarInfos,
        ]);
    }
}