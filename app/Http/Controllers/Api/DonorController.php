<?php

// app/Http/Controllers/Api/DonorController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Distribution;

class DonorController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        if ($user->role !== 'donatur') {
            return response()->json(['message' => 'Hanya donatur'], 403);
        }

        $donor = $user->donor;

        $totalDonasi = $donor->donations()->count();

        $distributions = Distribution::with('claim.donation')
            ->whereHas('claim.donation', function ($q) use ($donor) {
                $q->where('donor_id', $donor->id);
            })
            ->latest()
            ->get();

        return response()->json([
            'total_donasi' => $totalDonasi,
            'total_distribusi' => $distributions->count(),
            'gallery' => $distributions
        ]);
    }
}