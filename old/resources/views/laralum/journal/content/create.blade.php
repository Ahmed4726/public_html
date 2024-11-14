@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::journal::content::list', ['journal' => $journal->id]) }}">Journal Content List</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Journal Content Create</div>
    </div>
@endsection
@section('title', 'Journal Content Create')
@section('icon', "plus")
@section('subtitle', $journal->title)
@section('content')

    <div class="ui doubling stackable grid container">
        <div class="row">
            <div class="two wide column"></div>
            <div class="twelve wide column" id="vue-app">
                <form class="ui form" method="POST" action="{{ route('Laralum::journal::content::store', ['journal' => $journal->id]) }}" enctype="multipart/form-data">
                    <div class="ui very padded segment">
                        {{ csrf_field() }}

                        <div class="field required">
                            <label>Title</label>
                            <input type="text"  name="title" placeholder="Title..." required>
                        </div>

                        <div class="field">
                            <label>Author</label>
                            <input type="text"  name="author" placeholder="Author...">
                        </div>

                        <div class="field">
                            <label>Co-Author</label>
                            <input type="text"  name="co_author" placeholder="Co-Author...">
                        </div>

                        <div class="field">
                            <label>Volume</label>
                            <input type="text"  name="volume" placeholder="Volume...">
                        </div>

                        <div class="field">
                            <label>External Link</label>
                            <input type="text"  name="external_link" placeholder="External Link...">
                        </div>

                        <div class="field">
                            <label>Sorting Number</label>
                            <input type="number"  name="sorting_order" placeholder="Sorting Number...">
                        </div>

                        <div class="field">
                            <label>Upload Journal File</label>
                            <input type="file"  name="file" placeholder="Upload Journal File...">
                        </div>

                        <br>
                        <button type="submit" class="ui blue submit button">Save</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
