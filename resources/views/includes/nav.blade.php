<div class="container">
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ url('') }}">{{app_name()}}</a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    @if (Auth::guest())
                        <li>{!! link_to('auth/login', 'Development Login') !!}</li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> Navigation<span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <!--li><a href="{!! url('about') !!}"><i class="fa fa-users"></i> Über uns</a></li-->
                                <li><a href="{!! url('privacy') !!}"><i class="fa fa-heart"></i> Datenschutz</a></li>
                                <li><a href="{!! url('impress') !!}"><i class="fa fa-lock"></i> Impressum</a></li>
                            </ul>
                        </li>
                    @else

                        @if (Auth::user()->userrole == '1')
                        <li>
                            <a>
                                <span>
                                    SCP dieses Jahr
                                </span>
                            </a>
                        </li>
                        <li>
                            <a>
                                <div style="width: 300px"class="progress">
                                  <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="{{Session::get('scp_percentage')}}" aria-valuemin="0" aria-valuemax="100" style="width: {{Session::get('scp_percentage')}}%">
                                        {{ Session::get('student_scps') }} von
                                        {{ Session::get('scp_current_year') }} erreicht.

                                  </div>
                                </div>
                            </a>
                        </li>
<li>
                        <a href="{{ url('projects/create') }}">
                            Neues Projekt
                        </a>
</li>
                        @endif
                        @if (Auth::user()->userrole != '3')
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">


                                @if(!$notifications == [])
                                    <span class="glyphicon glyphicon-bell"> </span>
                                @endif

                                Notifications&nbsp;

                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                @if($notifications == [])
                                    <li>
                                        <a>
                                            Keine Neuigkeiten vorhanden
                                        </a>
                                    </li>
                                @endif
                                @foreach($notifications as $notification)
                                <li>
                                    <a href="{{$notification['url']}}">
                                        <span class="glyphicon glyphicon-{{$notification['icon']}}"></span>
                                        {{$notification['subject']}} wurde {{$notification['state']}}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        @endif
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                            <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{!! url('user') !!}"><i class="fa fa-home"></i> Dashboard</a></li>
                                <li><a href="{!! url('user/project-drafts') !!}"> <i class="fa fa-tasks"></i> Entw&uuml;rfe</a></li>
                                <li><a href="{!! url('user/project-suggestions') !!}"> <i class="fa fa-tasks"></i> Best&auml;tigung Ausstehend</a></li>
                                @if (Auth::user()->userrole == '1')
                                <li><a href="{!! url('user/projects') !!}"> <i class="fa fa-tasks"></i> Meine Projekte</a></li>
                                <li><a href="{!! url('user/scp') !!}"><i class="fa fa-calculator"></i> SCP Abrechnung</a></li>
                                @endif

                                @if (Auth::user()->userrole == '3')
                                    <li><a href="{!! url('user/scp') !!}"><i class="fa fa-calculator"></i> Übersicht SCP</a></li>
                                    <li><a href="{!! url('projects/documentation') !!}"><i class="fa fa-book"></i> Dokumentationen</a></li>
                                @endif

                                <li><a href="{!! url('projects') !!}"><i class="fa fa-list-ol"></i> Marketplace</a></li>
                                <li><a href="{!! url('user/profile') !!}"><i class="fa fa-briefcase"></i> Benutzerprofil</a></li>
                                <!--li><a href="{!! url('about') !!}"><i class="fa fa-users"></i> Über uns</a></li-->
                                <li><a href="{!! url('privacy') !!}"><i class="fa fa-heart"></i> Datenschutz</a></li>
                                <li><a href="{!! url('impress') !!}"><i class="fa fa-lock"></i> Impressum</a></li>
                                <li><a href="{!! url('auth/logout') !!}"><i class="fa fa-sign-out"></i> Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</div>
