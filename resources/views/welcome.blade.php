@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    Chat Room
                </div>

                <div class="links">
                    <a href="{{ url('/chat') }}">Enter the chat</a>
                </div>
            </div>
        </div>
    </div>
@endsection
