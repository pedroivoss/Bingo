{{-- resources/views/layouts/pdf.blade.php --}}
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>{{ $festa->nome ?? 'Cartelas de Bingo' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
            font-size: 12px;
            /* Garante que o corpo da página seja o contexto para o position absolute */
            position: relative;
            min-height: 297mm; /* Altura de uma folha A4 para garantir contexto */
            width: 210mm; /* Largura de uma folha A4 */
        }
        .page-wrapper {
            padding: 10mm; /* Margem interna da página */
        }
        .header-container {
            width: 100%;
            text-align: center;
            margin-bottom: 10mm;
        }
        .header-container img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        /* Marca d'água: posicionamento absoluto dentro do body para cobrir a página */
        .watermark-absolute {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.1;
            z-index: -1;
            width: 80%; /* Ajuste este valor para controlar o tamanho da marca d'água */
            height: auto;
            display: block;
        }
        .watermark-absolute img {
            width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        /* Container das cartelas usando FLOTUANTES */
        .cartelas-grid-container {
            /* clearfix para garantir que o container envolva os floats */
            overflow: hidden;
            margin: 0 -5mm; /* Compensar o padding das cartelas */
        }

        .bingo-card-wrapper {
            float: left; /* ESSENCIAL para layout em colunas no DomPDF */
            box-sizing: border-box;
            padding: 5mm; /* Espaçamento ENTRE as cartelas */
            margin-bottom: 5mm; /* Espaçamento vertical entre linhas de cartelas */
            page-break-inside: avoid; /* Evita quebras de página DENTRO de uma cartela */
            text-align: center;
            height: auto; /* Permite que a altura se ajuste ao conteúdo */
        }

        /* LARGURAS DAS CARTELAS (Float baseado em porcentagem) */
        .cartela-cols-1 .bingo-card-wrapper { width: 100%; }
        .cartela-cols-2 .bingo-card-wrapper { width: 50%; }
        .cartela-cols-3 .bingo-card-wrapper { width: 33.333%; }
        .cartela-cols-4 .bingo-card-wrapper { width: 50%; } /* Ex: 2x2 */
        .cartela-cols-5 .bingo-card-wrapper { width: 33.333%; } /* Ex: 3x2, onde a última linha tem 2 */
        .cartela-cols-6 .bingo-card-wrapper { width: 33.333%; } /* Ex: 3x2 */

        .prize-info {
            font-weight: bold;
            margin-bottom: 5px;
            line-height: 1.1;
            font-size: 16px;
            min-height: 40px; /* Garante altura mínima para prêmios de 2 linhas */
        }

        .bingo-table-container {
            border: 1px solid #000;
            padding: 0;
            margin-top: 5px;
            width: 100%; /* Ocupa 100% da largura do seu wrapper (float) */
            max-width: 220px; /* Limita a largura da tabela */
            margin-left: auto; /* Centraliza a tabela se max-width for menor que 100% */
            margin-right: auto;
        }

        .bingo-table {
            width: 100%;
            border-collapse: collapse;
        }
        .bingo-table th, .bingo-table td {
            border: 1px solid #000;
            padding: 5px; /* Ajuste o padding se precisar de mais espaço */
            text-align: center;
            font-weight: bold;
            font-size: 14px; /* Ajuste o tamanho da fonte se as células estiverem muito apertadas */
            vertical-align: middle;
            line-height: 1; /* Ajuda a controlar a altura da linha */
        }
        .bingo-table th {
            font-size: 16px; /* Ajuste o tamanho da fonte do cabeçalho */
        }
        .bingo-table td img {
            max-width: 100%;
            max-height: 25px; /* Limita a altura da imagem na célula */
            display: block;
            margin: 0 auto;
            object-fit: contain;
        }
        .barcode-section {
            display: block; /* Garante que ocupe seu próprio espaço */
            text-align: center;
            margin-top: 5mm;
        }
        .barcode-section p {
            margin: 0 0 2mm 0;
            font-size: 12px;
            font-weight: bold;
        }
        .barcode-image {
            width: 100px; /* Largura fixa para o código de barras */
            height: auto;
            border: 1px solid #000;
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body>
@yield('content')
</body>
</html>
