<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Property;
use App\Models\Favorite;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::with('images')->get();
        return response()->json($properties);
    }

    public function show($id)
    {
        $property = Property::with('images')->findOrFail($id);
        return response()->json($property);
    }

    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zipcode' => 'required|string',
            'property_type' => 'sometimes|required|in:house,apartment,villa',
            'square_feet' => 'sometimes|required|numeric',
            'rent_or_sale' => 'required|in:rent,sale',
            'bedrooms' => 'required|integer',
            'bathrooms' => 'required|integer',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $property = Property::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zipcode' => $request->zipcode,
            'type' => $request->type,
            'rent_or_sale' => $request->rent_or_sale,
            'bedrooms' => $request->bedrooms,
            'bathrooms' => $request->bathrooms,
            'area' => $request->area,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('property_images', 'public');

                Image::create([
                    'property_id' => $property->id,
                    'image_path' => $path, // ✅ This line is critical
                ]);
            }
        }

        return response()->json($property, 201);
    }



    public function update(Request $request, $id)
    {

        // dd($request->all());
        $property = Property::findOrFail($id);

        if (Auth::id() !== $property->user_id && Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric',
            'address' => 'sometimes|required|string',
            'city' => 'sometimes|required|string',
            'state' => 'sometimes|required|string',
            'zipcode' => 'sometimes|required|string',
            'property_type' => 'sometimes|required|in:house,apartment,villa',
            'rent_or_sale' => 'sometimes|required|in:rent,sale',
            'bedrooms' => 'sometimes|required|integer',
            'bathrooms' => 'sometimes|required|integer',
            'square_feet' => 'sometimes|required|numeric',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $property->update($request->only([
            'title',
            'description',
            'price',
            'address',
            'city',
            'state',
            'zipcode',
            'property_type',
            'rent_or_sale',
            'bedrooms',
            'bathrooms',
            'square_feet'
        ]));

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('property_images', 'public');
                Image::create([
                    'property_id' => $property->id,
                    'image_path' => $path,
                ]);
            }
        }

        return response()->json($property->load('images'));
    }



    public function destroy($id)
    {

        $property = Property::findOrFail($id);

        if (Auth::id() !== $property->user_id && Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $property->delete();

        // Delete associated images
        foreach ($property->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        return response()->json(['message' => 'Property deleted successfully']);
    }

    public function favorite(Request $request, $id)
    {
        $property = Property::findOrFail($id);
        $user = Auth::user();

        // dd($property, $user);

        $favorite = Favorite::where('user_id', $user->id)->where('property_id', $property->id)->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['message' => 'Property removed from favorites']);
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'property_id' => $property->id,
            ]);
            return response()->json(['message' => 'Property added to favorites']);
        }
    }

    public function getFavorites()
    {
        $user = Auth::user();

        $favorites = Favorite::with('property.images')
            ->where('user_id', $user->id)
            ->get();

        return response()->json($favorites);
    }
    public function getUserProperties()
    {
        $user = Auth::user();

        $properties = Property::with('images')->where('user_id', $user->id)->get();

        return response()->json($properties);
    }
}
