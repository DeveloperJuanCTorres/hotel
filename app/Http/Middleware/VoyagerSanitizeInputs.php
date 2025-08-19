<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VoyagerSanitizeInputs
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $inputs = $request->all();

        foreach ($inputs as $key => $value) {
            if (is_string($value)) {
                // Rechazar si contiene HTML
                if ($value !== strip_tags($value)) {
                    return redirect()->back()
                        ->withErrors(['error' => "El campo '$key' no debe contener código HTML."])
                        ->withInput();
                }
            }
        }

        $request->merge($inputs);

        return $next($request);
    }
}