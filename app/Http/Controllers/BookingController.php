<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Property;
use App\Models\Favorite;
use App\Models\Image;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::all();
        return response()->json($bookings);
    }

    public function createBooking(Request $request)
    {

        $validatedData = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'meeting_time' => 'required|date',
            'notes' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:15'
        ]);
        $user = Auth::user();
        $property = Property::findOrFail($validatedData['property_id']);

        $booking = Booking::create([
            'user_id' => $user->id,
            'property_id' => $property->id,
            'meeting_time' => $validatedData['meeting_time'],
            'notes' => $validatedData['notes'],
            'contact_number' => $validatedData['contact_number'],
        ]);


        return response()->json([
            'message' => 'Booking created successfully',
            'booking' => $booking
        ]);
    }



    public function showUserBookings($userId)
    {
        $bookings = Booking::where('user_id', $userId)->get();
        return response()->json($bookings);
    }
    public function showPropertyBookings($propertyId)
    {
        $bookings = Booking::where('property_id', $propertyId)->get();
        return response()->json($bookings);
    }

    public function show($id)
    {
        $booking = Booking::findOrFail($id);
        return response()->json($booking);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'meeting_time' => 'sometimes|required|date',
            'status' => 'sometimes|required|in:pending,confirmed,cancelled',
            'notes' => 'sometimes|nullable|string|max:255',
            'contact_number' => 'sometimes|nullable|string|max:15'
        ]);

        $booking = Booking::findOrFail($id);
        $booking->update($validatedData);

        return response()->json([
            'message' => 'Booking updated successfully',
            'booking' => $booking
        ]);
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return response()->json([
            'message' => 'Booking deleted successfully'
        ]);
    }
}
