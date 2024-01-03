<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class RequestResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        if ($response instanceof Response && $response->getOriginalContent() instanceof View) {
            if ($response->getOriginalContent() instanceof View) {
                $data_original = $response->getOriginalContent()->getData();
                if (isset($data_original['page']) && isset($data_original['page']['url'])) {
                    $data_original['page']['url'] = call_user_func(function () use ($data_original) {
                        $url = $data_original['page']['url'];
                        $uri_arr = explode('/', $url);
                        $uri_filter = array_filter($uri_arr, function ($item, $index) use ($uri_arr) {
                            return $item === '' || !in_array($item, array_slice($uri_arr, $index + 1));
                        }, ARRAY_FILTER_USE_BOTH);
                        return join('/', $uri_filter);
                    }, []);
                    $response->original->with('page', $data_original['page']);
                }
            }
        } else if ($response instanceof JsonResponse) {
            $response->setData(
                array_merge((array) $response->getData(), [
                    'url' => call_user_func(function () use ($response) {
                        $url = $response->original['url'];
                        $uri_arr = explode('/', $url);
                        $uri_filter = array_filter($uri_arr, function ($item, $index) use ($uri_arr) {
                            return $item === '' || !in_array($item, array_slice($uri_arr, $index + 1));
                        }, ARRAY_FILTER_USE_BOTH);
                        return join('/', $uri_filter);
                    }, [])
                ])
            );
        }
        //dd($response);
        return $response;
    }
}
