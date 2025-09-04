@extends('layouts.pdf')

@section('content')
    <div class="page-content">
        @if($festa->cabecalho_path)
            <img src="{{ public_path('storage/' . $festa->cabecalho_path) }}" class="cabecalho-img">
        @endif

        <div class="cartela-grid" style="
            @if($quantidadePorFolha == 2)
                grid-template-columns: repeat(2, 1fr);
            @elseif($quantidadePorFolha == 3)
                grid-template-columns: repeat(3, 1fr);
            @elseif($quantidadePorFolha == 4)
                grid-template-columns: repeat(2, 1fr);
            @elseif($quantidadePorFolha == 5 || $quantidadePorFolha == 6)
                grid-template-columns: repeat(3, 1fr); /* << Alterado para 3 colunas */
            @endif
            ">
            @foreach($cartelasData as $data)
                @include('pdf.cartela_template', $data)
            @endforeach
        </div>
        @if($festa->rodape_html)
            <div class="rodape-html">{!! $festa->rodape_html !!}</div>
        @endif
    </div>
@endsection
