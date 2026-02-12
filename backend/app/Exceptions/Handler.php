<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($request->is('api/*')) {

            // 🔐 NÃO AUTENTICADO
            if ($e instanceof AuthenticationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuário não autenticado',
                    'code' => 'AUTH_REQUIRED'
                ], 401);
            }

            // ✅ ERRO DE VALIDAÇÃO
            if ($e instanceof ValidationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação',
                    'code' => 'VALIDATION_ERROR',
                    'errors' => $e->errors()
                ], 422);
            }

            // ❌ MÉTODO NÃO PERMITIDO
            if ($e instanceof MethodNotAllowedHttpException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Método HTTP não permitido nesta rota',
                    'code' => 'METHOD_NOT_ALLOWED'
                ], 405);
            }

            // 🔍 NÃO ENCONTRADO
            if ($e instanceof NotFoundHttpException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Recurso não encontrado',
                    'code' => 'NOT_FOUND'
                ], 404);
            }

            // 🌐 HTTP EXCEPTION GENÉRICA
            if ($e instanceof HttpExceptionInterface) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage() ?: 'Erro HTTP',
                    'code' => 'HTTP_ERROR'
                ], $e->getStatusCode());
            }

            // 💥 ERRO REAL VAI PRO LOG
            logger()->error('API INTERNAL ERROR', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno no servidor',
                'code' => 'INTERNAL_ERROR'
            ], 500);
        }

        return parent::render($request, $e);
    }
}
