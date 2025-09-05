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
    // Aumenta o limite de memória para a execução desta função
    ini_set('memory_limit', '512M');

    $request->validate([
        'festa_id' => 'required|exists:festas,id',
        'cartelas_por_arquivo' => 'required|integer|min:1|max:1000',
    ]);

    $festa = Festa::findOrFail($request->festa_id);
    $cartelasPorArquivo = (int) $request->cartelas_por_arquivo; // Quantidade de cartelas por ARQUIVO PDF

    // 1. Busca todos os prêmios e cartelas
    $premios = $festa->premios()->get();
    $qtdPremios = $premios->count(); // Quantidade de prêmios

    $cartelasOriginais = $festa->cartelas()->get(); // As cartelas sem duplicação

    $todosOsArquivosHTML = []; // Para armazenar o HTML de cada PDF

    // 2. Divide as cartelas originais em grupos para cada arquivo PDF
    // Ex: 20 cartelas, 5 por arquivo = 4 grupos (1-5, 6-10, 11-15, 16-20)
    $gruposParaArquivos = $cartelasOriginais->chunk($cartelasPorArquivo);

    $fileIndex = 1; // Para nomear os arquivos PDF (ex: lote-1.pdf, lote-2.pdf)

    // 3. Processa cada grupo de cartelas originais para criar um PDF separado
    foreach ($gruposParaArquivos as $grupoCartelasParaArquivo) {
        $cartelasParaGerarNesteArquivo = collect();

        // Para cada cartela do grupo, duplica para cada prêmio
        foreach ($grupoCartelasParaArquivo as $cartelaOriginal) {
            foreach ($premios as $premio) {
                $cartelasParaGerarNesteArquivo->push([
                    'numeros' => $cartelaOriginal->numeros,
                    'codigo' => $cartelaOriginal->codigo,
                    'festa' => $festa,
                    'premios' => $premios, // Todos os prêmios
                    'premio_atual' => $premio, // Prêmio atual para destaque
                ]);
            }
        }

        // Divide o conteúdo deste arquivo em "páginas" (cada página com 'qtdPremios' cartelas)
        // Isso é para garantir que a duplicação por prêmio esteja dentro do mesmo arquivo PDF
        $lotesPaginasDesteArquivo = $cartelasParaGerarNesteArquivo->chunk($qtdPremios);

        $htmlDesteArquivo = '';
        foreach ($lotesPaginasDesteArquivo as $lote) {
            $htmlDesteArquivo .= view('pdf.template_teste', [
                'cartelasData' => $lote,
                'festa' => $festa,
                'premios' => $premios,
            ])->render();
            // Adiciona quebra de página se não for o último lote deste arquivo
            if (!$lote->last) {
                $htmlDesteArquivo .= '<div style="page-break-after: always;"></div>';
            }
        }

        // Agora geramos o PDF para este arquivo específico
        $pdf = Pdf::loadHtml($htmlDesteArquivo);
        $pdf->setPaper('a4', 'portrait');
        $dateTime = now()->format('Ymd_His');
        $filename = "lote-{$fileIndex}-festa-{$festa->id}-{$dateTime}.pdf";
        Storage::disk('public')->put("pdfs/{$filename}", $pdf->output());

        $fileIndex++; // Incrementa para o próximo nome de arquivo
    }

    return response()->json([
        'success' => true,
        'message' => 'Os PDFs foram gerados e salvos com sucesso. Verifique o storage.'
    ]);
}
}
