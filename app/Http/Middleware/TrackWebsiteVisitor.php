<?php

namespace App\Http\Middleware;

use App\Services\WebsiteVisitorService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackWebsiteVisitor
{
    protected $visitorService;

    public function __construct(WebsiteVisitorService $visitorService)
    {
        $this->visitorService = $visitorService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only track GET requests for frontend pages (exclude admin, api, assets)
        if (
            $request->isMethod('GET') &&
            !$request->is('admin/*') &&
            !$request->is('api/*') &&
            !$request->is('assets/*') &&
            !$request->is('build/*') &&
            !$request->is('vendor/*') &&
            !$request->ajax() &&
            !$request->wantsJson()
        ) {

            try {
                $this->visitorService->trackVisitor();
            } catch (\Exception $e) {
                // Log error but don't break the request
                \Illuminate\Support\Facades\Log::error('Website visitor tracking failed: ' . $e->getMessage());
            }
        }

        return $next($request);
    }
}
