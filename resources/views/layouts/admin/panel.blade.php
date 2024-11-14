<!DOCTYPE html>

<html lang="en">

<head>
	<meta charset="utf-8">

	<title>@yield('title') - Jahangirnagar University</title>
	<meta name="description" content="Laralum - Laravel administration panel">
	<meta name="author" content="Èrik Campobadal Forés">
	<link rel='shortcut icon' href="{{ asset(Laralum::publicPath() . '/favicon/laralum.ico') }}" type='image/x-icon' />

	{!! Laralum::includeAssets('laralum_header') !!}

	<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js'></script>

	@yield('css')

	<!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->

</head>

<body>

	<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
		{{ csrf_field() }}
	</form>

	<div class="ui inverted dimmer">
		<div class="ui text loader">Loading</div>
	</div>


	@if(session('success'))
	<script>
		swal({
				title: "Nice!",
				text: "{!! session('success') !!}",
				type: "success",
				confirmButtonText: "Cool"
			});
	</script>
	@endif
	@if(session('error'))
	<script>
		swal({
				title: "Whops!",
				text: "{!! session('error') !!}",
				type: "error",
				confirmButtonText: "Ok"
			});
	</script>
	@endif
	@if(session('warning'))
	<script>
		swal({
				title: "Watch out!",
				text: "{!! session('warning') !!}",
				type: "warning",
				confirmButtonText: "Ok"
			});
	</script>
	@endif
	@if(session('info'))
	<script>
		swal({
				title: "Watch out!",
				text: "{!! session('info') !!}",
				type: "info",
				html : true,
				confirmButtonText: "Ok"
			});
	</script>
	@endif
	@if (count($errors) > 0)
	<script>
		swal({
				title: "Whops!",
				text: "<?php foreach($errors->all() as $error){ echo "$error<br>"; } ?>",
				type: "error",
				confirmButtonText: "Okai",
				html: true
			});
	</script>
	@endif


	<div class="ui sidebar left-menu ">
		<header>
			<div class="ui left fixed vertical menu" id="vertical-menu">
				<div id="vertical-menu-height">
					<a href="{{ route('Laralum::dashboard') }}" class="item logo-box">
						<div class="logo-container">
							<img class="logo-image ui fluid image" src="{{ Laralum::laralumLogo() }}">
						</div>
					</a>

					@can('ADMIN')
					<div class="item">
						<div class="header">User</div>
						<div class="menu">
							<a href="{{ route('Laralum::users') }}" class="item active selection">User List</a>
							<a href="{{ route('Laralum::users::type::list') }}" class="item active selection">User
								Type</a>
						</div>
					</div>
					@endcan

					@can('ADMIN')
					<div class="item">
						<div class="header">Administration</div>
						<div class="menu">
							<a href="{{ route('Laralum::administration::list') }}"
								class="item active selection">Administrative Member</a>
							<a href="{{ route('Laralum::administration::role::list') }}"
								class="item active selection">Administrative Role</a>
							<a href="{{ route('Laralum::administration::role::type::list') }}"
								class="item active selection">Administrative Role Type</a>
						</div>
					</div>
					@endcan

					@can('ADMIN')
					<div class="item">
						<div class="header">Custom Page</div>
						<div class="menu">
							<a href="{{ route('Laralum::custom::page::list') }}" class="item active selection">Custom
								Page</a>
						</div>
					</div>
					@endcan

					@can('DEAN')
					<div class="item">
						<div class="header">Faculty</div>
						<div class="menu">
							<a href="{{ route('Laralum::faculty::list') }}" class="item active selection">Faculty
								List</a>
						</div>
					</div>
					@endcan

					@can('DEPARTMENT')
					<div class="item">
						<div class="header">Department</div>
						<div class="menu">
							<a href="{{ route('Laralum::department::list') }}" class="item active selection">Department
								List</a>
						</div>
					</div>
					@endcan


					@can('TEACHER')
					<div class="item">
						<div class="header">Teacher</div>
						<div class="menu">
							<a href="{{ route('Laralum::teacher::list') }}"
								class="item active selection">@if(Auth::user()->hasRole('TEACHER'))My Profile @else
								Teachers @endif</a>

							@can('ADMIN')
							<a href="{{ route('Laralum::teacher::designation::list') }}"
								class="item active selection">Designation</a>
							<a href="{{ route('Laralum::teacher::status::list') }}"
								class="item active selection">Status</a>
							<a href="{{ route('Laralum::teacher::publication::type::list') }}"
								class="item active selection">Publication Type</a>
							@endcan

						</div>
					</div>
					@endcan

					@can('HALL')
					<div class="item">
						<div class="header">Accommodation</div>
						<div class="menu">
							<a href="{{ route('Laralum::hall::list') }}" class="item active selection">Hall List</a>
						</div>
					</div>
					@endcan

					@can('CENTER')
					<div class="item">
						<div class="header">Centers / Offices</div>
						<div class="menu">
							<a href="{{ route('Laralum::center::list') }}" class="item active selection">Centers /
								Offices List</a>
							@can('ADMIN')<a href="{{ route('Laralum::center::type::list') }}"
								class="item active selection">Centers / Offices Type</a>@endcan
						</div>
					</div>
					@endcan

					@can('ADMIN')
					<div class="item">
						<div class="header">Employee</div>
						<div class="menu">
							{{--							<a href="{{ route('Laralum::officer::list') }}" class="item">Officers / Staffs
							List</a>--}}
							<a href="{{ route('Laralum::officer::type::list') }}" class="item active selection">Employee
								Type</a>
						</div>
					</div>
					@endcan

					@can('ADMIN')
					<div class="item">
						<div class="header">Program / Calender</div>
						<div class="menu">
							<a href="{{ route('Laralum::program::list') }}" class="item active selection">Program /
								Calender List</a>
							<a href="{{ route('Laralum::program::type::list') }}" class="item active selection">Program
								/ Calender Type</a>
						</div>
					</div>
					@endcan

					@can('ADMIN')
					<div class="item">
						<div class="header">Facility</div>
						<div class="menu">
							<a href="{{ route('Laralum::facility::list') }}" class="item active selection">Facility
								List</a>
						</div>
					</div>
					@endcan

					@can('ADMIN')
					<div class="item">
						<div class="header">Research</div>
						<div class="menu">
							{{--							<a href="{{ route('Laralum::research::list') }}" class="item">Research
							List</a>--}}
							<a href="{{ route('Laralum::research::type::list') }}"
								class="item active selection">Research Type</a>
						</div>
					</div>
					@endcan

					@can('EVENT')
					<div class="item">
						<div class="header">All Notice</div>
						<div class="menu">
							<a href="{{ route('Laralum::event::list') }}" class="item active selection">All Notice
								List</a>
							@can('ADMIN')<a href="{{ route('Laralum::event::type::list') }}"
								class="item active selection">All Notice Type List</a>@endcan
						</div>
					</div>
					@endcan

					@if(Gate::check('DEPARTMENT') || Gate::check('DEAN'))
					<div class="item">
						<div class="header">Journal</div>
						<div class="menu">
							<a href="{{ route('Laralum::journal::list') }}" class="item active selection">Journal</a>
						</div>
					</div>
					@endif

					@can('ADMIN')
					<div class="item">
						<div class="header">Menu</div>
						<div class="menu">
							<a href="{{ route('Laralum::menu::list') }}" class="item active selection">Menu</a>
						</div>
					</div>
					@endcan

					@can('NOC')
					<div class="item">
						<div class="header">NOC & GO</div>
						<div class="menu">
							<a href="{{ route('Laralum::certificate::list') }}" class="item active selection">NOC &
								GO</a>
							@can('ADMIN')<a href="{{ route('Laralum::certificate::type::list') }}"
								class="item active selection">NOC & GO Type</a>@endcan
						</div>
					</div>
					@endcan

					@can('ADMIN')
					<div class="item">
						<div class="header">Link</div>
						<div class="menu">
							<a href="{{ route('Laralum::link::list') }}" class="item active selection">Link List</a>
							<a href="{{ route('Laralum::link::type::list') }}" class="item active selection">Link
								Type</a>
						</div>
					</div>
					@endcan

					@can('ADMIN')
					<div class="item">
						<div class="header">Uploaded File</div>
						<div class="menu">
							<a href="{{ route('Laralum::file::list') }}" class="item active selection">Uploaded File</a>
						</div>
					</div>
					@endcan

					@can('INTERNET-CONNECTION-MANAGER')
					<div class="item">
						<div class="header">Internet Connection</div>
						<div class="menu">
							<a href="{{ route('Laralum::internet-connection::list') }}" class="item active selection">
								Connection List
							</a>
						</div>
					</div>
					@endcan

					@can('INTERNET-STAFF')
					<div class="item">
						<div class="header">Internet Complain</div>
						<div class="menu">
							<a href="{{ route('Laralum::internet-complain::list') }}" class="item active selection">
								Complain List
							</a>
							@can('ADMIN')
							<a href="{{ route('Laralum::internet-complain::category::list') }}"
								class="item active selection">
								Category
							</a>
							{{-- <a href="{{ route('Laralum::lineman::list') }}" class="item active
							selection">Lineman</a> --}}
							<a href="{{ route('Laralum::internet-complain::team::list') }}"
								class="item active selection">Team</a>
							<a href="{{ route('Laralum::internet-complain::user-type::list') }}"
								class="item active selection">User Type</a>
							@endcan
						</div>
					</div>
					@endcan

					@can('ADMIN')
					<div class="item">
						<div class="header">Employee Email</div>
						<div class="menu">
							<a href="{{ route('Laralum::employee-email::list') }}" class="item active selection">Email
								List</a>
							{{-- <a href="{{ route('Laralum::employee-email::type::list') }}"
							class="item active selection">Employee Type</a> --}}
						</div>
					</div>
					@endcan

					@can('STUDENT-EMAIL-VIEW')
					<div class="item">
						<div class="header">Student Email Apply</div>
						<div class="menu">
							<a href="{{ route('Laralum::student-email-apply::list') }}"
								class="item active selection">Email List</a>
							@can('STUDENT-EMAIL-MANAGE')
							<a href="{{ route('Laralum::student-email-apply::admission-session::list') }}"
								class="item active selection">Admission Session</a>
							@endcan
							@can('ADMIN')
							<a href="{{ route('Laralum::student-email-apply::program::list') }}"
								class="item active selection">Program</a>
							@endcan
						</div>
					</div>
					@endcan

					@can('ADMIN')
					<div class="item">
						<div class="header">Student List</div>
						<div class="menu">
							<a href="{{ route('Laralum::student::list') }}" class="item active selection">
								Student List
							</a>
							<a href="{{ route('Laralum::student::upload') }}" class="item active selection">
								Bulk Create
							</a>
						</div>
					</div>
					@endcan

					@can('ADMIN')
					<div class="item">
						<div class="header">Setting</div>
						<div class="menu">
							<a href="{{ route('Laralum::setting::edit') }}" class="item active selection">Setting</a>
						</div>
					</div>
					@endcan

					@can('ADMIN')
					<div class="item">
						<div class="header">E-mail Broadcast</div>
						<div class="menu">
							<a href="{{ route('Laralum::email::broadcast') }}" class="item active selection">Email
								Broacast</a>
						</div>
					</div>
					@endcan

					@can('ADMIN')
					<div class="item">
						<div class="header">Gallery</div>
						<div class="menu">
							<a href="{{ route('Laralum::gallery::image::list') }}" class="item active selection">Gallery
								Image List</a>
							<a href="{{ route('Laralum::gallery::category::list') }}"
								class="item active selection">Gallery Image Type List</a>
						</div>
					</div>
					@endcan

				</div>
		</header>
	</div>




	<div class="ui top fixed menu" id="menu-div">

		<div class="item" id="menu">
			<div class="ui secondary button"><i class="bars icon"></i> {{ trans('laralum.menu') }}</div>
		</div>

		<div class="item" id="breadcrumb" style="margin-left: 210px !important;">
			@yield('breadcrumb')
		</div>

		<div class="right menu">

			<div class="item">
				<div class="ui blue top labeled icon left pointing dropdown button responsive-button">
					<i class="user icon"></i>
					<span class="text responsive-text">{{ Auth::user()->name }}</span>

					<div class="menu">
						<a href="{{ url('/') }}" class="item">
							{{ trans('laralum.visit_site') }}
						</a>
						<a href="{{ route('Laralum::change-password') }}" class="item">Change Password</a>
						<a href="{{ url('/logout') }}"
							onclick="event.preventDefault();document.getElementById('logout-form').submit();"
							class="item">
							{{ trans('laralum.logout') }}
						</a>
					</div>

				</div>
			</div>
		</div>

	</div>




	<div class="pusher back">
		<div class="menu-margin">

			<div class="content-title" style="background-color: #1678c2;">
				<div class="menu-pusher">
					<div class="ui one column doubling stackable grid container">
						<div class="column">
							<h2 class="ui header left floated">
								<i class="@yield('icon') icon white-text"></i>
								<div class="content white-text">
									{{--									@yield('title')--}}
									<div class="sub header">
										<span class="white-text">@yield('subtitle')</span>
									</div>
								</div>
							</h2>
							@yield('createButton')
						</div>
					</div>
				</div>
			</div>


			<div class="page-content">
				<div class="menu-pusher">
					@yield('content')
				</div>
			</div>

			<br><br>
			<div class="page-footer">
				<div class="ui bottom fixed padded segment">
					<div class="menu-pusher">
						<div class="ui container">
							<a href="" class="ui tiny header">
								Jahangirnagar University
							</a>
							<a class="ui tiny header right floated" href=''>&copy; Copyright Jahangirnagar
								University</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>



	@stack('js')
	@yield('js')

	{!! Laralum::includeAssets('laralum_bottom') !!}

	<script>
		setInterval(function(){
			var footer = $('.page-footer');
			footer.removeAttr("style");
			var footerPosition = footer.position();
			var docHeight = $( document ).height();
			var winHeight = $( window ).height();
			if(winHeight == docHeight) {
				if((footerPosition.top + footer.height() + 3) < docHeight) {
					var topMargin = (docHeight - footer.height()) - footerPosition.top;
					footer.css({'margin-top' : topMargin + 'px'});
				}
			}
		}, 10);
	</script>

</body>

</html>