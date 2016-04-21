@extends('layouts.master')

@section('content')
    <div class="container">
        <a href=" {{ url('user') }}">
            <button type="button" class="btn btn-primary">Zurück zum Dashboard</button>
        </a>

        <h2>Meine Projekte in Vorbereitung</h2>

        <div class="alert alert-info" role="alert">Auf dieser Seite werden alle Projekte angezeigt, die der Nutzer angelegt hat, aber noch nicht abgesendet.</div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Projekte im Draft</h3>
            </div>
            <div class="panel-body">

                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th style="width: 20%;">Projektname</th>
                        <th style="width: 30%;">Beschreibung</th>
                        <th style="width: 10%;">Löschen</th>
                        <th style="width: 10%;">Bearbeiten</th>
                        <th style="width: 10%;">Freigeben</th>
                        <th style="width: 10%;">Ansehen</th>
                    </tr>
                    </thead>

                    <tbody>
                    @if($user_project_drafts == [])
                        <p>Keine Projekte vorhanden!</p>
                    @else
                        @foreach ($user_project_drafts as $project)
                            <tr>
                                <td>{{ $project->name }}</td>
                                <td>{{ $project->description }}</td>
                                <td>
                                    <a href="{{ url('user/project-drafts/' . $project->slug . '/destroy') }}"><button class="btn btn-danger">Löschen</button></a>
                                </td>

                                <td>
                                    <a href="{{ url('user/project-drafts/' . $project->slug . '/edit') }}"><button class="btn btn-default">Bearbeiten</button></a>
                                </td>

                                <td>
                                    <a href="{{ url('user/project-drafts/' . $project->slug . '/release') }}"><button class="btn btn-primary">Freigeben</button></a>

                                </td>
                                <td>
                                    <a href="{{ url('user/project-drafts/' . $project->slug)  }}"><button class="btn btn-success">Ansehen</button></a>
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