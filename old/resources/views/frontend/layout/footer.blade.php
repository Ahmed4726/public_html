
<!-- FOOTER -->

<div class="container-fluid">
    <div class="row separator"></div>
    <div class="row footer">
        <div class="container">

            <div class="row footer-links">

                <div class="col-md-3">
                    <h6>USEFUL LINKS</h6>
                    <ul class="no-style">
                        @foreach($links->where('type_id', 5) as $link)
                            <li><a href="{{$link->link_url}}" target="{{$link->target}}"><i class="fa fa-angle-double-right"></i> {{$link->label}}</a></li>
                        @endforeach
                    </ul>

                </div>

                <div class="col-md-3">
                    <h6>ADMISSION LINKS</h6>
                    <ul class="no-style">
                        @foreach($links->where('type_id', 2) as $link)
                            <li><a href="{{$link->link_url}}" target="{{$link->target}}"><i class="fa fa-angle-double-right"></i> {{$link->label}}</a></li>
                        @endforeach
                    </ul>
                </div>

                <div class="col-md-3">
                    <h6>OTHERS</h6>
                    <ul class="no-style">
                        @foreach($links->where('type_id', 4) as $link)
                            <li><a href="{{$link->link_url}}" target="{{$link->target}}"><i class="fa fa-angle-double-right"></i> {{$link->label}}</a></li>
                        @endforeach
                    </ul>
                </div>

                <div class="col-md-3">

                    <row>

                        <div class="col-md-12">
                            <img class="lazy" data-src="{{asset('images/logo-ju-small.png')}}"
                                 style="width: 100px;background: white;border-radius: 5%;"/>
                        </div>

                    </row>

                    <row>
                        <div class="col-md-12 social-icons">
                            <a href="{{$setting->facebook_link}}"> <i class="fab fa-facebook-f" data-pack="social" data-tags="like, post, share"></i> </a>
                            <a href="{{$setting->twitter_link}}"> <i class="fab fa-twitter" data-pack="social" data-tags="like, post, share"></i> </a>
                            <a href="{{$setting->linkedin_link}}"> <i class="fab fa-linkedin-in" data-pack="social" data-tags="like, post, share"></i> </a>
                        </div>
                    </row>

                </div>

            </div>

            <div class="row footer-copy-rights-text">
                <div class="col-md-8">
                    {{$setting->footer_text}}
                </div>
                <div class="col-md-4 text-md-right"> {{$setting->copyright_text}} </div>
            </div>
        </div>
    </div>
</div>

</main>

<script type="text/javascript">
    $(function() {
        $('.lazy').Lazy();
    });

    //    jQuery('#accordion .accordion-toggle').click(function () {
    //        jQuery(this).find('i').toggleClass('fa-plus fa-minus')
    //                .closest('.accordion-toggle').next().slideToggle()
    //                .siblings('.accordion-toggle').slideUp();
    //    });

    $("#header-nav").find(".dropdown").hover(function() {
        $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500);
    }, function() {
        $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(500);
    });

    {!! $setting->custom_js !!}
</script>

@yield('footerScript')

</body>
</html>