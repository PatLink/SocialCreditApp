@extends('layouts.master')
@section('content')
    <div class="container">

        <a href=" {{ url('user/projects') }}">
            <button type="button" class="btn btn-primary">Zurück zu meinen Projekten</button>
        </a>

        <hr />

        <h2>Projekt abschließen / Dokumentation - {{ $project->name }}</h2>

        {!! Form::open(array('method'=>'POST', 'accept-charset'=>'UTF-8', 'files'=> true, 'class' => 'dropzone')) !!}

        <div class="form-group">
            <label for="documentation">Dokumentation hochladen</label>
            {!! Form::file('documentation', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            <label for="beschreibung">Kommentar</label>
            {!! Form::textarea('comment', null, ['class' => 'form-control', 'rows' => '7']) !!}
        </div>

        {!! Form::submit('Dokumentation hochladen', ['name' => 'suggest', 'class' => 'btn btn-success pull-right']) !!}

        {!! Form::close() !!}

    </div>
@endsection