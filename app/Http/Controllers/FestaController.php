<?php

namespace App\Http\Controllers;

use App\Models\Festa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class FestaController extends Controller
{
    public function showPdfs(Festa $festa)
    {
        // Pega todos os arquivos PDF gerados para esta festa na pasta 'pdfs'
        // Assumindo que o nome do arquivo contém o ID da festa
        $allPdfs = Storage::disk('public')->files('pdfs');

        $festaPdfs = collect($allPdfs)->filter(function ($path) use ($festa) {
            return str_contains($path, "festa-{$festa->id}.pdf");
        })->map(function ($path) {
            return Storage::url($path); // Converte o caminho para uma URL pública
        })->values()->all(); // Remove as chaves para ter um array limpo

        if (empty($festaPdfs)) {
            return redirect()->back()->with('error', 'Nenhum PDF encontrado para esta festa.');
        }

        return view('festas.pdfs', compact('festa', 'festaPdfs'));
    }

    public function gerarPdfs()
    {
        return view('festas.gerar_pdfs');
    }

    // Se você quiser um método para download direto:
    public function downloadPdf(Festa $festa, string $filename)
    {
        $filePath = "pdfs/{$filename}";

        // Verifica se o arquivo existe e se ele pertence a esta festa (segurança)
        if (Storage::disk('public')->exists($filePath) && str_contains($filename, "festa-{$festa->id}.pdf")) {
            return Storage::disk('public')->download($filePath, $filename);
        }

        abort(404, 'Arquivo PDF não encontrado ou acesso negado.');
    }

    public function gerarPdfLote(Request $request)
    {
        $request->validate([
            'festa_id' => 'required|exists:festas,id',
            'cartelas_por_arquivo' => 'required|integer|min:1|max:1000',
        ]);

        $festa = Festa::findOrFail($request->festa_id);
        $cartelasPorArquivo = (int) $request->cartelas_por_arquivo;

        // **Ajuste:** Buscando as cartelas da festa para simular o Job.
        $cartelas = $festa->cartelas()->get();
        $loteCartelas = $cartelas->chunk($cartelasPorArquivo);

        // **Ajuste:** Inicia a variável de HTML vazia.
        $html = '';

        // **Ajuste:** Loop para processar os lotes de cartelas.
        foreach ($loteCartelas as $lote) {
            $cartelasData = $lote->map(function ($cartela) use ($festa) {
                return [
                    'numeros' => $cartela->numeros,
                    'codigo' => $cartela->codigo,
                    'festa' => $festa,
                    'premios' => $festa->premios()->get(),
                ];
            });

            // **Ajuste:** Renderiza o template uma única vez com os dados corretos.
            // A quebra de página (page-break) já está no CSS do template_teste.blade.php.
            $html .= view('pdf.template_teste', compact('cartelasData', 'festa'))->render();
        }

        $pdf = Pdf::loadHtml($html);
        $dateTime = now()->format('Ymd_His');
        $pdf->setPaper('a4', 'portrait');
        $filename = "lote-1-festa-{$festa->id}-{$dateTime}.pdf";
        Storage::disk('public')->put("pdfs/{$filename}", $pdf->output());

        return response()->json([
            'success' => true,
            'message' => 'O PDF foi gerado e salvo com sucesso. Verifique o storage.'
        ]);
    }
}
