public function render($request, Throwable $e)
{
    if ($request->expectsJson()) {
        if ($e instanceof \Illuminate\Validation\ValidationException) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors(),
            ], 422);
        }

        return response()->json([
            'message' => app()->environment('production')
                ? 'An unexpected error occurred.'
                : $e->getMessage(),
        ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
    }

    return parent::render($request, $e);
}