<?php

namespace App\Controller;

use App\Entity\Travel;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $lastTravels = $this->getDoctrine()->getRepository(Travel::class)->findBy([], ["id" => "ASC"], '3');

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'lastTravels' => $lastTravels
        ]);
    }
}