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
    <img src="{{ public_path('storage/uploads/lzYBrAWTqXmYH5mHDQsBErPabLaZ9ZjcIU8eGvLv.png') }}" alt="Cabeçalho da Festa do Divino">
</div>

<div class="watermark">
    <img src="{{ public_path('storage/uploads/Wp8NJtTtOtoxWWjWcbmzTkkN8YorKW5LT9I6kkQm.png') }}" alt="Marca D'água">
</div>

<table class="cartelas-table">
    <tbody>
        @php
            $cartelasData = [
                ['numeros' => [['01', '04', '06', '08', '15'], ['17', '20', '22', '25', '28'], ['31', '33', null, '38', '42'], ['46', '49', '51', '56', '58'], ['62', '65', '70', '73', '75']], 'codigo' => '0001', 'premio' => '1 Moto Honda CG Star 160', 'premio_nome' => '1º prêmio'],
                ['numeros' => [['01', '04', '06', '08', '15'], ['17', '20', '22', '25', '28'], ['31', '33', null, '38', '42'], ['46', '49', '51', '56', '58'], ['62', '65', '70', '73', '75']], 'codigo' => '0002', 'premio' => '1 Moto Honda Biz 110 cc', 'premio_nome' => '2º prêmio'],
                ['numeros' => [['01', '04', '06', '08', '15'], ['17', '20', '22', '25', '28'], ['31', '33', null, '38', '42'], ['46', '49', '51', '56', '58'], ['62', '65', '70', '73', '75']], 'codigo' => '0003', 'premio' => '1 Moto Honda Biz 110 cc', 'premio_nome' => '3º prêmio'],
                ['numeros' => [['01', '04', '06', '08', '15'], ['17', '20', '22', '25', '28'], ['31', '33', null, '38', '42'], ['46', '49', '51', '56', '58'], ['62', '65', '70', '73', '75']], 'codigo' => '0004', 'premio' => 'R$ 5.000,00', 'premio_nome' => '4º prêmio'],
                ['numeros' => [['01', '04', '06', '08', '15'], ['17', '20', '22', '25', '28'], ['31', '33', null, '38', '42'], ['46', '49', '51', '56', '58'], ['62', '65', '70', '73', '75']], 'codigo' => '0005', 'premio' => 'R$ 3.000,00', 'premio_nome' => '5º prêmio'],
            ];
            $cartelasPorLinha = 3;
        @endphp
        @for ($i = 0; $i < count($cartelasData); $i += $cartelasPorLinha)
            <tr>
                @for ($j = 0; $j < $cartelasPorLinha; $j++)
                    @if (isset($cartelasData[$i + $j]))
                        @php $data = $cartelasData[$i + $j]; @endphp
                        <td>
                            <div class="bingo-card">
                                <div class="prize-info">{{ $data['premio_nome'] }}<br>{{ $data['premio'] }}</div>
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
                                    <img src="placeholder-barcode.png" alt="Código de Barras">
                                </div>
                            </div>
                        </td>
                    @else
                        <td></td>
                    @endif
                @endfor
            </tr>
        @endfor
    </tbody>
</table>

</body>
</html>
