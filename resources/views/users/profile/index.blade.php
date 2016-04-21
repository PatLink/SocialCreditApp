@extends('layouts.master')

@section('content')
    <div class="container">
        <a href=" {{ url('user') }}">
            <button type="button" class="btn btn-primary">Zurück zum Dashboard</button>
        </a>

        <div class="alert alert-info" role="alert">Das Benutzerprofil zeigt Nutzerdaten und falls es sich bei dem Nutzer auch um einen Studenten handelt zeigt es zudem, ob dieser das SCP-Ziel des Jahres erreicht hat.</div>

        <div class="panel panel-default">
            <div class="panel panel-success">
                <div class="panel-heading heading-top">
                    <p>Benutzerprofil -
                        @if ( Auth::user()->userrole == 2)
                            Dozent
                        @elseif(Auth::user()->userrole == 3)
                            Studiengangsleiter
                        @else
                            Student
                        @endif
                    </p>
                </div>
            </div>

            <div class="panel-heading">
                <h3 class="panel-title">Vorname: {{ Auth::user()->first_name }} / Nachname: {{ Auth::user()->last_name }}</h3>
            </div>

            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group">
                            <li class="list-group-item">Studiengang: Onlinemedien</li>
                            <li class="list-group-item">Campus: Mosbach</li>
                            <li class="list-group-item">Email: {{ Auth::user()->email }}</li>
                            @if (Auth::user()->userrole == '1')
                                <li class="list-group-item">Benutzerrolle: Student</li>
                            @endif

                            @if (Auth::user()->userrole == '2')
                                <li class="list-group-item">Benutzerrolle: Dozent</li>
                            @endif

                            @if (Auth::user()->userrole == '3')
                                <li class="list-group-item">Benutzerrolle: Professor</li>
                            @endif


                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection