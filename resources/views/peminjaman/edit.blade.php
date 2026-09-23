@extends('layouts.app')

@section('content')

<h4>Edit Peminjaman</h4>

@if (session('gagal'))
    <div class="alert alert-danger">
        {{ session('gagal') }}
    </div>
@endif

<form action="{{ route('peminjaman.update', $peminjaman) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-2">
        <label>Buku</label>

        <select name="buku_id" class="form-select">
            @foreach ($buku as $b)
                <option
                    value="{{ $b->id }}"
                    {{ old('buku_id', $peminjaman->buku_id) == $b->id ? 'selected' : '' }}
                >
                    {{ $b->judul }} (stok: {{ $b->stok }})
                </option>
            @endforeach
        </select>

        @error('buku_id')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-2">
        <label>Anggota</label>

        <select name="anggota_id" class="form-select">
            @foreach ($anggota as $a)
                <option
                    value="{{ $a->id }}"
                    {{ old('anggota_id', $peminjaman->anggota_id) == $a->id ? 'selected' : '' }}
                >
                    {{ $a->nama }}
                </option>
            @endforeach
        </select>

        @error('anggota_id')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-3">
        <label>Tanggal Pinjam</label>

        <input
            type="date"
            name="tanggal_pinjam"
            class="form-control"
            value="{{ old('tanggal_pinjam', $peminjaman->tanggal_pinjam) }}"
        >

        @error('tanggal_pinjam')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <button class="btn btn-primary">
        Simpan Perubahan
    </button>

    <a
        href="{{ route('peminjaman.index') }}"
        class="btn btn-secondary"
    >
        Batal
    </a>
</form>

@endsection
