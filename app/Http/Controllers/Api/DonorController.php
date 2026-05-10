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

    public function myDonations()
    {
        $user = auth()->user();

        if ($user->role !== 'donatur') {
            return response()->json(['message' => 'Hanya donatur'], 403);
        }

        $donor = $user->donor;

        $donations = $donor->donations()->latest()->get()->map(function ($donation) {
            return [
                'id' => $donation->id,
                'title' => $donation->title,
                'description' => $donation->description,
                'location' => $donation->location,
                'status' => $donation->status,
                'total_portion' => $donation->total_portion,
                'pickup_deadline' => $donation->pickup_deadline,
                'photo_url' => $donation->photo_path
                    ? asset('storage/' . $donation->photo_path)
                    : null,
            ];
        });

        return response()->json($donations);
    }
}