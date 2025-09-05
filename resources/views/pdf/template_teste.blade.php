<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartelas de Bingo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10mm;
            background-color: #fff;
            position: relative;
        }

        .header-container {
            width: 100%;
            text-align: center;
            margin-bottom: 20px;
        }

        .header-container img {
            width: 100%;
            height: auto;
            max-width: 900px;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.1;
            z-index: -1;
            width: 200px;
        }

        .watermark img {
            width: 100%;
            height: auto;
        }

        .cartelas-table {
            width: 100%;
            border-collapse: collapse;
        }

        .cartelas-table td {
            width: 33.333%;
            vertical-align: top;
            padding: 10px; /* Aumentei o espaçamento para criar espaço lateral e vertical */
            box-sizing: border-box;
        }

        .bingo-card {
            border: none;
            text-align: center;
            page-break-inside: avoid;
            padding: 0;
            margin: 0; /* Removido o margin-bottom para evitar conflito de espaçamento */
        }

        .prize-info {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
            line-height: 1.1;
        }

        .bingo-table {
            width: 100%;
            border-collapse: collapse;
        }

        .bingo-table th, .bingo-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            font-weight: bold;
            font-size: 18px;
        }

        .bingo-table th {
            font-size: 20px;
            background-color: #f2f2f2;
        }

        .bingo-table td img {
            max-width: 100%;
            max-height: 100%;
            display: block;
            margin: 0 auto;
            object-fit: contain;
        }

        .barcode-section {
            padding-top: 5px;
            text-align: center;
        }

        .barcode-section p {
            margin: 0 0 5px 0;
            font-size: 14px;
            font-weight: bold;
        }

        .barcode-section img {
            width: 150px;
            height: auto;
            border: none;
        }
    </style>
</head>
<body>

<div class="header-container">
    <img src="{{ public_path('storage/' . $festa->cabecalho_path) }}" alt="Cabeçalho da Festa">
</div>

<table class="cartelas-table">
    <tbody>
        @php
            $rows = $cartelasData->chunk(3);
        @endphp
        @foreach($rows as $cartelaRow)
            <tr>
                @foreach($cartelaRow as $data)
                    <td>
                        <div class="bingo-card">
                            <div class="prize-info">
                                {{ $data['premios'][0]['nome'] ?? 'Prêmio' }}<br>
                                {{ $data['premios'][0]['premio'] ?? 'N/A' }}
                            </div>
                            <table class="bingo-table">
                                <thead>
                                    <tr><th>B</th><th>I</th><th>N</th><th>G</th><th>O</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($data['numeros'] as $linha)
                                        <tr>
                                            @foreach($linha as $numero)
                                                <td>
                                                    @if($numero === null)
                                                        <img src="{{ public_path('storage/uploads/Qd9YnMvp4AxkO7F7p5yNu0L0Re6Zl3gsS4Urdb79.png') }}" alt="Coringa">
                                                    @else
                                                        {{ $numero }}
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="barcode-section">
                                <p>Cartela:{{ $data['codigo'] }}</p>
                            </div>
                        </div>
                    </td>
                @endforeach
                @if($cartelaRow->count() < 3)
                    @for($i = 0; $i < 3 - $cartelaRow->count(); $i++)
                        <td></td>
                    @endfor
                @endif
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
