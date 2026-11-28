<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $reportCounts = $user->wasteReports()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN LOWER(status) IN ('pending', 'verified') THEN 1 ELSE 0 END) as pending")
            ->selectRaw("SUM(CASE WHEN LOWER(status) = 'resolved' THEN 1 ELSE 0 END) as resolved")
            ->first();

        return view('user-profile', [
            'user' => $user->load('barangay'),
            'barangays' => Barangay::orderBy('name')->get(),
            'reportCounts' => [
                'total' => (int) ($reportCounts->total ?? 0),
                'pending' => (int) ($reportCounts->pending ?? 0),
                'resolved' => (int) ($reportCounts->resolved ?? 0),
            ],
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $request->merge(['mobile' => preg_replace('/[\s\-()]/', '', (string) $request->input('mobile'))]);
        $changingLoginDetails = $request->input('email') !== $user->email
            || preg_replace('/[\s\-()]/', '', (string) $request->input('mobile')) !== $user->mobile;
        $rules = [
            'first_name' => ['required', 'string', 'max:60'],
            'middle_name' => ['nullable', 'string', 'max:60'],
            'last_name' => ['required', 'string', 'max:60'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'mobile' => ['required', 'regex:/^(09\d{9}|\+639\d{9})$/', Rule::unique('users', 'mobile')->ignore($user->id)],
            'birthdate' => ['required', 'date', 'after:1900-01-01', 'before_or_equal:' . now()->subYears(13)->toDateString()],
            'sex' => ['nullable', Rule::in(['Female', 'Male', 'Prefer not to say'])],
            'barangay_id' => ['required', 'exists:barangays,id'],
            'address' => ['required', 'string', 'max:150'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'current_password' => [$changingLoginDetails ? 'required' : 'nullable', 'current_password'],
        ];
        $data = $request->validateWithBag('profile', $rules, [
            'mobile.regex' => 'Enter a valid PH mobile number, like 0917 123 4567.',
            'birthdate.before_or_equal' => 'You must be at least 13 years old.',
            'profile_photo.max' => 'Your profile photo must be 5 MB or smaller.',
            'current_password.required' => 'Enter your current password to change your email address or mobile number.',
            'current_password.current_password' => 'Your current password is incorrect.',
        ]);

        $data['mobile'] = preg_replace('/[\s\-()]/', '', $data['mobile']);
        unset($data['current_password'], $data['profile_photo']);
        $data['name'] = trim($data['first_name'] . ' ' . $data['last_name']);

        if ($request->hasFile('profile_photo')) {
            $newPath = $request->file('profile_photo')->store('profile-photos', 'public');
            $oldPath = $user->profile_photo_path;
            $data['profile_photo_path'] = $newPath;
            $user->fill($data)->save();
            if ($oldPath) {
                Storage::disk('public')->delete($oldPath);
            }
        } else {
            $user->fill($data)->save();
        }

        return redirect()->route('user-profile')->with('success', 'Your profile information has been updated.');
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validateWithBag('password', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'current_password.current_password' => 'Your current password is incorrect.',
        ]);

        $request->user()->forceFill(['password' => Hash::make($data['password'])])->save();

        return redirect()->route('user-profile')->with('password_success', 'Your password has been changed successfully.');
    }
}
