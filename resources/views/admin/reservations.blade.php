<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Reservations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-semibold mb-6">Upcoming Reservations</h3>

                    @if ($reservations->isEmpty())
                        <p class="text-gray-600">There are no future reservations at this time.</p>
                    @else
                        <div class="space-y-6">
                            @foreach ($reservations as $reservation)
                                <div class="p-4 border rounded-lg border-indigo-300 bg-indigo-50">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div class="font-medium">
                                            <p class="text-xs text-indigo-700 uppercase tracking-wider">Date & Time</p>
                                            <p class="text-lg font-bold">{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('M d, Y') }}</p>
                                            <p class="text-sm text-gray-700">
                                                {{ $reservation->schedule->facility->facility_name }} ({{ \App\Http\Controllers\ReservationController::formatTime($reservation->schedule->start_time) }} - {{ \App\Http\Controllers\ReservationController::formatTime($reservation->schedule->end_time) }})
                                            </p>
                                        </div>
                                        
                                        <div class="font-medium">
                                            <p class="text-xs text-indigo-700 uppercase tracking-wider">Reserved By</p>
                                            <p class="text-lg font-bold">{{ $reservation->user->first_name }}</p>
                                            <p class="text-sm text-gray-700">{{ $reservation->user->email }}</p>
                                        </div>

                                        <div class="font-medium">
                                            <p class="text-xs text-indigo-700 uppercase tracking-wider">Status</p>
                                            <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                                {{ $reservation->reservation_status }}
                                            </span>
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>