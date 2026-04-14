<?php

// app/Http/Controllers/Api/DistributionController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Distribution;
use App\Models\Claim;
use Illuminate\Http\Request;

class DistributionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'claim_id' => 'required|exists:claims,id',
            'receiver_name' => 'required',
            'receiver_type' => 'required',
            'location' => 'required',
            'photo' => 'required|image'
        ]);

        $claim = Claim::findOrFail($request->claim_id);

        // hanya relawan yg klaim bisa upload
        if ($claim->volunteer_id !== auth()->id()) {
            return response()->json(['message' => 'Tidak diizinkan'], 403);
        }

        $path = $request->file('photo')->store('distributions', 'public');

        $distribution = Distribution::create([
            'claim_id' => $request->claim_id,
            'receiver_name' => $request->receiver_name,
            'receiver_type' => $request->receiver_type,
            'location' => $request->location,
            'story' => $request->story,
            'photo_path' => $path
        ]);

        // update status claim
        $claim->update(['status' => 'distributed']);

        return response()->json([
            'message' => 'Bukti berhasil diupload',
            'data' => $distribution
        ]);
    }

    public function index(Request $request)
    {
        // update expired otomatis
        \App\Models\Donation::where('status', 'tersedia')
            ->where('pickup_deadline', '<', now())
            ->update(['status' => 'expired']);

        $query = \App\Models\Donation::with('donor');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        return response()->json($query->latest()->get());
    }
}