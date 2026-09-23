```blade
@extends('layouts.app')

@section('content')

    <h1>Edit Buku</h1>

    <form action="{{ route('buku.update', $buku) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label for="judul">Judul</label>
            <input
                type="text"
                id="judul"
                name="judul"
                value="{{ old('judul', $buku->judul) }}"
            >

            @error('judul')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="penulis">Penulis</label>
            <input
                type="text"
                id="penulis"
                name="penulis"
                value="{{ old('penulis', $buku->penulis) }}"
            >
        </div>

        <div>
            <label for="penerbit">Penerbit</label>
            <input
                type="text"
                id="penerbit"
                name="penerbit"
                value="{{ old('penerbit', $buku->penerbit) }}"
            >
        </div>

        <div>
            <label for="tahun_terbit">Tahun Terbit</label>
            <input
                type="number"
                id="tahun_terbit"
                name="tahun_terbit"
                value="{{ old('tahun_terbit', $buku->tahun_terbit) }}"
            >
        </div>

        <div>
            <label for="isbn">ISBN</label>
            <input
                type="text"
                id="isbn"
                name="isbn"
                value="{{ old('isbn', $buku->isbn) }}"
            >

            @error('isbn')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="stok">Stok</label>
            <input
                type="number"
                id="stok"
                name="stok"
                value="{{ old('stok', $buku->stok) }}"
                min="0"
            >
        </div>

        <button type="submit">Simpan</button>
    </form>

@endsection

