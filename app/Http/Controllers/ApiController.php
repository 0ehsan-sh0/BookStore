<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function successResponse($message, $data){
        return response()->json([
            'success' => 'success',
            'message' => $message,
            'data' => $data
        ]);
    }

    public function errorResponse($message, $data){
        return response()->json([
            'error' => 'error',
            'message' => $message,
            'errors' => $data
        ]);
    }
}
