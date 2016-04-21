@extends('layouts.master')

@section('content')
    <div class="container scp">

        <a href=" {{ url('user') }}">
               <button type="button" class="btn btn-primary">Zurück zum Dashboard</button>
        </a>

        <div class="loading">
           <img src="{{ URL::asset('images/ajax-loader.gif') }}" alt="Loading"/>
        </div>

        <h2>Übersicht - Social Credit Points Abrechung</h2>

        <div class="row">
            <div class="col-md-4">
                <div class="btn-group" role="group" data-activeonload="{{ $filter_id }}">
                    @for ($i = 0; $i < count($abbreviation); $i++)
                        <button type="button" class="btn btn-default filter" data-id="{{ $i }}">
                            {{ $abbreviation[$i] }}
                        </button>
                    @endfor
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <div class="input-group-addon">Suche</div>
                    <input id="search" type="text" class="form-control" placeholder="Nachname eines Studenten">
                </div>
            </div>
            <div class="col-md-4">
                <div class="pull-right">
                    <button type="button" id="drucken" class="btn btn-primary pdf"><span class="glyphicon glyphicon-print" aria-hidden="true"></span>&nbsp;Drucken</button>
                    <button type="button" id="download" class="btn btn-primary pdf"><span class="glyphicon glyphicon-download" aria-hidden="true"></span>&nbsp;Download</button></button>
                </div>
            </div>
        </div>
        <table class="table">
            <tr class="header">
                <th>Nachname</th>
                <th>Vorname</th>
                <th>Social Credit Points</th>
                <th>Kurs</th>
            </tr>
            @include ('includes.user.students_table')
        </table>
    </div>
@endsection