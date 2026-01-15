<?php

namespace App\Support;

class Response
{
    public static function success($data = null, $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'error' => null
        ], $statusCode);
    }

    public static function error(int $statusCode, string $errorMsg, mixed $details = null, $isFriendly = false)
    {

        $error = [
            'errorId' => $statusCode,
            'isFriendly' => $isFriendly,
            'errorMsg' => $errorMsg,
        ];

        if (!is_null($details)) {
            $error['details'] = $details;
        }

        return response()->json([
            'success' => false,
            'data' => null,
            'error' => $error
        ], $statusCode);
    }
}
