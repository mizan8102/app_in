<?php
namespace App\Http\Api;
use App\Enums\ApiResponseEnum;
use App\Enums\HttpStatusCodeEnum;

trait ApiResponseTrait
{

    public function successResponse($data, string $message = '', $statusCode = HttpStatusCodeEnum::OK)
    {
        return response()->json([
            'status' => ApiResponseEnum::SUCCESS,
            'data' => $data,
            'message' => $message,
        ], $statusCode);
    }

    public function errorResponse($message, $statusCode = HttpStatusCodeEnum::BAD_REQUEST)
    {
        return response()->json([
            'status' => ApiResponseEnum::ERROR,
            'message' => $message,
        ], $statusCode);
    }
}
