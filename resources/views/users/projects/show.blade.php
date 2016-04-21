@extends('layouts.master')
@section('content')
    <div class="container">

        <a href=" {{ url('user/projects') }}">
            <button type="button" class="btn btn-primary">Zurück zu meinen Projekten</button>
        </a>

        <hr />

        @include('includes.project.default')
    </div>
@endsection