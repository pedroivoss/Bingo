<?php

namespace App\Http\Controllers;

use App\Models\Festa;
use App\Models\Folha;
use App\Traits\mainTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Barryvdh\DomPDF\Facade\Pdf;
use Milon\Barcode\Facades\DNS1DFacade;

class FestaController extends Controller
{
    use mainTrait;

    public function showPdfs(Festa $festa)
    {
        // Pega todos os arquivos PDF gerados para esta festa na pasta 'pdfs'
        // Assumindo que o nome do arquivo contém o ID da festa
        $allPdfs = Storage::disk('public')->files('pdfs');

        $listaDePDFCadastrados = Folha::where('festa_id', $festa->id)->get();

        $festaPdfs = [];
        foreach ($listaDePDFCadastrados as $folha) {
            if (Storage::disk('public')->exists($folha->pdf_path)) {
                $festaPdfs[] = Storage::url($folha->pdf_path);
            }
        }

        if (empty($festaPdfs)) {
            return redirect()->back()->with('error', 'Nenhum PDF encontrado para esta festa.');
        }

        return view('festas.pdfs', compact('festa', 'festaPdfs'));
    }

    public function gerarPdfs()
    {
        return view('festas.gerar_pdfs');
    }//fim funcao

    // Se você quiser um método para download direto:
    public function downloadPdf(Festa $festa, string $filename)
    {
        $filePath = "pdfs/{$filename}";

        // Verifica se o arquivo existe e se ele pertence a esta festa (segurança)
        if (Storage::disk('public')->exists($filePath) && str_contains($filename, "festa-{$festa->id}.pdf")) {
            return Storage::disk('public')->download($filePath, $filename);
        }

        abort(404, 'Arquivo PDF não encontrado ou acesso negado.');
    }//fim funcao

    public function gerarPdfLote(Request $request)
    {
        // Aumenta o limite de memória para a execução desta função
        ini_set('memory_limit', '512M');

        $request->validate([
            'festa_id' => 'required|exists:festas,id',
            'cartelas_por_arquivo' => 'required|integer|min:1|max:1000',
        ]);

        $festa = Festa::findOrFail($request->festa_id);

        $nomeFesta = str_replace(' ', '_', $festa->nome);

        //return $this->success('Validação bem-sucedida. Iniciando geração de PDFs.', 200);

        $cartelasPorArquivo = (int) $request->cartelas_por_arquivo;
        $isCartelaCheia = (bool) $festa->is_cartela_cheia;

        $premios = $festa->premios()->get();
        $qtdPremios = $premios->count();

        $cartelasOriginais = $festa->cartelas()->get();
        $gruposParaArquivos = $cartelasOriginais->chunk($cartelasPorArquivo);

        $fileIndex = 1;

        foreach ($gruposParaArquivos as $grupoCartelasParaArquivo) {
            $cartelasParaGerarNesteArquivo = collect();

            foreach ($grupoCartelasParaArquivo as $cartelaOriginal) {

                $numerosOriginais = array_values($cartelaOriginal->numeros);

                $numerosPorColuna = [];

                // Agrupa os números por coluna (B, I, N, G, O)
                $coluna = $numerosOriginais;
                for ($i = 0; $i < 5; $i++) {

                    // Adiciona o espaço vazio na coluna "N" se não for cartela cheia
                    if ($isCartelaCheia && $i == 2) {
                        //tratar coringa
                        $coluna[$i][2] = 99; // Usando 99 como valor temporário para o espaço vazio
                        sort($coluna[$i]);
                        //reestrutura o array para garantir que o null fique na posição correta
                        $coluna[$i] = [
                            $coluna[$i][0],
                            $coluna[$i][1],
                            null,
                            $coluna[$i][2],
                            $coluna[$i][3],
                        ];

                    }

                    // Se for cartela cheia, ordena os números de cada coluna
                    if ($isCartelaCheia && $i != 2) {
                        sort($coluna[$i]);
                    }

                    $numerosPorColuna[$i] = $coluna[$i];
                }

                // Transforma o array de colunas em array de linhas para facilitar a renderização no template
                $numerosParaRenderizar = [];
                for ($i = 0; $i < 5; $i++) {
                    $linha = [];
                    for ($j = 0; $j < 5; $j++) {
                        $linha[] = $numerosPorColuna[$j][$i];
                    }
                    $numerosParaRenderizar[] = $linha;
                }

                foreach ($premios as $premio) {
                    $barcode = DNS1DFacade::getBarcodeSVG($cartelaOriginal->codigo, 'C39');
                    $cartelasParaGerarNesteArquivo->push([
                        'numeros' => $numerosParaRenderizar,
                        'codigo' => $cartelaOriginal->codigo,
                        'barcode' => 'data:image/svg+xml;base64,' . base64_encode($barcode),
                        'festa' => $festa,
                        'premios' => $premios,
                        'premio_atual' => $premio,
                    ]);
                }
            }

            $lotesPaginasDesteArquivo = $cartelasParaGerarNesteArquivo->chunk($qtdPremios);
            $htmlDesteArquivo = '';

            foreach ($lotesPaginasDesteArquivo as $lote) {

                $htmlDesteArquivo .= view('pdf.page_multiple_cartelas_per_page', [
                    'cartelasData' => $lote,
                    'festa' => $festa,
                    'premios' => $premios,
                ])->render();
                if (!$lote->last()) {
                    $htmlDesteArquivo .= '<div style="page-break-after: always;"></div>';
                }
            }

            $pdf = Pdf::loadHtml($htmlDesteArquivo);
            $pdf->setPaper('a4', 'portrait');
            $dateTime = now()->format('Ymd_His');
            $nomeFesta = str_replace(' ', '_', $festa->nome);

            $contadorCartelasIni = str_pad((($fileIndex - 1) * $cartelasPorArquivo + 1), 4, '0', STR_PAD_LEFT);
            $contadorCartelasTerm = str_pad((($fileIndex - 1) * $cartelasPorArquivo + $grupoCartelasParaArquivo->count()), 4, '0', STR_PAD_LEFT);

            $filename = "lote_{$fileIndex}_de_{$contadorCartelasIni}-{$contadorCartelasTerm}_festa_{$nomeFesta}_{$festa->id}_{$dateTime}.pdf";

            Storage::disk('public')->put("pdfs/{$filename}", $pdf->output());

            $newFolha = new Folha();
            $newFolha->festa_id = $festa->id;
            $newFolha->nome_arquivo = $filename;
            $newFolha->quantidade_por_arquivo = intval($grupoCartelasParaArquivo->count());
            $newFolha->primeira_cartela_codigo = $contadorCartelasIni;
            $newFolha->ultima_cartela_codigo = $contadorCartelasTerm;
            $newFolha->pdf_path = "pdfs/{$filename}";
            $newFolha->save();

            $fileIndex++;
        }

        return response()->json([
            'success' => true,
            'message' => 'Os PDFs foram gerados e salvos com sucesso. Verifique o storage.'
        ]);
    }//fim funcao
}//fim classe
