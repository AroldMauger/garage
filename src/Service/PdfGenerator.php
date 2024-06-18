<?php

namespace App\Service;

use Dompdf\Dompdf;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class PdfGenerator
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function generatePdf($template, $data)
    {
        $html = $this->twig->render($template, $data);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

    public function generatePdfResponse($template, $data, $filename)
    {
        $pdfContent = $this->generatePdf($template, $data);

        $response = new Response($pdfContent);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $filename));

        return $response;
    }
}
