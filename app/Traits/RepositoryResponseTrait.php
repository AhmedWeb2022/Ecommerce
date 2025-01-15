<?php

namespace App\Traits;

trait RepositoryResponseTrait
{
    /**
     * Return a successful response.
     *
     * @param mixed $data
     * @param string $message
     * @return array
     */
    protected function successResponse($data = null, string $message = 'Operation successful'): array
    {
        return [
            'status' => true,
            'message' => $message,
            'data' => $data,
        ];
    }

    /**
     * Return an error response.
     *
     * @param string $message
     * @param mixed $errors
     * @return array
     */
    protected function errorResponse(string $message = 'Operation failed', $errors = null): array
    {
        return [
            'status' => false,
            'message' => $message,
            'errors' => $errors,
        ];
    }

    /**
     * Return a not found response.
     *
     * @param string $message
     * @return array
     */
    protected function notFoundResponse(string $message = 'Resource not found'): array
    {
        return [
            'status' => false,
            'message' => $message,
        ];
    }

    /**
     * Return a validation error response.
     *
     * @param mixed $errors
     * @param string $message
     * @return array
     */
    protected function validationErrorResponse($errors, string $message = 'Validation failed'): array
    {
        return [
            'status' => false,
            'message' => $message,
            'errors' => $errors,
        ];
    }
}
