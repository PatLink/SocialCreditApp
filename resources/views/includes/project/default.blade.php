<div class="panel panel-default">
    <div class="panel panel-success">
        <div class="panel-heading heading-top">{{ $project->name }}<span class="pull-right">Projekt-Status: {{ $project->status }}</span> </div>
        <div class="panel-body">

            <div class="row">
                <div class="col-md-6">
                    <img class="header-bild"src="{{ URL::asset('images/25.jpg') }}" />
                </div>

                <div class="col-md-6">
                    <ul class="list-group">

                        @if($project->status != 'unbestätigt' | 'draft')
                        <li class="list-group-item">
                            <button class="btn btn-block dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
                            Teilnehmer: {{count($participants)}} von {{ $project->participants_capacity }} m&ouml;glichen
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                                @foreach ($participants as $participant)
                                    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">{{$participant->first_name}} {{$participant->last_name}} </a></li>
                                @endforeach
                            </ul>
                        </li>
                        @endif
                        <li class="list-group-item">Anzahl der SCP:	{{ $project->scp_reward }}</li>
                        <li class="list-group-item">Engagementart: {{ $project->engagement }}</li>
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
            </div>

            <div class="row">
                <div class="col-md-6">
                    <p class="unteruntertitel">Beschreibung:</p>
                    <textarea class="form-control" rows="7" disabled>{{ $project->description }}</textarea>
                </div>

                <div class="col-md-6">

                    <div>
                        <p class="unteruntertitel">Kategorie des Projekts:</p>
                        <p>{{ $project->categories }}</p>

                        <p class="unteruntertitel">Betreuer:</p>
                        <p>{{ $project->tutor }}</p>
                    </div>

                    <div>
                        @if (Auth::user()->userrole == '1')
                            @if($project_applied == null)
                                @if( $project->status == 'bestätigt')
                                    {!! Form::open(array('url'=>'projects/' . $project->slug . '/apply', 'method'=>'POST', 'accept-charset'=>'UTF-8')) !!}
                                    {!! Form::submit('Einschreiben', ['name' => 'einschreiben', 'class' => 'btn btn-success']) !!}
                                    {!! Form::close() !!}
                                @endif
                            @else
                                {!! Form::open(array('url'=>'projects/' . $project->slug . '/withdraw', 'method'=>'POST', 'accept-charset'=>'UTF-8')) !!}
                                {!! Form::submit('Einschreiben rückgängig machen', ['name' => 'ausschreiben', 'class' => 'btn btn-danger']) !!}
                                {!! Form::close() !!}
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
