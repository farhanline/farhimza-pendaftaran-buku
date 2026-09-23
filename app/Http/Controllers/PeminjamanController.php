<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::with(['buku', 'anggota'])
            ->latest()
            ->paginate(10);

        return view('peminjaman.index', compact('peminjaman'));
    }

    public function create()
    {
        $buku = Buku::where('stok', '>', 0)->get();
        $anggota = Anggota::all();

        return view('peminjaman.create', compact('buku', 'anggota'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:buku,id',
            'anggota_id' => 'required|exists:anggota,id',
            'tanggal_pinjam' => 'required|date',
        ]);

        $buku = Buku::findOrFail($request->buku_id);

        if ($buku->stok < 1) {
            return back()->with(
                'gagal',
                'Stok buku habis, tidak bisa dipinjam.'
            );
        }

        Peminjaman::create([
            'buku_id' => $buku->id,
            'anggota_id' => $request->anggota_id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'status' => 'dipinjam',
        ]);

        $buku->pinjamkan();

        return redirect()
            ->route('peminjaman.index')
            ->with('sukses', 'Peminjaman berhasil dicatat.');
    }

    public function edit(Peminjaman $peminjaman)
    {
        $buku = Buku::all();
        $anggota = Anggota::all();

        return view(
            'peminjaman.edit',
            compact('peminjaman', 'buku', 'anggota')
        );
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'buku_id' => 'required|exists:buku,id',
            'anggota_id' => 'required|exists:anggota,id',
            'tanggal_pinjam' => 'required|date',
        ]);

        $bukuLama = $peminjaman->buku;
        $bukuBaru = Buku::findOrFail($request->buku_id);

        if (
            $bukuLama->id !== $bukuBaru->id
            && $peminjaman->status === 'dipinjam'
        ) {
            if ($bukuBaru->stok < 1) {
                return back()
                    ->withInput()
                    ->with(
                        'gagal',
                        'Stok buku yang dipilih sedang habis.'
                    );
            }

            $bukuLama->kembalikan();
            $bukuBaru->pinjamkan();
        }

        $peminjaman->update([
            'buku_id' => $bukuBaru->id,
            'anggota_id' => $request->anggota_id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
        ]);

        return redirect()
            ->route('peminjaman.index')
            ->with('sukses', 'Peminjaman berhasil diperbarui.');
    }

    public function kembalikan(Peminjaman $peminjaman)
    {
        if ($peminjaman->status === 'kembali') {
            return back()->with(
                'gagal',
                'Buku ini sudah dikembalikan sebelumnya.'
            );
        }

        $peminjaman->update([
            'status' => 'kembali',
            'tanggal_kembali' => now(),
        ]);

        $peminjaman->buku->kembalikan();

        return redirect()
            ->route('peminjaman.index')
            ->with('sukses', 'Buku berhasil dikembalikan.');
    }

    public function destroy(Peminjaman $peminjaman)
    {
        $peminjaman->delete();

        return redirect()
            ->route('peminjaman.index')
            ->with('sukses', 'Data peminjaman dihapus.');
    }
}
