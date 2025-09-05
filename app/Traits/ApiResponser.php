<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

/*
|--------------------------------------------------------------------------
| Api Responser Trait
|--------------------------------------------------------------------------
|
| This trait will be used for any response we sent to clients.
|
*/

trait ApiResponser
{

    /**
     * Return a success JSON response.
     *
     * @param array<string, mixed>|string|null $data
     * @param string|null $message
     * @param int|null $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success(?string $message = null, ?int $code = 200, array|string|null $data = null): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code ?? 200);
    }//fim funcao

    /**
     * Return an error JSON response.
     *
     * @param string|null $message
     * @param int|null $code
     * @param array<string, mixed>|string|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function error(?string $message = null, ?int $code = 203, array|string|null $data = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data
        ], $code ?? 203);
    }//fim funcao

    /**
     * Return a modal JSON response.
     *
     * @param array<string, mixed>|string $html
     * @param int|null $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function modal(array|string $html = '', ?int $code = 200, array|string|null $data = null): JsonResponse
    {
        return response()->json([
            'success' => true,
            'html' => $html,
            'data' => $data
        ], $code ?? 200);
    }//fim funcao
}//fim classe
