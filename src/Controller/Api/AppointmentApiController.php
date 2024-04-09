<?php

namespace App\Controller\Api;

use App\Context\UserApiContext;
use App\Repository\AppointmentsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/api/appointments")]
class AppointmentApiController extends AbstractController
{
    #[Route("")]
    public function getAppointments(UserApiContext $userApiContext, AppointmentsRepository $repo, Request $request)
    {
        $selectedDate = $request->request->get('selected-date');

        if ($selectedDate) {
            $date = new DateTime($selectedDate);
            $appointments = $repo->findByDate($date, "en cours");
        } else {
            $appointments = $repo->findBy(['status' => 'en cours']);
        }
        $userApiContext ->getUser();

        return $this ->json($appointments);
    }
}