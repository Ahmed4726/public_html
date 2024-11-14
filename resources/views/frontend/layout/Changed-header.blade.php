<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="{{asset('images/favicon.ico')}}">

    <title>Jahangirnagar University</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/priority-custom.css')}}">
    <link rel="stylesheet" href="{{asset('css/client-style.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,700;1,400;1,700&display=swap"
        rel="stylesheet">


    <!-- Bootstrap core JavaScript
    ================================================== -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>


      <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>


    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-168459382-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-168459382-1');
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.11/jquery.lazy.min.js"></script>

    @php
    if($setting->custom_css){
    echo "<style>
        $setting->custom_css
    </style>";
    }
    @endphp

    @yield('headerStyle')

</head>

<body>

    <header>

        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <a href="{{route("Frontend::home")}}">
                        <img src="{{ asset('images/logo-ju.png') }}" alt="Jahangirnagar University" class="juniv-logo">
                    </a>
                </div>
                <div class="col-lg-8 text-right">
                    <div class="top-contact-info">
                        <a href="tel:{{$setting->phone}}" class="d-inline-block"><i class="fa fa-phone"
                                aria-hidden="true"></i> {{$setting->phone}}</a>

                        <a href="tel:{{$setting->fax}}" class="d-inline-block"><i class="fa fa-fax"
                                aria-hidden="true"></i> Fax: {{$setting->fax}}</a>
                        <a href="mailto:registrar@juniv.edu" target="_top" class="d-inline-block"><i
                                class="fas fa-envelope"></i> {{$setting->email}}</a>
                    </div>
                    <div class="top-contact-info-2">
                        {!!$setting->top_contact_menu!!}
                    </div>
                </div>
            </div>
        </div>

        <div class="container" id="header-nav">
            <nav class="navbar navbar-expand-lg navbar-dark bg-blue main-nav">
                <div class="container menu-fix">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                        aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarCollapse">
                        <ul class="navbar-nav mr-auto">
                            @foreach($menus as $menu)
                            <li class="nav-item break-fix @if($menu->subMenu->isNotEmpty()) dropdown @endif">
                                <a class="nav-link @if($menu->subMenu->isNotEmpty()) dropdown-toggle @endif"
                                    href="{{url($menu->link)}}" @if($menu->subMenu->isNotEmpty()) id="navbarDropdown"
                                    role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                    @endif>
                                    @if($menu->animation_enabled) <i
                                        class="fa fa-asterisk no-padding no-margin animated infinite flash menu"></i>
                                    @endif
                                    {{$menu->display_text}}
                                </a>

                                @if($menu->subMenu->isNotEmpty())
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    @foreach($menu->subMenu as $subMenu)
                                    <a class="dropdown-item" href="{{url($subMenu->link)}}">
                                        @if($subMenu->animation_enabled) <i
                                            class="fa fa-asterisk no-padding no-margin animated infinite flash submenu"></i>
                                        @endif
                                        {{$subMenu->display_text}}
                                    </a>
                                    @endforeach
                                </div>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <main role="main">