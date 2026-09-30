<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class ApplicantPrintController extends Controller
{
    public function __invoke(Applicant $applicant): Response
    {
        $applicant->load([
            'workExperiences',
            'organizations',
            'diseases',
            'references',
            'internalConnections',
            'psychoTests',
        ]);

        $logo = 'data:image/png;base64,'.base64_encode(
            file_get_contents(public_path('images/logo-black.png'))
        );

        $pdf = Pdf::loadView('applicants.print', compact('applicant', 'logo'))
            ->setPaper('a4');

        $pdf->render();
        $pdf->getDomPDF()->getCanvas()->page_text(
            500,
            810,
            'Halaman {PAGE_NUM} dari {PAGE_COUNT}',
            null,
            9,
            [0, 0, 0]
        );

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="lamaran-'.$applicant->id.'.pdf"',
        ]);
    }
}
