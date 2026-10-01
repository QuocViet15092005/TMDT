<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\ProductRecommendationService;

class RecordBrowsingHistory
{
    public function __construct(protected ProductRecommendationService $recommendationService)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Ghi lại lịch sử duyệt nếu người dùng xem chi tiết sản phẩm
        if ($request->route()->getName() === 'products.show' && auth()->check()) {
            $product = $request->route('product');
            if ($product) {
                $this->recommendationService->recordBrowse(auth()->user(), $product);
            }
        }

        return $response;
    }
}
