<div class="modal fade modal-form" id="portletModal" tabindex="-1" aria-labelledby="portletModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="" method="post" id="modalForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="portletModalLabel">Nowy klient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fe-x"></i></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" style="display:none"></div>
                    <div class="modal-form container">
                        <div class="form-group row">
                            <div class="col-6">
                                <div class="row">
                                    <label for="inputName" class="col-12 col-form-label control-label required justify-content-start">Imię<span class="text-danger d-inline w-auto ps-1">*</span>
                                    </label>
                                    <div class="col-12">
                                        <input type="text" class="validate[required] form-control @error('title') is-invalid @enderror" id="inputName" name="name" value="" required>
                                        @if($errors->first('name'))
                                            <div class="invalid-feedback d-block">{{ $errors->first('name') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="row">
                                    <label for="inputSurname" class="col-12 col-form-label control-label required justify-content-start">Nazwisko<span class="text-danger d-inline w-auto ps-1">*</span>
                                    </label>
                                    <div class="col-12">
                                        <input type="text" class="validate[required] form-control @error('surname') is-invalid @enderror" id="inputSurname" name="surname" value="{{$entry->surname}}" required>
                                        @if($errors->first('surname'))
                                            <div class="invalid-feedback d-block">{{ $errors->first('surname') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-3">
                                <div class="row">
                                    <label for="inputEmail" class="col-12 col-form-label control-label required justify-content-start">E-mail<span class="text-danger d-inline w-auto ps-1">*</span>
                                    </label>
                                    <div class="col-12">
                                        <input type="text" class="validate[required] form-control @error('mail') is-invalid @enderror" id="inputEmail" name="mail" value="{{$entry->mail}}" required>
                                        @if($errors->first('mail'))
                                            <div class="invalid-feedback d-block">{{ $errors->first('mail') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-2 mt-3 d-none">
                                <label for="" class="col-12 col-form-label control-label required mb-2 justify-content-start">&nbsp;</label>
                                <div class="col-12 text-center">
                                    <p style="font-size: 17px"><i class="las la-arrow-left"></i> lub <i class="las la-arrow-right"></i></p>
                                </div>
                            </div>
                            <div class="col-5 mt-3 d-none">
                                <div class="row">
                                    <label for="inputPhone" class="col-12 col-form-label control-label required justify-content-start">Telefon<span class="text-danger d-inline w-auto ps-1">*</span></label>
                                    <div class="col-12">
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="inputPhone" name="phone" value="{{$entry->phone}}">
                                        @if($errors->first('phone'))
                                            <div class="invalid-feedback d-block">{{ $errors->first('phone') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-5 mt-3 d-none">
                                <div class="row">
                                    <label for="inputInvestment" class="col-12 col-form-label control-label required justify-content-start">Inwestycja</label>
                                    <div class="col-12">
                                        <select class="form-select" id="inputInvestment" name="investment_id">
                                            <option value="">Nazwa inwestycji</option>
                                            <option value="">Nazwa inwestycji</option>
                                            <option value="">Nazwa inwestycji</option>
                                            <option value="">Nazwa inwestycji</option>
                                            <option value="">Nazwa inwestycji</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @foreach ($required_rodo_rules as $r)
                                <div class="mt-3 col-12 @error('rule_'.$r->id) is-invalid @enderror">
                                    <div class="d-flex align-items-start">
                                        <input name="rule_{{$r->id}}" id="rule_{{$r->id}}" value="1" type="checkbox" @if($r->required === 1) class="validate[required]" @endif data-prompt-position="topLeft:0">
                                        <label for="rule_{{$r->id}}" class="rules-text ms-2">
                                            {!! $r->text !!}
                                            @error('rule_'.$r->id)
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                    <button type="submit" class="btn btn-primary">Dodaj</button>
                </div>
            </form>
        </div>
    </div>
</div>
