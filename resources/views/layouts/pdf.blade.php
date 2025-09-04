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

        /* Layout de Grade para as Cartelas */
        .cartela-grid {
            display: grid;
            gap: 5mm; /* Espaçamento entre as cartelas */
        }
        .cartela-grid-1 { grid-template-columns: 1fr; }
        .cartela-grid-2 { grid-template-columns: repeat(2, 1fr); }
        .cartela-grid-3 { grid-template-columns: repeat(3, 1fr); }
        .cartela-grid-4 { grid-template-columns: repeat(2, 1fr); }
        .cartela-grid-5 { grid-template-columns: repeat(3, 1fr); }
        .cartela-grid-6 { grid-template-columns: repeat(3, 1fr); }

        /* Estilos da Cartela Individual */
        .cartela {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            width: 100%;
            box-sizing: border-box;
            page-break-inside: avoid;
        }

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
            display: flex;
            justify-content: space-around;
        }
        .tabela-numeros .coluna {
            display: flex;
            flex-direction: column;
            width: 20%;
        }
        .tabela-numeros .celula {
            border: 1px solid #ccc;
            height: 20px;
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
