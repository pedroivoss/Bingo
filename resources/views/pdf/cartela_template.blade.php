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
    <table class="tabela-numeros">
        @foreach($data['numeros'] as $coluna)
            <tr class="coluna">
                @foreach($coluna as $numero)
                    <td class="celula">
                        @if($numero === null)
                            <div class="coringa"></div>
                        @else
                            {{ $numero }}
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
    </table>
</div>
