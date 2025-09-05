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

    // 1. Busca todos os prêmios da festa, uma única vez.
    $premios = $festa->premios()->get();

    // 2. Busca todas as cartelas da festa.
    $cartelas = $festa->cartelas()->get();

    $cartelasParaGerar = collect();

    // 3. Itera sobre cada cartela e cria uma nova entrada para cada prêmio.
    foreach ($cartelas as $cartela) {
        foreach ($premios as $premio) {
            $cartelasParaGerar->push([
                'numeros' => $cartela->numeros,
                'codigo' => $cartela->codigo,
                'festa' => $festa,
                'premios' => $premios, // Repetindo todos os prêmios para cada cartela.
                'premio_atual' => $premio, // Prêmio atual para destaque.
            ]);
        }
    }

    // 4. Divide a coleção em lotes (páginas) com base em $cartelasPorArquivo.
     $lotesPaginas = $cartelasParaGerar->chunk($cartelasPorArquivo);

    $html = '';
    foreach ($lotesPaginas as $lote) {
        $html .= view('pdf.template_teste', [
            'cartelasData' => $lote,
            'festa' => $festa,
            'premios' => $premios,
        ])->render();

        // Adiciona uma quebra de página para começar uma nova folha
        $html .= '<div style="page-break-after: always;"></div>';
    }

    $pdf = Pdf::loadHtml($html);
    $pdf->setPaper('a4', 'portrait');
    $dateTime = now()->format('Ymd_His');
    $filename = "lote-festa-{$festa->id}-{$dateTime}.pdf";
    Storage::disk('public')->put("pdfs/{$filename}", $pdf->output());

    return response()->json([
        'success' => true,
        'message' => 'O PDF foi gerado e salvo com sucesso. Verifique o storage.'
    ]);
}
}
