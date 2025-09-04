<?php

namespace App\Jobs;

use App\Models\Festa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class GerarPdfsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Construtor agora só recebe os dois argumentos que você passa do controller
    public function __construct(
        public Festa $festa,
        public int $cartelasPorArquivo
    ) {}

    public function handle(): void
    {
        // Define valores padrão para as variáveis que não estão no construtor
        $quantidadePorFolha = 6;
        $textoLateral = null;

        $festa = $this->festa;
        $premios = $festa->premios()->orderBy('ordem')->get();
        $cartelas = $festa->cartelas()->get();
        $lote = 1;

        //$cartelas->chunk($this->cartelasPorArquivo)->each(function ($loteCartelas) use ($festa, $premios, &$lote, $quantidadePorFolha, $textoLateral) {
            $html = '';

        //    $loteCartelas->chunk($quantidadePorFolha)->each(function ($paginaCartelas, $index) use ($festa, $premios, &$html, $quantidadePorFolha, $textoLateral) {

                // Prepara os dados de cada cartela
                /*$cartelasData = $paginaCartelas->map(function ($cartela) use ($festa) {
                    return [
                        'numeros' => $cartela->numeros,
                        'codigo'  => $cartela->codigo,
                        'festa'   => $festa,
                    ];
                });*/

            $html .= view('pdf.template_teste')->render();

                // Adiciona quebra de página (menos na última)
            $html .= '<div style="page-break-after: always;"></div>';
            //});

            $pdf = Pdf::loadHtml($html);
            $dateTime = now()->format('Ymd_His');
            $pdf->setPaper('a4', 'portrait');
            $filename = "lote-{$lote}-festa-{$festa->id}-{$dateTime}.pdf";
            Storage::disk('public')->put("pdfs/{$filename}", $pdf->output());

            $lote++;
        //});
    }
}
