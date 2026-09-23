@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h4>Daftar Peminjaman</h4>

    <a
        href="{{ route('peminjaman.create') }}"
        class="btn btn-primary"
    >
        + Tambah Peminjaman
    </a>
</div>

@if (session('sukses'))
    <div class="alert alert-success">
        {{ session('sukses') }}
    </div>
@endif

@if (session('gagal'))
    <div class="alert alert-danger">
        {{ session('gagal') }}
    </div>
@endif

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Buku</th>
            <th>Anggota</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
        @forelse ($peminjaman as $item)
            <tr>
                <td>
                    {{ $item->buku->judul }}
                </td>

                <td>
                    {{ $item->anggota->nama }}
                </td>

                <td>
                    {{ $item->tanggal_pinjam }}
                </td>

                <td>
                    {{ $item->tanggal_kembali ?? '-' }}
                </td>

                <td>
                    @if ($item->status === 'dipinjam')
                        <span class="badge bg-warning text-dark">
                            Dipinjam
                        </span>
                    @else
                        <span class="badge bg-success">
                            Kembali
                        </span>
                    @endif
                </td>

                <td>
                    {{-- Tombol Edit --}}
                    <a
                        href="{{ route('peminjaman.edit', $item) }}"
                        class="btn btn-sm btn-warning"
                    >
                        Edit
                    </a>

                    {{-- Tombol Kembalikan --}}
                    @if ($item->status === 'dipinjam')
                        <form
                            action="{{ route('peminjaman.kembalikan', $item) }}"
                            method="POST"
                            class="d-inline"
                        >
                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="btn btn-sm btn-success"
                                onclick="return confirm('Yakin buku ini sudah dikembalikan?')"
                            >
                                Kembalikan
                            </button>
                        </form>
                    @endif

                    {{-- Tombol Hapus --}}
                    <form
                        action="{{ route('peminjaman.destroy', $item) }}"
                        method="POST"
                        class="d-inline"
                    >
                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="btn btn-sm btn-danger"
                            onclick="return confirm('Yakin hapus data peminjaman ini?')"
                        >
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">
                    Belum ada data peminjaman.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $peminjaman->links() }}

@endsection
