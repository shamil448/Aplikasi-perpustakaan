<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // menampilkan daftar buku
    public function index()
    {
        $books = Book::latest()->get();
        return view('books.index', compact('books'));
    }

    // halaman tambah buku
    public function create()
    {
        return view('books.create');
    }

    // simpan buku
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
            'gambar' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        $gambar = null;

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar')->store('books', 'public');
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
            'gambar' => $gambar
        ]);

        return redirect('/books')->with('success','Buku berhasil ditambahkan');
    }

    public function destroy($id)
    {
        Book::destroy($id);
        return redirect('/books')->with('success','Buku berhasil dihapus');
    }

    public function deleteSelected(Request $request)
    {
        Book::whereIn('id',$request->ids)->delete();
        return redirect('/books');
    }

    // halaman edit buku
    public function edit($id)
    {
        $book = Book::findOrFail($id);
        return view('books.edit', compact('book'));
    }

    // update buku
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
            'gambar' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('books','public');
        }

        $book->update($data);

         return redirect('/books')->with('success','Buku berhasil diupdate');
    }

    public function eksemplar($id)
    {
        $book = Book::findOrFail($id);
        return view('books.eksemplar', compact('book'));
    }

    public function storeEksemplar(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $jumlah = $request->jumlah;

        // sementara hanya menampilkan hasil
        return redirect('/books')->with('success','Eksemplar berhasil diisi sebanyak '.$jumlah);
    }
}
