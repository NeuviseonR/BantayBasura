<?php

namespace App\Http\Controllers;

use App\Models\WasteReport;
use App\Services\NominatimGeocodingService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WasteReportController extends Controller
{
    /** value stored in the database => label shown to people */
    public const CATEGORIES = [
        'illegal_dumping'   => 'Illegal dumping',
        'overflowing_bin'   => 'Overflowing bin',
        'broken_bin'        => 'Broken trash bin',
        'plastic_waste'     => 'Plastic waste',
        'hazardous_waste'   => 'Hazardous waste',
        'missed_collection' => 'Missed collection',
        'other'             => 'Other',
    ];

    public function create()
    {
        return view('user-report', ['categories' => self::CATEGORIES]);
    }

    public function store(Request $request, NominatimGeocodingService $geocoding)
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:100'],
            'category'    => ['required', Rule::in(array_keys(self::CATEGORIES))],
            'description' => ['required', 'string', 'min:10', 'max:500'],
            'photo'       => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], // 5 MB
            'latitude'    => ['required', 'numeric', 'between:-90,90'],
            'longitude'   => ['required', 'numeric', 'between:-180,180'],
        ], [
            'category.required'    => 'Please choose a category.',
            'description.min'      => 'Please add a little more detail (at least 10 characters).',
            'photo.required'       => 'Please add a photo of the problem.',
            'photo.image'          => 'That file isn\'t a picture.',
            'photo.mimes'          => 'Use a JPG, PNG or WEBP photo.',
            'photo.max'            => 'The photo is too big. Please use one under 5 MB.',
            'photo.uploaded'       => 'The photo couldn\'t be uploaded. It may be too large for the server.',
            'latitude.required'    => 'Please send your GPS location so the team can find the spot.',
            'longitude.required'   => 'Please send your GPS location so the team can find the spot.',
        ]);

        // Saves the file to storage/app/public/reports/ with a random name
        // and returns the path ("reports/xxxx.jpg") that we keep in the database.
        $path = $request->file('photo')->store('reports', 'public');

        $user = $request->user();

        $report = WasteReport::create([
            'user_id'     => $user->id,
            'barangay_id' => $user->barangay_id,
            'zone'        => $user->zone,
            'title'       => $data['title'],
            'category'    => $data['category'],
            'description' => $data['description'],
            'photo_path'  => $path,
            'latitude'    => $data['latitude'],
            'longitude'   => $data['longitude'],
            'location_name' => $geocoding->reverseGeocode($data['latitude'], $data['longitude']),
            'status'      => 'pending',
        ]);

        return redirect()
            ->route('user-my-reports')
            ->with('success', 'Report ' . $report->reference . ' was submitted successfully.');
    }
}
