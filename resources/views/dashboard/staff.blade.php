@extends('layouts.staff')

@section('content')

<h1>Dashboard Staff</h1>

<form method="POST" action="/logout">
@csrf
<button type="submit">Logout</button>
</form>

@endsection
