<div class="modal fade modal-form" id="portletModal" tabindex="-1" aria-labelledby="portletModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 p-0">
                            <button href="#" class="btn btn-primary" data-type="0">Wszystkie</button>
                            <button href="#" class="btn btn-secondary" data-type="2">Komórki lokatorskie</button>
                            <button href="#" class="btn btn-secondary" data-type="3">Miejsce parkingowe</button>
                            <button href="#" class="btn btn-secondary" data-type="6">Miejsce parkingowe z komórką lokatorską</button>
                        </div>
                        <div class="col-3 ps-0">
                            <ul class="list-group rounded-0 mt-3 mb-3" id="otherListGroup">
                                @foreach($others as $o)
                                <li class="list-group-item" data-id="{{ $o->id }}" data-type="{{ $o->type }}">
                                    <button>{{ $o->name }}</button>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-9 pe-0">
                            <div id="relatedInfo"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer ps-0 pe-0">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-6 text-start">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                        </div>
                        <div class="col-6 text-end">
                            <button type="submit" class="btn btn-primary">Dodaj</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    #relatedInfo {
        overflow: scroll;
        max-height: 360px;
        min-height: 360px;
        margin-top: 17px;
    }
    #otherListGroup {
        height: 340px;
        overflow-y: auto;
        border-top:1px solid #dee2e6
    }
    #otherListGroup li {
        padding: 0;
    }
    #otherListGroup li:first-child {
       border-top: 0;
    }
    #otherListGroup button {
        border: 0;
        width: 100%;
        background: none;
        text-align: start;
        font-size: 14px;
        padding: var(--bs-list-group-item-padding-y) var(--bs-list-group-item-padding-x);
        line-height: 1.3;
    }
    #otherListGroup button.active {
        background-color: var(--bs-primary);
        color: #fff;
    }
</style>
