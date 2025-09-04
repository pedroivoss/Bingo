{{-- resources/views/pdf/page_multiple_cartelas_per_page.blade.php --}}
@extends('layouts.pdf')

@section('content')
    <div class="page-wrapper"> {{-- Novo wrapper para controlar a página --}}
        <div class="header-container">
            @if ($festa->cabecalho_path)
                <img src="{{ public_path('storage/' . $festa->cabecalho_path) }}" alt="Cabeçalho da Festa">
            @endif
        </div>

        @if($festa->watermark_path)
            <div class="watermark-absolute">
                <img src="{{ public_path('storage/' . $festa->watermark_path) }}" alt="Marca D'água">
            </div>
        @endif

        <div class="cartelas-grid-container cartela-cols-{{ $quantidadePorFolha }}">
            @foreach($cartelasData as $data)
                @include('pdf.cartela_template', ['data' => $data, 'premios' => $premios])
            @endforeach
        </div>

        {{-- Adicione um clearfix se estiver usando floats --}}
        <div style="clear: both;"></div>

        @if($festa->rodape_html)
            <div class="rodape-html">{!! $festa->rodape_html !!}</div>
        @endif
    </div>
@endsection
