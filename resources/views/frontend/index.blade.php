@extends('layouts.master')

@section('content')
    <div class="container">

        <div>
            <p style="max-width: 500px;">Willkommem auf der Webanwendung zum Verwalten deiner Social Credit Points. Bitte melde dich über den DHBW Login an und startet damit
            deine Projekte online zu verwalten.</p>
        </div>

        <div style="margin: 20px 0;">
            <a href="https://www.dhbw-mosbach.de/no_cache/service-pages/login/socialcreditpointsapp.html">
                <button class="btn btn-success btn-lg scp-btn-login">
                    DHBW Login
                    <span class="glyphicon glyphicon-circle-arrow-right" aria-hidden="true"></span>
                </button>
            </a>
        </div>

        <div>
            <a href="{{ url('auth/login') }}">
                <button class="btn btn-success btn-lg scp-btn-login">
                    Development - Login
                    <span class="glyphicon glyphicon-circle-arrow-right" aria-hidden="true"></span>
                </button>
            </a>
        </div>

    </div>
@endsection