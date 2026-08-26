<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn(Request $request) => $request->is('api/*'),
        );

        //fmaneja errores 404
        $exceptions->render(function (NotFoundHttpException $exception, Request $request) {
            // si no es una peticion request
            if (!$request->is('api/*')) {
                return null;
            }
            return response()->json([
                "message" => "recurso no encontrado",
                "status" => 404,
                "error" => (object)[]
            ], 404);
        });

        //maneja erroees 422, validaciones de datos
        $exceptions->render(function (ValidationException $exception, Request $request) {
            if (!$request->is('api/*')) {
                return null;
            }
            return response()->json([
                "message" => $exception->getMessage(),
                "status" => 422,
                "error" => (object)[]
            ], 422);
        });

        //manejo de errores 400, badrequest
        $exceptions->render(function (BadRequestHttpException $exception, Request $request) {
            if (!$request->is('api/*')) {
                return null;
            }

            return response()->json([
                "message" => $exception->getMessage(),
                "status" => 400,
                "error" => (object)[]
            ], 400);
        });


        //manejo de errores 500, error interno del servidor
        // $exceptions->render(function (\Throwable $exception, Request $request) {
        //     if (!$request->is("api/*")) {
        //         return null;
        //     }

        //     return response()->json([
        //         // "message" => "Error interno del servidor",
        //         "message" => $exception->getMessage(),
        //         "status" => 500,
        //         "error" => (object)[]
        //     ], 500);
        // });
    })->create();
