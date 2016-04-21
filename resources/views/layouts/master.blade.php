<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="_token" content="{{ csrf_token() }}" />

        <title>@yield('title', app_name())</title>

        <meta name="description" content="@yield('meta_description', 'Default Description')">
        <meta name="author" content="@yield('author', 'Social Credits App')">

        @yield('meta')

        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" media="all" rel="stylesheet" type="text/css" />
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" media="all" rel="stylesheet" />
        <link href="{{ URL::asset('css/app.css') }}" media="all" rel="stylesheet" type="text/css" />
        <link href="{{ URL::asset('css/owl.carousel.min.css') }}" media="all" rel="stylesheet" type="text/css" />
        <link href="{{ URL::asset('css/owl.theme.default.min.css') }}" media="all" rel="stylesheet" type="text/css" />

        <!-- Icons-->
        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <!-- Place favicon.ico in the root directory -->
    </head>
    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        @include('includes.nav')

        <div class="container-fluid">
            @yield('content')
        </div>

        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>

        <script type="text/javascript" src="{{ URL::asset('js/owl.carousel.min.js') }}"></script>
        <script type="text/javascript" src="{{ URL::asset('js/module.js') }}"></script>

    </body>
</html>