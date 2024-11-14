@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        @if(isset($uri)) <a class="section" href="{{ route("Laralum::$uri::list") }}">{{ucfirst($uri)}} List</a>
        <i class="right angle icon divider"></i>
        <a class="section" href="{{ route("Laralum::$uri::advance", [$uri => $uriValue]) }}">Advanced Option</a>
        <i class="right angle icon divider"></i>@endif
        <div class="active section">Gallery Image List</div>
    </div>
@endsection
@section('title', 'Gallery Image List')
@section('icon', "list")
@section('subtitle', 'Gallery Image List')

@section('createButton')
    @if(isset($uri)) <a href="{{route("Laralum::$uri::gallery::image::create", [$uri => $uriValue])}}" class='large ui green right floated button white-text'>Create Gallery Image</a>
    @else <a href="{{route('Laralum::gallery::image::create')}}" class='large ui green right floated button white-text'>Create Gallery Image</a> @endif
@endsection

@section('content')

    <div class="ui one column doubling stackable grid container" id="vue-app" >

        <div class="column">
            <form>
                <div class="ui fluid action input">
                    <input type="text" placeholder="Search by title..." class="" v-model="search" name="search" value="{{ Request::get('search') }}">

                    <select class="ui selection dropdown" v-model="category_id" name="category_id">
                        <option value="">Please Select a Type</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @if(Request::get('category_id') == $category->id) selected @endif>{{ $category->name }}</option>
                        @endforeach
                    </select>

                    <select class="ui selection dropdown" name="status">
                        <option value="">Please Select a type</option>
                        <option value="1" @if(Request::get('status') == 1) selected @endif>Enabled</option>
                        <option value="disable" @if(Request::get('status') == 'disable') selected @endif>Disable</option>
                    </select>

                    <button class="ui violet button" type="submit"><i class="icon search"></i>Search</button>
                </div>
            </form>
            @if(isset($uri)) <a class="float-right" href="{{route("Laralum::$uri::gallery::image::list", [$uri => $uriValue])}}">Clear Search</a>
            @else <a class="float-right" href="{{route('Laralum::gallery::image::list')}}">Clear Search</a> @endif
        </div>

        <div class="column">
            <div class="ui very padded segment">

                <table class="ui selectable striped celled small table">
                    <thead>
                    <tr>
                        @if($sortable)<th>#</th>@endif
                        <th>Title</th>
{{--                        <th class="three wide">Link</th>--}}
                        <th>Department</th>
                        <th>Category Name</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody @if($sortable)id="sortable"@endif>
                        @foreach($images as $key => $image)
                            <tr data-item="{{$image->id}}">
                                @if($sortable)<td>{{$key+ $images->firstItem()}}</td>@endif
                                <td>
                                    @if(!$image->enabled)
                                        <i data-position="top center" data-content="Disabled" class="pop orange warning icon"></i>
                                    @else
                                        <i data-position="top center" data-content="Enabled" class="pop green checkmark icon"></i>
                                    @endif
                                    {!! $image->title !!}</td>
{{--                                <td><a href='{{ asset("storage/image/gallery/$image->path") }}' target="_blank">{{ $image->path }}</a></td>--}}
                                <td>@if($image->galleryImageCategories->department()->exists()){{ $image->galleryImageCategories->department->name }}<label class="ui mini label">Department</label>@else Global @endif</td>
                                <td>{{ $image->galleryImageCategories->name }}</td>
                                <td>
                                    <a href="{{ route('Laralum::gallery::image::edit', ['image' => $image->id]) }}" class="mini ui blue icon button"><i class="edit icon"></i> Edit</a>
                                    <a href='{{ asset("storage/image/gallery/$image->path") }}' target="_blank" class="mini ui green icon button"><i class="eye icon"></i> View</a>
                                    <a href='{{ route('Laralum::gallery::image::delete', ['categories' => $image->category_id ,'image' => $image->id]) }}' class="delete mini ui red icon button"><i class="delete icon"></i> Delete</a>
                                </td>
                            </tr>
                        @endforeach

                        {{--<tr v-for="(image, index) in images.data" v-if="!loading" v-cloak>
                            <td>
                                <i data-position="top center" :data-content="[(image.enabled) ? 'Enabled' : 'Disabled']" :class="[(image.enabled) ? 'green checkmark' : 'orange warning', 'pop icon']"></i>
                                <span v-html="image.title"></span>
                            </td>
                            <td><a :href="assetURL(image.path)" target="_blank">@{{ image.path }}</a></td>
                            <td>@{{ image.gellery_image_categories.name }}</td>
                            <td>
                                <div class="ui blue top icon left pointing dropdown button">
                                    <i class="configure icon"></i>
                                    <div class="menu">
                                        <div class="header">{{ trans('laralum.editing_options') }}</div>
                                        <a :href="editURL(image.id)" class="item">
                                            <i class="edit icon"></i>
                                            Edit
                                        </a>
                                        <a :href="assetURL(image.path)" target="_blank" class="item">
                                            <i class="eye icon"></i>
                                            View Image
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>--}}
                    </tbody>
                </table>
                @if($sortable) <button class="ui green button" type="button" id="applyReOrder"><i class="icon list"></i>Update Order</button><br/><br/> @endif
                {{ $images->links() }}
                {{--<vue-pagination v-if="is_pagination" :pagination="parcels" @paginate="getParcelList()"></vue-pagination>--}}
                {{--<vue-pagination :pagination="images" @paginate="getImageList()" v-cloak></vue-pagination>--}}

            </div>
            <br>
        </div>
    </div>

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
@endsection

@section('css')
    <style>
        .mini.button {
            margin: 1px !important;
        }
    </style>
@endsection

@section('js')

    <script type="text/javascript">

        $(document).ready( function() {

            $('.delete').on("click", function (e) {
                e.preventDefault();
                var _this = $(this);

                $('.mini.modal').modal({
                    onApprove: function() {
                        window.location.href = _this.attr('href');
                    }
                }).modal('show');
            });
        });

    </script>

    @if($sortable)
        @include('laralum.include.jquery-ui')
        @include('laralum.include.pnotify')
        @include('laralum.include.vue.vue-axios')

        <script type="text/javascript">

            $(document).ready( function() {

                $('td, th', '#sortable').each(function () {
                    var cell = $(this);
                    cell.width(cell.width());
                });

                // $( "#sortable" ).sortable().disableSelection();

                $( "#sortable" ).sortable( {
                    update: function( event, ui ) {
                        $(this).children().each(function(index) {
                            $(this).find('td').first().html(index + {{$images->firstItem()}})
                        });
                    }
                });

                $("#applyReOrder").click(function(){
                    var data = $("#sortable").sortable('toArray', {attribute: "data-item"});
                    axios.get("{{route('Laralum::setting::reorder')}}", {
                        params: {
                            data : data,
                            model : 'App\\GalleryImages',
                            field : 'sorting_order',
                            orderStart : {{$images->firstItem()}}
                        }
                    }).
                    then(response => {
                        if(response.status == 200) {
                            PNotify.success({title: 'Success', text: response.data})
                        }
                        else PNotify.error('Something Went Wrong!');
                    });
                });
            });

        </script>
    @endif

@endsection
