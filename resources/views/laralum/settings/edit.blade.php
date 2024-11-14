@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
    <div class="active section">Settings Update</div>
</div>
@endsection
@section('title', 'Settings Update')
@section('icon', "edit")
@section('subtitle', 'Settings Update')
@section('content')

<div class="ui doubling stackable grid container">
    <div class="row">
        <div class="two wide column"></div>
        <div class="twelve wide column" id="vue-app">
            <form class="ui form" method="POST" action="{{ route('Laralum::setting::update') }}"
                enctype="multipart/form-data">
                <div class="ui very padded segment">
                    {{ csrf_field() }}

                    <div class="field required">
                        <label>Type</label>
                        <select name="type" required>
                            <option @if($setting->type == 'UNIVERSITY') selected="selected" @endif
                                value="UNIVERSITY">UNIVERSITY</option>
                            <option @if($setting->type == 'HALL') selected="selected" @endif value="HALL">HALL</option>
                            <option @if($setting->type == 'DEPARTMENT') selected="selected" @endif
                                value="DEPARTMENT">DEPARTMENT</option>
                        </select>
                    </div>

                    <div class="field">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="Email..." value="{{ $setting->email }}">
                    </div>

                    <div class="field">
                        <label>Notify Employee Email Application</label>
                        <input type="email" name="notify_email" placeholder="Notify Email..."
                            value="{{ $setting->notify_email }}">
                    </div>

                    <div class="field">
                        <label>Notify Internet Connection Application</label>
                        <input type="email" name="notify_internet_connection"
                            placeholder="Notify Internet Connection Application..."
                            value="{{ $setting->notify_internet_connection }}">
                    </div>

                    <div class="field">
                        <label>Notify Internet Complain</label>
                        <input type="email" name="notify_internet_complain" placeholder="Notify Internet Complain..."
                            value="{{ $setting->notify_internet_complain }}">
                    </div>

                    <div class="field">
                        <label>Notify Teacher Application</label>
                        <input type="email" name="notify_teacher" placeholder="Notify Teacher Application..."
                            value="{{ $setting->notify_teacher }}">
                    </div>

                    <div class="field">
                        <label>Phone</label>
                        <input type="text" name="phone" placeholder="Phone..." value="{{ $setting->phone }}">
                    </div>

                    <div class="field required">
                        <label>FAX</label>
                        <input type="text" name="fax" placeholder="FAX..." value="{{ $setting->fax }}" required>
                    </div>

                    <div class="field">
                        <label>Top Contact Menu</label>
                        <textarea name="top_contact_menu">{{$setting->top_contact_menu}}</textarea>
                    </div>

                    <div class="field">
                        <label>Welcome Message</label>
                        <textarea name="welcome_message">{{$setting->welcome_message}}</textarea>
                    </div>

                    <div class="field">
                        <label>Address</label>
                        <textarea name="address">{{$setting->address}}</textarea>
                    </div>

                    <div class="field">
                        <label>Footer Text</label>
                        <input type="text" name="footer_text" placeholder="Footer Text..."
                            value="{{ $setting->footer_text }}">
                    </div>

                    <div class="field required">
                        <label>Copyright Text</label>
                        <input type="text" name="copyright_text" placeholder="Copyright Text..."
                            value="{{ $setting->copyright_text }}" required>
                    </div>

                    <div class="field">
                        <label>VC Message</label>
                        <textarea name="owner_msg">{{$setting->owner_msg}}</textarea>
                    </div>

                    <div class="field">
                        <label>Contact Page Link</label>
                        <input type="text" name="contact_us_link" placeholder="Contact Page Link..."
                            value="{{ $setting->contact_us_link }}">
                    </div>

                    <div class="field">
                        <label>Carrier Page Link</label>
                        <input type="text" name="jobs_link" placeholder="Carrier Page Link..."
                            value="{{ $setting->jobs_link }}">
                    </div>

                    <div class="field">
                        <label>Webmail Link</label>
                        <input type="text" name="webmail_link" placeholder="Webmail Link..."
                            value="{{ $setting->webmail_link }}">
                    </div>

                    <div class="field">
                        <label>About Us Link</label>
                        <input type="text" name="about_us_link" placeholder="About Us Link..."
                            value="{{ $setting->about_us_link }}">
                    </div>

                    <div class="field">
                        <label>Mission & Vision Link</label>
                        <input type="text" name="mission_and_vission_link" placeholder="Mission & Vision Link..."
                            value="{{ $setting->mission_and_vission_link }}">
                    </div>

                    <div class="field">
                        <label>Facebook Link</label>
                        <input type="text" name="facebook_link" placeholder="Facebook Link..."
                            value="{{ $setting->facebook_link }}">
                    </div>

                    <div class="field">
                        <label>Twitter Link</label>
                        <input type="text" name="twitter_link" placeholder="Twitter Link..."
                            value="{{ $setting->twitter_link }}">
                    </div>

                    <div class="field">
                        <label>Linkedin Link</label>
                        <input type="text" name="linkedin_link" placeholder="Linkedin Link..."
                            value="{{ $setting->linkedin_link }}">
                    </div>

                    <div class="inline field">
                        <div class="ui slider checkbox">
                            <input type="checkbox" name="featured_news_enabled" tabindex="0" class="hidden"
                                @if($setting->featured_news_enabled) checked @endif>
                            <label>Enabled Featured News On Home</label>
                        </div>
                    </div>

                    <div class="inline field">
                        <div class="ui slider checkbox">
                            <input type="checkbox" name="hall_enabled" tabindex="0" class="hidden"
                                @if($setting->hall_enabled) checked @endif>
                            <label>Enable Hall On header</label>
                        </div>
                    </div>

                    <div class="inline field">
                        <div class="ui slider checkbox">
                            <input type="checkbox" name="animate_header_admission_link" tabindex="0" class="hidden"
                                @if($setting->animate_header_admission_link) checked @endif>
                            <label>Animate Header Admission Link</label>
                        </div>
                    </div>

                    <div class="field">
                        <label>Max Profile Image Size in KB (1MB = 1000 KB)</label>
                        <input type="text" name="max_profile_image_size_in_kb"
                            placeholder="Max Profile Image Size in KB (1MB = 1000 KB)..."
                            value="{{ $setting->max_profile_image_size_in_kb }}">
                    </div>

                    <div class="field">
                        <label>Max Discussion (news, event, tender, notice, job circular, press release) Image Size in
                            KB (1MB = 1000 KB)</label>
                        <input type="text" name="max_discussion_image_size_in_kb"
                            placeholder="Max Discussion (news, event, tender, notice, job circular, press release) Image Size in KB (1MB = 1000 KB)..."
                            value="{{ $setting->max_discussion_image_size_in_kb }}">
                    </div>

                    <div class="field">
                        <label>Banner Image Limit</label>
                        <input type="text" name="banner_image_limit" placeholder="Banner Image Limit..."
                            value="{{ $setting->banner_image_limit }}">
                    </div>

                    <div class="field">
                        <label>Custom CSS</label>
                        <textarea name="custom_css">{{$setting->custom_css}}</textarea>
                    </div>

                    <div class="field">
                        <label>Custom JavaScript</label>
                        <textarea name="custom_js">{{$setting->custom_js}}</textarea>
                    </div>

                    <div class="field">
                        <label>Select Event for Home Page First Section</label>

                        <div class="ui multiple selection dropdown">
                            <!-- This will receive comma separated value like OH,TX,WY !-->
                            <input name="home_first_section_event" type="hidden"
                                value="{{implode(',', $setting->home_first_section_event)}}">
                            <i class="dropdown icon"></i>
                            <div class="default text">Please Select Event for Home Page First Section</div>
                            <div class="menu">
                                @foreach($events as $event)
                                <div class="item" data-value="{{$event->id}}">{{$event->name}}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="field">
                        <label>Select Event for Home Page Second Section</label>

                        <div class="ui multiple selection dropdown">
                            <!-- This will receive comma separated value like OH,TX,WY !-->
                            <input name="home_second_section_event" type="hidden"
                                value="{{implode(',', $setting->home_second_section_event)}}">
                            <i class="dropdown icon"></i>
                            <div class="default text">Please Select Event for Home Page Second Section</div>
                            <div class="menu">
                                @foreach($events as $event)
                                <div class="item" data-value="{{$event->id}}">{{$event->name}}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="field">
                        <label>Select Event for Home Page Third Section</label>

                        <div class="ui multiple selection dropdown">
                            <!-- This will receive comma separated value like OH,TX,WY !-->
                            <input name="home_third_section_event" type="hidden"
                                value="{{implode(',', $setting->home_third_section_event)}}">
                            <i class="dropdown icon"></i>
                            <div class="default text">Please Select Event for Home Page Third Section</div>
                            <div class="menu">
                                @foreach($events as $event)
                                <div class="item" data-value="{{$event->id}}">{{$event->name}}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="field">
                        <label>Select Event for Department Page</label>

                        <div class="ui multiple selection dropdown">
                            <!-- This will receive comma separated value like OH,TX,WY !-->
                            <input name="department_event" type="hidden"
                                value="{{implode(',', $setting->department_event)}}">
                            <i class="dropdown icon"></i>
                            <div class="default text">Please Select Event for Department Page</div>
                            <div class="menu">
                                @foreach($events as $event)
                                <div class="item" data-value="{{$event->id}}" data-selected="selected">{{$event->name}}
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{--<div class="field required">
                            <label>Default Password for New User</label>
                            <input type="text" required name="default_password_new_user" placeholder="Default Password for New User..." value="{{ $setting->default_password_new_user }}">
                </div>--}}
                <div class="field required">
                    <label>Paginating Per Page For Frontend</label>
                    <input type="number" required name="frontend_pagination_number"
                        placeholder="Paginating Per Page For Frontend..."
                        value="{{ $setting->frontend_pagination_number }}">
                </div>

                <div class="field required">
                    <label>Paginating Per Page For Backend</label>
                    <input type="number" required name="backend_pagination_number"
                        placeholder="Paginating Per Page For Backend..."
                        value="{{ $setting->backend_pagination_number }}">
                </div>

                <div class="field required">
                    <label>Spotlight Number</label>
                    <input type="number" required name="spotlight_number" placeholder="Spotlight Number..."
                        value="{{ $setting->spotlight_number }}">
                </div>

                <br>
                <button type="submit" class="ui blue submit button">Save</button>
        </div>
        </form>
    </div>

</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    $( document ).ready(function() {
            // $('.ui.dropdown').dropdown('set selected',['1','2']);
        });
</script>

@endsection
