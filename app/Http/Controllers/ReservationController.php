<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();
        $today = Carbon::today();

        $allSchedules = Schedule::with('facility')
            ->orderByRaw('FIELD(day_of_week, "Tuesday", "Wednesday", "Thursday", "Friday")') 
            ->orderBy('start_time')
            ->get();

        $reservations = Reservation::whereBetween('reservation_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->get(['reservation_date', 'schedule_id']);
            
        $reservationStatus = [];
        foreach ($reservations as $reservation) {
            $key = $reservation->reservation_date . '_' . $reservation->schedule_id;
            $reservationStatus[$key] = true;
        }

        $calendar = [];
        $bookableDates = []; 
        $dayOfWeek = $startOfMonth->dayOfWeek; 

        $currentDate = $startOfMonth->copy()->subDays($dayOfWeek);
        
        while ($currentDate->lessThanOrEqualTo($endOfMonth) || $currentDate->dayOfWeek !== Carbon::SUNDAY) {
            
            if ($currentDate->dayOfWeek === Carbon::SUNDAY) {
                $calendar[] = [];
            }

            $isCurrentMonth = $currentDate->month === $now->month;
            $isBookableDayOfWeek = $currentDate->dayOfWeek >= Carbon::TUESDAY && $currentDate->dayOfWeek <= Carbon::FRIDAY;
            $isPastDay = $currentDate->lt($today);
            $isBookable = $isCurrentMonth && $isBookableDayOfWeek && !$isPastDay;
            
            $calendar[count($calendar) - 1][] = [
                'date' => $currentDate->toDateString(),
                'day' => $currentDate->day,
                'day_name' => $currentDate->format('l'),
                'is_current_month' => $isCurrentMonth,
                'is_bookable' => $isBookable,
                'is_today' => $currentDate->isToday(),
            ];
            
            if ($isBookable) {
                $bookableDates[] = [
                    'date' => $currentDate->toDateString(),
                    'day_name' => $currentDate->format('l'),
                    'label' => $currentDate->format('M j (l)'),
                ];
            }

            $currentDate->addDay();
        }
        
        $facilities = $allSchedules->pluck('facility.facility_name', 'facility_id')->unique()->map(function ($name, $id) {
            return ['id' => $id, 'name' => $name];
        })->values()->all();

        return view('dashboard', [
            'calendar' => $calendar,
            'allSchedules' => $allSchedules, 
            'reservationStatus' => $reservationStatus, 
            'facilities' => $facilities, 
            'bookableDates' => $bookableDates,
            'currentMonthYear' => $now->format('F Y'),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'reservation_date' => 'required|date|after_or_equal:today',
            'schedule_id' => 'required|exists:schedules,schedule_id',
        ]);
        
        $date = Carbon::parse($request->reservation_date);

        $schedule = Schedule::find($request->schedule_id);
        if (!$schedule || $date->format('l') !== $schedule->day_of_week) {
             return back()->withErrors(['schedule_id' => 'The selected timeslot is not available.']);
        }
        
        $isBooked = Reservation::where('reservation_date', $date->toDateString())
                               ->where('schedule_id', $request->schedule_id)
                               ->exists();
                               
        if ($isBooked) {
            return back()->withErrors(['schedule_id' => 'This timeslot has just been booked.']);
        }

        Reservation::create([
            'reservation_date' => $date->toDateString(),
            'user_id' => Auth::id(),
            'schedule_id' => $request->schedule_id,
        ]);

        return redirect()->route('dashboard')->with('status', 'Reservation created.');
    }

    public static function formatTime(string $timeString): string
    {
        return Carbon::parse($timeString)->format('g:i A');
    }
}
