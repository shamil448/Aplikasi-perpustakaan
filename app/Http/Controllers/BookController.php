<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{

    // =============================
    // LIST BUKU
    // =============================
    public function index()
    {
        $books = Book::with('loans')->latest()->get();
        return view('books.index', compact('books'));
    }


    // =============================
    // HALAMAN TAMBAH BUKU
    // =============================
    public function create()
    {
        return view('books.create');
    }


    // =============================
    // SIMPAN BUKU
    // =============================
    public function store(Request $request)
    {

        $request->validate([
            'judul' => 'required',
            'pengarang' => 'required',

            'edisi' => 'nullable',
            'isbn_issn' => 'nullable',
            'tahun_terbit' => 'nullable',
            'tempat_terbit' => 'nullable',
            'deskripsi_fisik' => 'nullable',
            'bahasa' => 'nullable',

            'no_panggil' => 'nullable',
            'kode_inventaris' => 'nullable',
            'lokasi' => 'nullable',
            'lokasi_rak' => 'nullable',

            // sekarang string
            'eksemplar' => 'nullable|string',

            'gambar' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);


        $gambar = null;

        if ($request->hasFile('gambar')) {

            $gambar = $request
                ->file('gambar')
                ->store('books', 'public');
        }


        Book::create([

            'judul' => $request->judul,
            'pengarang' => $request->pengarang,

            'edisi' => $request->edisi,
            'isbn_issn' => $request->isbn_issn,
            'tahun_terbit' => $request->tahun_terbit,
            'tempat_terbit' => $request->tempat_terbit,
            'deskripsi_fisik' => $request->deskripsi_fisik,
            'bahasa' => $request->bahasa,

            'no_panggil' => $request->no_panggil,
            'kode_inventaris' => $request->kode_inventaris,
            'lokasi' => $request->lokasi,
            'lokasi_rak' => $request->lokasi_rak,
            'eksemplar' => $request->eksemplar,

            'gambar' => $gambar

        ]);


        return redirect('/books')
            ->with('success', 'Buku berhasil ditambahkan');
    }



    // =============================
    // HAPUS BUKU
    // =============================
    public function destroy($id)
    {
        Book::destroy($id);

        return redirect('/books')
            ->with('success', 'Buku berhasil dihapus');
    }



    // =============================
    // HAPUS BANYAK BUKU
    // =============================
    public function deleteSelected(Request $request)
    {

        Book::whereIn('id', $request->ids)->delete();

        return redirect('/books')
            ->with('success', 'Buku berhasil dihapus');
    }



    // =============================
    // HALAMAN EDIT BUKU
    // =============================
    public function edit($id)
    {

        $book = Book::findOrFail($id);

        return view('books.edit', compact('book'));
    }



    // =============================
    // UPDATE BUKU
    // =============================
    public function update(Request $request, $id)
    {

        $book = Book::findOrFail($id);

        $request->validate([

            'judul' => 'required',
            'pengarang' => 'required',

            'edisi' => 'nullable',
            'isbn_issn' => 'nullable',
            'tahun_terbit' => 'nullable',
            'tempat_terbit' => 'nullable',
            'deskripsi_fisik' => 'nullable',
            'bahasa' => 'nullable',

            'no_panggil' => 'nullable',
            'kode_inventaris' => 'nullable',
            'lokasi' => 'nullable',
            'lokasi_rak' => 'nullable',

            // ubah ke string
            'eksemplar' => 'nullable|string',

            'gambar' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'

        ]);


        $data = $request->all();


        if ($request->hasFile('gambar')) {

            $data['gambar'] = $request
                ->file('gambar')
                ->store('books', 'public');
        }


        $book->update($data);


        return redirect('/books')
            ->with('success', 'Buku berhasil diupdate');
    }



    // =============================
    // HALAMAN EKSEMPLAR
    // =============================
    public function eksemplar($id)
    {

        $book = Book::findOrFail($id);

        return view('books.eksemplar', compact('book'));
    }



    // =============================
    // SIMPAN EKSEMPLAR
    // =============================
    public function storeEksemplar(Request $request, $id)
    {

        $request->validate([
            'eksemplar' => 'nullable|string'
        ]);


        $book = Book::findOrFail($id);


        $book->update([
            'eksemplar' => $request->eksemplar
        ]);


        return redirect('/books')
            ->with('success', 'Data eksemplar berhasil disimpan');
    }

    // =============================
    // DAFTAR EKSEMPLAR
    // =============================
    public function daftarEksemplar(Request $request)
    {
        $query = Book::query();

        // pencarian kode eksemplar
        if ($request->search) {

            $query->where('eksemplar', 'like', '%' . $request->search . '%');
        }

        $books = $query->latest()->get();

        return view('books.daftar-eksemplar', compact('books'));
    }

    // =============================
    // DAFTAR EKSEMPLAR KELUAR
    // =============================
    public function eksemplarKeluar(Request $request)
    {
        $books = collect();

        if ($request->search) {

            $books = Book::where('eksemplar', 'like', '%' . $request->search . '%')
                ->get();
        }

        return view('books.eksemplar-keluar', compact('books'));
    }
}
