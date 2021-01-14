<?php

namespace Sheenazien8\Hascrudactions\Helpers;

use Illuminate\Http\JsonResponse;

/**
 * Class Response
 * @author sheenazien8
 */
class Response
{
    /**
     * Success Response 200
     *
     * @param array $data
     * @param int $code (optional)
     *
     * @return JsonResponse
     */
    public function success(array $data, $code = 200): JsonResponse
    {
        $data = [
            'success' => true,
            'payload' => $data
        ];

        return response()->json($data, $code);
    }

    /**
     * Client Erro
     *
     * @param array $data
     * @param int $code (optional)
     *
     * @return JsonResponse
     */
    public function clientError(array $data, $code = 422): JsonResponse
    {
        $data['success'] = false;

        return response()->json($data, $code);
    }

    /**
     * Server Error
     *
     * @param array $data
     * @param int $code (optional)
     *
     * @return JsonResponse
     */
    public function serverError(array $data, $code = 500): JsonResponse
    {
        $data['success'] = false;

        return response()->json($data, $code);
    }
}
