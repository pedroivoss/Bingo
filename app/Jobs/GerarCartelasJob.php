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
            $numerosJson = json_encode($numeros);
            $hashIntegridade = hash('sha256', $numerosJson);

            $codigo = str_pad($proximoCodigo, 4, '0', STR_PAD_LEFT);

            Cartela::create([
                'festa_id' => $this->festa->id,
                'codigo' => $codigo,
                'numeros' => $numeros,
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
        $cartela['B'] = $this->pegarNumerosAleatorios(collect($colunas['B']), 5);
        $cartela['I'] = $this->pegarNumerosAleatorios(collect($colunas['I']), 5);
        $cartela['N'] = $this->pegarNumerosAleatorios(collect($colunas['N']), 5);
        $cartela['G'] = $this->pegarNumerosAleatorios(collect($colunas['G']), 5);
        $cartela['O'] = $this->pegarNumerosAleatorios(collect($colunas['O']), 5);

        $cartela['N'][2] = null;

        return array_values($cartela);
    }

    private function pegarNumerosAleatorios(\Illuminate\Support\Collection $numeros, int $quantidade): array
    {
        $selecionados = $numeros->shuffle()->take($quantidade)->sort()->all();
        return $selecionados;
    }
}
