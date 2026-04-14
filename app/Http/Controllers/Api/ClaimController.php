<?php

// app/Http/Controllers/Api/ClaimController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\Donation;
use Illuminate\Support\Facades\DB;

class ClaimController extends Controller
{
    public function claim($donation_id)
    {
        $user = auth()->user();

        if ($user->role !== 'relawan') {
            return response()->json(['message' => 'Hanya relawan'], 403);
        }

        $donation = Donation::findOrFail($donation_id);

        try {
            DB::transaction(function () use ($donation, $user) {

                if ($donation->remaining_portion <= 0) {
                    throw new \Exception("Sudah diklaim");
                }

                Claim::create([
                    'donation_id' => $donation->id,
                    'volunteer_id' => $user->id
                ]);

                $donation->update([
                    'remaining_portion' => 0,
                    'status' => 'habis'
                ]);
            });

            return response()->json(['message' => 'Berhasil klaim']);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }

        if ($donation->pickup_deadline < now()) {
            return response()->json([
                'message' => 'Donasi sudah expired'
            ], 400);
        }
    }
}