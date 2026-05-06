<?php

// app/Http/Controllers/Api/VolunteerController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Claim;

class VolunteerController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        if ($user->role !== 'relawan') {
            return response()->json(['message' => 'Hanya untuk relawan.'], 403);
        }

        $totalKlaim = Claim::where('volunteer_id', $user->id)->count();

        $klaimAktif = Claim::where('volunteer_id', $user->id)
            ->where('status', 'diklaim')
            ->with('donation')
            ->latest()
            ->get();

        $riwayat = Claim::where('volunteer_id', $user->id)
            ->where('status', 'selesai')
            ->with(['donation', 'distribution'])
            ->latest()
            ->limit(10)
            ->get();

        return response()->json([
            'total_klaim'  => $totalKlaim,
            'klaim_aktif'  => $klaimAktif,
            'riwayat'      => $riwayat,
        ]);
    }
}
