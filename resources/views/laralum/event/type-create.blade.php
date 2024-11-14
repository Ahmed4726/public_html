@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">All Notice Create</div>
    </div>
@endsection
@section('title', 'All Notice Create')
@section('icon', "plus")
@section('subtitle', "All Notice Create")
@section('content')

    <div class="ui doubling stackable grid container" id="vue-app">
        <div class="row">
            <div class="nine wide column">
                <form class="ui form" method="POST" action="{{ route('Laralum::event::type::store') }}">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}

                        <div class="field required">
                            <label>Name</label>
                            <input type="text" name="name" placeholder="Name" required>
                        </div>

                        <div class="fields">
                            <div class="five wide field">
                                <label>Image Width</label>
                                <div class="ui right labeled input">
                                    <input type="number" name="width" placeholder="Width...">
                                    <div class="ui basic label">
                                        px
                                    </div>
                                </div>
                            </div>
                            <div class="five wide field">
                                <label>Image Height</label>
                                <div class="ui right labeled input">
                                    <input type="number" name="height" placeholder="Height...">
                                    <div class="ui basic label">
                                        px
                                    </div>
                                </div>
                            </div>
                            <div class="six wide field">
                                <label>Max Image Size</label>
                                <div class="ui right labeled input">
                                    <input type="number" name="max_size" placeholder="Max Size...">
                                    <div class="ui basic label">
                                        kb
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br>
                        <button type="submit" class="ui blue submit button">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
