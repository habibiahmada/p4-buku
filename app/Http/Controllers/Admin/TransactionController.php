<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrow;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Borrow::with(['user', 'borrowDetails']);

        if ($name = $request->query('name')) {
            $query->whereHas('user', function ($query) use ($name) {
                $query->where('name', 'like', "%{$name}%");
            });
        }

        if ($fromDate = $request->query('from_date')) {
            $query->whereDate('borrowed_date', '>=', $fromDate);
        }

        if ($toDate = $request->query('to_date')) {
            $query->whereDate('borrowed_date', '<=', $toDate);
        }

        if ($status = $request->query('status')) {
            if ($status === 'overdue') {
                $query->where('status', 'borrowed')
                    ->whereDate('due_date', '<', Carbon::today());
            } elseif (in_array($status, ['borrowed', 'returned'], true)) {
                $query->where('status', $status);
            }
        }

        $query->orderByDesc('borrowed_date');

        $transactionItems = (clone $query)->get();
        $borrowings = $query->paginate(10)->withQueryString();

        $today = Carbon::today();
        $countBooks = fn ($transaction) => $transaction->borrowDetails->sum('qty');
        $isOverdue = fn ($transaction) => $transaction->status !== 'returned' && filled($transaction->due_date) && Carbon::parse($transaction->due_date)->startOfDay()->lt($today);

        $totalPeminjaman = $transactionItems->count();
        $peminjamanAktif = $transactionItems->where('status', 'borrowed')->count();
        $peminjamanSelesai = $transactionItems->where('status', 'returned')->count();
        $peminjamanTerlambat = $transactionItems->filter($isOverdue)->count();
        $totalBukuDipinjam = $transactionItems->sum($countBooks);

        return view('pages.admin.transactions.index', compact(
            'borrowings',
            'totalPeminjaman',
            'peminjamanAktif',
            'peminjamanSelesai',
            'peminjamanTerlambat',
            'totalBukuDipinjam'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
