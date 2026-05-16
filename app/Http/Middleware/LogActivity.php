<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log mutations & non-GET against authenticated routes.
        if (auth()->check() && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            ActivityLog::create([
                'user_id'    => auth()->id(),
                'action'     => $request->method(),
                'module'     => $request->segment(1) ?? 'app',
                'description'=> $request->method().' '.$request->path(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return $response;
    }
}
