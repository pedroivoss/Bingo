<?php

namespace App\Jobs;

use App\Models\Festa;
use App\Models\Cartela;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\GerarPdfsJob; // <<< Adicione esta linha

class GerarCartelasJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Festa $festa,
        public int $quantidade
    ) {}

    public function handle(): void
    {
        $ultimoCodigo = $this->festa->cartelas()->max('codigo');
        $proximoCodigo = $ultimoCodigo ? (int)$ultimoCodigo + 1 : 1;

        for ($i = 0; $i < $this->quantidade; $i++) {
            $numeros = $this->gerarNumerosCartela();

            // Gera JSON exatamente no formato esperado
            $numerosJson = json_encode($numeros, JSON_UNESCAPED_UNICODE);
            $hashIntegridade = hash('sha256', $numerosJson);

            $codigo = str_pad($proximoCodigo, 4, '0', STR_PAD_LEFT);

            Cartela::create([
                'festa_id' => $this->festa->id,
                'codigo' => $codigo,
                'numeros' => $numeros, // vai para o banco já como array
                'hash_integridade' => $hashIntegridade,
            ]);

            $proximoCodigo++;
        }
    }

    private function gerarNumerosCartela(): array
    {
        $colunas = [
            'B' => range(1, 15),
            'I' => range(16, 30),
            'N' => range(31, 45),
            'G' => range(46, 60),
            'O' => range(61, 75),
        ];

        $cartela = [];

        foreach ($colunas as $letra => $numeros) {
            $selecionados = $this->pegarNumerosAleatorios(collect($numeros), 5);

            if ($letra === 'N') {
                $selecionados[2] = null; // centro livre
            }

            // ⚡ força cada coluna a ser objeto numerado {0: x, 1: y, ...}
            $cartela[] = collect($selecionados)->values()->all();
        }

        return $cartela;
    }

    private function pegarNumerosAleatorios(\Illuminate\Support\Collection $numeros, int $quantidade): array
    {
        return $numeros->shuffle()->take($quantidade)->sort()->values()->all();
    }
}

