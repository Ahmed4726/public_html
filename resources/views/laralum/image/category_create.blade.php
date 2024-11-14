@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Gallery Category Create</div>
    </div>
@endsection
@section('title', 'Gallery Category Create')
@section('icon', "plus")
@section('subtitle', 'Gallery Category Create')
@section('content')

    <div class="ui doubling stackable grid container" id="vue-app">
        <div class="row">
        <div class="nine wide column">
            <form class="ui form" method="POST">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}

                        <div class="field required">
                            <label>Name</label>
                            <input type="text"  name="name" placeholder="Name" required>
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
                                    <input type="number" name="max_image_size_in_kb" placeholder="Max Size...">
                                    <div class="ui basic label">
                                        kb
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="field">
                            <label>Help Text</label>
                            <input type="text"  name="help_text" placeholder="Help Text">
                        </div>

                        <div class="field">
                            <label>Department</label>
                        </div>

                        <div class="inline field">
                            <div class="ui slider checkbox">
                                <input type="checkbox" name="enabled" tabindex="0" class="hidden" v-model="global"  style="z-index: 2">
                                <label>Global</label>
                            </div>
                        </div>

                        <div class="field" v-if="!global">
                            <select name="department_id">
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->short_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field">
                            <label>Description</label>
                            <textarea name="description"></textarea>
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
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>



    <script type="text/javascript">

        new Vue({
            el : "#vue-app",
            data : {
                global : true,
            },

            methods : {
            }
        });
    </script>
@endsection
