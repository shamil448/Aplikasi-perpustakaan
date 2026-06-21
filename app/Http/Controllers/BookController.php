<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
            'judul' => 'required|string|max:255',
            'kategori' => 'nullable|in:novel,komik,buku pembelajaran',
            'sinopsis' => 'nullable|string',

            'pengarang' => 'required|string|max:255',
            'edisi' => 'nullable|string|max:255',
            'isbn_issn' => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable',
            'tempat_terbit' => 'nullable|string|max:255',
            'deskripsi_fisik' => 'nullable|string|max:255',
            'bahasa' => 'nullable|string|max:255',

            'no_panggil' => 'nullable|string|max:255',
            'kode_inventaris' => 'nullable|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'lokasi_rak' => 'nullable|string|max:255',
            'eksemplar' => 'nullable|string|max:255',

            'gambar' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        $gambar = null;

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar')->store('books', 'public');
        }

        Book::create([
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'sinopsis' => $request->sinopsis,

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

        return redirect('/books')->with('success', 'Buku berhasil ditambahkan');
    }

    // =============================
    // HAPUS BUKU
    // =============================
    public function destroy($id)
    {
        Book::destroy($id);
        return redirect('/books')->with('success', 'Buku berhasil dihapus');
    }

    // =============================
    // HAPUS BANYAK BUKU
    // =============================
    public function deleteSelected(Request $request)
    {
        if (!$request->ids) {
            return redirect('/books')->with('error', 'Pilih buku terlebih dahulu');
        }

        Book::whereIn('id', $request->ids)->delete();

        return redirect('/books')->with('success', 'Buku berhasil dihapus');
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
            'judul' => 'required|string|max:255',
            'kategori' => 'nullable|in:novel,komik,buku pembelajaran',
            'sinopsis' => 'nullable|string',

            'pengarang' => 'required|string|max:255',
            'edisi' => 'nullable|string|max:255',
            'isbn_issn' => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable',
            'tempat_terbit' => 'nullable|string|max:255',
            'deskripsi_fisik' => 'nullable|string|max:255',
            'bahasa' => 'nullable|string|max:255',

            'no_panggil' => 'nullable|string|max:255',
            'kode_inventaris' => 'nullable|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'lokasi_rak' => 'nullable|string|max:255',
            'eksemplar' => 'nullable|string|max:255',

            'gambar' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        $data = [
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'sinopsis' => $request->sinopsis,

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
        ];

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('books', 'public');
        }

        $book->update($data);

        return redirect('/books')->with('success', 'Buku berhasil diupdate');
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
            'eksemplar' => 'nullable|string|max:255'
        ]);

        $book = Book::findOrFail($id);

        $book->update([
            'eksemplar' => $request->eksemplar
        ]);

        return redirect('/books')->with('success', 'Data eksemplar berhasil disimpan');
    }

    // =============================
    // DAFTAR EKSEMPLAR
    // =============================
    public function daftarEksemplar(Request $request)
    {
        $query = Book::query();

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
        $query = Loan::with([
            'book',
            'user'
        ])
            ->whereIn('status', [
                'dipinjam',
                'denda'
            ]);

        if ($request->search) {

            $query->where(function ($q) use ($request) {

                $q->where(
                    'kode_eksemplar',
                    'like',
                    '%' . $request->search . '%'
                )

                    ->orWhereHas('user', function ($user) use ($request) {

                        $user->where(
                            'name',
                            'like',
                            '%' . $request->search . '%'
                        );
                    });
            });
        }

        $loans = $query
            ->latest()
            ->get();

        return view(
            'books.eksemplar-keluar',
            compact('loans')
        );
    }

    // =============================
    // DAFTAR EKSEMPLAR DENDA
    // =============================
    public function eksemplarDenda(Request $request)
    {
        $query = Loan::with(['book', 'user'])
            ->whereIn('status', ['dipinjam', 'denda']);

        if ($request->search) {
            $query->where('kode_eksemplar', 'like', '%' . $request->search . '%');
        }

        $loans = $query->latest()->get()->filter(function ($loan) {
            $today = now()->startOfDay();
            $jatuhTempo = Carbon::parse($loan->tanggal_kembali)->startOfDay();

            return $loan->status == 'denda' || $today->gt($jatuhTempo);
        });

        return view('books.eksemplar-denda', compact('loans'));
    }

    // =============================
    // DAFTAR BUKU SISWA / UMUM
    // =============================
    public function daftarBuku(Request $request)
    {
        $query = Book::query();

        if ($request->search) {
            if ($request->filter == 'judul') {
                $query->where('judul', 'like', '%' . $request->search . '%');
            } elseif ($request->filter == 'pengarang') {
                $query->where('pengarang', 'like', '%' . $request->search . '%');
            } elseif ($request->filter == 'isbn') {
                $query->where('isbn_issn', 'like', '%' . $request->search . '%');
            } elseif ($request->filter == 'kategori') {
                $query->where('kategori', 'like', '%' . $request->search . '%');
            } else {
                $query->where(function ($q) use ($request) {
                    $q->where('judul', 'like', '%' . $request->search . '%')
                        ->orWhere('pengarang', 'like', '%' . $request->search . '%')
                        ->orWhere('isbn_issn', 'like', '%' . $request->search . '%')
                        ->orWhere('kategori', 'like', '%' . $request->search . '%')
                        ->orWhere('sinopsis', 'like', '%' . $request->search . '%');
                });
            }
        }

        $books = $query->latest()->get();

        return view('books.daftar-buku', compact('books'));
    }
}
