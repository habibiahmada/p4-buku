<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Book::query();

        // Search
        if (request('search')) {
            $query->where('title', 'like', '%' . request('search') . '%')
                  ->orWhere('author', 'like', '%' . request('search') . '%');
        }

        // Filter by publisher
        if (request('publisher')) {
            $query->where('publisher', request('publisher'));
        }

        $books = $query->paginate(10);

        // Statistics
        $totalBuku = Book::count();
        $bukuTersedia = Book::sum('stock');
        $bukuDipinjam = \App\Models\BorrowDetail::whereHas('borrow', function ($query) {
            $query->where('status', '!=', 'returned');
        })->sum('qty');

        // Publishers for filter
        $publishers = Book::distinct('publisher')->pluck('publisher');

        return view('pages.admin.books.index', compact('books', 'totalBuku', 'bukuTersedia', 'bukuDipinjam', 'publishers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.books.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'publication_year' => 'required|integer|min:1000|max:' . (date('Y') + 1),
            'stock' => 'required|integer|min:0',
        ]);

        Book::create($request->all());

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $book = Book::findOrFail($id);
        return view('pages.admin.books.edit', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'publication_year' => 'required|integer|min:1000|max:' . (date('Y') + 1),
            'stock' => 'required|integer|min:0',
        ]);

        $book = Book::findOrFail($id);
        $book->update($request->all());

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil dihapus.');
    }
}
