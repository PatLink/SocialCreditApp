@extends('layouts.master')

@section('content')
	<div class="container">

        @if (Auth::user()->userrole == '1')
        <div class="alert alert-info" role="alert">Herzlich Willkommen. Sie sind momentan als <strong>Student</strong> angemeldet!</div>
        @endif

        @if (Auth::user()->userrole == '2')
        <div class="alert alert-info" role="alert">Herzlich Willkommen. Sie sind momentan als <strong>Dozent</strong> angemeldet!</div>
        @endif

        @if (Auth::user()->userrole == '3')
        <div class="alert alert-info" role="alert">Herzlich Willkommen. Sie sind momentan als <strong>Professor</strong> angemeldet!</div>

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Teilnahme der Studenten an Projekten</h3>
            </div>
            <div class="panel-body">

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 20%;">Projektname</th>
                            <th style="width: 40%;">Beschreibung</th>
                            <th style="width: 40%;">Name</th>

                            <th style="width: 10%;">Ablehnen</th>
                            <th style="width: 10px;">Bestätigen</th>
                            <th style="width: 20%;">Mehr Infos</th>
                        </tr>
                    </thead>

                    <tbody>
                    @if($applyed_projects == [])
                        <p>Keine Anträge auf Teilnahme an Projekten vorhanden!</p>
                    @else
                        @foreach ($applyed_projects as $unconfirmed_student_project)
                            <tr>
                                <td>{{ $unconfirmed_student_project['project_name'] }}</td>
                                <td>{{ $unconfirmed_student_project['description'] }}</td>
                                <td>{{ $unconfirmed_student_project['student_first_name'] . ' ' . $unconfirmed_student_project['student_last_name'] }}</td>
                                <td>
                                    <a href="{{ url('user/project/reject/' . $unconfirmed_student_project['project_slug'] . '/' . $unconfirmed_student_project['student_id']) }}">
                                        <button class="btn btn-danger">Ablehnen</button>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ url('user/project/confirm/' . $unconfirmed_student_project['project_slug'] . '/' . $unconfirmed_student_project['student_id']) }}">
                                        <button class="btn btn-success">Bestätigen</button>
                                    </a>

                                </td>
                                <td>
                                    <a href="{{ url('projects/' . $unconfirmed_student_project['project_slug']) }}">
                                        <button class="btn btn-primary">Mehr Erfahren</button>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif

                    </tbody>
                </table>
            </div>
        </div>

                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <h3 class="panel-title">Projekte bestätigen oder ablehnen</h3>
                    </div>
                    <div class="panel-body">

                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 20%;">Projektname</th>
                                <th style="width: 40%;">Beschreibung</th>
                                <th style="width: 10%;">Ablehnen</th>
                                <th style="width: 10%;">Bestätigen</th>
                                <th style="width: 10%;">Mehr Infos</th>
                            </tr>
                            </thead>

                            <tbody>
                            @if($unconfirmed_projects == [])
                                <p>Keine Projekte vorhanden!</p>
                            @else
                                @foreach ($unconfirmed_projects as $unconfirmed_project)
                                    <tr>
                                        <td>{{ $unconfirmed_project->name }}</td>
                                        <td>{{ $unconfirmed_project->description }}</td>
                                        <td>
                                            <a href="{{ url('user/reject/' . $unconfirmed_project->slug) }}">
                                                <button class="btn btn-danger">Ablehnen</button>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ url('user/confirm/' . $unconfirmed_project->slug) }}">
                                                <button class="btn btn-success">Bestätigen</button>
                                            </a>

                                        </td>
                                        <td>
                                            <a href="{{ url('projects/' . $unconfirmed_project->slug)  }}">
                                                <button class="btn btn-primary">Mehr Erfahren</button>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                            </tbody>
                        </table>
                    </div>
                </div>
        @endif

        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title">Meine Projekte</h3>
            </div>
            <div class="panel-body">
                @if($userprojects == '[]')
                    <div class="alert alert-warning" role="alert">Sie haben noch keine eigenen Projekte.</div>
                    <a href="{{ url('projects/create') }}">
                        <button class="btn btn-default">Neues Projekt anlegen</button>
                    </a>
                @else
                    <div class="scp-project-slider">
                        <div id="add-new-project">
                            <a href="{{ url('projects/create') }}">
                                <div>Neues Projekt anlegen</div>
                                <div class="scp-icon-add"><i class="fa fa-plus-square"></i></div>
                            </a>
                        </div>

                        @foreach ($userprojects as $project)
                            <div id="view-detail-project">
                                <p><strong>Projektname</strong>
                                <a href="{{ url('user/projects/' . $project->slug) }}">
                                    {{ $project->name }}
                                </a></p>
                                <hr />
                                <strong>Projektstatus:</strong> @include ('includes.user.project_status')
                            </div>
                        @endforeach

                    </div>
                @endif
            </div>
        </div>
        <hr />

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Marketplace</h3>
            </div>
            <div class="panel-body">
                @include('includes.marketplace.overview')

            </div>
        </div>
    </div>
@endsection