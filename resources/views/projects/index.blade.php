@extends('layouts.master')

@section('content')
    <div class="container">
        <a href=" {{ url('user') }}">
            <button type="button" class="btn btn-primary">Zurück zum Dashboard</button>
        </a>

        <h2>Marktplatz für alle Projekte</h2>

        <div class="alert alert-info" role="alert">Der Marktplatz zeigt alle Projekte die angelegt wurden und vom Studiengangsleiter bestätigt wurden sind. Auf die Projekte die sich im Marketplace befinden kann sich jeder Student bewerben, wenn der Student die Restiktionen des Projektes einhält..</div>

        @include('includes.marketplace.overview')

    </div>
@endsection