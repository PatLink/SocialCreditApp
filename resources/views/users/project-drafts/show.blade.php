@extends('layouts.master')
@section('content')
    <div class="container">

        <h1>Einzelansicht für den Projekt-Vorschlag - {{ $project->name }}</h1>


        <div class="panel panel-default">
            <div class="panel panel-success">
                <div class="panel-heading heading-top">{{ $project->name }}</div>
                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-6">
                            <img class="header-bild"src="penguin.jpg">

                        </div>

                        <div class="col-md-6">
                            <ul class="list-group">
                                <li class="list-group-item">Anzahl der möglichen Teilnehmer:{{ $project->participants_capacity }} </li>
                                <li class="list-group-item">Anzahl der aktuellen Teilnehmer: xxx</li>
                                <li class="list-group-item">Anzahl der SCP:	{{ $project->scp_reward }}</li>
                                <li class="list-group-item">Engagementart:	<img src="noun_4405.png" class="hochschule">  {{ $project->engagement }}</li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>



            <div class="panel panel-default">
                <div class="panel-heading heading-middle">Weitere Informationen</div>
                <div class="panel-body">

                    <div>
                        <p class="unteruntertitel">Teilnehmer:</p>

                        <img src="noun_4520_cc.png" alt="Teilnehmer Foto 1" class="img-rounded ji-member">
                        <img src="noun_4520_cc.png" alt="Teilnehmer Foto 2" class="img-rounded ji-member">
                        <img src="noun_4520_cc.png" alt="Teilnehmer Foto 3" class="img-rounded ji-member">
                        <img src="noun_4520_cc.png" alt="Teilnehmer Foto 4" class="img-rounded ji-member">
                        <img src="noun_4520_cc.png" alt="Teilnehmer Foto 5" class="img-rounded ji-member">
                        <img src="noun_4520_cc.png" alt="Teilnehmer Foto 6" class="img-rounded ji-member">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <p class="unteruntertitel">Beschreibung:</p>
                            <textarea class="form-control" rows="7" disabled>{{ $project->description }}</textarea>
                        </div>

                        <div class="col-md-6">

                            <p class="unteruntertitel">Kategorie des Projekts:</p>
                            <p><img src="noun_4405.png" class="hochschule">  {{ $project->categories }}</p>

                            <p class="unteruntertitel">Betreuer:</p>
                            <p><img src="noun_4405.png" class="hochschule">  {{ $project->tutor }}</p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
@endsection