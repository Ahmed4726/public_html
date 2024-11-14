@extends('layouts.admin.panel')
@section('breadcrumb')
<div class="ui breadcrumb">
  <div class="active section">{{ trans('laralum.user_list') }}</div>
</div>
@endsection
@section('title', trans('laralum.user_list'))
@section('icon', "users")
@section('subtitle', trans('laralum.users_subtitle'))

@section('createButton')
<a href="{{route('Laralum::users_create')}}" class='large ui green right floated button white-text'>Create User</a>
@endsection

@section('content')

<div class="ui one column doubling stackable grid container">

  <div class="column">
    <form>
      <div class="ui action input container grid">
        <input type="text" class="three wide column " placeholder="Search by name / email..." name="search"
          value="{{ Request::get('search') }}">

        <select class="ui compact selection two wide column dropdown" name="faculty_id">
          <option value="">Faculty</option>
          @foreach($faculties as $faculty)
          <option value="{{$faculty->id}}" @if(Request::get('faculty_id')==$faculty->id) selected
            @endif>{{$faculty->name}}</option>
          @endforeach
        </select>

        <select class="ui compact selection dropdown two wide column " name="department_id">
          <option value="">Department</option>
          @foreach($departments as $department)
          <option value="{{$department->id}}" @if(Request::get('department_id')==$department->id) selected
            @endif>{{$department->name}}</option>
          @endforeach
        </select>

        <select class="ui compact selection dropdown two wide column " name="center_id">
          <option value="">Center</option>
          @foreach($centers as $center)
          <option value="{{$center->id}}" @if(Request::get('center_id')==$center->id) selected @endif>{{$center->name}}
          </option>
          @endforeach
        </select>

        <select class="ui compact selection dropdown two wide column " name="hall_id">
          <option value="">Hall</option>
          @foreach($halls as $hall)
          <option value="{{$hall->id}}" @if(Request::get('hall_id')==$hall->id) selected @endif>{{$hall->name}}</option>
          @endforeach
        </select>

        <select class="ui compact selection dropdown two wide column " name="role_id">
          <option value="">Role</option>
          @foreach($roles as $role)
          <option value="{{$role->id}}" @if(Request::get('role_id')==$role->id) selected @endif>{{$role->type}}</option>
          @endforeach
        </select>

        <select class="ui compact selection dropdown" name="state_id">
          <option value="">Status</option>
          @foreach($types as $type)
          <option value="{{$type->id}}" @if(Request::get('state_id')==$type->id) selected @endif>{{$type->name}}
          </option>
          @endforeach
        </select>

        <button class="ui violet button" type="submit"><i class="icon search"></i>Search</button>
      </div>
    </form>
    <br />
    <a class="float-right" href="{{route('Laralum::users')}}">Clear Search</a>
  </div>

  <div class="column">
    <div class="ui very padded segment">
      <table class="ui selectable striped celled small table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Login At</th>
            <th>IP</th>
            <th>Device</th>
            <th>Roles</th>
            <th>Options</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $user)
          <tr>
            <td>
              {{ $user->name }}
              @if($user->isAdmin())
              <div class="ui red tiny left pointing basic label pop" data-title="Admin" data-variation="wide"
                data-content="The user is admin" data-position="top center">Admin</div>
              @endif
            </td>
            <td>
              @if($user->stateInfo()->exists())
              @if($user->stateInfo->name == 'ACTIVE')
              <i data-position="top center" data-content="{{$user->stateInfo->name}}"
                class="pop green checkmark icon"></i>
              @else
              <i data-position="top center" data-content="{{$user->stateInfo->name}}"
                class="pop orange warning icon"></i>
              @endif
              @endif
              {{ $user->email }}
            </td>
            <td>
              @if($user->last_login_at)
              {{$user->last_login_at->format('Y-m-d g:i A')}}
              @endif
            </td>
            <td>
              {{$user->last_login_ip}}
            </td>
            <td>
              @if($user->last_login_device)
              <label class="label ui basic">{{$user->last_login_device}}</label>
              @endif
            </td>
            <td>
              @foreach($user->roles->pluck('type') as $role)
              {{$role}}<br />
              @endforeach
            </td>
            <td>
              @if(!$user->isAdmin())
              <div class="ui blue top icon left pointing dropdown button">
                Edit
                <div class="menu">
                  <div class="header">{{ trans('laralum.editing_options') }}</div>
                  <a href="{{ route('Laralum::users_edit', ['user' => $user->id]) }}" class="item">
                    <i class="edit icon"></i>
                    Basic Edit
                  </a>
                  <div class="header">Advanced Options</div>

                  <a href="{{ route('Laralum::users_roles', ['id' => $user->id]) }}" class="item">
                    <i class="star icon"></i>
                    {{ trans('laralum.users_edit_roles') }}
                  </a>
                  <a href="{{ route('Laralum::users_force_login', ['id' => $user->id]) }}" class="item">
                    <i class="user secret icon"></i>
                    Force Login
                  </a>
                  <a href="/{{$user->id}}/delete" class="item delete">
                    <i class="trash alternate outline icon"></i>Delete
                  </a>
                </div>
              </div>
              @else
              <div class="ui disabled blue icon button">
                <i class="lock icon"></i>
              </div>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      {{ $users->links() }}
    </div>
    <br>
  </div>
</div>

{{-- Delete Confirmation Modal Start --}}
<div class="ui mini modal">
  <div class="ui icon header">
    <i class="archive icon"></i>
    Are you sure you want to delete this item?
  </div>

  <div class="actions">
    <div class="ui negative button">
      No
    </div>
    <div class="ui positive right labeled icon button">
      Yes
      <i class="checkmark icon"></i>
    </div>
  </div>
</div>
{{-- Delete Confirmation Modal End --}}

@endsection


@section('js')

{{-- Script For Delete Interaction Start --}}
<script type="text/javascript">
  $(document).ready( function() {
      $('.delete').on("click", function (e) {
          e.preventDefault();
          var _this = $(this);

          $('.mini.modal').modal({
              onApprove: function() {
                  window.location.href = "{{route('Laralum::users')}}"+_this.attr('href');
              }
          }).modal('show');
      });
  });

</script>
{{-- Script For Delete Interaction End --}}

@endsection