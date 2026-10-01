@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div id="portlets">
            <div class="row">
                <!-- widget -->
                <div class="col-3 mt-3">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-6 d-flex align-items-center">
                                    <div class="portlet-title">Wybierz lokal</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="modal-form container">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group row">
                                            <label for="inputClient" class="col-3 col-form-label control-label text-end">Inwestycja</label>
                                            <div class="col-9">
                                                <select name="investment_id" id="investment" class="form-select">
                                                    <option value="">Wybierz inwestycję</option>
                                                    @foreach($list as $inv)
                                                        <option value="{{ $inv->id }}">{{ $inv->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div id="dependencies" class="form-group row d-none">
                                            <label for="inputClient" class="col-3 col-form-label control-label text-end">Lokale</label>
                                            <div class="col-9"><div id="propertiesSelect"></div></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end of widget -->
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <link href="{{ asset('/js/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet">
    <script src="{{ asset('/js/bootstrap-select/bootstrap-select.min.js') }}" charset="utf-8"></script>
    <script>
        const select = document.getElementById('investment');

        select.addEventListener('change', function handleChange(event) {
            // Get investment ID from #investment select after change event.
            const investId = event.target.value;

            // Placeholder for select element
            const propertiesSelect = document.getElementById("propertiesSelect");

            // Form row with select element
            const dependencies = document.getElementById("dependencies");

            // If form row with select element is visible - hide it.
            if (!dependencies.classList.contains("d-none")) {
                dependencies.classList.add("d-none");
            }
            // Clear placeholder
            propertiesSelect.innerHTML = "";

            let postString;
            if (investId) {
                const httpRequest = new XMLHttpRequest();
                if (!httpRequest) {
                    alert("Cannot create an XMLHTTP instance");
                    return false;
                }
                httpRequest.open("POST", "{{ route('admin.ux.properties') }}", true);
                httpRequest.setRequestHeader("X-CSRF-TOKEN", "{{ csrf_token() }}");
                httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                httpRequest.onreadystatechange = function () {
                    if (this.readyState === 4 && this.status === 200) {

                        if (this.responseText.length === 2) {
                            console.log('Response is empty');
                        } else {
                            const properties = JSON.parse(this.responseText);
                            const selectList = document.createElement("select");
                            selectList.id = "properties";
                            selectList.className = "selectpicker";
                            selectList.dataset.liveSearch = "true";
                            propertiesSelect.appendChild(selectList);

                            for (const key in properties) {
                                const option = document.createElement("option");
                                option.value = key;
                                option.text = properties[key];
                                selectList.appendChild(option);
                            }
                            $('#properties').selectpicker({
                                width: '100%'
                            });
                            dependencies.classList.remove("d-none");
                        }
                    }
                };
                postString = "id=" + investId;
                httpRequest.send(postString);
            }
        });
    </script>
@endpush
