<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

trait HandlesApiResponses
{
    protected function successResponse(string $message, $data = null, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    protected function errorResponse(string $message, $errors = null, int $statusCode = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }

    protected function successRedirect(string $route, string $message): RedirectResponse
    {
        return redirect()->route($route)->with('success', $message);
    }

    protected function errorRedirect(string $message): RedirectResponse
    {
        return back()->with('error', $message)->withInput();
    }
}
