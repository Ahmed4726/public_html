

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Jahangirnagar University</title>
    <link rel="shortcut icon" type="image/png" sizes="16x16" href="{{ asset('laralum_public/images/logo-ju.png') }}">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" >
    <link rel="stylesheet" href="{{asset('css/login.css')}}" >
</head>

<body style="overflow-x: hidden;">

<div class="wrapper">

    <div id="content">

        <div class="row" align="center">
            <div class="col-0 col-sm-3 col-md-4 col-lg-4 col-xl-4"></div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-4">
                <a href="{{url('')}}">
                    <img style="margin-top: 10px;" src="{{ asset('images/logo-ju.png') }}"/>
                </a>
            </div>
            <div class="col-0 col-sm-3 col-md-4 col-lg-4 col-xl-4"></div>
        </div>

        <div class="pagecontent">
            <div class="row">
                <div class="col-0 col-sm-1 col-md-2 col-lg-3">
                </div>

                <div class="col-12 col-sm-10 col-md-8 col-lg-6">

                    @if(session('success'))
                        <div class="alert alert-success" role="alert"> {!! session('success') !!} </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger" role="alert"> {!! session('error') !!} </div>
                    @endif

                    @if(session('status'))
                        <div class="alert alert-success" role="alert"> {!! session('status') !!} </div>
                    @endif

                    @if (count($errors) > 0)
                        @foreach($errors->all() as $error)
                            <div class="alert alert-danger" role="alert">
                                {{$error}}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endforeach
                    @endif

                    <div class="card">
                        <div class="row">
                            @yield('content')
                        </div>
                    </div>

                </div>

                <div class="col-0 col-sm-1 col-md-2 col-lg-3">
                </div>

            </div>

            <div class="row" style="margin-bottom: 15px; ">

                <div class="col-0 col-sm-1 col-md-3">
                </div>

                <div class="col-12 col-sm-10 col-md-6" align="center">
                </div>

                <div class="col-0 col-sm-1 col-md-3">
                </div>

            </div>

        </div> <!-- end of pagecontent-->
    </div>
</div>

<!-- Footer - Start -->
<footer class="mainfooter" role="contentinfo">

    <footer class="mainfooter" role="contentinfo">
        <div class="copyright">
            <br/>
           <span>JAHANGIRNAGAR UNIVERSITY</span>
        </div>

        <div class="copyright">
            {{--<p style="padding-top: 0px; margin-bottom: 0px;">Developed by</p>
            <p class="text-xs-center"><a href="http://www.agamilabs.com">AGAMiLabs Limited</a></p>--}}
        </div>

    </footer>
</footer>
<!-- Footer - End -->


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
<!-- Bootstrap Js CDN -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>


</body>
</html>
