<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bingo Digital</title>
    <link href="{{ asset('assets/css/plugins/bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/fontawesome.all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/sweetalert2.min.css') }}">

    {{-- Estilos do sistema --}}
    <link rel="stylesheet" href="{{ asset('storage/css/sysApp.css') }}?v={{ Storage::disk('public')->lastModified('css/sysApp.css') }}">

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
    <script>
        const base_URL = "{{env('APP_URL')}}";
        const maxCartelas = 9999;
    </script>
    <script src="{{ asset('assets/js/jQuery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/fontawesome.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/blockUI.js') }}"></script>
    {{-- Script do sistema --}}

    <script src="{{ asset('storage/js/sysApp.js') }}?v={{ Storage::disk('public')->lastModified('js/sysApp.js') }}"></script>


    @stack('scripts')
    {{-- Espaço para scripts personalizados --}}
    @yield('jsPersonalizado')
</body>
</html>
