@extends('front.auth.client.layouts.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-12 pl-0">
                    <h4 class="page-title"><i class="fe-inbox"></i>Pliki</h4>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body card-body-rem">
                <div id="files">
                    <div class="note">
                        <div class="noteItemIcon"><i class="fe-hard-drive"></i></div>
                        <div class="noteItemContent">
                            @foreach($files as $file)
                                <div class="file" data-file-id="{{$file->id}}">
                                    <div class="noteItemType"><i class="{{mime2icon($file->mime)}}"></i></div>
                                    <div class="noteItemText">
                                        <a href="{{ asset('/uploads/storage/'.$file->file) }}" target="_blank">{{$file->name}}</a>
                                        <p>{{$file->description}}</p>
                                    </div>
                                    <div class="noteItemDate">{{$file->created_at->diffForHumans()}}<span class="separator">·</span>{{$file->user()->first()->name}} {{$file->user()->first()->surname}}<span class="separator">·</span>{{parseFilesize($file->size)}}</div>
                                    <div class="noteItemButtons">
                                        <a role="button" data-bs-toggle="dropdown" aria-expanded="false" class="dropdown-menu-dots"><i class="fe-more-horizontal-"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item dropdown-item-download" href="{{ asset('/uploads/user_files/'.$file->file) }}" download>Pobierz</a></li>
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="note-start">
                        <div class="noteItemDate">{{$client->created_at}}</div>
                        <div class="noteItemClient"><strong>Klient dodany do systemu</strong></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
