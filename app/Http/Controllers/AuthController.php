<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public const SEX   = ['Female', 'Male', 'Prefer not to say'];
    public const MIN_AGE = 13;

    public function showLogin()
    {
        if (Auth::check()) {
            return $this->dashboardRedirect(Auth::user());
        }

        return view('login', [
            'barangays' => Barangay::orderBy('name')->get(),
            'sexes'     => self::SEX,
            'minAge'    => self::MIN_AGE,
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return $this->dashboardRedirect(Auth::user());
        }

        return back()
            ->withErrors(['email' => 'That email and password don\'t match our records.'])
            ->onlyInput('email');
    }

    private function dashboardRedirect($user)
    {
        return redirect()->route($user->role === 'leader' || $user->role === 'admin' ? 'purok-dash' : 'user-dash');
    }

    public function register(Request $request)
    {
        // Accept "0917 123 4567", "0917-123-4567" or "+63 917 123 4567"
        $request->merge([
            'mobile' => preg_replace('/[\s\-()]/', '', (string) $request->input('mobile')),
        ]);

        $data = $request->validateWithBag('register', [
            'first_name'  => ['required', 'string', 'max:60'],
            'middle_name' => ['nullable', 'string', 'max:60'],
            'last_name'   => ['required', 'string', 'max:60'],
            'birthdate'   => [
                'required', 'date', 'after:1900-01-01',
                'before_or_equal:' . now()->subYears(self::MIN_AGE)->toDateString(),
            ],
            'sex'         => ['nullable', Rule::in(self::SEX)],
            'mobile'      => ['required', 'regex:/^(09\d{9}|\+639\d{9})$/', 'unique:users,mobile'],
            'barangay_id' => ['required', 'exists:barangays,id'],
            'address'     => ['required', 'string', 'max:150'],
            'email'       => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'    => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'terms'       => ['accepted'],
        ], [
            'birthdate.before_or_equal' => 'You must be at least ' . self::MIN_AGE . ' years old to register.',
            'barangay_id.required'      => 'Please choose your barangay.',
            'mobile.regex'              => 'Enter a valid PH mobile number, like 0917 123 4567.',
            'mobile.unique'             => 'That mobile number is already registered.',
            'email.unique'              => 'That email is already registered. Try logging in instead.',
            'terms.accepted'            => 'Please agree to continue.',
        ]);

        $user = User::create([
            'name'        => trim($data['first_name'] . ' ' . $data['last_name']),
            'first_name'  => $data['first_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'last_name'   => $data['last_name'],
            'birthdate'   => $data['birthdate'],
            'sex'         => $data['sex'] ?? null,
            'mobile'      => $data['mobile'],
            'barangay_id' => $data['barangay_id'],
            'address'     => $data['address'],
            'email'       => $data['email'],
            'password'    => Hash::make($data['password']),
            'role'        => 'resident', // never taken from the form
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('user-dash');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
