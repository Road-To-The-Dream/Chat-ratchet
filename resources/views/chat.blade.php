@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-9">
                <chat-message-component
                    usertoken="{{ $user->token }}"
                    allmessages="{{ $messages }}"
                    user="{{ $user }}">
                </chat-message-component>
            </div>

            @if(isset($members))
                <div class="col-3 members-online">
                    <members-component members="{{ $members }}"></members-component>
                </div>
            @endif
        </div>
    </div>
@endsection
