<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Volunteer Application</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/user-dash.css', 'resources/css/volunteer.css'])
</head>

<body>@include('partials.sidebar')<div class="main-wrapper">
        <header class="top-header">
            <div class="user-greeting">
                @if (auth()->user()->profile_photo_path)
                    <img src="{{ Storage::disk('public')->url(auth()->user()->profile_photo_path) }}" alt="{{ auth()->user()->name }}" class="avatar-img">
                @else
                    <div class="avatar-initials">{{ auth()->user()->initials }}</div>
                @endif
                <div class="greeting-text">
                    <h2>Hello, {{ auth()->user()->name }}!</h2>
                </div>
            </div>

            <div class="top-actions">

                <div class="profile-pill">
                    <div class="avatar-initials">{{ auth()->user()->initials }}</div>
                    <div class="profile-info">
                        <div class="name">{{ auth()->user()->name }}</div>
                        <div class="role">{{ ucfirst(auth()->user()->role) }}</div>
                    </div>
                    <i class="fa-solid fa-chevron-down dropdown-icon"></i>
                </div>
            </div>
        </header>

        <main class="dashboard-content volunteer-page"><a class="back-link" href="{{ route('user-cleanup-drives') }}"><i
                    class="fa-solid fa-arrow-left"></i> Back to cleanup drives</a>
            <h1>Register as a Volunteer</h1>
            <p class="page-sub">Provide the details below. Your application will be reviewed before volunteer privileges
                are activated.</p>
            @if ($errors->any())
                <div class="notice error">Please check the fields marked below. {{ $errors->first() }}</div>
            @endif
            <form class="form-card" method="POST" action="{{ route('volunteer.apply.submit') }}"
                enctype="multipart/form-data">@csrf
                <h2>Identity verification</h2>
                <div class="form-grid"><label>ID type<select name="id_type" required>
                            <option value="">Select ID</option>
                            <option>Barangay ID</option>
                            <option>National ID (PhilSys)</option>
                            <option>Driver's license</option>
                            <option>Passport</option>
                            <option>UMID / SSS</option>
                            <option>Student ID</option>
                            <option>Voter's ID</option>
                        </select></label><label>ID number<input name="id_number" value="{{ old('id_number') }}"
                            required></label><label>ID photo - front<input type="file" name="id_front"
                            accept="image/*" required></label><label>ID photo - back (optional)<input type="file"
                            name="id_back" accept="image/*"></label></div>
                @if (auth()->user()->birthdate && auth()->user()->birthdate->age < 18)
                    <h2>Parent / guardian</h2>
                    <div class="form-grid"><label>Guardian name<input name="guardian_name"
                                required></label><label>Guardian mobile<input name="guardian_phone" required></label>
                    </div>
                @endif
                <h2>Organization and skills (optional)</h2>
                <div class="form-grid"><label>Organization name<input
                            name="organization_name"></label><label>Organization type<input
                            name="organization_type"></label><label>Position<input name="organization_role"></label>
                </div>
                <div class="choice-list"><span>Skills you can offer</span>
                    @foreach (['First aid', 'Heavy lifting', 'Driving', 'Coordinating groups', 'Photography', 'Cooking / food'] as $skill)
                        <label><input type="checkbox" name="skills[]" value="{{ $skill }}">
                            {{ $skill }}</label>
                    @endforeach
                </div>
                <div class="choice-list"><span>Availability</span>
                    @foreach (['Weekday mornings', 'Weekday afternoons', 'Saturdays', 'Sundays'] as $availability)
                        <label><input type="checkbox" name="availability[]" value="{{ $availability }}">
                            {{ $availability }}</label>
                    @endforeach
                </div>
                <h2>Emergency contact</h2>
                <div class="form-grid"><label>Name<input name="emergency_contact_name"
                            required></label><label>Phone<input name="emergency_contact_phone"
                            required></label><label>Relationship<input name="emergency_contact_relationship"
                            required></label></div>
                <div class="consent"><label><input type="checkbox" name="terms" value="1" required> I will follow
                        event safety instructions and volunteer guidelines.</label><label><input type="checkbox"
                            name="privacy" value="1" required> I consent to the barangay using these details for
                        volunteer verification.</label></div>
                <div class="form-actions"><a class="btn secondary"
                        href="{{ route('user-cleanup-drives') }}">Cancel</a><button class="btn primary"
                        type="submit">Submit application</button></div>
            </form>
        </main>
    </div>
</body>

</html>
