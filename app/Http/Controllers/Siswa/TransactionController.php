<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\BorrowDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('pages.siswa.transactions.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $books = Book::where('stock', '>', 0)->get();
        return view('pages.siswa.transactions.create', compact('books'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'borrowed_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:borrowed_date',
            'books' => 'required|array|min:1',
            'books.*.id' => 'required|exists:books,id',
            'books.*.qty' => 'required|integer|min:1',
        ], [
            'borrowed_date.required' => 'Tanggal peminjaman harus diisi',
            'borrowed_date.date' => 'Format tanggal peminjaman tidak valid',
            'due_date.required' => 'Tanggal jatuh tempo harus diisi',
            'due_date.date' => 'Format tanggal jatuh tempo tidak valid',
            'due_date.after_or_equal' => 'Tanggal jatuh tempo harus sama atau lebih besar dari tanggal peminjaman',
            'books.required' => 'Pilih minimal satu buku',
            'books.min' => 'Pilih minimal satu buku',
            'books.*.id.required' => 'ID buku harus diisi',
            'books.*.id.exists' => 'Buku tidak ditemukan',
            'books.*.qty.required' => 'Jumlah buku harus diisi',
            'books.*.qty.integer' => 'Jumlah buku harus berupa angka',
            'books.*.qty.min' => 'Jumlah buku minimal 1',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                foreach ($validated['books'] as $bookData) {
                    $book = Book::query()->lockForUpdate()->findOrFail($bookData['id']);

                    if ((int) $book->stock < (int) $bookData['qty']) {
                        throw new \RuntimeException(
                            "Stok buku '{$book->title}' tidak mencukupi. Stok tersedia: {$book->stock}"
                        );
                    }
                }

                $borrow = Borrow::create([
                    'user_id' => auth()->id(),
                    'borrowed_date' => $validated['borrowed_date'],
                    'due_date' => $validated['due_date'],
                    'charge' => 0,
                    'status' => 'borrowed',
                ]);

                foreach ($validated['books'] as $bookData) {
                    BorrowDetail::create([
                        'borrowing_id' => $borrow->id,
                        'book_id' => $bookData['id'],
                        'qty' => $bookData['qty'],
                    ]);

                    Book::query()
                        ->lockForUpdate()
                        ->findOrFail($bookData['id'])
                        ->decrement('stock', (int) $bookData['qty']);
                }
            });
        } catch (\RuntimeException $exception) {
            return back()
                ->withInput()
                ->withErrors(['books' => $exception->getMessage()]);
        }

        return redirect()
            ->route('siswa.transactions.index')
            ->with('success', 'Peminjaman berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $borrow = Borrow::with('borrowDetails.book')
            ->where('user_id', auth()->id())
            ->findOrFail($id);
        $borrowDetails = $borrow->borrowDetails;

        return view('pages.siswa.transactions.edit', compact('borrow', 'borrowDetails'));
    }

    /**
     * Show the return form without specific transaction.
     */
    public function return()
    {
        $borrow = null;
        $borrowDetails = null;
        return view('pages.siswa.transactions.edit', compact('borrow', 'borrowDetails'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'borrowing_id' => 'required|exists:borrowings,id',
            'returned_date' => 'required|date',
            'charge' => 'required|numeric|min:0',
        ], [
            'borrowing_id.required' => 'Pilih pinjaman yang akan dikembalikan',
            'borrowing_id.exists' => 'Pinjaman tidak ditemukan',
            'returned_date.required' => 'Tanggal kembali harus diisi',
            'returned_date.date' => 'Format tanggal kembali tidak valid',
            'charge.required' => 'Denda harus diisi',
            'charge.numeric' => 'Denda harus berupa angka',
            'charge.min' => 'Denda tidak boleh negatif',
        ]);

        if ((string) $validated['borrowing_id'] !== (string) $id) {
            return back()
                ->withErrors(['borrowing_id' => 'Pinjaman yang dipilih tidak sesuai dengan transaksi yang sedang dibuka']);
        }

        try {
            DB::transaction(function () use ($validated) {
                $borrow = Borrow::with('borrowDetails')
                    ->where('user_id', auth()->id())
                    ->lockForUpdate()
                    ->findOrFail($validated['borrowing_id']);

                if ($borrow->status === 'returned') {
                    throw new \RuntimeException('Buku ini sudah dikembalikan sebelumnya');
                }

                $borrow->update([
                    'returned_date' => $validated['returned_date'],
                    'charge' => $validated['charge'],
                    'status' => 'returned',
                ]);

                foreach ($borrow->borrowDetails as $detail) {
                    Book::query()
                        ->lockForUpdate()
                        ->findOrFail($detail->book_id)
                        ->increment('stock', (int) $detail->qty);
                }
            });
        } catch (\RuntimeException $exception) {
            return back()
                ->withErrors(['borrowing_id' => $exception->getMessage()]);
        }

        return redirect()
            ->route('siswa.transactions.index')
            ->with('success', 'Pengembalian buku berhasil dicatat. Denda sebesar Rp' . number_format((float) $validated['charge'], 0, ',', '.') . ' telah dihitung.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
