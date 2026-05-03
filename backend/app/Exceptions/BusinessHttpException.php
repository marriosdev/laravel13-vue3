<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class BusinessHttpException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json([
            'error' => 'Business Rule Violation',
            'message' => $this->getMessage(),
        ], 400);
    }
}
