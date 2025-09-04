{{-- resources/views/pdf/cartela_template.blade.php --}}
<div class="bingo-card-wrapper">
    <div class="prize-info">
        {{-- Precisa garantir que $premios seja um array acessível pelo loop->index --}}
        @if (!empty($premios[$loop->index]->titulo))
            {{ $premios[$loop->index]->titulo }}<br>
            {{ $premios[$loop->index]->descricao }}
        @endif
    </div>

    <div class="bingo-table-container">
        <table class="bingo-table">
            <thead>
                <tr>
                    <th>B</th>
                    <th>I</th>
                    <th>N</th>
                    <th>G</th>
                    <th>O</th>
                </tr>
            </thead>
            <tbody>
                @foreach(array_values($data['numeros']) as $coluna)
                    <tr>
                        @foreach($coluna as $celula)
                            <td>
                                @if (is_array($celula) && isset($celula['imagem']))
                                    <img src="{{ public_path('storage/' . $celula['imagem']) }}" alt="Imagem da Cartela">
                                @else
                                    {{ $celula }}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="barcode-section">
        <p>Cartela: {{ str_pad($data['codigo'], 4, '0', STR_PAD_LEFT) }}</p> {{-- Mantive o str_pad aqui, se quiser remover, pode --}}
        {{-- Inserir código de barras real aqui --}}
        {{-- Exemplo: <img src="data:image/png;base64,{{ DNS1D::get   ('1234567890', 'C39') }}" alt="barcode" class="barcode-image"/> --}}
        <img src="{{ public_path('images/placeholder-barcode.png') }}" alt="Código de Barras" class="barcode-image">
    </div>
</div>
