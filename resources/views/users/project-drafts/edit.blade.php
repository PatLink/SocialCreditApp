@extends('layouts.master')

@section('content')

<div class="container">

    <a href=" {{ url('user') }}">
        <button type="button" class="btn btn-primary">Zurück zu  Projekte Drafts</button>
    </a>

    <hr />
    @include('includes.partials.errors')

    <h1>Projekt bearbeiten - {{ $project->name }}</h1>


    <!-- Projektnamenteil-->
    {!! Form::model($project, array('url'=>'user/project-drafts/' . $project->slug .'/update', 'method'=>'POST', 'accept-charset'=>'UTF-8', 'files'=> true, 'class' => 'dropzone')) !!}

    @include('includes.project.edit')

</div>

@endsection