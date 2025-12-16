<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminReservationController extends Controller
{
    /**
     * Display a list of all future reservations for admin users.
     */
    public function index()
    {
        if (Auth::user()->user_types_id !== 1) {
            abort(403, 'Unauthorized access.');
        }

        $reservations = Reservation::where('reservation_date', '>=', Carbon::today())
            ->with([
                'user:id,first_name,email',
                'schedule.facility:facility_id,facility_name'
            ])
            ->orderBy('reservation_date', 'asc')
            ->orderBy('schedule_id', 'asc')
            ->get();

        return view('admin.reservations', [
            'reservations' => $reservations,
        ]);
    }
}