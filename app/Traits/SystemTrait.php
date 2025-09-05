<?php

namespace App\Traits;

use App\Models\client;
use App\Models\employee_description_pay;
use App\Models\employee_description_pay_roll;
use App\Models\surveys\survey_questions;
use App\Models\surveys\survey_scheduling_answers;
use App\Models\surveys\survey_schedulings;
use App\Models\sys_group;
use App\Models\sys_image_store;
use App\Models\sys_log_update;
use App\Models\sys_status;
use App\Models\sys_table_code;
use DateTime;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Laravel\Sanctum\PersonalAccessToken;

trait SystemTrait {

    use ApiResponser;


    public function dataDefault(): array
    {
        $data_grupo = sys_group::where('id',Auth::user()->id_group)->get();

        $data['groupName'] = ($data_grupo->isEmpty()) ? 'no sign' : ($data_grupo[0]->name ?? 'no sign');

        $data_sistema = "Allima Cleaning Service LLC";

        $data['nome_sistema'] = "Allima Cleaning Service LLC";
        $data['img_sistema'] = 'Sem sistema atribuido!';
        $data['app_name'] = env('APP_NAME');

        //permissoes do usuario
        $data['userPermissionsList'] = $this->permissionByUser(Auth::user()->id);

        $verificaClient = client::where('id', Auth::user()->id_client)->first();
        if (null !== $verificaClient) {
            $data['nome_sistema'] = $verificaClient->name;
            $data['img_client'] = $verificaClient->path_img;
            $data['height_img_client'] = $verificaClient->height_img;
        } else {
            $data['clientName'] = 'Sem cliente atribuido!';
            $data['img_client'] = '';
        }

        // Obtém cor navbar
        $data['primeiraLetra'] = $this->obterIniciais(Auth::user()->name, 2);

        $corFundo = $this->obterCorInicialNome(Auth::user()->name);
        $data['corInicial'] = $this->rgbToHex($corFundo[0], $corFundo[1], $corFundo[2]);

        $corTexto = $this->ajustarCor($corFundo[0], $corFundo[1], $corFundo[2]);
        $data['corTexto'] = $this->rgbToHex($corTexto[0], $corTexto[1], $corTexto[2]);

        return $data;
    }//fim função

}//fim classe

