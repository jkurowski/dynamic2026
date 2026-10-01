@extends('layouts.page')

@section('meta_title', 'Inline Editor')

@section('content')
    <div id="inline" class="container pt-5 pb-5">
        <div class="row">
            <div class="col-12 inline inline-tc">
                <h2 data-modaltytul="1">{{ getInline($array, 1, 'modaltytul') }}</h2>
                <div data-modaleditor="1">
                    <p>{{ getInline($array, 1, 'modaleditor') }}</p>
                </div>

                <div class="inline-btn"><button type="button" class="btn btn-primary btn-modal btn-sm" data-toggle="modal" data-target="#inlineModal" data-inline="1" data-hideinput="modaleditortext,modallink,modallinkbutton,file,file_alt" data-method="update" data-imgwidth="100" data-imgheight="100"></button></div>
            </div>

            <div class="col-12 mt-5 mb-5"><hr></div>

            <div class="col-12 inline inline-tc">
                <div class="row">
                    <div class="col-4"><img src="{{ getInline($array, 2, 'file') }}" alt="{{ getInline($array, 2, 'file_alt') }}" data-img="2"></div>
                    <div class="col-8">
                        <h2 data-modaltytul="2">{{ getInline($array, 2, 'modaltytul') }}</h2>
                        <div data-modaleditor="2">
                            <p>{{ getInline($array, 2, 'modaleditor') }}</p>
                        </div>
                    </div>
                </div>
                <div class="inline-btn"><button type="button" class="btn btn-primary btn-modal btn-sm" data-toggle="modal" data-target="#inlineModal" data-inline="2" data-hideinput="modaleditortext,modallink,modallinkbutton" data-method="update" data-imgwidth="600" data-imgheight="400"></button></div>
            </div>

            <div class="col-12 mt-5 mb-5"><hr></div>

            <div class="col-12 inline inline-tc">
                <div class="row">
                    <div class="col-4"><img src="{{ getInline($array, 3, 'file') }}" alt="{{ getInline($array, 3, 'file_alt') }}" data-img="3"></div>
                    <div class="col-8">
                        <h2 data-modaltytul="3">{{ getInline($array, 3, 'modaltytul') }}</h2>
                        <div class="mb-4">
                            <p data-modaleditor="3">{{ getInline($array, 3, 'modaleditor') }}</p>
                        </div>
                        <a href="{{ getInline($array, 3, 'modallink') }}" class="btn btn-info" data-modalbutton="3" data-modallink="3">{{ getInline($array, 3, 'modallinkbutton') }}</a>
                    </div>
                </div>
                <div class="inline-btn"><button type="button" class="btn btn-primary btn-modal btn-sm" data-toggle="modal" data-target="#inlineModal" data-inline="3" data-hideinput="modaleditortext" data-method="update" data-imgwidth="600" data-imgheight="400"></button></div>
            </div>
        </div>
    </div>
@endsection

@include('inline.modal')
