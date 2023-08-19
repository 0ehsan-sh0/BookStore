<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function successResponse($message, $data, $status = 200)
    {
        return response()->json([
            'success' => 'success',
            'message' => $message,
            'data' => $data
        ], $status);
    }

    public function errorResponse($message, $data, $status = 404)
    {
        return response()->json([
            'error' => 'error',
            'message' => $message,
            'errors' => $data
        ], $status);
    }
}
