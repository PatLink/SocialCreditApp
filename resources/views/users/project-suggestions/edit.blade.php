@extends('layouts.master')

@section('content')

    <div class="container">

        <a href=" {{ url('user') }}">
            <button type="button" class="btn btn-primary">Zurück zu den Projekt Vorschlägen</button>
        </a>


        <h1>Projekt bearbeiten - {{ $project->name }}</h1>

        @if($errors->has())
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        @endif
    </div>


    <div class="container">
        <!-- Header Bereich Überschrift-->
        <div class="panel panel-default">
            <div class="panel panel-success">
                <div class="panel-heading heading-top">

                    <p>Projekt bearbeiten - Projekt Vorschläge</p>

                </div>
            </div>
        </div>


        <!-- Projektnamenteil-->
        {!! Form::model($project, array('url'=>'user/project-suggestions/' . $project->slug .'/update', 'method'=>'POST', 'accept-charset'=>'UTF-8', 'files'=> true, 'class' => 'dropzone')) !!}

        @include('includes.project.edit')

    </div>

@endsection