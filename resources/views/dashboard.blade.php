<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Facility Reservation Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 p-4 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-4 font-medium text-sm text-red-600 bg-red-100 p-4 rounded-lg">
                    <p class="font-bold mb-1">Reservation failed. Please check the errors below:</p>
                    <ul class="mt-1 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div x-data="reservationForm()" x-init="initializeForm(window.allSchedules, window.facilities, window.reservationStatus)" class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-8 border border-indigo-200">
                <h3 class="text-2xl font-semibold mb-6 text-gray-800 border-b pb-2">Book a Timeslot</h3>
                <form id="reservation-form" method="POST" action="{{ route('reservations.store') }}">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                        
                        <div>
                            <label for="reservation_date" class="block font-medium text-sm text-gray-700">
                                Select Date (Tuesdays - Fridays)
                            </label>
                            <select id="reservation_date" name="reservation_date" required x-model="selectedDate" x-on:change="filterSchedules()"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Select a Date</option>
                                @foreach ($bookableDates as $date)
                                    <option 
                                        value="{{ $date['date'] }}"
                                        data-day-name="{{ $date['day_name'] }}"
                                    >
                                        {{ $date['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="modal_facility_id" class="block font-medium text-sm text-gray-700">Select Facility</label>
                            <select id="modal_facility_id" required x-model.number="selectedFacilityId" x-on:change="filterSchedules()"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="0">Select a Facility</option>
                                @foreach ($facilities as $facility)
                                    <option value="{{ $facility['id'] }}">{{ $facility['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="modal_schedule_id" class="block font-medium text-sm text-gray-700">Available Timeslots</label>
                            <select id="modal_schedule_id" name="schedule_id" required x-model="selectedTimeslotId"
                                    x-bind:disabled="!selectedFacilityId || filteredSchedules.length === 0"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                
                                <template x-if="filteredSchedules.length === 0">
                                    <option value="" disabled>
                                        <span x-text="selectedDate && selectedFacilityId != 0 ? 'No available slots for ' + selectedDayName + '.' : 'Select date and facility first.'"></span>
                                    </option>
                                </template>
                                
                                <template x-if="filteredSchedules.length > 0">
                                    <option value="">Choose Timeslot</option>
                                </template>

                                <template x-for="schedule in filteredSchedules" :key="schedule.schedule_id">
                                    <option :value="schedule.schedule_id" x-text="`${formatTime(schedule.start_time)} - ${formatTime(schedule.end_time)}`"></option>
                                </template>
                            </select>
                        </div>

                        <div>
                            <button type="submit" 
                                    x-bind:disabled="!isFormValid()"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm disabled:opacity-50">
                                Confirm Reservation
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <hr class="my-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <h3 class="text-2xl font-semibold mb-6 text-gray-800 flex justify-between items-center">
                    <span>{{ $currentMonthYear }} Reservation Grid</span>
                    <span class="text-sm font-normal text-gray-500">
                        <span class="inline-block w-3 h-3 bg-red-500 rounded-full mr-1"></span> Reserved Slot
                    </span>
                </h3>

                <div class="grid grid-cols-7 gap-px text-center text-xs font-semibold uppercase tracking-wider text-gray-700 bg-gray-200">
                    <div class="p-2">Sun</div>
                    <div class="p-2">Mon</div>
                    <div class="p-2">Tue</div>
                    <div class="p-2">Wed</div>
                    <div class="p-2">Thu</div>
                    <div class="p-2">Fri</div>
                    <div class="p-2">Sat</div>
                </div>

                <div class="grid grid-cols-7 gap-px text-sm bg-gray-200 border border-gray-200">
                    @foreach ($calendar as $week)
                        @foreach ($week as $day)
                            <div 
                                class="h-32 p-1 border-r border-b border-gray-200 overflow-y-auto cursor-default"
                                :class="{
                                    // Day styling
                                    'bg-white text-gray-900': {{ $day['is_current_month'] ? 'true' : 'false' }},
                                    'bg-gray-100 text-gray-400': !{{ $day['is_current_month'] ? 'true' : 'false' }},
                                    // Today marker
                                    'ring-2 ring-indigo-500 z-10': {{ $day['is_today'] ? 'true' : 'false' }},
                                }"
                            >
                                <time datetime="{{ $day['date'] }}" class="block font-semibold"
                                    :class="{
                                        'text-red-600': {{ $day['day_name'] === 'Saturday' || $day['day_name'] === 'Sunday' ? 'true' : 'false' }},
                                        'text-indigo-600': {{ $day['is_today'] ? 'true' : 'false' }},
                                    }"
                                >
                                    {{ $day['day'] }}
                                </time>

                                <div class="mt-1 space-y-1">
                                    {{-- Loop through the schedules to find reserved ones for this date --}}
                                    @if ($day['is_bookable'])
                                        @foreach ($allSchedules as $schedule)
                                            {{-- Use the simple key check. Check if key exists in PHP-generated map --}}
                                            @php
                                                $lookupKey = $day['date'] . '_' . $schedule->schedule_id;
                                                $isReserved = isset($reservationStatus[$lookupKey]);
                                            @endphp

                                            @if ($isReserved)
                                                <div class="flex items-center space-x-1 p-0.5 rounded-full bg-red-100 text-red-800">
                                                    <div class="w-2 h-2 bg-red-500 rounded-full flex-shrink-0"></div>
                                                    {{-- FIX: Call the newly added static method on the controller --}}
                                                    <span class="text-xs truncate" title="{{ $schedule->facility->facility_name }} ({{ \App\Http\Controllers\ReservationController::formatTime($schedule->start_time) }})">
                                                        {{ $schedule->facility->facility_name }}
                                                    </span>
                                                </div>
                                            @endif
                                        @endforeach
                                        
                                        {{-- Fallback: If no reservations shown, but day is bookable, show a general cue --}}
                                        @php
                                            // Check if any schedule for this date is reserved to determine if we need the 'Available Slots' cue
                                            $hasReservation = false;
                                            foreach($allSchedules as $schedule) {
                                                if (isset($reservationStatus[$day['date'] . '_' . $schedule->schedule_id])) {
                                                    $hasReservation = true;
                                                    break;
                                                }
                                            }
                                        @endphp
                                        
                                        @if (!$hasReservation)
                                            <div class="text-xs text-green-500 mt-2 font-medium">Available Slots</div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
            </div>
    </div>

    <script>
        window.allSchedules = @json($allSchedules);
        window.facilities = @json($facilities);
        window.reservationStatus = @json($reservationStatus);
    </script>
</x-app-layout>