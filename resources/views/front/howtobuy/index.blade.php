@extends('layouts.page')

@section('meta_title', $page->title)
@section('seo_title', $page->meta_title)
@section('seo_description', $page->meta_description)

@section('pageheader')
    @include('layouts.partials.page-header', ['page' => $page, 'header_file' => 'jak-kupic.jpg'])
@stop

@section('content')
    <div id="howtobuy">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="section-title">Kilka kroków do Bliskiego Olechowa</h2>
                    <div class="timeline-block">
                        <div class="format-container">
                            <div class="js-timeline timeline">
                                <div class="js-timeline_line timeline_line">
                                    <div class="js-timeline_line-progress timeline_line-progress"></div>
                                </div>
                                <div class="timeline_list">
                                    <div class="timeline_item js-active">
                                        <div class="timeline-card_box">
                                            <div class="js-timeline-card_point-box timeline-card_point-box">
                                                <div class="timeline-card_point">1</div>
                                            </div>
                                        </div>
                                        <div class="timeline-card_item">
                                            <div class="timeline-card_inner">
                                                <div class="timeline-card_img-box">
                                                    <img src="{{asset('uploads/krok_1.jpg') }}" class="timeline-card_img" width="630" height="355" />
                                                </div>
                                                <div class="timeline-card_info">
                                                    <div class="timeline-card_desc">
                                                        <h2>Spotkajmy się! Zapraszamy do naszego biura, gdzie pokażemy Państwu wszystkie zalety inwestycji.</h2>
                                                        <p>Nawet najbardziej wyczerpujący folder reklamowy nie zastąpi spotkania w biurze sprzedaży. Nasi sprzedawcy są kompetentni i znają nasze inwestycje doskonale, dlatego będą w stanie zapewnić Państwu komplet rzetelnych informacji i odpowiedzieć na każde pytanie. Ponadto, tylko w biurze sprzedaży możecie Państwo obejrzeć szczegółowe plany osiedla i rozbudowane wizualizacje!</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="timeline-card_arrow"></div>
                                        </div>
                                    </div>

                                    <div class="js-timeline_item timeline_item">
                                        <div class="timeline-card_box">
                                            <div class="js-timeline-card_point-box timeline-card_point-box">
                                                <div class="timeline-card_point">2</div>
                                            </div>
                                        </div>
                                        <div class="timeline-card_item">
                                            <div class="timeline-card_inner">
                                                <div class="timeline-card_img-box">
                                                    <img src="{{asset('uploads/krok_2.jpg') }}" class="timeline-card_img" width="630" height="355" alt="" />
                                                </div>
                                                <div class="timeline-card_info">
                                                    <div class="timeline-card_desc">
                                                        <h2>Jak możemy pomóc? Nasi sprzedawcy postarają się jak najlepiej zrozumieć Państwa potrzeby i zaproponować mieszkania, które idealnie na nie odpowiadają.</h2>
                                                        <p>Lokalizacja, cena, układ mieszkania – o te i inne kluczowe dla wyboru przyszłego domu elementy zapyta Państwa nasz zespół. Jak najlepsze zrozumienie potrzeb naszych klientów umożliwia zaproponowanie rozwiązań idealnie na nie odpowiadających. Wszystko po to, aby nowe mieszkanie stało się wymarzonym nowym domem. </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="timeline-card_arrow"></div>
                                        </div>
                                    </div>

                                    <div class="js-timeline_item timeline_item">
                                        <div class="timeline-card_box">
                                            <div class="js-timeline-card_point-box timeline-card_point-box">
                                                <div class="timeline-card_point">3</div>
                                            </div>
                                        </div>
                                        <div class="timeline-card_item">
                                            <div class="timeline-card_inner">
                                                <div class="timeline-card_img-box">
                                                    <img src="{{asset('uploads/krok_3.jpg') }}" class="timeline-card_img" width="630" height="355" alt="" />
                                                </div>
                                                <div class="timeline-card_info">
                                                    <div class="timeline-card_desc">
                                                        <h2>Czas na konkrety: terminy realizacji inwestycji, harmonogram płatności, treść umowy rezerwacyjnej i deweloperskiej – to wszystko wyjaśniamy w jasny i zrozumiały sposób.</h2>
                                                        <p>Zakup mieszkania to jedna z najważniejszych (i największych!) transakcji w życiu człowieka, dlatego tak ważne jest rzetelne uzgodnienie konkretów. To właśnie dział sprzedaży dysponuje najbardziej aktualnymi i pełnymi informacjami dotyczącymi harmonogramu realizacji inwestycji, możliwości finansowania i warunków umowy, więc po wszelkie szczegóły zapraszamy do biura sprzedaży.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="timeline-card_arrow"></div>
                                        </div>
                                    </div>

                                    <div class="js-timeline_item timeline_item">
                                        <div class="timeline-card_box">
                                            <div class="js-timeline-card_point-box timeline-card_point-box">
                                                <div class="timeline-card_point">4</div>
                                            </div>
                                        </div>
                                        <div class="timeline-card_item">
                                            <div class="timeline-card_inner">
                                                <div class="timeline-card_img-box">
                                                    <img src="{{asset('uploads/krok_4.jpg') }}" class="timeline-card_img" width="630" height="355" alt="" />
                                                </div>
                                                <div class="timeline-card_info">
                                                    <div class="timeline-card_desc">
                                                        <h2>Umowa rezerwacyjna</h2>
                                                        <p>Pierwszym krokiem w zakupie mieszkania jest podpisanie umowy rezerwacyjnej, w której zawarte są wszelkie niezbędne informacje dotyczące realizacji inwestycji. Umowa rezerwacyjna jest zawierana w biurze sprzedaży i wiąże się z uiszczeniem opłaty rezerwacyjnej. </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="timeline-card_arrow"></div>
                                        </div>
                                    </div>

                                    <div class="js-timeline_item timeline_item">
                                        <div class="timeline-card_box">
                                            <div class="js-timeline-card_point-box timeline-card_point-box">
                                                <div class="timeline-card_point">5</div>
                                            </div>
                                        </div>
                                        <div class="timeline-card_item">
                                            <div class="timeline-card_inner">
                                                <div class="timeline-card_img-box">
                                                    <img src="{{asset('uploads/krok_5.jpg') }}" class="timeline-card_img" width="630" height="355" alt="" />
                                                </div>
                                                <div class="timeline-card_info">
                                                    <div class="timeline-card_desc">
                                                        <h2>Wsparcie w uzyskaniu finansowania</h2>
                                                        <p>Po podpisaniu umowy rezerwacyjnej nasz zespół z przyjemnością pomoże Państwu w uzyskaniu finansowania. Udzielamy wsparcia w porównaniu ofert i przygotowaniu niezbędnej dokumentacji dla banku. Wieloletnie doświadczenie i rozległa współpraca z wieloma bankami pozwalają nam kompetentnie wspierać naszych klientów w procesie uzyskania kredytu.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="timeline-card_arrow"></div>
                                        </div>
                                    </div>

                                    <div class="js-timeline_item timeline_item">
                                        <div class="timeline-card_box">
                                            <div class="js-timeline-card_point-box timeline-card_point-box">
                                                <div class="timeline-card_point">6</div>
                                            </div>
                                        </div>
                                        <div class="timeline-card_item">
                                            <div class="timeline-card_inner">
                                                <div class="timeline-card_img-box">
                                                    <img src="{{asset('uploads/krok_6.jpg') }}" class="timeline-card_img" width="630" height="355" alt="" />
                                                </div>
                                                <div class="timeline-card_info">
                                                    <div class="timeline-card_desc">
                                                        <h2>Podpisanie umowy deweloperskiej u notariusza</h2>
                                                        <p>Następnym krokiem do posiadania własnego mieszkania jest umowa deweloperska. Tego rodzaju kontrakt podpisywany jest u notariusza, po wpłacie pierwszej raty. Koszt podpisania umowy u notariusza, tzw. taksa notarialna jest rozłożony na kupującego i dewelopera. W umowie deweloperskiej są zawarte takie informacje jak np. harmonogram wpłat i realizacji etapów inwestycji.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="timeline-card_arrow"></div>
                                        </div>
                                    </div>

                                    <div class="js-timeline_item timeline_item">
                                        <div class="timeline-card_box">
                                            <div class="js-timeline-card_point-box timeline-card_point-box">
                                                <div class="timeline-card_point">7</div>
                                            </div>
                                        </div>
                                        <div class="timeline-card_item">
                                            <div class="timeline-card_inner">
                                                <div class="timeline-card_img-box">
                                                    <img src="{{asset('uploads/krok_7.jpg') }}" class="timeline-card_img" width="630" height="355" alt="" />
                                                </div>
                                                <div class="timeline-card_info">
                                                    <div class="timeline-card_desc">
                                                        <h2>Zmiany lokatorskie i formalny odbiór mieszkania</h2>
                                                        <p>Gdy mieszkanie zostanie wybudowane spotykamy się na miejscu, aby uzgodnić czy jakieś elementy nie wymagają zmiany. Gdy już wszystkie elementy są przygotowane zgodnie z życzeniem kupującego a deweloper uzyskał formalną zgodę na użytkowanie mieszkania następuje formalny odbiór techniczny nieruchomości. W tym momencie dostajecie Państwo klucze do swojego nowego mieszkania!</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="timeline-card_arrow"></div>
                                        </div>
                                    </div>

                                    <div class="js-timeline_item timeline_item">
                                        <div class="timeline-card_box">
                                            <div class="js-timeline-card_point-box timeline-card_point-box">
                                                <div class="timeline-card_point">8</div>
                                            </div>
                                        </div>
                                        <div class="timeline-card_item">
                                            <div class="timeline-card_inner">
                                                <div class="timeline-card_img-box">
                                                    <img src="{{asset('uploads/krok_8.jpg') }}" class="timeline-card_img" width="630" height="355" alt="" />
                                                </div>
                                                <div class="timeline-card_info">
                                                    <div class="timeline-card_desc">
                                                        <h2>Finalna umowa notarialna przenosząca własność mieszkania</h2>
                                                        <p>Umowa przenosząca własność mieszkania znów musi być zawarta w obecności notariusza. W tym wypadku taksę notarialną pokrywa kupujący. Na podstawie tej umowy przenoszona jest własność mieszkania na kupującego.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="timeline-card_arrow"></div>
                                        </div>
                                    </div>

                                    <div class="js-timeline_item timeline_item">
                                        <div class="timeline-card_box">
                                            <div class="js-timeline-card_point-box timeline-card_point-box">
                                                <div class="timeline-card_point">9</div>
                                            </div>
                                        </div>
                                        <div class="timeline-card_item">
                                            <div class="timeline-card_inner">
                                                <div class="timeline-card_img-box">
                                                    <img src="{{asset('uploads/krok_9.jpg') }}" class="timeline-card_img" width="630" height="355" alt="" />
                                                </div>
                                                <div class="timeline-card_info">
                                                    <div class="timeline-card_desc">
                                                        <h2>Komfortowe życie na osiedlu Bliski Olechów</h2>
                                                        <p>I to już naprawdę ostatni etap przed realizacją marzenia – wykończenie mieszkania i można się wprowadzać! Wierzymy że życie na Bliskim Olechowie będzie komfortowe, przyjemne i szczęśliwe.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="timeline-card_arrow"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        (function ($) {
            $(function () {
                $(window).on('scroll', function () {
                    fnOnScroll();
                });

                $(window).on('resize', function () {
                    fnOnResize();
                });

                let agTimeline = $('.js-timeline'),
                    agTimelineLine = $('.js-timeline_line'),
                    agTimelineLineProgress = $('.js-timeline_line-progress'),
                    agTimelinePoint = $('.js-timeline-card_point-box'),
                    agTimelineItem = $('.js-timeline_item'),
                    agOuterHeight = $(window).outerHeight(),
                    agHeight = $(window).height(),
                    f = -1,
                    agFlag = false;

                function fnOnScroll() {
                    agPosY = $(window).scrollTop();
                    fnUpdateFrame();
                }

                function fnOnResize() {
                    agPosY = $(window).scrollTop();
                    agHeight = $(window).height();
                    fnUpdateFrame();
                }

                function fnUpdateWindow() {
                    agFlag = false;
                    agTimelineLine.css({
                        top: agTimelineItem.first().find(agTimelinePoint).offset().top - agTimelineItem.first().offset().top,
                        bottom: agTimeline.offset().top + agTimeline.outerHeight() - agTimelineItem.last().find(agTimelinePoint).offset().top
                    });
                    f !== agPosY && (f = agPosY, agHeight, fnUpdateProgress());
                }

                function fnUpdateProgress() {
                    const agTop = agTimelineItem.last().find(agTimelinePoint).offset().top;

                    i = agTop + agPosY - $(window).scrollTop();
                    a = agTimelineLineProgress.offset().top + agPosY - $(window).scrollTop();
                    n = agPosY - a + agOuterHeight / 2;
                    i <= agPosY + agOuterHeight / 2 && (n = i - a);
                    agTimelineLineProgress.css({height: n + "px"});
                    agTimelineItem.each(function () {
                        const agTop = $(this).find(agTimelinePoint).offset().top;
                        (agTop + agPosY - $(window).scrollTop()) < agPosY + .5 * agOuterHeight ? $(this).addClass('js-active') : $(this).removeClass('js-active');
                    })
                }

                function fnUpdateFrame() {
                    agFlag || requestAnimationFrame(fnUpdateWindow);
                    agFlag = true;
                }
            });
        })(jQuery);
    </script>
@endpush
