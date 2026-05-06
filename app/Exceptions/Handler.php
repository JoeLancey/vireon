<?php
namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        // Keep default reporting behavior.
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        // Convert duplicate-key DB errors into user-friendly validation errors
        if ($e instanceof QueryException) {
            $sqlState = $e->getCode();
            $driverErrorCode = $e->errorInfo[1] ?? null;

            if ($sqlState === '23000' || $driverErrorCode == 1062) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'The given data was invalid.',
                        'errors' => ['email' => [__('validation.unique', ['attribute' => 'email'])]],
                    ], 422);
                }

                return redirect()->back()->withInput()->withErrors([
                    'email' => __('validation.unique', ['attribute' => 'email']),
                ]);
            }
        }

        return parent::render($request, $e);
    }
}
