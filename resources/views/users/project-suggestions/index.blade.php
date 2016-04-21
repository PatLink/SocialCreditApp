@extends('layouts.master')

@section('content')
    <div class="container">
        <a href=" {{ url('user') }}">
            <button type="button" class="btn btn-primary">Zurück zum Dashboard</button>
        </a>

        <h2>Meine Projekt-Vorschläge</h2>

        <div class="alert alert-info" role="alert">Auf dieser Seite werden alle Projkete angezeigt, die der Nutzer angelegt hat. Zumdem werden hier auch von dem Studiengangsleiter abglehnte Projekte angezeigt.</div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Meine Projekt-Vorschläge</h3>
            </div>
            <div class="panel-body">

                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th style="width: 20%;">Projektname</th>
                        <th style="width: 30%;">Beschreibung</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 10%;">Zurück ziehen</th>
                        <th style="width: 10%;">Bearbeiten</th>
                        <th style="width: 10%;">Ansehen</th>
                    </tr>
                    </thead>

                    <tbody>
                    @if($user_projects == [])
                        <p>Keine Projekte vorhanden!</p>
                    @else
                        @foreach ($user_projects as $project)
                            <tr>
                                <td>{{ $project->name }}</td>
                                <td>{{ $project->description }}</td>
                                <td>
                                    @include ('includes.user.project_status')
                                </td>


                                @if ( $project->status == 'unbestätigt')

                                <td>
                                    <a href="{{ url('user/project-suggestions/' . $project->slug . '/withdraw') }}"><button class="btn btn-danger">Zurück ziehen</button></a>

                                </td>
                                <td>
                                    <a href="{{ url('user/project-suggestions/' . $project->slug . '/edit') }}"><button class="btn btn-default">Bearbeiten</button></a>
                                </td>
                                @else
                                    <td></td>
                                    <td></td>
                                @endif
                                <td>
                                    <a href="{{ url('user/project-suggestions/' . $project->slug)  }}"><button class="btn btn-success">Ansehen</button></a>
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