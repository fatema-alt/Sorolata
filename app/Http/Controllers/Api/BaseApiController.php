<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseApiController extends Controller
{
    /**
     * Standard success JSON envelope
     */
    protected function respondWithSuccess(
        mixed $data = null,
        string $message = 'Request completed successfully.',
        int $statusCode = 200,
        array $meta = []
    ): JsonResponse {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
            'meta' => $meta,
            'errors' => [],
        ];

        return response()->json($response, $statusCode);
    }

    /**
     * Standard error JSON envelope
     */
    protected function respondWithError(
        string $message = 'An error occurred.',
        int $statusCode = 400,
        array $errors = []
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $message,
            'data' => null,
            'meta' => [],
            'errors' => $errors,
        ];

        return response()->json($response, $statusCode);
    }

    /**
     * Standard paginated resource JSON envelope
     */
    protected function respondWithPaginatedResource(
        AnonymousResourceCollection $resourceCollection,
        string $message = 'Data retrieved successfully.'
    ): JsonResponse {
        $paginator = $resourceCollection->resource;

        $meta = [];
        if ($paginator instanceof LengthAwarePaginator) {
            $meta = [
                'pagination' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                    'has_more_pages' => $paginator->hasMorePages(),
                ],
            ];
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $resourceCollection->resolve(),
            'meta' => $meta,
            'pagination' => $meta['pagination'] ?? [],
            'errors' => [],
        ], 200);
    }
}
