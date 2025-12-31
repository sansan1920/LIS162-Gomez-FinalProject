<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;

class MyReservationController extends Controller
{
    public function index()
    {
        $reservations = auth()->user()->reservations()
            ->with(['schedule.facility', 'feedback'])
            ->orderBy('reservation_date', 'asc')
            ->get();

        return view('reservations.index', [
            'reservations' => $reservations,
        ]);
    }

    public function submitFeedback(Reservation $reservation, Request $request)
    {
        if ($reservation->user_id !== auth()->id()) {
            abort(403, 'You do not own this reservation.');
        }

        $request->validate([
            'user_feedback' => 'required|string|max:200',
        ]);
        
        if (Carbon::parse($reservation->reservation_date)->isFuture()) {
             return back()->withErrors(['user_feedback' => 'Feedback can only be submitted after reservation date.']);
        }
        
        if ($reservation->feedback_id !== null) {
             return back()->withErrors(['user_feedback' => 'Feedback already submitted.']);
        }

        $feedback = Feedback::create([
            'user_feedback' => $request->user_feedback,
        ]);

        $reservation->update([
            'feedback_id' => $feedback->feedback_id,
        ]);

        return back()->with('status', 'Feedback sent.');
    }

    public function destroy(Reservation $reservation)
    {
        if ($reservation->user_id !== auth()->id()) {
            abort(403, 'You do not own this reservation.');
        }

        if (Carbon::parse($reservation->reservation_date)->isPast()) {
             return back()->withErrors(['deletion_error' => 'Past reservations cannot be cancelled.']);
        }
        
        $reservation->delete();

        return redirect()->route('my-reservations')->with('status', 'Reservation cancelled.');
    }
}
