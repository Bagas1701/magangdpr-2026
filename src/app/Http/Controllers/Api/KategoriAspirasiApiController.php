<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KategoriAspirasi;
use Illuminate\Http\JsonResponse;

class KategoriAspirasiApiController extends Controller
{
    public function index(): JsonResponse
    {
        $kategori = KategoriAspirasi::query()
            ->where('is_active', true)
            ->orderBy('nama')
            ->get(['id', 'nama', 'slug', 'deskripsi']);

        return response()->json([
            'success' => true,
            'message' => 'Daftar kategori aspirasi berhasil diambil.',
            'data' => $kategori,
        ]);
    }
}