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
        public ?string $textoLateral = null
    ) {}

    public function handle(): void
    {
        $festa = $this->festa;
        $cartelas = $festa->cartelas()->get();
        $arquivosGerados = [];
        $lote = 1;

        // O chunk divide a coleção em pedaços do tamanho que você definir.
        // Isso elimina a necessidade de loops aninhados e fatiamento manual.
        $cartelas->chunk($this->cartelasPorArquivo)->each(function ($loteCartelas) use ($festa, &$arquivosGerados, &$lote) {
            $html = '';

            $loteCartelas->chunk($this->quantidadePorFolha)->each(function ($paginaCartelas) use ($festa, &$html) {
                // Prepara os dados de cada cartela para a view
                $cartelasData = $paginaCartelas->map(function ($cartela) use ($festa) {
                    return [
                        'numeros' => $cartela->numeros,
                        'codigo' => $cartela->codigo,
                        'festa' => $festa,
                    ];
                });

                // Renderiza a view da página
                $html .= view('pdf.page_multiple_cartelas_per_page', [
                    'cartelasData' => $cartelasData,
                    'festa' => $festa,
                    'quantidadePorFolha' => $this->quantidadePorFolha,
                    'texto_lateral' => ($this->quantidadePorFolha == 1) ? $this->textoLateral : null,
                ])->render();
            });

            // Gera e salva o PDF para o lote atual
            $pdf = Pdf::loadHtml($html);
            $filename = "lote-{$lote}-festa-{$festa->id}.pdf";
            Storage::disk('public')->put("pdfs/{$filename}", $pdf->output());

            $arquivosGerados[] = $filename;
            $lote++;
        });

        // Você pode adicionar uma notificação para o usuário aqui, se desejar.
        // event(new PdfsGeradosEvent($festa, $arquivosGerados));
    }
}
