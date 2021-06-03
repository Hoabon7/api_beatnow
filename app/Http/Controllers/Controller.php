<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function response(int $httpCode, bool $success, string $message, $data) {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ], $httpCode);
    }

    public function responseSuccess( $data) {
        return $this->response(200, true, 'success', $data );
    }

    public function responseFail( $message = null) {
        return $this->response(200, false, $message, null );
    }

    public function responseBadRequest( string $message ) {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], 400);
    }
}
