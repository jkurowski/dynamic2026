<div class="modal fade" id="paymentEditModal" tabindex="-1" aria-labelledby="paymentEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="post" id="modalForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentEditModalLabel">Edytuj płatność</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fe-x"></i></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" style="display:none"></div>
                    <div class="modal-form container">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group row">
                                    <label for="inputDate" class="col-5 col-form-label control-label required text-end">Termin płatności</label>
                                    <div class="col-7">
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon1"><i class="las la-calendar"></i></span>
                                            <input type="text"
                                                   value="{{$payment->due_date}}"
                                                   class="validate[required] form-control @error('due_date') is-invalid @enderror date-picker"
                                                   id="inputDate" name="due_date">
                                        </div>
                                        @if($errors->first('due_date'))
                                            <div class="invalid-feedback d-block">{{ $errors->first('due_date') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPercent" class="col-5 col-form-label control-label required text-end">Procent</label>
                                    <div class="col-7">
                                        <div class="input-group">
                                            <input type="text"
                                                   value="{{$payment->percent}}"
                                                   class="validate[required] form-control @error('percent') is-invalid @enderror"
                                                   id="inputPercent" name="percent">
                                        </div>
                                        @if($errors->first('percent'))
                                            <div class="invalid-feedback d-block">{{ $errors->first('percent') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputAmount" class="col-5 col-form-label control-label required text-end">Wartość w zł</label>
                                    <div class="col-7">
                                        <div class="input-group">
                                            <input type="text"
                                                   value="{{$payment->amount}}"
                                                   class="validate[required] form-control @error('amount') is-invalid @enderror"
                                                   id="inputAmount" name="amount">
                                        </div>
                                        @if($errors->first('amount'))
                                            <div class="invalid-feedback d-block">{{ $errors->first('amount') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputStatus" class="col-5 col-form-label control-label required text-end">Zapłacono</label>
                                    <div class="col-7">
                                        <select class="form-select" id="inputStatus" name="status">
                                            <option value="0" @if($payment->status == 0) selected @endif>Nie</option>
                                            <option value="1" @if($payment->status == 1) selected @endif>Tak</option>
                                        </select>
                                        @if($errors->first('status'))
                                            <div class="invalid-feedback d-block">{{ $errors->first('status') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPaid" class="col-5 col-form-label control-label required text-end">Data wpłaty</label>
                                    <div class="col-7">
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon1"><i class="las la-calendar"></i></span>
                                            <input type="text"
                                                   value="{{$payment->paid_at}}"
                                                   class="validate[required] form-control @error('paid_at') is-invalid @enderror date-picker"
                                                   id="inputPaid" name="paid_at">
                                        </div>
                                        @if($errors->first('paid_at'))
                                            <div class="invalid-feedback d-block">{{ $errors->first('paid_at') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="event_id" value="{{ $payment->id }}" id="inputPaymentId">
                    <input type="hidden" name="property_id" value="{{ $property_id }}" id="inputPropertyId">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                    <button type="submit" class="btn btn-primary">Zapisz</button>
                </div>
            </form>
        </div>
    </div>
</div>