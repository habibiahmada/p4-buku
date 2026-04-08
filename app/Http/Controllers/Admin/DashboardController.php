<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\BorrowDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $today = Carbon::today();

        $totalAnggota = User::where('role', 'siswa')->count();
        $totalAdmin = User::where('role', 'admin')->count();
        $totalBuku = Book::count();
        $totalStokBuku = Book::sum('stock');
        $bukuDipinjam = BorrowDetail::whereHas('borrow', function ($query) {
            $query->where('status', '!=', 'returned');
        })->sum('qty');
        $bukuStokMenipis = Book::where('stock', '<=', 3)->count();
        $peminjamanAktif = Borrow::where('status', 'borrowed')->count();
        $peminjamanSelesai = Borrow::where('status', 'returned')->count();
        $peminjamanTerlambat = Borrow::where('status', 'borrowed')
            ->whereDate('due_date', '<', $today)
            ->count();

        $monthlyBorrowings = collect(range(5, 0))->map(function ($offset) use ($today) {
            $month = $today->copy()->startOfMonth()->subMonths($offset);

            return [
                'label' => $month->format('M'),
                'full_label' => $month->format('M Y'),
                'total' => Borrow::whereBetween('borrowed_date', [
                    $month->copy()->startOfMonth(),
                    $month->copy()->endOfMonth(),
                ])->count(),
            ];
        });

        $recentBorrowings = Borrow::with('user')
            ->orderByDesc('borrowed_date')
            ->take(5)
            ->get();

        return view('pages.admin.dashboard', compact(
            'totalAnggota',
            'totalAdmin',
            'totalBuku',
            'totalStokBuku',
            'bukuDipinjam',
            'bukuStokMenipis',
            'peminjamanAktif',
            'peminjamanSelesai',
            'peminjamanTerlambat',
            'monthlyBorrowings',
            'recentBorrowings'
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
