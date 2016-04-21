@extends('layouts.master')

@section('content')
   <div class="container scp">
       <a href=" {{ url('user') }}">
           <button type="button" class="btn btn-primary">Zurück zum Dashboard</button>
       </a>

       <div class="loading">
           <img src="{{ URL::asset('images/ajax-loader.gif') }}" alt="Loading"/>
       </div>

       <h2>Social Credit Points von {{ Auth::user()->first_name }} {{ Auth::user()->last_name  }}</h2>

       <div class="btn-group" role="group" data-activeonload="{{ $filter_id }}">
           @for ($i = 0; $i < count($years); $i++)
               <button type="button" class="btn btn-default filter" data-id="{{ $i }}">
                   {{ $years[$i][0]  }} / {{ $years[$i][1] }}
               </button>
           @endfor
       </div>

       <div class="pull-right">
           <button type="button" id="drucken" class="btn btn-primary pdf"><span class="glyphicon glyphicon-print" aria-hidden="true"></span>&nbsp;Drucken</button>
           <button type="button" id="download" class="btn btn-primary pdf"><span class="glyphicon glyphicon-download" aria-hidden="true"></span>&nbsp;Download</button></button>
       </div>

       <table class="table">
            <tr>
                <th rowspan="2">Lfd Nr.</th>
                <th rowspan="2">Betreuer</th>
                <th rowspan="2">Projekt</th>
                <th colspan="3">Stunden</th>
            </tr>
            <tr class="header">
                <th>Studiengang</th>
                <th>Hochschule</th>
                <th>Extern</th>
            </tr>
            @include ('includes.user.project_table')
       </table>

   </div>

@endsection