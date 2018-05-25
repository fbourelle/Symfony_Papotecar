<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Question;
use App\Entity\Travel;
use App\Entity\User;
use App\Form\TravelSearchType;
use App\Form\TravelType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Tests\Functional\Bundle\CsrfFormLoginBundle\Form\UserLoginType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Flex\Response;

class TravelController extends Controller
{
    /**
     * @Route("/travel_add/{id}", name="travel_add")
     */
    public function travelAdd(EntityManagerInterface $em, $id)
    {
        $user = $this->getUser();
        // verification si l'user est identifier pour cette page
        if ($user) {
            $travel = $this->getDoctrine()->getRepository(Travel::class)->findOneBy(['id' => $id]);

            if ($travel != null) {
                if ($travel->getPassengers()->contains($user)) {
                    $this->addFlash("warning", "Erreur: Vous faite déjà parti du trajet");
                } else {
                    $travel->addPassenger($user);
                    $em->flush();
                    $this->addFlash("success", "Félicitation: Vous faite parti du trajet");
                }
            } else {
                $this->addFlash("warning", "Erreur lors de l'insertion");
            }

            return $this->redirect("../travel_detail/".$id);
        } else {
            $this->addFlash("error", "Vous devez être identifié pour pouvoir ajouter un trajet");
            return $this->redirectToRoute("home");
        }
    }

    /**
     * @Route("/travel_remove/{id}", name="travel_remove")
     */
    public function travelRemove($id, EntityManagerInterface $em)
    {
        $user = $this->getUser();
        // verification si l'user est identifier pour cette page
        if ($user) {
            $travel = $this->getDoctrine()->getRepository(Travel::class)->findOneBy(['id' => $id]);

            if ($travel != null) {
                if ($travel->getPassengers()->contains($user)) {
                    $travel->removePassenger($user);
                    $em->flush();
                    $this->addFlash("warning", "Vous ne faite désormais plus parti du trajet");
                } else {
                    $this->addFlash("warning", "Erreur: Vous faite déjà parti du trajet");
                }
            }

            return $this->redirect("../travel_detail/".$id);
        } else {
            $this->addFlash("error", "Vous devez être identifié pour pouvoir ajouter un trajet");
            return $this->redirectToRoute("home");
        }
    }

    /**
     * @Route("/travel-list", name="travel-list")
     */
    public function travelParticipate(Request $request, EntityManagerInterface $em)
    {
        if ($this->getUser()) {
            $user = $this->getUser();
            $travels = $this->getDoctrine()->getRepository(Travel::class)->findBy(['user' => $this->getUser()->getId()]);

            return $this->render('travel/travel_participate.html.twig', [
                "travels" => $travels,
                "user" => $user
            ]);

        } else {
            $this->addFlash("error", "Vous devez être identifié pour pouvoir ajouter un trajet");
            return $this->redirectToRoute("home");
        }
    }

    /**
     * @Route("/travel", name="travel_home")
     */
    public function travelHome(Request $request, AuthenticationUtils $authenticationUtils, EntityManagerInterface $em)
    {
        if ($this->getUser()) {
            // we get the actual user information

            $user = $this->getUser();
            $car = $this->getDoctrine()->getRepository(Car::class)->find(1);

            // creation of the formular type
            $travel = new Travel();
            // modifier le schema et rajouter dans la table car une cle etrangère vers la table user
            // afin qu'ici soit hydraté setcarid avec le car correspondant à l'user
            $travel->setCar($car);
            $travel->setDatecreated(new \DateTime());
            $travel->setUser($user);

            $travelForm = $this->createForm(TravelType::class, $travel);
            $travelForm->handleRequest($request);

            // add to the foreign key travel the user key [inner join...]

            if ($travelForm->isSubmitted() &&
                $travelForm->isValid() &&
                $this->getUser()) {
                $em->persist($travelForm->getData());
                $em->flush();

                $this->addFlash("success", "Votre trajet a été ajouté");
                // modifier ici pour mettre redirect vers user pannel
                return $this->redirectToRoute('travel_home');
            }

            return $this->render('travel/index.html.twig', [
                "user" => $user, // renvoie toute les infos de l'user au cas ou
                "travel" => $travel, // affiche les infos travel au cas ou si le mec ait déjà des trajets
                "travelForm" => $travelForm->createView(),
            ]);

        } else {
            $this->addFlash("error", "Vous devez être identifié pour pouvoir ajouter un trajet");
            return $this->redirectToRoute("home");
        }
    }

    /**
     * @Route("travelSearch/{villeDepart}/{villeArrivee}/{date}/{page}",
     *     name="travel_search",
     *     defaults={"page":"1"},
     *     requirements={"page":"[0-9]+"})
     */
    public function travelSearch(EntityManagerInterface $em, Request $request, $villeDepart, $villeArrivee, $date, $page) {
        $travelRepo = $this->getDoctrine()->getRepository(Travel::class);

        // a changer et a mettre dans les paramètres de la fonction
        //donnée par défaut du formulaire de recherche

        $searchData = [
            "startcity" => $villeDepart,
            "endcity" => $villeArrivee,
            "starthour" => $date
        ];

        $searchTravelForm = $this->createForm(TravelSearchType::class, $searchData);
        $searchTravelForm->handleRequest($request);

        //si le form est soumis, on récupère les données
        if ($searchTravelForm->isSubmitted()) {
            $searchData = $searchTravelForm->getData();
        }

        $travels = $travelRepo->findPaginated($page, $searchData["startcity"], $searchData["endcity"], $searchData["starthour"]);

        return $this->render("travel/search.twig", [
            "form" => $searchTravelForm->createView(),
            "travels" => $travels,
            "nextPage" => $page+1,
            "prevPage" => $page-1,
            "totalResults" => count($travels),
            "lastPage" => ceil(count($travels) / 50),
            "startcity" => $villeDepart,
            "endcity" => $villeArrivee
        ]);
    }

    /**
     * @Route("/travel_detail/{id}", name="travel_detail")
     */
    public function travelDetail(EntityManagerInterface $em, $id) {
        $user = $this->getUser();
        // verification si l'user est identifier pour cette page
        if ($user) {
            $travelDetail = $this->getDoctrine()->getRepository(Travel::class)->findOneBy(['id' => $id]);

            return $this->render('travel/travel_detail.twig', [
                "travelDetail" => $travelDetail, // affiche les infos travel au cas ou si le mec ait déjà des trajets
            ]);
        }
    }
}