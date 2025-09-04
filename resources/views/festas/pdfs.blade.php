{{-- resources/views/festas/pdfs.blade.php --}}
@extends('layouts.app') {{-- Ou o layout que você estiver usando --}}

@section('content')
<div class="container">
    <h1>PDFs Gerados para {{ $festa->nome_da_festa ?? 'Festa' }}</h1>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if (count($festaPdfs) > 0)
        <ul>
            @php
                $base_url = env('APP_URL')
            @endphp
            @foreach ($festaPdfs as $pdfUrl)
                <li>
                    @php
                        $urlPDF = $base_url . '/' . ltrim($pdfUrl, '/');
                    @endphp
                    <a href="{{ $urlPDF }}" target="_blank">
                        {{ basename($pdfUrl) }}
                    </a>
                    {{-- Opcional: link para download direto --}}
                    {{-- <a href="{{ route('festas.downloadPdf', ['festa' => $festa->id, 'filename' => basename($pdfUrl)]) }}" download>
                        (Download)
                    </a> --}}
                </li>
            @endforeach
        </ul>
    @else
        <p>Ainda não há PDFs gerados para esta festa.</p>
    @endif

    <a href="{{ route('painel.index') }}" class="btn btn-secondary">Voltar</a> {{-- Ou a rota do seu painel --}}
</div>
@endsection
