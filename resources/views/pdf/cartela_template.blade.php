{{-- resources/views/pdf/cartela_template.blade.php --}}
<div class="cartela">
    <div class="bingo-header">
        <div class="title">BINGO</div>
        <div class="codigo">Cartela: {{ $data['codigo'] }}</div>
    </div>
    <div class="letras-bingo">
        <span>B</span>
        <span>I</span>
        <span>N</span>
        <span>G</span>
        <span>O</span>
    </div>
    <div class="tabela-numeros">
        @foreach($data['numeros'] as $coluna)
            <div class="coluna">
                @foreach($coluna as $numero)
                    <div class="celula">
                        @if($numero === null)
                            <div class="coringa"></div>
                        @else
                            {{ $numero }}
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
    {{-- Acesso à festa e outras informações --}}
    @if (!empty($data['festa']->rodape_html))
        <div class="rodape-html">{!! $data['festa']->rodape_html !!}</div>
    @endif
</div>
