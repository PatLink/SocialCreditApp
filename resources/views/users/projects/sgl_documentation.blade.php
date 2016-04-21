@extends('layouts.master')
@section('content')
    <div class="container">

        <a href=" {{ url('user') }}">
            <button type="button" class="btn btn-primary">Zurück zum Dashboard</button>
        </a>

        <hr />

        <h2>Dokumentation der Projekte</h2>

    </div>
@endsection