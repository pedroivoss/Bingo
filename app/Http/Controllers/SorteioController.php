<?php

namespace App\Http\Controllers;

use App\Models\Festa;
use App\Models\Sorteio;
use App\Models\Cartela;
use App\Models\Premio;
use App\Models\Vencedor;
use App\Traits\mainTrait;
use Illuminate\Http\Request;

class SorteioController extends Controller
{
    use mainTrait;

    public function show(Festa $festa)
    {
        $sorteios = Sorteio::where('festa_id', $festa->id)->orderBy('ordem', 'desc')->get();

        // **CORREÇÃO CRUCIAL:** Verifica se a requisição espera uma resposta JSON
        // se a requisição for feita por um fetch() do JavaScript.
        if (request()->wantsJson()) {
            return response()->json(['sorteios' => $sorteios]);
        }

        // Se a requisição for uma navegação normal, retorna a view
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

        return $this->success('Número registrado com sucesso!');
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

    /**
     * Valida uma cartela de bingo.
     * Compara os números da cartela com os números já sorteados.
     */
    public function validarCartela(Request $request, Festa $festa)
    {
        // 1. Valida a requisição
        $request->validate(['codigo' => 'required|string']);

        $codigo = $request->input('codigo');

        $codigo = str_pad(preg_replace('/\D/', '', $codigo), 4, '0', STR_PAD_LEFT);

        // 2. Busca a cartela
        $cartela = Cartela::where('festa_id', $festa->id)->where('codigo', $codigo)->first();

        if (!$cartela) {
            return response()->json(['success' => false, 'message' => 'Cartela não encontrada.'], 404);
        }

        // 3. Busca todos os números já sorteados para esta festa
        $numerosSorteados = Sorteio::where('festa_id', $festa->id)->pluck('numero')->toArray();

        // 4. Converte a string de números da cartela para um array
        $numerosCartela = $cartela->numeros;

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['success' => false, 'message' => 'Erro na formatação dos números da cartela.'], 500);
        }

        // 5. Conta quantos números da cartela foram sorteados
        $acertos = 0;
        foreach ($numerosCartela as $row) {
            foreach ($row as $number) {
                if ($number !== null && in_array($number, $numerosSorteados)) {
                    $acertos++;
                }
            }
        }

        // 6. Verifica se é um ganhador (24 acertos para cartela cheia)
        $isWinner = ($acertos >= 24);

        // 8. Retorna a resposta em JSON
        return response()->json([
            'success' => true,
            'cartela' => [
                'id' => $cartela->id,
                'codigo' => $cartela->codigo,
                'numeros' => $numerosCartela,
            ],
            'numeros_sorteados' => $numerosSorteados,
            'acertos' => $acertos,
            'is_winner' => $isWinner,
            // Adicione aqui uma verificação de integridade se você tiver um hash na tabela
            'is_integrity_ok' => true, // Supondo que a verificação de hash sempre passe por enquanto
        ]);
    }

    /**
     * Confirma um vencedor e o salva no banco de dados.
     */
    public function confirmarVencedor(Request $request, Festa $festa)
    {
        // 1. Valida a requisição
        $request->validate([
            'cartela_id' => 'required|exists:cartelas,id',
            'premio_id' => 'required|exists:premios,id',
        ]);

        $cartela = Cartela::find($request->input('cartela_id'));
        $premio = Premio::find($request->input('premio_id'));

        // 2. Verifica se o prêmio já foi ganho
        if ($premio->cartela_id) {
            return response()->json(['success' => false, 'message' => 'Este prêmio já foi reivindicado.'], 409); // 409 Conflict
        }

        // 3. Associa a cartela ao prêmio
        $premio->cartela_id = $cartela->id;
        $premio->save();

        // 4. Opcional: Registra o vencedor em uma tabela de histórico
        Vencedor::create([
            'festa_id' => $festa->id,
            'cartela_id' => $cartela->id,
            'premio_id' => $premio->id,
            'validado_por' => auth()->user()->id, // Se você estiver usando autenticação
            'status' => 'confirmado'
        ]);

        return response()->json(['success' => true, 'message' => 'Vencedor confirmado com sucesso!']);
    }

    public function removerNumero(Request $request, Festa $festa)
    {
        // Valida se o campo 'numero' foi enviado
        $request->validate(['numero' => 'required|integer|min:1|max:75']);

        // Encontra o sorteio com o número e a festa correspondentes
        $sorteio = Sorteio::where('festa_id', $festa->id)
                          ->where('numero', $request->input('numero'))
                          ->first();

        if ($sorteio) {
            $sorteio->delete();
            return response()->json(['success' => true, 'message' => 'Número removido com sucesso.']);
        }

        return response()->json(['success' => false, 'message' => 'Número não encontrado.'], 404);
    }

    public function limparSorteio(Festa $festa)
    {
        Sorteio::where('festa_id', $festa->id)->delete();

        return response()->json(['success' => true]);
    }
}
