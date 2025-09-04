{{-- resources/views/pdf/page_multiple_cartelas_per_page.blade.php --}}
@extends('layouts.pdf')

@section('content')
    <div class="page-content">
        @if ($festa->cabecalho_path)
            <img src="{{ public_path('storage/' . $festa->cabecalho_path) }}" class="cabecalho-img">
        @endif

        @if ($quantidadePorFolha > 1)
            <div class="premios-container">
                @foreach($premios as $premio)
                    <div class="premio">
                        <div class="premio-titulo">{{ $premio->titulo }}</div>
                        <div class="premio-descricao">{{ $premio->descricao }}</div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="cartela-grid cartela-grid-{{ $quantidadePorFolha }}">
            @foreach($cartelasData as $data)
                @include('pdf.cartela_template', ['data' => $data])
            @endforeach
        </div>

        @if($festa->rodape_html)
            <div class="rodape-html">{!! $festa->rodape_html !!}</div>
        @endif
    </div>
@endsection
