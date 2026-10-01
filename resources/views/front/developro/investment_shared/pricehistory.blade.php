<div class="modal fade modal-form" id="portletModal" tabindex="-1" aria-labelledby="portletModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="modal-form">
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="text-center"></th>
                            <th class="text-center">Cena przed zmianą<br>(brutto)</th>
                            <th class="text-center"></th>
                            <th class="text-center">Cena po zmianie<br>(brutto)</th>
                            <th class="text-center">Powierzchnia<br>(m2)</th>
                            <th class="text-center">Data zmiany</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($property->priceHistory as $entry)
                            <tr>
                                <td valign="middle">
                                    @if($entry->price_brutto < $entry->new_price_brutto)
                                        <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                                             fill="none" stroke="#ae1515" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-arrow-up-right">
                                            <line x1="7" y1="17" x2="17" y2="7" />
                                            <polyline points="7 7 17 7 17 17" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                             viewBox="0 0 24 24" stroke="#00a304" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-arrow-down-right">
                                            <line x1="7" y1="7" x2="17" y2="17"></line>
                                            <polyline points="17 7 17 17 7 17"></polyline>
                                        </svg>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <strong style="font-weight: 700">@money($entry->price_brutto)</strong><br>
                                    <span style="font-size: 12px">@money($entry->price_brutto / $entry->area ) / m2</span>
                                </td>
                                <td>-></td>
                                <td class="text-center">
                                    @if($entry->promotion) <span style="font-size: 11px;color:red;display:block">(Promocja)</span> @endif
                                    <strong style="font-weight: 700">@money($entry->new_price_brutto)</strong><br>
                                    <span style="font-size: 12px">@money($entry->new_price_brutto / $entry->area ) / m2</span>
                                </td>
                                <td class="text-center" valign="middle">
                                    {{ $entry->area }}
                                </td>
                                <td class="text-center" valign="middle">
                                    {{ $entry->changed_at }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
            </div>
        </div>
    </div>
</div>
