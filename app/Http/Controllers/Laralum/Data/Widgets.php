<?php

// Operations here
require('WidgetsOperations.php');

// Widgets here
$widgets = [
    'latest_users_graph' =>  Laralum::lineChart($latest_users_graph['title'], $latest_users_graph['element_label'], $latest_users_graph['labels'], $latest_users_graph['data']),
    'latest_posts_graph' =>  Laralum::lineChart($latest_posts_graph['title'], $latest_posts_graph['element_label'], $latest_posts_graph['labels'], $latest_posts_graph['data']),
    'users_country_pie_graph'  =>   Laralum::pieChart($users_country_pie_graph['title'], $users_country_pie_graph['labels'], $users_country_pie_graph['data']),
    'users_country_geo_graph'  =>   Laralum::geoChart($users_country_geo_graph['title'], $users_country_geo_graph['element_label'], $users_country_geo_graph['data']),
    'roles_users'   =>  Laralum::barChart('Total users per role', 'Users', ['one', 'tow', 'three'], [1, 2, 3]),
    'basic_stats_1'   =>  "
        <div class='ui doubling stackable three column grid container'>
            <div class='column'>
                <center>
                    <div class='ui statistic'>
                        <div class='value'>
                            " . count(Laralum::users()) . "
                        </div>
                        <div class='label'>Total User</div>
                    </div>
                </center>
            </div>
            <div class='column'>
                <center>
                    <div class='ui statistic'>
                        <div class='value'>
                            " . count(Laralum::generalRoles()) . "
                        </div>
                        <div class='label'>
                            " . trans('laralum.roles') . "
                        </div>
                    </div>
                </center>
            </div>
            <div class='column'>
                <center>
                    <div class='ui statistic'>
                        <div class='value'>
                            " . count(Laralum::users('enabled', '0')) . "
                        </div>
                        <div class='label'>Disabled User</div>
                    </div>
                </center>
            </div>
        </div>
    ",
    'basic_stats_2'   =>  "
        <div class='ui doubling stackable three column grid container'>
            <div class='column'>
                <center>
                    <div class='ui statistic'>
                        <div class='value'>
                            " . count(Laralum::posts()) . "
                        </div>
                        <div class='label'>
                            " . trans('laralum.posts') . "
                        </div>
                    </div>
                </center>
            </div>
            <div class='column'>
                <center>
                    <div class='ui statistic'>
                        <div class='value'>
                            " . count(Laralum::postViews()) . "
                        </div>
                        <div class='label'>
                            " . trans('laralum.post_views') . "
                        </div>
                    </div>
                </center>
            </div>
            <div class='column'>
                <center>
                    <div class='ui statistic'>
                        <div class='value'>
                            " . count(Laralum::comments()) . "
                        </div>
                        <div class='label'>
                            " . trans('laralum.comments') . "
                        </div>
                    </div>
                </center>
            </div>
        </div>
    ",
];
