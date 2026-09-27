<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan user sudah login
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        // Hanya role admin yang boleh melakukan perubahan data
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya Admin yang dapat mengubah data transaksi.',
            ], 403);
        }

        return $next($request);
    }
}