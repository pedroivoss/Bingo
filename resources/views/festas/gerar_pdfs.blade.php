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
            padding: 10px;
            background-color: #fff;
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

        .page-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px 10px;
            width: 100%;
            max-width: none;
            margin: 0 auto;
            position: relative; /* Essencial para que a marca d'água seja posicionada corretamente */
        }

        /* Novo estilo para a marca d'água */
        .watermark {
            position: absolute;
            top: 50%; /* Centraliza a altura */
            left: 50%; /* Centraliza horizontalmente */
            transform: translate(-50%, -50%); /* Ajusta o alinhamento para o centro exato */
            opacity: 0.1; /* Opacidade bem leve */
            pointer-events: none;
        }

        .watermark img {
            width: 100%; /* Ajuste o tamanho da marca d'água para 75% da largura da página */
            height: auto;
        }

        .bingo-card-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            box-sizing: border-box;
        }

        .prize-info {
            font-weight: bold;
            margin-bottom: 5px;
            line-height: 1.1;
            font-size: 16px;
        }

        .bingo-table-container {
            border: 1px solid #000;
            padding: 0;
            margin-top: 5px;
            width: 100%;
            max-width: 220px;
        }

        .bingo-table {
            width: 100%;
            border-collapse: collapse;
        }

        .bingo-table th, .bingo-table td {
            border: 1px solid #000;
            padding: 10px;
            text-align: center;
            font-weight: bold;
            font-size: 18px;
        }

        .bingo-table th {
            font-size: 20px;
        }

        .bingo-table td img {
            max-width: 100%;
            max-height: 100%;
            display: block;
            margin: 0 auto;
            object-fit: contain;
        }

        .barcode-section {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-top: 10px;
        }

        .barcode-section p {
            margin: 0 0 5px 0;
            font-size: 14px;
            font-weight: bold;
        }

        .barcode-image {
            width: 150px;
            height: auto;
            border: 1px solid #000;
        }
    </style>
</head>
<body>

<div class="header-container">
    <img src="{{asset('storage/uploads/nQu81I7erF5WgyiOQxNrjPd3T309liZyLYGLPAxU.png')}}" alt="Cabeçalho da Festa do Divino">
</div>

<div class="page-container">
    <div class="watermark">
        <img src="{{asset('storage/uploads/YpYUdltIDf5uxb7fcjtjm4prMG4hBJBpeN5L6jwd.png')}}" alt="Marca D'água">
    </div>

    <div class="bingo-card-wrapper">
        <div class="prize-info">
            1º prêmio<br>
            1 Moto Honda CG Star 160 cc
        </div>
        <div class="bingo-table-container">
            <table class="bingo-table">
                <thead>
                    <tr>
                        <th>B</th>
                        <th>I</th>
                        <th>N</th>
                        <th>G</th>
                        <th>O</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>01</td>
                        <td>17</td>
                        <td>31</td>
                        <td>46</td>
                        <td>62</td>
                    </tr>
                    <tr>
                        <td>04</td>
                        <td>20</td>
                        <td>33</td>
                        <td>49</td>
                        <td>65</td>
                    </tr>
                    <tr>
                        <td>06</td>
                        <td>22</td>
                        <td>
                            <img src="{{asset('storage/uploads/Q4XZts69stApeENf1nz63xiSJYyjp18Wq18t5lYW.png')}}" alt="Sua Imagem">
                        </td>
                        <td>51</td>
                        <td>70</td>
                    </tr>
                    <tr>
                        <td>08</td>
                        <td>25</td>
                        <td>38</td>
                        <td>56</td>
                        <td>73</td>
                    </tr>
                    <tr>
                        <td>15</td>
                        <td>28</td>
                        <td>42</td>
                        <td>58</td>
                        <td>75</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bingo-card-wrapper">
        <div class="prize-info">
            2º prêmio<br>
            1 Moto Honda Biz 110 cc
        </div>
        <div class="bingo-table-container">
            <table class="bingo-table">
                <thead>
                    <tr>
                        <th>B</th>
                        <th>I</th>
                        <th>N</th>
                        <th>G</th>
                        <th>O</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>01</td>
                        <td>17</td>
                        <td>31</td>
                        <td>46</td>
                        <td>62</td>
                    </tr>
                    <tr>
                        <td>04</td>
                        <td>20</td>
                        <td>33</td>
                        <td>49</td>
                        <td>65</td>
                    </tr>
                    <tr>
                        <td>06</td>
                        <td>22</td>
                        <td>
                            <img src="{{asset('storage/uploads/Q4XZts69stApeENf1nz63xiSJYyjp18Wq18t5lYW.png')}}" alt="Sua Imagem">
                        </td>
                        <td>51</td>
                        <td>70</td>
                    </tr>
                    <tr>
                        <td>08</td>
                        <td>25</td>
                        <td>38</td>
                        <td>56</td>
                        <td>73</td>
                    </tr>
                    <tr>
                        <td>15</td>
                        <td>28</td>
                        <td>42</td>
                        <td>58</td>
                        <td>75</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bingo-card-wrapper">
        <div class="prize-info">
            3º prêmio<br>
            1 Moto Honda Biz 110 cc
        </div>
        <div class="bingo-table-container">
            <table class="bingo-table">
                <thead>
                    <tr>
                        <th>B</th>
                        <th>I</th>
                        <th>N</th>
                        <th>G</th>
                        <th>O</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>01</td>
                        <td>17</td>
                        <td>31</td>
                        <td>46</td>
                        <td>62</td>
                    </tr>
                    <tr>
                        <td>04</td>
                        <td>20</td>
                        <td>33</td>
                        <td>49</td>
                        <td>65</td>
                    </tr>
                    <tr>
                        <td>06</td>
                        <td>22</td>
                        <td>
                            <img src="{{asset('storage/uploads/Q4XZts69stApeENf1nz63xiSJYyjp18Wq18t5lYW.png')}}" alt="Sua Imagem">
                        </td>
                        <td>51</td>
                        <td>70</td>
                    </tr>
                    <tr>
                        <td>08</td>
                        <td>25</td>
                        <td>38</td>
                        <td>56</td>
                        <td>73</td>
                    </tr>
                    <tr>
                        <td>15</td>
                        <td>28</td>
                        <td>42</td>
                        <td>58</td>
                        <td>75</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bingo-card-wrapper">
        <div class="prize-info">
            4º prêmio<br>
            R$ 5.000,00
        </div>
        <div class="bingo-table-container">
            <table class="bingo-table">
                <thead>
                    <tr>
                        <th>B</th>
                        <th>I</th>
                        <th>N</th>
                        <th>G</th>
                        <th>O</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>01</td>
                        <td>17</td>
                        <td>31</td>
                        <td>46</td>
                        <td>62</td>
                    </tr>
                    <tr>
                        <td>04</td>
                        <td>20</td>
                        <td>33</td>
                        <td>49</td>
                        <td>65</td>
                    </tr>
                    <tr>
                        <td>06</td>
                        <td>22</td>
                        <td>
                            <img src="{{asset('storage/uploads/Q4XZts69stApeENf1nz63xiSJYyjp18Wq18t5lYW.png')}}" alt="Sua Imagem">
                        </td>
                        <td>51</td>
                        <td>70</td>
                    </tr>
                    <tr>
                        <td>08</td>
                        <td>25</td>
                        <td>38</td>
                        <td>56</td>
                        <td>73</td>
                    </tr>
                    <tr>
                        <td>15</td>
                        <td>28</td>
                        <td>42</td>
                        <td>58</td>
                        <td>75</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bingo-card-wrapper">
        <div class="prize-info">
            5° prêmio<br>
            R$ 3.000,00
        </div>
        <div class="bingo-table-container">
            <table class="bingo-table">
                <thead>
                    <tr>
                        <th>B</th>
                        <th>I</th>
                        <th>N</th>
                        <th>G</th>
                        <th>O</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>01</td>
                        <td>17</td>
                        <td>31</td>
                        <td>46</td>
                        <td>62</td>
                    </tr>
                    <tr>
                        <td>04</td>
                        <td>20</td>
                        <td>33</td>
                        <td>49</td>
                        <td>65</td>
                    </tr>
                    <tr>
                        <td>06</td>
                        <td>22</td>
                        <td>
                            <img src="{{asset('storage/uploads/Q4XZts69stApeENf1nz63xiSJYyjp18Wq18t5lYW.png')}}" alt="Sua Imagem">
                        </td>
                        <td>51</td>
                        <td>70</td>
                    </tr>
                    <tr>
                        <td>08</td>
                        <td>25</td>
                        <td>38</td>
                        <td>56</td>
                        <td>73</td>
                    </tr>
                    <tr>
                        <td>15</td>
                        <td>28</td>
                        <td>42</td>
                        <td>58</td>
                        <td>75</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bingo-card-wrapper">
        <div class="barcode-section">
            <p>Cartela:0001</p>
            <img src="placeholder-barcode.png" alt="Código de Barras" class="barcode-image">
        </div>
    </div>
</div>

</body>
</html>
