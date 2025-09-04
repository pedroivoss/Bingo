{{-- resources/views/layouts/pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $festa->nome }}</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
            font-size: 12px;
        }

        .page-content {
            padding: 10mm;
        }

        .cabecalho-img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto 10mm;
        }

        .premios-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-bottom: 10mm;
        }
        .premio {
            border: 1px solid #ccc;
            padding: 5px;
            margin: 5px;
        }

        /* Layout de Grade para as Cartelas */
        .cartela-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 5mm;
        }

        /* Estilos da Cartela Individual */
        .cartela {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            box-sizing: border-box;
            page-break-inside: avoid;
        }
        .cartela-grid-1 .cartela { width: 100%; }
        .cartela-grid-2 .cartela { width: calc(50% - 2.5mm); }
        .cartela-grid-3 .cartela { width: calc(33.33% - 3.33mm); }
        .cartela-grid-4 .cartela { width: calc(50% - 2.5mm); }
        .cartela-grid-5 .cartela, .cartela-grid-6 .cartela { width: calc(33.33% - 3.33mm); }

        .bingo-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
        }
        .bingo-header .title {
            font-size: 16px;
            font-weight: bold;
        }

        .letras-bingo {
            display: flex;
            justify-content: space-around;
            font-weight: bold;
            font-size: 14px;
        }

        .tabela-numeros {
            width: 100%;
            border-collapse: collapse;
        }
        .tabela-numeros tr.coluna {
            display: flex;
        }
        .tabela-numeros td.celula {
            border: 1px solid #ccc;
            height: 20px;
            width: 20%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-sizing: border-box;
        }
        .tabela-numeros .coringa {
            /* Estilo para a célula coringa, se necessário */
        }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>
