<?php

namespace App\Controller;

use App\Entity\Appointments;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\httpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\Users;
use App\Repository\UsersRepository;
use App\Form\NewAppointmentType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\AppointmentsRepository;
use \DateTime;
class HomeController extends AbstractController {

    #[Route('/dashboard', name:"dashboard", methods: ['GET'])]
    public function dashboard(AppointmentsRepository $repo)
    {
        $date = new DateTime();
        $appointments = $repo->findByDate($date, "en cours");
       return $this->render("pages/dashboard.html.twig", ["appointments" => $appointments]);
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
    #[Route('/history', name:"history", methods: ['GET'])]
    public function history(AppointmentsRepository $repo, Request $request)
    {
        $page = $request->get('page', 0);
        $limit = $request->get('limit', 10);
        $count = $repo->count(["status" => "terminé"]);
        $date = new DateTime();
        $appointments = $repo->findFinishedPaginated($page, $limit);
        $totalPages = (int) ceil($count/$limit);
        $previousPage = $page == 0? null:$page-1;
        $nextPage = ($page == $totalPages || $page>$totalPages) ? null:$page+1;

       return $this->render("pages/history.html.twig", ["appointments" => $appointments, "previousPage" => $previousPage, "nextPage" => $nextPage]);
    }
    #[Route('/finish/{id}', name:"finish", methods: ['GET'])]
    public function finish(#[MapEntity(id:"id")] Appointments $appointment, AppointmentsRepository $repo)
    {
        $appointment->setStatus("terminé");
        $repo->save($appointment);
        return $this->redirectToRoute("dashboard");
    }
    #[Route('/delete/{id}', name:"delete", methods: ['GET'])]
    public function delete(#[MapEntity(id:"id")] Appointments $appointment, AppointmentsRepository $repo)
    {
        $repo->delete($appointment);
        return $this->redirectToRoute("dashboard");
    }
}

