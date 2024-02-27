<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\httpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\Users;
use App\Repository\UsersRepository;
use App\Form\NewAppointmentType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\AppointmentsRepository;

class HomeController extends AbstractController {

    #[Route('/dashboard', name:"dashboard", methods: ['GET'])]
    public function dashboard()
    {
       return $this->render("pages/dashboard.html.twig");
    }

    #[Route('/new_appointment', name:"new_appointment")]
    public function newAppointment(Request $request, AppointmentsRepository $repo)
    {
        $form = $this->createForm(NewAppointmentType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $appointment= $form->getData();
            $appointment->setStatus('en cours');
            $repo->save($appointment);
            return $this->redirectToRoute("dashboard");
        }        
        return $this->render("pages/formulaire_rdv.html.twig", ["form" => $form->createView()]);
        
    }
    
}

