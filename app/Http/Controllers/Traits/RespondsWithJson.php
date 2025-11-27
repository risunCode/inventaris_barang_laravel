<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Trait untuk handle response yang konsisten
 * Mengurangi duplicated code di controllers
 */
trait RespondsWithJson
{
    /**
     * Response sukses - bisa JSON atau redirect
     */
    protected function successResponse(Request $request, string $message, string $redirectRoute = null, array $data = []): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                ...$data
            ]);
        }

        $redirect = $redirectRoute ? redirect()->route($redirectRoute) : back();
        return $redirect->with('success', $message);
    }

    /**
     * Response error - bisa JSON atau redirect
     */
    protected function errorResponse(Request $request, string $message, int $status = 422): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], $status);
        }

        return back()->with('error', $message);
    }

    /**
     * Check if feature is enabled
     */
    protected function featureEnabled(string $feature): bool
    {
        return config("sibarang.features.{$feature}", true);
    }

    /**
     * Response feature disabled
     */
    protected function featureDisabledResponse(Request $request, string $feature): JsonResponse|RedirectResponse
    {
        $message = "Fitur {$feature} tidak diaktifkan.";
        return $this->errorResponse($request, $message, 403);
    }
}
