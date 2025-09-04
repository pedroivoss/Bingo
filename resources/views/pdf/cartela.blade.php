@extends('layouts.pdf')

@section('content')
    @for ($i = 0; $i < $quantidade_por_folha; $i++)
        <div class="cartela">
            <h2 style="text-align: center;">B I N G O</h2>
            <table class="bingo-card">
                <thead>
                    <tr>
                        <td>B</td><td>I</td><td>N</td><td>G</td><td>O</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cartela->numeros as $row)
                        <tr>
                            @foreach ($row as $number)
                                @if (is_null($number))
                                    <td><img src="{{ asset($festa->coringa_path) }}" style="width: 40px;"></td>
                                @else
                                    <td>{{ $number }}</td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-2 text-center">
                <p>Cartela: {{ $cartela->codigo }}</p>
                {!! $barcode !!}
            </div>
            @if (!is_null($festa->rodape_html))
                <div class="mt-2 text-center">
                    {!! $festa->rodape_html !!}
                </div>
            @endif
        </div>
    @endfor
@endsection
