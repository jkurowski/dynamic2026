<footer class="page-footer fs-14">
    <div class="container">
        <div class="row row-gap-3">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="mb-4 col-5">
                    <a href="/">
                        <img src="{{ asset('img/logo.svg') }}" alt="Logo" width="262" height="87" class="img-fluid footer-logo" loading="lazy" decoding="async">
                    </a>
                </div>
                <p class="text-balance">Gwarantujemy wysoką jakość świadczonych usług, zapewniamy profesjonalną, stałą współpracę z klientem oraz pewność, że powierzone nam zadania znajdują się w rękach fachowców.</p>
                <p><a class="small link-body-primary text-decoration-underline" href="/pl/polityka-prywatnosci" target="_blank">Polityka prywatności*</a></p>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <p class="text-uppercase text-secondary mb-md-4 fw-semibold fs-6">Oferta</p>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('developro.index') }}">Lokale mieszkalne</a>
                    </li>
                    <li class="mb-2">
                        <a href="/pl/wyniki-wyszukiwania?type=2">Lokale usługowe</a>
                    </li>
                    <li class="mb-2">
                        <a href="/pl/wyniki-wyszukiwania?city=&rooms=&area=&advanced=6&invest=&status=1&kitchen=&garden=&price=&type=1">Gotowe do odbioru</a>
                    </li>
                </ul>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <p class="text-uppercase text-secondary mb-md-4 fw-semibold  fs-6">O firmie</p>
                <ul class="list-unstyled">
                    <li class="mb-2 d-none">
                        <a href="{{ route('developro.completed') }}">Inwestycje<br>Zrealizowane</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('menu.show', ['uri' => 'deweloper']) }}">Deweloper</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('menu.show', ['uri' => 'kariera']) }}">Kariera</a>
                    </li>
                    <li class="mb-2">
                        <a href="https://www.kalter.pl/o-nas" target="_blank">Grupa Kalter</a>
                    </li>
                </ul>
            </div>
            <div class="col-12 col-md-6 col-lg-2">
                <p class="text-uppercase text-secondary mb-md-4 fw-semibold  fs-6">Dla klienta</p>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('menu.show', ['uri' => 'kredyty']) }}">Kredyty</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('menu.show', ['uri' => 'programy-wykonczeniowe']) }}">Programy wykończeniowe</a>
                    </li>
                </ul>
            </div>

        </div>

        <hr class="footer-hr">

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xxl-4 row-gap-3">
            <?php foreach ($cities as $city) : ?>
            @if ($city->footer)
                <div class="col-auto">
                    <p class="text-uppercase text-secondary mb-md-4 fw-semibold fs-6">{{ $city->name }}</p>
                    <div class="footer-office d-flex gap-3 align-items-start mb-2">
                        <svg class="text-secondary" xmlns="http://www.w3.org/2000/svg" width="12.204" height="17.435" viewBox="0 0 12.204 17.435"><path id="Icon_material-location-on" data-name="Icon material-location-on" d="M13.6,3A6.1,6.1,0,0,0,7.5,9.1c0,4.577,6.1,11.333,6.1,11.333S19.7,13.679,19.7,9.1A6.1,6.1,0,0,0,13.6,3Zm0,8.282A2.179,2.179,0,1,1,15.782,9.1,2.18,2.18,0,0,1,13.6,11.282Z" transform="translate(-7.5 -3)" fill="currentcolor"/></svg>

                        <p class="mb-0">{{ $city->address_line_1 }},<br>{{ $city->address_line_2 }}</p>
                    </div>

                    <div class="footer-office d-flex gap-3 align-items-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15.636" height="15.636" viewBox="0 0 15.636 15.636"><path id="Icon_awesome-phone" data-name="Icon awesome-phone" d="M15.067.751,11.891.018a.738.738,0,0,0-.84.424L9.586,3.863a.731.731,0,0,0,.211.855l1.851,1.515a11.318,11.318,0,0,1-5.411,5.411L4.721,9.793a.732.732,0,0,0-.855-.211L.445,11.049a.742.742,0,0,0-.428.843l.733,3.176a.733.733,0,0,0,.715.568,14.168,14.168,0,0,0,14.17-14.17A.732.732,0,0,0,15.067.751Z" transform="translate(15.635 0) rotate(90)" fill="currentcolor"/></svg>

                        <a href="tel:{{ $city->phone }}">{{ $city->phone }}</a>
                    </div>

                    @if($city->phone2)
                    <div class="footer-office d-flex gap-3 align-items-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15.636" height="15.636" viewBox="0 0 15.636 15.636"><path id="Icon_awesome-phone" data-name="Icon awesome-phone" d="M15.067.751,11.891.018a.738.738,0,0,0-.84.424L9.586,3.863a.731.731,0,0,0,.211.855l1.851,1.515a11.318,11.318,0,0,1-5.411,5.411L4.721,9.793a.732.732,0,0,0-.855-.211L.445,11.049a.742.742,0,0,0-.428.843l.733,3.176a.733.733,0,0,0,.715.568,14.168,14.168,0,0,0,14.17-14.17A.732.732,0,0,0,15.067.751Z" transform="translate(15.635 0) rotate(90)" fill="currentcolor"/></svg>

                        <a href="tel:{{ $city->phone2 }}">{{ $city->phone2 }}</a>
                    </div>
                    @endif

                    <div class="footer-office d-flex gap-3 align-items-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17.827" height="12.341" viewBox="0 0 17.827 12.341"><g id="Icon_ionic-ios-mail" data-name="Icon ionic-ios-mail" transform="translate(-3.375 -7.875)"><path id="Path_93" data-name="Path 93" d="M21.056,10.34l-4.611,4.7a.083.083,0,0,0,0,.12l3.227,3.437a.556.556,0,0,1,0,.788.559.559,0,0,1-.788,0l-3.214-3.424a.088.088,0,0,0-.124,0l-.784.8a3.45,3.45,0,0,1-2.46,1.037A3.519,3.519,0,0,1,9.79,16.725l-.754-.767a.088.088,0,0,0-.124,0L5.7,19.382a.559.559,0,0,1-.788,0,.556.556,0,0,1,0-.788l3.227-3.437a.091.091,0,0,0,0-.12l-4.615-4.7a.084.084,0,0,0-.146.06v9.4a1.375,1.375,0,0,0,1.371,1.371H19.83A1.375,1.375,0,0,0,21.2,19.8V10.4A.086.086,0,0,0,21.056,10.34Z" transform="translate(0 -0.952)" fill="currentcolor"/><path id="Path_94" data-name="Path 94" d="M12.621,15.721a2.33,2.33,0,0,0,1.676-.7L21.02,8.175a1.347,1.347,0,0,0-.848-.3H5.074a1.338,1.338,0,0,0-.848.3l6.724,6.844A2.33,2.33,0,0,0,12.621,15.721Z" transform="translate(-0.332)" fill="currentcolor"/></g></svg>

                        <a href="mailto:{{ $city->email }}">{{ $city->email }}</a>
                    </div>
                </div>
            @endif
            <?php endforeach; ?>
        </div>

        <div class="row mt-4">
            <div class="col-12 text-center">
                <p style="font-size:11px;line-height:14px">Przedstawione na stronie internetowej www.kalternieruchomosci.pl wizualizacje, animacje oraz modele budynku/budynków mają charakter poglądowy. Wygląd budynku/budynków oraz zagospodarowanie terenu mogą nieznacznie ulec zmianie na etapie realizacji. Zmianie nie ulegną istotne cechy świadczenia oraz funkcjonalność budynku/budynków. Przedstawione materiały graficzne nie mogą być traktowane jako ostateczne projekty realizacyjne, oznacza to, że wizualizacje te nie przedstawiają ostatecznych, docelowych właściwości inwestycji wraz z najbliższym otoczeniem i tym samym nie stanowią próbki lub wzoru w rozumieniu art. 556(1) § 1pkt 2 KC.</p>
                <p style="font-size:11px;line-height:14px">Treści i materiały dostępne na stronie internetowej są chronione prawem - ustawa z dnia 4 lutego 1994 r. o Prawie autorskim i prawach pokrewnych (Dz. U. 2006 Nr 90 poz. 631 z późn. zm.). Bez pisemnej zgody Kalter Nieruchomości Sp. z o.o. zabrania się używania, kopiowania i rozpowszechniania wszelkich danych i materiałów dostępnych na niniejszej stronie internetowej.</p>
            </div>
        </div>
    </div>
</footer>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/slick.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/aos.js') }}"></script>
<script src="{{ asset('js/glightbox.min.js') }}"></script>
<script src="{{ asset('js/main.js') }}?v=17052026"></script>
<link rel="stylesheet" href="{{ asset('css/glightbox.min.css') }}" media="print" onload="this.media='all'">
