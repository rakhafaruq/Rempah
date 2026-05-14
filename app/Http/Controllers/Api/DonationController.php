<?php

// app/Http/Controllers/Api/DonationController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        // auto expire
        Donation::where('status', 'tersedia')
            ->where('pickup_deadline', '<', now())
            ->update(['status' => 'expired']);

        $query = Donation::with('donor.user');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                ->orWhere('location', 'like', '%' . $searchTerm . '%');
            });
        }

        return response()->json(
            $query->latest()->get()->map(function ($donation) {
                return [
                    'id' => $donation->id,
                    'title' => $donation->title,
                    'description' => $donation->description,
                    'location' => $donation->location,
                    'status' => $donation->status,
                    'pickup_deadline' => $donation->pickup_deadline,
                    'total_portion' => $donation->total_portion,
                    'photo_url' => $donation->photo_path
                        ? asset('storage/' . $donation->photo_path)
                        : null,
                    'donor' => $donation->donor ? [
                        'user' => [
                            'name' => $donation->donor->user->name ?? 'Anonim'
                        ]
                    ] : null,
                ];
            })
        );
    }
    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->role !== 'donatur') {
            return response()->json(['message' => 'Hanya donatur'], 403);
        }

        $request->validate([
            'title' => 'required',
            'location' => 'required',
            'pickup_deadline' => 'required|date',
            'total_portion' => 'required|integer|min:1',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $donor = $user->donor;

        $photoPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('donations', 'public');
        }

        $donation = \App\Models\Donation::create([
            'title' => $request->title,
            'description' => $request->description,
            'donor_id' => $donor->id,
            'location' => $request->location,
            'pickup_deadline' => $request->pickup_deadline,
            'total_portion' => $request->total_portion,
            'remaining_portion' => $request->total_portion,
            'status' => 'tersedia',
            'photo_path' => $photoPath
        ]);

        return response()->json([
            'message' => 'Donasi berhasil dibuat',
            'data' => $donation
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();

        if ($user->role !== 'donatur') {
            return response()->json(['message' => 'Hanya donatur'], 403);
        }

        $donation = Donation::findOrFail($id);

        if ($donation->donor_id !== $user->donor->id) {
            return response()->json(['message' => 'Tidak diizinkan'], 403);
        }

        $request->validate([
            'title' => 'required',
            'location' => 'required',
            'pickup_deadline' => 'required|date',
            'total_portion' => 'required|integer|min:1',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $dataToUpdate = [
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'pickup_deadline' => $request->pickup_deadline,
            'total_portion' => $request->total_portion,
        ];

        if ($donation->status === 'expired' && strtotime($request->pickup_deadline) > time()) {
            $dataToUpdate['status'] = 'tersedia';
        }

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('donations', 'public');
            $dataToUpdate['photo_path'] = $photoPath;
        }

        $donation->update($dataToUpdate);

        return response()->json([
            'message' => 'Donasi berhasil diperbarui',
            'data' => $donation
        ]);
    }

    public function destroy($id)
    {
        $user = auth()->user();

        if ($user->role !== 'donatur') {
            return response()->json(['message' => 'Hanya donatur'], 403);
        }

        $donation = Donation::findOrFail($id);

        if ($donation->donor_id !== $user->donor->id) {
            return response()->json(['message' => 'Tidak diizinkan'], 403);
        }

        $donation->delete();

        return response()->json(['message' => 'Donasi berhasil dihapus']);
    }
}