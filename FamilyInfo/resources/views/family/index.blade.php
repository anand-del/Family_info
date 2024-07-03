@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Family List</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Members Count</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($families as $family)
                <tr>
                    <td>{{ $family->name }} {{ $family->surname }}</td>
                    <td>{{ $family->members_count }}</td>
                    <td>
                        <a href="{{ route('family.show', $family->id) }}" class="btn btn-info">View Details</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
