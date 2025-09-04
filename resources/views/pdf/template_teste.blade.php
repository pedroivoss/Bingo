<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cartelas de Bingo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
            background-color: #fff;
        }

        .header-container {
            text-align: center;
            margin-bottom: 10px;
        }

        .header-container img {
            width: 100%;
            max-width: 800px;
        }

        .watermark {
            position: fixed;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.1;
            z-index: -1;
        }

        .cartelas-table {
            width: 100%;
            border-collapse: collapse;
        }

        .cartelas-table td {
            width: 33%;
            vertical-align: top;
            padding: 10px;
        }

        .bingo-card {
            border: 1px solid #000;
            text-align: center;
            margin-bottom: 10px;
        }

        .prize-info {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .bingo-table {
            width: 100%;
            border-collapse: collapse;
        }

        .bingo-table th, .bingo-table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
            font-weight: bold;
            font-size: 14px;
        }

        .bingo-table th {
            background: #f2f2f2;
        }

        .barcode-section {
            margin-top: 5px;
            font-size: 12px;
            text-align: center;
        }

        .barcode-section img {
            width: 120px;
            height: auto;
            border: 1px solid #000;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

<div class="header-container">
    <img src="{{ public_path('storage/uploads/lzYBrAWTqXmYH5mHDQsBErPabLaZ9ZjcIU8eGvLv.png') }}" alt="Cabeçalho">
</div>

<div class="watermark">
    <img src="{{ public_path('storage/uploads/Wp8NJtTtOtoxWWjWcbmzTkkN8YorKW5LT9I6kkQm.png') }}" width="500" alt="Marca D'água">
</div>

{{-- GRADE DE 6 CARTELAS (3x2) --}}
<table class="cartelas-table">
    <tr>
        <td>
            <div class="bingo-card">
                <div class="prize-info">1º prêmio<br>1 Moto Honda CG 160</div>
                <table class="bingo-table">
                    <thead>
                        <tr>
                            <th>B</th><th>I</th><th>N</th><th>G</th><th>O</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>01</td><td>17</td><td>31</td><td>46</td><td>62</td></tr>
                        <tr><td>04</td><td>20</td><td>33</td><td>49</td><td>65</td></tr>
                        <tr><td>06</td><td>22</td><td><img src="{{ public_path('storage/uploads/Qd9YnMvp4AxkO7F7p5yNu0L0Re6Zl3gsS4Urdb79.png') }}" width="20"></td><td>51</td><td>70</td></tr>
                        <tr><td>08</td><td>25</td><td>38</td><td>56</td><td>73</td></tr>
                        <tr><td>15</td><td>28</td><td>42</td><td>58</td><td>75</td></tr>
                    </tbody>
                </table>
                <div class="barcode-section">
                    <p>Cartela:0001</p>
                    <img src="placeholder-barcode.png" alt="Código de Barras">
                </div>
            </div>
        </td>

        <td>
             <div class="bingo-card">
                <div class="prize-info">1º prêmio<br>1 Moto Honda CG 160</div>
                <table class="bingo-table">
                    <thead>
                        <tr>
                            <th>B</th><th>I</th><th>N</th><th>G</th><th>O</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>01</td><td>17</td><td>31</td><td>46</td><td>62</td></tr>
                        <tr><td>04</td><td>20</td><td>33</td><td>49</td><td>65</td></tr>
                        <tr><td>06</td><td>22</td><td><img src="{{ public_path('storage/uploads/Qd9YnMvp4AxkO7F7p5yNu0L0Re6Zl3gsS4Urdb79.png') }}" width="20"></td><td>51</td><td>70</td></tr>
                        <tr><td>08</td><td>25</td><td>38</td><td>56</td><td>73</td></tr>
                        <tr><td>15</td><td>28</td><td>42</td><td>58</td><td>75</td></tr>
                    </tbody>
                </table>
                <div class="barcode-section">
                    <p>Cartela:0001</p>
                    <img src="placeholder-barcode.png" alt="Código de Barras">
                </div>
            </div>
        </td>

        <td>
             <div class="bingo-card">
                <div class="prize-info">1º prêmio<br>1 Moto Honda CG 160</div>
                <table class="bingo-table">
                    <thead>
                        <tr>
                            <th>B</th><th>I</th><th>N</th><th>G</th><th>O</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>01</td><td>17</td><td>31</td><td>46</td><td>62</td></tr>
                        <tr><td>04</td><td>20</td><td>33</td><td>49</td><td>65</td></tr>
                        <tr><td>06</td><td>22</td><td><img src="{{ public_path('storage/uploads/Qd9YnMvp4AxkO7F7p5yNu0L0Re6Zl3gsS4Urdb79.png') }}" width="20"></td><td>51</td><td>70</td></tr>
                        <tr><td>08</td><td>25</td><td>38</td><td>56</td><td>73</td></tr>
                        <tr><td>15</td><td>28</td><td>42</td><td>58</td><td>75</td></tr>
                    </tbody>
                </table>
                <div class="barcode-section">
                    <p>Cartela:0001</p>
                    <img src="placeholder-barcode.png" alt="Código de Barras">
                </div>
            </div>
        </td>
    </tr>

    <tr>
        <td>
             <div class="bingo-card">
                <div class="prize-info">1º prêmio<br>1 Moto Honda CG 160</div>
                <table class="bingo-table">
                    <thead>
                        <tr>
                            <th>B</th><th>I</th><th>N</th><th>G</th><th>O</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>01</td><td>17</td><td>31</td><td>46</td><td>62</td></tr>
                        <tr><td>04</td><td>20</td><td>33</td><td>49</td><td>65</td></tr>
                        <tr><td>06</td><td>22</td><td><img src="{{ public_path('storage/uploads/Qd9YnMvp4AxkO7F7p5yNu0L0Re6Zl3gsS4Urdb79.png') }}" width="20"></td><td>51</td><td>70</td></tr>
                        <tr><td>08</td><td>25</td><td>38</td><td>56</td><td>73</td></tr>
                        <tr><td>15</td><td>28</td><td>42</td><td>58</td><td>75</td></tr>
                    </tbody>
                </table>
                <div class="barcode-section">
                    <p>Cartela:0001</p>
                    <img src="placeholder-barcode.png" alt="Código de Barras">
                </div>
            </div>
        </td>
        <td>
             <div class="bingo-card">
                <div class="prize-info">1º prêmio<br>1 Moto Honda CG 160</div>
                <table class="bingo-table">
                    <thead>
                        <tr>
                            <th>B</th><th>I</th><th>N</th><th>G</th><th>O</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>01</td><td>17</td><td>31</td><td>46</td><td>62</td></tr>
                        <tr><td>04</td><td>20</td><td>33</td><td>49</td><td>65</td></tr>
                        <tr><td>06</td><td>22</td><td><img src="{{ public_path('storage/uploads/Qd9YnMvp4AxkO7F7p5yNu0L0Re6Zl3gsS4Urdb79.png') }}" width="20"></td><td>51</td><td>70</td></tr>
                        <tr><td>08</td><td>25</td><td>38</td><td>56</td><td>73</td></tr>
                        <tr><td>15</td><td>28</td><td>42</td><td>58</td><td>75</td></tr>
                    </tbody>
                </table>
                <div class="barcode-section">
                    <p>Cartela:0001</p>
                    <img src="placeholder-barcode.png" alt="Código de Barras">
                </div>
            </div>
        </td>
    </tr>
</table>

<div class="page-break"></div>

</body>
</html>
