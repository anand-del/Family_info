@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Family Details</h2>
    <div class="card mb-4">
        <div class="card-header">
            <h4>Head of the Family</h4>
        </div>
        <div class="card-body">
            <p><strong>Name:</strong> {{ $family->name }} {{ $family->surname }}</p>
            <p><strong>Birthdate:</strong> {{ $family->birthdate }}</p>
            <p><strong>Mobile No:</strong> {{ $family->mobile_no }}</p>
            <p><strong>Address:</strong> {{ $family->address }}</p>
            <p><strong>State:</strong> {{ $family->state->name }}</p>
            <p><strong>City:</strong> {{ $family->city->name }}</p>
            <p><strong>Pincode:</strong> {{ $family->pincode }}</p>
            <p><strong>Marital Status:</strong> {{ $family->marital_status }}</p>
            @if($family->marital_status == 'Married')
                <p><strong>Wedding Date:</strong> {{ $family->wedding_date }}</p>
            @endif
            <p><strong>Photo:</strong>
            @if ($family->photo)
                <img src="{{ asset('storage/' . $family->photo) }}" alt="Photo" class="img-thumbnail" width="100">
            @else
                No photo available.
            @endif
        </p>
            <p><strong>Hobbies:</strong>
                @if ($family->hobbies)
                    @php $hobbies = json_decode($family->hobbies); @endphp
                    @foreach ($hobbies as $hobby)
                        {{ $hobby }}
                        @if (!$loop->last), @endif
                    @endforeach
                @else
                    No hobbies listed.
                @endif
            </p>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h4>Family Members</h4>
        </div>
        <div class="card-body">
            <ul class="list-group">
                @foreach($family->members as $member)
                    <li class="list-group-item">
                        <p><strong>Name:</strong> {{ $member->name }}</p>
                        <p><strong>Birthdate:</strong> {{ $member->birthdate }}</p>
                        <p><strong>Marital Status:</strong> {{ $member->marital_status }}</p>
                        @if($member->marital_status == 'Married')
                            <p><strong>Wedding Date:</strong> {{ $member->wedding_date }}</p>
                        @endif
                        <p><strong>Education:</strong> {{ $member->education }}</p>

                        <p><strong>Photo:</strong>
                            @if ($family->photo)
                                <img src="{{ asset('storage/' . $family->photo) }}" alt="Photo" class="img-thumbnail" width="100">
                            @else
                                No photo available.
                            @endif
                        </p>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
