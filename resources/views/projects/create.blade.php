@extends('layouts.master')

@section('content')
    <div class="container">

        <a href=" {{ url('user') }}">
            <button type="button" class="btn btn-primary">Zurück zur User-Seite</button>
        </a>

        <hr />
        @include('includes.partials.errors')

        <h1>Projekt erstellen</h1>

        <!-- Projektnamenteil-->
        {!! Form::open(array('url'=>'projects/create', 'method'=>'POST', 'accept-charset'=>'UTF-8', 'files'=> true, 'class' => 'dropzone')) !!}


        <div class="panel body">

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="projektname">Name</label>
                        {!! Form::text('name', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="project_img">Projekt Bild</label>
                        {!! Form::file('image', null, ['class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="col-md-6">

                    <div class="textangaben">
                        <div class="form-group">
                            <label for="anzahl_der_personen">Teilnehmeranzahl</label>
                            {!! Form::input('number', 'participants_capacity', null, ['class' => 'form-control']) !!}
                        </div>


                        <div class="form-group">
                            <label for="anzahl_der_scp">Anzahl der Socialcreditpoints Stunden</label>
                            {!! Form::input('number', 'scp_reward', null, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            <label for="engagement_art">Engagementart</label>
                            {!! Form::select('engagement', array(
                            'Studiengang' => 'Studiengang',
                            'Hochschule' => 'Hochschule',
                            'Extern' => 'Exterm'), 'Studiengang', ['class' => 'form-control'])
                            !!}
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">

                        <div class="panel panel-default">
                            <div class="panel-heading information">Weitere Informationen</div>
                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="form-group">
                            <label for="beschreibung">Beschreibung</label>
                            {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '7']) !!}
                        </div>
                    </div>

                    <div class="col-md-6">

                        <div class="form-group">
                            <label for="kategorie">Kategorie</label>
                            {!! Form::select('categories', array(
                            'Zusammenhalt' => 'Zusammenhalt',
                            'Hilfe bei Projekten' => 'Hilfe bei Projekten',
                            'Verbreitung des Studiengangs' => 'Verbreitung des Studiengangs',
                            'Sonstiges' => 'Sonstges'
                            ), 'Zusammenhalt', ['class' => 'form-control'])
                            !!}
                        </div>


                        <div class="form-group">
                            <label for="betreuer_id">Betreuer</label>
                            {!! Form::select('tutor', array('' => 'Bitte Betreuer benennen.') + $available_tutors , '',['class' => 'form-control']) !!}
                        </div>

                        <p>Datei:</p>
                        <div class="form-group">
                            <label for="project_file_id">Dateien</label>
                            {!! Form::file('storage_id', null, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group hide">
                            {!! Form::select('restrictions', array(
                            'Keine' => 'Keine',
                            'ON12' => 'ON12',
                            'ON13' => 'ON13',
                            'ON14' => 'ON14'), 'ON12', ['class' => 'form-control'])
                            !!}
                        </div>

                    </div>

                </div>

                <div class="row">
                    <div class="col-md-12">
                        {!! Form::submit('Im Draft speichern', ['name' => 'draft', 'class' => 'btn btn-default']) !!}
                        @if( Auth::user()->userrole == 3)
                        {!! Form::submit('Eintragen', ['name' => 'insert', 'class' => 'btn btn-default']) !!}
                        @else
                            {!! Form::submit('Vorschlagen', ['name' => 'suggest', 'class' => 'btn btn-default']) !!}
                        @endif
                        {!! Form::close() !!}
                    </div>
                </div>


            </div> <!-- Ende body-->



        </div>
    </div>
@endsection
