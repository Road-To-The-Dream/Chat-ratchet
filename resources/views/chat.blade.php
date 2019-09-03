@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-6">
                <div class="col users border scrollbar" id="messages-canvas">
                </div>
            </div>

            <div class="col-3 members-online">
                <div><label>Members online:</label></div>
            </div>

            <chat-component></chat-component>
            {{--            @if(isset($users))--}}
            {{--                <div class="col-3" id="all-members">--}}
            {{--                    <label>All members:</label>--}}
            {{--                    @foreach($users as $user)--}}
            {{--                        <div class="shadow pl-3 pr-3 mb-1 bg-white rounded">--}}
            {{--                            <div class="row border-bottom mb-2">--}}
            {{--                                <div class="col">--}}
            {{--                                    <div class="row mb-2">--}}
            {{--                                        <div class="col-3 p-0">--}}
            {{--                                            <img src="{{$user->gravatar_img}}" alt="" class="rounded-circle">--}}
            {{--                                        </div>--}}
            {{--                                        <div class="col-6 align-self-end">{{$user->name}}</div>--}}
            {{--                                        <div class="col-3 text-right align-self-end">{{$user->role}}</div>--}}
            {{--                                    </div>--}}
            {{--                                </div>--}}
            {{--                            </div>--}}
            {{--                            <div class="row user-status">--}}
            {{--                                <div class="col-6">--}}
            {{--                                    <button id="isBan" type="button" class="block btn btn-danger btn-sm mb-1"--}}
            {{--                                            data-value="{{$user->isBan}}" data-wait="{{$user->isBan ? 'Ban' : 'UnBan'}}"--}}
            {{--                                            data-userid="{{$user->id}}">{{$user->isBan ? 'UnBan' : 'Ban'}}--}}
            {{--                                    </button>--}}
            {{--                                </div>--}}
            {{--                                <div class="col-6">--}}
            {{--                                    <button id="isMute" type="button" class="block btn btn-warning btn-sm"--}}
            {{--                                            data-value="{{$user->isMute}}"--}}
            {{--                                            data-wait="{{$user->isMute ? 'Mute' : 'UnMute'}}"--}}
            {{--                                            data-userid="{{$user->id}}">{{$user->isMute ? 'UnMute' : 'Mute'}}--}}
            {{--                                    </button>--}}
            {{--                                </div>--}}
            {{--                            </div>--}}
            {{--                        </div>--}}
            {{--                    @endforeach--}}
            {{--                </div>--}}
            {{--            @endif--}}
        </div>

        <div class="row">
            <div class="col-6 block-typing">
                <input class="form-control mt-3" id="input-message" type="text" placeholder="Введите сообщение"
                       autofocus>
                {{--                @if(!Auth::user()->isMuted())--}}
                {{--                    <input class="form-control mt-3" id="input-message" type="text" placeholder="Введите сообщение"--}}
                {{--                           autofocus>--}}
                {{--                @else--}}
                {{--                    <p class="mt-2 text-danger message-mute">Админ замутил вас !</p>--}}
                {{--                @endif--}}

                <div class="row">
                    <div class="col mt-3">
                        <label id="typing-message"></label>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
