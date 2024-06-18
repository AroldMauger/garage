<?php

namespace App\Controller;

use App\Entity\Billing;
use App\Form\BillingType;
use App\Service\PdfGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BillingController extends AbstractController
{
    private $pdfGenerator;

    public function __construct(PdfGenerator $pdfGenerator)
    {
        $this->pdfGenerator = $pdfGenerator;
    }

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

            return $this->redirectToRoute('billing_success', ['id' => $billing->getId()]);
        }

        return $this->render('pages/billing_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/billing/success/{id}', name: 'billing_success')]
    public function success(int $id, EntityManagerInterface $entityManager): Response
    {
        $billing = $entityManager->getRepository(Billing::class)->find($id);

        if (!$billing) {
            throw $this->createNotFoundException('La facture n\'existe pas');
        }

        return $this->render('pages/billing_success.html.twig', [
            'billing' => $billing,
        ]);
    }

    #[Route('/billing_form', name: 'billing_form')]
    public function showBillingForm(): Response
    {
        return $this->redirectToRoute('billing_new');
    }

    #[Route('/billing/{id}/download', name: 'billing_download')]
    public function download(Billing $billing): Response
    {
        $template = 'billing-template/billing-template.html.twig';
        $data = ['billing' => $billing];
        $filename = sprintf('facture-%s.pdf', $billing->getId());

        return $this->pdfGenerator->generatePdfResponse($template, $data, $filename);
    }
}
