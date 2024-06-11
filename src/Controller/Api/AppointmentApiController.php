<?php

namespace App\Controller\Api;

use App\Context\UserApiContext;
use App\DTO\NewAppointmentDTO;
use App\Entity\Appointments;
use App\Repository\AppointmentsRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/api/appointments")]
class AppointmentApiController extends AbstractController
{
    #[Route("", methods: ["GET"])]
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

    #[Route('/new_appointment', name:"api.new_appointment", methods: ["POST"])]
    public function newAppointment(AppointmentsRepository $repo,
                                   #[MapRequestPayload]
                                    NewAppointmentDTO $newAppointmentDTO,
                                    )
    {
        $date = new \DateTimeImmutable($newAppointmentDTO->date);
        $appointment = new Appointments();
        $appointment
            ->setStatus("en cours")
            ->setDate($date)
            ->setCustomer($newAppointmentDTO->customer)
            ->setPhone($newAppointmentDTO->phone)
            ->setCar($newAppointmentDTO->car)
            ->setReason($newAppointmentDTO->reason);
        $repo->save($appointment);
        return $this->json($appointment, Response::HTTP_CREATED);

    }

    #[Route('/history', name:"api.history", methods: ['GET'])]
    public function history(AppointmentsRepository $repo,
                                       #[MapQueryParameter] int $page = 0,
                                        #[MapQueryParameter(options: ["min_range" => 5, "max_range" => 50])] int $limit = 5

)
    {
        $count = $repo->count(["status" => "terminé"]);
        $appointments = $repo->findFinishedPaginated($page, $limit);
        $totalPages = (int) ceil($count/$limit);
        $previousPage = $page == 0? null:$page-1;
        $nextPage = ($page+1 == $totalPages || $page>$totalPages) ? null:$page+1;
        $firstPage = $page == 0? null: 0;
        $lastPage = $totalPages - 1;
    return $this->json(["appointments" => $appointments, "firstPage" => $firstPage, "lastPage" => $lastPage, "previousPage" => $previousPage, "nextPage" => $nextPage]);
    }

    #[Route('/{id}', name:"api.finish", methods: ['PATCH'])]
    public function finish(#[MapEntity(id:"id")] Appointments $appointment, AppointmentsRepository $repo)
    {
        $appointment->setStatus("terminé");
        $repo->save($appointment);
        return $this->json("", Response::HTTP_NO_CONTENT);
    }

    #[Route('/{id}', name:"api.delete", methods: ['DELETE'])]
    public function delete(
        #[MapEntity(id:"id")] Appointments $appointment,
        AppointmentsRepository $repo,
    )
    {
        $repo->delete($appointment);
        return $this->json("", Response::HTTP_NO_CONTENT);
    }

    #[Route('/{id}', name: "api.modify_appointment", methods: ['PUT'])]
    public function modifyAppointment(
        #[MapEntity(id:"id")] Appointments $appointment,
        AppointmentsRepository $repo,
        #[MapRequestPayload]
        NewAppointmentDTO $newAppointmentDTO,
        )
    {
        $date = new \DateTimeImmutable($newAppointmentDTO->date);
        $appointment
            ->setDate($date)
            ->setCustomer($newAppointmentDTO->customer)
            ->setPhone($newAppointmentDTO->phone)
            ->setCar($newAppointmentDTO->car)
            ->setReason($newAppointmentDTO->reason);
        $repo->save($appointment);
        return $this->json($appointment, Response::HTTP_OK);
    }
}