<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Reservations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 p-4 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-semibold mb-6">Your Booking History</h3>

                    @if ($reservations->isEmpty())
                        <p class="text-gray-600">You have no reservations yet.</p>
                    @else
                        <div class="space-y-6">
                            @foreach ($reservations as $reservation)
                                @php
                                    $isPast = \Carbon\Carbon::parse($reservation->reservation_date)->isPast();
                                @endphp
                                <div class="p-4 border rounded-lg {{ $isPast ? 'border-gray-200' : 'border-indigo-300 bg-indigo-50' }}">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-lg font-bold">{{ $reservation->schedule->facility->facility_name }}</p>
                                            <p class="text-sm text-gray-600">
                                                Date: **{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('F d, Y') }}**
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                Time: {{ \Carbon\Carbon::parse($reservation->schedule->start_time)->format('g:i A') }} - 
                                                {{ \Carbon\Carbon::parse($reservation->schedule->end_time)->format('g:i A') }}
                                            </p>
                                            <p class="text-sm font-semibold mt-1">Status: <span class="text-green-600">{{ $reservation->reservation_status }}</span></p>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                    {{-- Cancellation Section --}}
                                    @if (!$isPast)
                                        <form method="POST" action="{{ route('reservations.destroy', $reservation) }}" 
                                            onsubmit="return confirm('Are you sure you want to cancel this reservation for {{ $reservation->reservation_date }}? This action cannot be undone.');">
                                            @csrf
                                            @method('delete')
                                            
                                            <button type="submit" 
                                                class="px-3 py-1 bg-red-600 text-white text-xs uppercase font-semibold tracking-wider rounded-md hover:bg-red-500 transition ease-in-out duration-150">
                                                Cancel Reservation
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                    <div class="mt-4 pt-4 border-t border-gray-100">
                                        @if ($isPast && !$reservation->feedback)
                                            <h4 class="font-semibold mb-2">Submit Feedback (Optional)</h4>
                                            <form method="POST" action="{{ route('reservations.submitFeedback', $reservation) }}">
                                                @csrf
                                                <textarea 
                                                    name="user_feedback" 
                                                    rows="3" 
                                                    maxlength="200"
                                                    class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                                    placeholder="Max 200 characters. Your feedback is anonymous."
                                                    required
                                                >{{ old('user_feedback') }}</textarea>
                                                
                                                <div class="mt-2 text-sm text-red-500">
                                                    @error('user_feedback')
                                                        {{ $message }}
                                                    @enderror
                                                </div>

                                                <button type="submit" class="mt-3 px-4 py-2 bg-indigo-600 text-white font-semibold text-xs uppercase tracking-widest rounded-md hover:bg-indigo-500 transition ease-in-out duration-150">
                                                    Submit Feedback
                                                </button>
                                            </form>
                                        @elseif ($reservation->feedback)
                                            <h4 class="font-semibold mb-2">Your Submitted Feedback:</h4>
                                            <p class="italic text-gray-700">"{{ $reservation->feedback->user_feedback }}"</p>
                                        @elseif (!$isPast)
                                            <p class="text-sm text-gray-500">Feedback can be submitted after the reservation date has passed.</p>
                                        @endif
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