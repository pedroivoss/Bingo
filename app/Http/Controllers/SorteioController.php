<?php

namespace App\Http\Controllers;

use App\Models\Festa;
use App\Models\Sorteio;
use App\Models\Cartela;
use App\Models\Vencedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SorteioController extends Controller
{
    public function show(Festa $festa)
    {
        $sorteios = Sorteio::where('festa_id', $festa->id)->orderBy('ordem', 'desc')->get();
        return view('sorteio.show', compact('festa', 'sorteios'));
    }

    // Este método agora APENAS REGISTRA o número digitado pelo operador
    public function registrarNumero(Request $request, Festa $festa)
    {
        $request->validate(['numero' => 'required|integer|min:1|max:75']);

        // Verifica se o número já foi registrado para esta festa
        $existing = Sorteio::where('festa_id', $festa->id)->where('numero', $request->numero)->first();
        if ($existing) {
            return response()->json(['success' => false, 'message' => 'Número já foi registrado.'], 409);
        }

        $letras = ['B', 'I', 'N', 'G', 'O'];
        $letra = $letras[floor(($request->numero - 1) / 15)];

        $sorteio = Sorteio::create([
            'festa_id' => $festa->id,
            'numero' => $request->numero,
            'letra' => $letra,
            'ordem' => Sorteio::where('festa_id', $festa->id)->count() + 1,
        ]);

        return response()->json([
            'success' => true,
            'sorteio' => $sorteio,
            'message' => 'Número registrado com sucesso!'
        ]);
    }

    // Método para remover o último número registrado
    public function removerUltimoNumero(Festa $festa)
    {
        $lastSorteio = Sorteio::where('festa_id', $festa->id)->orderBy('ordem', 'desc')->first();

        if ($lastSorteio) {
            $lastSorteio->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Nenhum número para remover.']);
    }

    // Método de Validação de Cartela
    public function validarCartela(Request $request, Festa $festa)
    {
        $request->validate(['codigo' => 'required|string']);

        $cartela = Cartela::where('codigo', $request->codigo)->where('festa_id', $festa->id)->first();

        if (!$cartela) {
            return response()->json(['success' => false, 'message' => 'Cartela não encontrada.'], 404);
        }

        // Validação de Integridade do Hash
        $expectedHash = hash('sha256', $cartela->codigo . json_encode($cartela->numeros));
        $isIntegrityOk = $cartela->hash_integridade === $expectedHash;

        $numerosSorteados = Sorteio::where('festa_id', $festa->id)->pluck('numero')->toArray();

        // Lógica para verificar se a cartela é ganhadora
        $acertos = 0;
        $matriz = $cartela->numeros;

        // Itera sobre a matriz para contar acertos
        foreach ($matriz as $row) {
            foreach ($row as $number) {
                if ($number !== null && in_array($number, $numerosSorteados)) {
                    $acertos++;
                }
            }
        }

        $status = 'faltam_numeros';
        $vencedoresExistentes = Vencedor::where('cartela_id', $cartela->id)->pluck('premio_id')->toArray();
        $premiosDisponiveis = $festa->premios->whereNotIn('id', $vencedoresExistentes)->sortBy('ordem');

        // Lógica de verificação de linhas, colunas, diagonais, etc. (Simplificada para o exemplo)
        // Para uma implementação completa, seria necessária uma lógica mais complexa aqui
        $isWinner = $acertos >= 24; // Exemplo para cartela cheia (24 números + coringa)

        return response()->json([
            'success' => true,
            'cartela' => $cartela,
            'numeros_sorteados' => $numerosSorteados,
            'acertos' => $acertos,
            'is_winner' => $isWinner,
            'is_integrity_ok' => $isIntegrityOk,
            'premios_disponiveis' => $premiosDisponiveis,
        ]);
    }

    public function confirmarVencedor(Request $request, Festa $festa)
    {
        $request->validate([
            'cartela_id' => 'required|exists:cartelas,id',
            'premio_id' => 'required|exists:premios,id',
        ]);

        $vencedorExistente = Vencedor::where('cartela_id', $request->cartela_id)
                                      ->where('premio_id', $request->premio_id)
                                      ->first();
        if ($vencedorExistente) {
            return response()->json(['success' => false, 'message' => 'Esta cartela já foi validada para este prêmio.'], 409);
        }

        // Simulação do ID do usuário logado. Substituir por Auth::id()
        $validadoPor = 1;

        Vencedor::create([
            'festa_id' => $festa->id,
            'cartela_id' => $request->cartela_id,
            'premio_id' => $request->premio_id,
            'validado_por' => $validadoPor,
            'status' => 'confirmado'
        ]);

        return response()->json(['success' => true, 'message' => 'Vencedor confirmado com sucesso!']);
    }

    public function limparSorteio(Festa $festa)
    {
        Sorteio::where('festa_id', $festa->id)->delete();

        return response()->json(['success' => true]);
    }
}
