<?php

$includes = [
    'header'    =>  "
        <script src=" . asset(Laralum::publicPath() . '/js/jquery-3.0.0.min.js') . "></script>
    ",
    /*
    |--------------------------------------------------------------------------
    | Laralum Includes, please do not remove any lines as it may cause problems
    |--------------------------------------------------------------------------
    */
    'laralum_header'    =>  "
        <link rel='stylesheet' type='text/css' href='https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css'>
        <link rel='stylesheet' type='text/css' href='" . asset(Laralum::publicPath() . '/sweetalert/sweetalert.css') . "'>
        <link rel='stylesheet' type='text/css' href='" . asset(Laralum::publicPath() . '/css/style.css') . "'>
        <script src='" . asset(Laralum::publicPath() . '/sweetalert/sweetalert.min.js') . "'></script>
    ",

    'laralum_bottom'    =>  "
        <script src='https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js'></script>
        <script src='" . asset(Laralum::publicPath() . '/js/script.js') . "'></script>
    ",

    'charts'    =>  "
        <script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>
        <script type='text/javascript' src='https://www.google.com/jsapi'></script>
        <script type='text/javascript'>google.charts.load('current', {'packages':['corechart', 'geochart']});</script>
        <script src='" . asset(Laralum::publicPath() . '/highcharts/js/highcharts.js') . "'></script>
        <script src='" . asset(Laralum::publicPath() . '/highcharts/js/modules/exporting.js') . "'></script>
        <script src='" . asset(Laralum::publicPath() . '/highmaps/js/modules/map.js') . "'></script>
        <script src='" . asset(Laralum::publicPath() . '/highmaps/js/modules/data.js') . "'></script>
        <script src='" . asset(Laralum::publicPath() . '/highmaps/maps/world.js') . "'></script>
        <script src='" . asset(Laralum::publicPath() . '/chartjs/Chart.js') . "'></script>
    ",
];
