<?php

namespace App\Controller;

use App\Entity\Billing;
use App\Form\BillingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BillingController extends AbstractController
{
    #[Route('/billing/new', name: 'billing_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $billing = new Billing();
        $form = $this->createForm(BillingType::class, $billing);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($billing->getItems() as $item) {
                $item->setBilling($billing);
                $entityManager->persist($item);
            }

            $entityManager->persist($billing);
            $entityManager->flush();

            return $this->redirectToRoute('billing_success');
        }

        return $this->render('pages/billing_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/billing/success', name: 'billing_success')]
    public function success(): Response
    {
        return $this->render('pages/billing_success.html.twig');
    }

    #[Route('/billing_form', name: 'billing_form')]
    public function showBillingForm(): Response
    {
        return $this->redirectToRoute('billing_new');
    }

// POUR TESTER LE TWIG
    #[Route('/billing/view', name: 'billing_view')]
    public function view(): Response
    {
        // Récupérer les données de la facture par ID (simulé pour cet exemple)
        $billing = [
            'status' => "FACTURE",
            'id' => 1,
            'name' => 'Arold MAUGER',
            'adress' => '4 ancienne route de Caen, CABOURG',
            'creationDate' => new \DateTime(),
            'status' => 'DEVIS',
            'brand' => 'PEUGEOT',
            'model' => '307',
            'mileage' => 320000,
            'release_date' =>new \DateTime(),
            'numberplate' => "BP794GR",
            'serial_number' => '3894JRZ589',
            'tva' => 20,
            'items' => [
                ['quantity' => 2, 'name' => 'Pneus', 'price' => 40],
                ['quantity' => 1, 'name' => 'Feu arrière', 'price' => 30],
            ],
        ];

        // Rendre le template Twig avec les données
        return $this->render('billing-template/billing-template.html.twig', [
            'billing' => $billing,
        ]);
    }
}
