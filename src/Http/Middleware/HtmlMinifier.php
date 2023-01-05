<?php

namespace OEngine\Core\Http\Middleware;


use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OEngine\Core\Facades\Core;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class HtmlMinifier
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        /*
        * @var \Illuminate\Http\Response $response
        */
        $response = $next($request);
        // Skip special response types
        if (($response instanceof BinaryFileResponse) or
            ($response instanceof JsonResponse) or
            ($response instanceof RedirectResponse) or
            ($response instanceof StreamedResponse) or
            (!$response->isOk()&& app()->hasDebugModeEnabled())
        )
            return $response;
        if (!$response instanceof Response || !$response->headers) {
            $response = new Response($response);
            if (!$response->headers->has('content-type'))
                $response->headers->set('content-type', 'text/html');
        }
        $contentType = $response->headers->get('content-type');
        if (strpos($contentType, 'text/html') !== false) {
            $response->setContent(Core::minifyOptimizeHtml($response->getContent()));
        }
        return  $response;
    }
}
