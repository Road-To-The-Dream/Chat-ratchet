@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-6">
                <chat-message-component></chat-message-component>
            </div>

            <div class="col-3 members-online">
                <div><label>Members online:</label></div>
            </div>

            <div class="col-3 members-online">
                <div><label>Members online:</label></div>
            </div>
        </div>
    </div>
@endsection
