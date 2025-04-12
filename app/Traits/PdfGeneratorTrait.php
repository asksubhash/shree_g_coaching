<?php

namespace App\Traits;

use Mpdf\Mpdf;

trait PdfGeneratorTrait
{
    public function generateAndShowPdf($html)
    {
        // Enable HTTP URLs
        $options = [
            'allow_url_fopen' => true,
            'mode' => 'utf-8',
            'allow_external_images' => false,
        ];

        // Create mPDF object
        $mpdf = new Mpdf($options);

        $mpdf->debug = false;
        $mpdf->showImageErrors = false;

        // Generate PDF content
        $mpdf->WriteHTML($html);

        // Output PDF
        $mpdf->Output();
    }
}
