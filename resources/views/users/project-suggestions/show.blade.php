@extends('layouts.master')

@section('content')
    <div class="container">

        <a href=" {{ url('user/project-suggestions') }}">
            <button type="button" class="btn btn-primary">Zurück zu den Projekt-Vorschlägen</button>
        </a>

        <h1>Einzelansicht für ein eigenes Projekt - {{ $project->name }}</h1>

        @include('includes.project.default')
    </div>

    </div>
@endsection