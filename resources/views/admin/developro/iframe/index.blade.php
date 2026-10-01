@extends('admin.layout')
@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-12 pl-0">
                    <h4 class="page-title"><i class="fe-home"></i><a
                            href="{{ route('admin.developro.investment.index') }}">Inwestycje</a><span
                            class="d-inline-flex me-2 ms-2">/</span><a
                            href="{{ route('admin.developro.investment.edit', $investment) }}">{{ $investment->name }}</a><span
                            class="d-inline-flex me-2 ms-2">/</span>Osadź na stronie</h4>
                </div>
            </div>
        </div>
        @include('admin.developro.investment_shared.menu')
        <div class="card mt-3">
            <div class="p-4 ">
                <div class="row">
                    <div class="col-md-6">
                        <p class='col-form-label pb-3 control-label'>
                            Skopiuj poniższy kod i wklej go w odpowiednie miejsce na swojej stronie internetowej.
                        </p>
                        <code id='iframe-code'>
                            &lt;style&gt;
                            iframe {
                            max-width:100%;
                            height:auto;
                            min-height: 80svh;
                            }

                            &lt;/style&gt;

                            &lt;iframe src="{{ route('front.iframe.index', $investment) }}" width="100%" height="500px"
                            frameborder="0"&gt;&lt;/iframe&gt;
                        </code>
                        <p class='mt-3'>
                            <button class="btn btn-primary" id="copy-iframe-code">Skopiuj kod</button>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="mt-5">
                            @if (Session::has('success'))
                                <p class="alert alert-success mb-3">
                                    {{ Session::get('success') }}</p>
                            @endif
                            <form action="{{ route('admin.developro.investment.iframe.store', $investment->id) }}"
                                method="POST">
                                @csrf
                                @include('form-elements.textarea', [
                                    'name' => 'custom_css',
                                    'label' => 'Własne style css',
                                    'value' => $custom_css,
                                    'rows' => 10,
                                ])
                                <div class="text-end mt-4">
                                    <button type="submit" class="btn btn-primary">Zapisz</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

            </div>
            <div class="card mt-3">
                <iframe src="{{ route('front.iframe.index', $investment) }}" width="100%" height="500px"
                    frameborder="0"></iframe>
            </div>
        </div>
    @endsection
    @push('scripts')
        <script defer>
            document.addEventListener('DOMContentLoaded', () => {
                async function copyToClipboard(text) {
                    try {
                        await navigator.clipboard.writeText(text);
                        toastr.success('Skopiowano kod do schowka');
                    } catch (err) {
                        toastr.error('Nie udało się skopiować kodu');
                    }
                }

                const copyButton = document.getElementById('copy-iframe-code');
                const iframeCode = document.getElementById('iframe-code');
                copyButton.addEventListener('click', async () => {
                    await copyToClipboard(iframeCode.innerText);
                });
            })
        </script>
    @endpush
