@extends('layouts.master')

@section('content')
    <div class="container">
        <a href=" {{ url('user') }}">
            <button type="button" class="btn btn-primary">Zurück zum Dashboard</button>
        </a>

        <h2>{{ Auth::user()->first_name }}'s Projekte</h2>

        <div class="alert alert-info" role="alert">Auf dieser Seite werden alle Projkete angezeigt, die der Nutzer angelegt hat. Zumdem werden hier auch von dem Studiengangsleiter abglehnte Projekte angezeigt.</div>


        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Meine Projekte</h3>
            </div>
            <div class="panel-body">

                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th style="width: 20%;">Projektname</th>
                        <th style="width: 50%;">Beschreibung</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 10%;">Dokumentation abgeben</th>
                        <th style="width: 10%;">Ansehen</th>
                    </tr>
                    </thead>

                    <tbody>
                    @if($user_projects == [])
                        <p>Keine Projekte vorhanden!</p>
                    @else
                        @foreach ($user_projects as $project)
                            <tr>
                                <td>{{ $project['name'] }}</td>
                                <td>{{ $project['description'] }}</td>
                                <td>@include ('includes.user.my_project_status')</td>
                                <td>
                                    @if($project['status'] == 'bestätigt')
                                        <a href="{{ url('user/projects/' . $project['slug'] . '/documentation') }}">
                                            <button class="btn btn-primary">Dokumentation abgeben</button>
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ url('user/projects/' .$project['slug'])  }}"><button class="btn btn-success">Ansehen</button></a>
                                </td>
                            </tr>
                        @endforeach
                    @endif

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection