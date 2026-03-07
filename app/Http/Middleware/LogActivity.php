<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // bail early if controller indicated no log
        if ($request->attributes->get('skip_log')) {
            return $response;
        }

        // Only log POST, PUT, DELETE requests if user is authenticated
        if (in_array($request->method(), ['POST', 'PUT', 'DELETE']) && auth()->check()) {
            $action = match($request->method()) {
                'POST' => 'Création',
                'PUT' => 'Mise à jour',
                'DELETE' => 'Suppression',
                default => 'Action',
            };

            $route = $request->route();
            // try to get a named route; if none, fall back to HTTP method + path
            $routeName = $route?->getName();
            if (!$routeName) {
                $routeName = $request->method() . ' ' . $request->path();
            }

            // append key parameters (id) to description to make logs clearer
            $desc = $routeName;
            if ($route) {
                foreach ($route->parameters() as $key => $value) {
                    // include scalar parameters only
                    if (is_scalar($value)) {
                        $desc .= " [{$key}:{$value}]";
                    }
                }
            }

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => $action,
                'description' => $desc,
                'ip_address' => $request->ip(),
            ]);
        }

        return $response;
    }
}
