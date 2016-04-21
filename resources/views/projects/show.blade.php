@extends('layouts.master')

@section('content')
    <div class="container">
        <a href=" {{ url('projects') }}">
            <button type="button" class="btn btn-primary">Zurück zum Marketplace</button>
        </a>

        <h1>Einzelansicht für das Projekt - {{ $project->name }}</h1>

		@include('includes.project.default')

    </div>
@endsection