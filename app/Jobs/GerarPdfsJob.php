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

    public function __construct(
        public Festa $festa,
        public int $quantidadePorFolha,
        public int $cartelasPorArquivo,
        public ?string $texto_lateral = null
    ) {}

    public function handle(): void
    {
        $festa = $this->festa;
        $prêmios = $festa->premios()->orderBy('ordem')->get(); // Buscar os prêmios ordenados
        $cartelas = $festa->cartelas()->get();
        $lote = 1;

        $cartelas->chunk($this->cartelasPorArquivo)->each(function ($loteCartelas) use ($festa, $prêmios, &$lote) {
            $html = '';

            // Agora, agrupa as cartelas pela quantidade que você escolheu por folha
            $loteCartelas->chunk($this->quantidadePorFolha)->each(function ($paginaCartelas) use ($festa, $prêmios, &$html) {
                $cartelasData = [];
                foreach ($paginaCartelas as $cartela) {
                    // Duplica o mesmo objeto cartela na coleção, conforme a quantidade por folha
                    for ($i = 0; $i < $this->quantidadePorFolha; $i++) {
                        $cartelasData[] = [
                            'numeros' => $cartela->numeros,
                            'codigo' => $cartela->codigo,
                            'festa' => $festa,
                        ];
                    }
                }

                $html .= view('pdf.page_multiple_cartelas_per_page', [
                    'cartelasData' => collect($cartelasData),
                    'festa' => $festa,
                    'premios' => $prêmios,
                    'texto_lateral' => $this->texto_lateral,
                    'quantidadePorFolha' => $this->quantidadePorFolha
                ])->render();
            });

            // Gera e salva o PDF para o lote atual
            $pdf = Pdf::loadHtml($html);
            $filename = "lote-{$lote}-festa-{$festa->id}.pdf";
            Storage::disk('public')->put("pdfs/{$filename}", $pdf->output());

            $lote++;
        });
    }
}
