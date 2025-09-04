<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bingo Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        .bingo-card { border: 2px solid #000; border-collapse: collapse; }
        .bingo-card td { width: 50px; height: 50px; text-align: center; border: 1px solid #ccc; font-size: 1.5rem; }
        .marked { background-color: #ffc107; color: #fff; }
    </style>
</head>
<body>
    <div class="container mt-5">
        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    {{-- Espaço para scripts personalizados --}}
    @yield('jsPersonalizado')
</body>
</html>
