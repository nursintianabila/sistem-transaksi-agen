<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksi = Transaksi::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $transaksi,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_transaksi' => 'required|string|max:255',
            'nama_pelanggan' => 'required|string|max:255',
            'nomor_tujuan' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'biaya_admin' => 'required|numeric|min:0',
            'tanggal_transaksi' => 'required|date',
        ]);

        $validated['total'] =
            $validated['nominal'] + $validated['biaya_admin'];

        $transaksi = Transaksi::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil ditambahkan.',
            'data' => $transaksi,
        ], 201);
    }

    public function show(string $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $transaksi,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $validated = $request->validate([
            'jenis_transaksi' => 'required|string|max:255',
            'nama_pelanggan' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'biaya_admin' => 'required|numeric|min:0',
            'tanggal_transaksi' => 'required|date',
        ]);

        $validated['total'] =
            $validated['nominal'] + $validated['biaya_admin'];

        $transaksi->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil diperbarui.',
            'data' => $transaksi,
        ]);
    }

    public function destroy(string $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $transaksi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dihapus.',
        ]);
    }
}