<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCanonicalHost
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!app()->isProduction()) {
            return $next($request);
        }

        $appUrl = config('app.url');
        if (!$appUrl) {
            return $next($request);
        }

        $targetScheme = parse_url($appUrl, PHP_URL_SCHEME);
        $targetHost = parse_url($appUrl, PHP_URL_HOST);

        if (!$targetScheme || !$targetHost) {
            return $next($request);
        }

        $currentScheme = $request->getScheme();
        $currentHost = $request->getHost();

        if ($currentScheme === $targetScheme && $currentHost === $targetHost) {
            return $next($request);
        }

        $targetUrl = $targetScheme.'://'.$targetHost.$request->getRequestUri();

        return redirect()->to($targetUrl, 301);
    }
}
