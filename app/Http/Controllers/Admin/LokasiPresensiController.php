<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LokasiPresensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LokasiPresensiController extends Controller
{
    public function index()
    {
        $lokasi = LokasiPresensi::latest()->get();
        return view('admin.lokasi.index', compact('lokasi'));
    }

    public function create() { return view('admin.lokasi.form'); }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        DB::transaction(function () use ($data) {
            $this->activateOnly($data['status']);
            LokasiPresensi::create($data);
        });
        return redirect()->route('admin.lokasi.index')->with('success', 'Lokasi presensi berhasil ditambahkan.');
    }

    public function edit(LokasiPresensi $lokasi) { return view('admin.lokasi.form', compact('lokasi')); }

    public function update(Request $request, LokasiPresensi $lokasi)
    {
        $data = $this->validateData($request);
        DB::transaction(function () use ($data, $lokasi) {
            $this->activateOnly($data['status'], $lokasi->id);
            $lokasi->update($data);
        });
        return redirect()->route('admin.lokasi.index')->with('success', 'Lokasi presensi berhasil diperbarui.');
    }

    public function destroy(LokasiPresensi $lokasi)
    {
        $lokasi->update(['status' => 'nonaktif']);
        return back()->with('success', 'Lokasi presensi berhasil dinonaktifkan.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'nama_lokasi' => 'required|max:100', 'alamat' => 'required',
            'latitude' => 'required|numeric|between:-90,90', 'longitude' => 'required|numeric|between:-180,180',
            'radius_meter' => 'required|integer|min:1|max:10000',
            'zona_waktu' => 'required|in:Asia/Jakarta,Asia/Makassar,Asia/Jayapura',
            'jam_masuk' => 'required|date_format:H:i', 'batas_terlambat' => 'required|date_format:H:i',
            'jam_pulang' => 'required|date_format:H:i', 'status' => 'required|in:aktif,nonaktif',
        ]);
    }

    private function activateOnly(string $status, ?int $except = null): void
    {
        if ($status === 'aktif') {
            LokasiPresensi::when($except, fn ($query) => $query->where('id', '!=', $except))
                ->where('status', 'aktif')->update(['status' => 'nonaktif']);
        }
    }
}