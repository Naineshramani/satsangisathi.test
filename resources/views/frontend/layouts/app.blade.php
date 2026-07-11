@php
if (Session::has('locale')) {
    $locale = Session::get('locale', Config::get('app.locale'));
} else {
    $locale = env('DEFAULT_LANGUAGE');
}
$lang = \App\Models\Language::where('code', $locale)->first();
@endphp

<!DOCTYPE html>
@if (\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
    <html dir="rtl" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@else
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@endif

<head>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-url" content="{{ getBaseURL() }}">
    <meta name="file-base-url" content="{{ getFileBaseURL() }}">
    <!-- Title -->
    <title>@yield('meta_title', get_setting('website_name') . ' | ' . get_setting('site_motto'))</title>

    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="@yield('meta_description', get_setting('meta_description'))" />
    <meta name="keywords" content="@yield('meta_keywords', get_setting('meta_keywords'))">

    @yield('meta')

    @if (!isset($page))
        <!-- Schema.org markup for Google+ -->
        <meta itemprop="name" content="{{ config('app.name', env('APP_NAME')) }}">
        <meta itemprop="description" content="{{ get_setting('meta_description') }}">
        <meta itemprop="image" content="{{ uploaded_asset(get_setting('meta_image')) }}">

        <!-- Twitter Card data -->
        <meta name="twitter:card" content="summary">
        <meta name="twitter:site" content="@publisher_handle">
        <meta name="twitter:title" content="{{ config('app.name', env('APP_NAME')) }}">
        <meta name="twitter:description" content="{{ get_setting('meta_description') }}">
        <meta name="twitter:creator" content="@author_handle">
        <meta name="twitter:image" content="{{ uploaded_asset(get_setting('meta_image')) }}">

        <!-- Open Graph data -->
        <meta property="og:title" content="{{ config('app.name', env('APP_NAME')) }}" />
        <meta property="og:type" content="Business Site" />
        <meta property="og:url" content="{{ env('APP_URL') }}" />
        <meta property="og:image" content="{{ uploaded_asset(get_setting('meta_image')) }}" />
        <meta property="og:description" content="{{ get_setting('meta_description') }}" />
        <meta property="og:site_name" content="{{ get_setting('website_name') }}" />
        <meta property="fb:app_id" content="{{ env('FACEBOOK_PIXEL_ID') }}">
    @endif

    <!-- Favicon -->
    <link rel="icon" href="{{ uploaded_asset(get_setting('site_icon')) }}">

    <!-- CSS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ static_asset('assets/css/vendors.css?v=2') }}">
    <link rel="stylesheet" href="{{ static_asset('assets/css/aiz-core.css?v=') }}{{ rand(1000,9999) }}">
    <link rel="stylesheet" href="{{ static_asset('assets/css/custom-style.css?v=3.0') }}">

    @if (\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
        <link rel="stylesheet" href="{{ static_asset('assets/css/bootstrap-rtl.min.css') }}">
    @endif

    <script>
        var AIZ = AIZ || {};
    </script>
    <style>
        @@font-face {
            font-family: 'Line Awesome Free';
            font-style: normal;
            font-weight: 900;
            font-display: swap;
            src: url('{{ static_asset("assets/fonts/la-solid-900.woff2") }}') format('woff2'),
                 url('{{ static_asset("assets/fonts/la-solid-900.ttf") }}') format('truetype');
        }
        @@font-face {
            font-family: 'Line Awesome Free';
            font-style: normal;
            font-weight: 400;
            font-display: swap;
            src: url('{{ static_asset("assets/fonts/la-regular-400.woff2") }}') format('woff2'),
                 url('{{ static_asset("assets/fonts/la-regular-400.ttf") }}') format('truetype');
        }
        @@font-face {
            font-family: 'Line Awesome Brands';
            font-style: normal;
            font-weight: 400;
            font-display: swap;
            src: url('{{ static_asset("assets/fonts/la-brands-400.woff2") }}') format('woff2'),
                 url('{{ static_asset("assets/fonts/la-brands-400.ttf") }}') format('truetype');
        }
        :root {
            --primary: {{ get_setting('base_color', '#E8A800') }};
            --hov-primary: {{ get_setting('base_hov_color', '#D49800') }};
            --soft-primary: {{ hex2rgba(get_setting('base_hov_color', '#D49800'), 0.15) }};
            --secondary: {{ get_setting('secondary_color', '#C68A00') }};
            --soft-secondary: {{ hex2rgba(get_setting('secondary_color', '#C68A00'), 0.15) }};
            --color-bg: #FFF8F0;
            --color-bg-secondary: #FFF4E6;
            --color-card: #FFFFFF;
            --color-text: #4A2E00;
            --color-border: #F2D78A;
            --color-success: #22C55E;
            --color-error: #E53935;
        }

        body {
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            color: var(--color-text);
            background-color: var(--color-bg);
        }

        .text-primary-grad {
            background: {{ hex2rgba(get_setting('base_color', '#E8A800'), 1) }};
            background: -moz-linear-gradient(0deg, {{ hex2rgba(get_setting('base_color', '#E8A800'), 1) }} 0%, {{ hex2rgba(get_setting('secondary_color', '#C68A00'), 1) }} 100%);
            background: -webkit-linear-gradient(0deg, {{ hex2rgba(get_setting('base_color', '#E8A800'), 1) }} 0%, {{ hex2rgba(get_setting('secondary_color', '#C68A00'), 1) }} 100%);
            background: linear-gradient(0deg, {{ hex2rgba(get_setting('base_color', '#E8A800'), 1) }} 0%, {{ hex2rgba(get_setting('secondary_color', '#C68A00'), 1) }} 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-primary,
        .bg-primary-grad {
            background: {{ hex2rgba(get_setting('base_color', '#E8A800'), 1) }};
            background: -moz-linear-gradient(225deg, {{ hex2rgba(get_setting('base_color', '#E8A800'), 1) }} 0%, {{ hex2rgba(get_setting('secondary_color', '#C68A00'), 1) }} 100%);
            background: -webkit-linear-gradient(225deg, {{ hex2rgba(get_setting('base_color', '#E8A800'), 1) }} 0%, {{ hex2rgba(get_setting('secondary_color', '#C68A00'), 1) }} 100%);
            background: linear-gradient(225deg, {{ hex2rgba(get_setting('base_color', '#E8A800'), 1) }} 0%, {{ hex2rgba(get_setting('secondary_color', '#C68A00'), 1) }} 100%);
        }

        /* Page-level background: cream for all sections */
        .aiz-main-wrapper,
        section.bg-white,
        div.bg-white:not(.card):not(.card-body):not(.modal-content):not(.dropdown-menu):not(.top-navbar):not(.aiz-header) {
            background-color: var(--color-bg) !important;
        }
        /* Keep header, cards, modals pure white */
        .top-navbar, .aiz-header,
        .card, .card-body, .modal-content,
        .dropdown-menu { background-color: var(--color-card) !important; }

        .fill-dark {
            fill: #4d4d4d;
        }

        .fill-primary-grad stop:nth-child(1) {
            stop-color: {{ hex2rgba(get_setting('secondary_color', '#FD655B'), 1) }};
        }

        .fill-primary-grad stop:nth-child(2) {
            stop-color: {{ hex2rgba(get_setting('base_color', '#FD2C79'), 1) }};
        }

        /* ── Elegance overrides ── */

        /* Refined card style */
        .card {
            border-radius: 14px !important;
            border: 1px solid rgba(232,168,0,0.10) !important;
            box-shadow: 0 2px 18px rgba(74,46,0,0.06) !important;
            transition: box-shadow 0.25s ease !important;
        }
        .card:hover {
            box-shadow: 0 6px 32px rgba(232,168,0,0.13) !important;
        }
        .card-header {
            border-radius: 14px 14px 0 0 !important;
            border-bottom: 1px solid rgba(232,168,0,0.10) !important;
            background: linear-gradient(135deg,#fff 70%,rgba(232,168,0,0.04)) !important;
        }

        /* Pill buttons */
        .btn-primary {
            border-radius: 50px !important;
            letter-spacing: 0.04em;
            box-shadow: 0 4px 14px rgba(232,168,0,0.28) !important;
            border: none !important;
            font-weight: 600 !important;
            transition: transform 0.15s ease, box-shadow 0.15s ease !important;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(232,168,0,0.38) !important;
        }
        .btn-sm { border-radius: 50px !important; }

        /* Gold focus ring on form controls */
        .form-control {
            border-radius: 8px !important;
            border-color: rgba(232,168,0,0.22) !important;
            transition: border-color 0.2s, box-shadow 0.2s !important;
        }
        .form-control:focus {
            border-color: #E8A800 !important;
            box-shadow: 0 0 0 3px rgba(232,168,0,0.12) !important;
        }
        .bootstrap-select .dropdown-toggle {
            border-radius: 8px !important;
            border-color: rgba(232,168,0,0.22) !important;
        }

        /* Nav menu letter-spacing */
        .aiz-navbar .nav-link span {
            letter-spacing: 0.07em;
        }

        /* Member sub-tab: gold underline on active */
        .border-top .list-inline-item a {
            border-bottom: 3px solid transparent;
            transition: border-color 0.2s, color 0.2s;
            font-size: 13px;
        }
        .border-top .list-inline-item a.text-primary-grad,
        .border-top .list-inline-item a:hover {
            border-bottom-color: #E8A800;
        }

        /* Sidebar nav links refined */
        .aiz-side-nav-link {
            border-radius: 8px !important;
            margin: 2px 8px !important;
            transition: background 0.2s !important;
        }
        .aiz-side-nav-link:hover,
        .aiz-side-nav-link.active {
            background: rgba(232,168,0,0.08) !important;
        }

        /* Top helpline bar */
        .top-navbar {
            background: linear-gradient(135deg,#fff 60%,rgba(232,168,0,0.03)) !important;
        }

        /* Input group text */
        .input-group-text {
            border-radius: 8px 0 0 8px !important;
            border-color: rgba(232,168,0,0.22) !important;
            background: rgba(232,168,0,0.06) !important;
            color: #B8860B !important;
            font-weight: 600;
        }

        /* Section headings in cards */
        .card-header h5, .card-header .h6 {
            font-weight: 700;
            letter-spacing: 0.01em;
            color: #4A2E00;
        }

        /* Logout & Registration button in top bar */
        .top-navbar .btn-primary {
            padding: 4px 18px !important;
            font-size: 13px !important;
        }

        /* Verify Now banner */
        .card[style*="border: 2px solid"] {
            border-radius: 14px !important;
        }

        /* ── 1. Playfair Display for all headings ── */
        h1, h2, h3, h4, h5, h6,
        .h1, .h2, .h3, .h4, .h5, .h6,
        .card-header h5, .card-header .h6,
        .modal-title {
            font-family: 'Playfair Display', Georgia, serif !important;
            letter-spacing: 0.01em;
        }
        /* Keep nav & buttons in Poppins */
        .aiz-navbar *, .btn, .top-navbar *,
        .border-top *, .aiz-side-nav-link {
            font-family: 'Poppins', sans-serif !important;
        }

        /* ── 2. Glassmorphism header on scroll ── */
        .aiz-header { transition: background 0.3s ease, box-shadow 0.3s ease, backdrop-filter 0.3s ease; }
        .aiz-header.header-glass {
            background: rgba(255,255,255,0.82) !important;
            backdrop-filter: blur(18px) !important;
            -webkit-backdrop-filter: blur(18px) !important;
            box-shadow: 0 4px 30px rgba(232,168,0,0.12) !important;
            border-bottom: 1px solid rgba(232,168,0,0.18) !important;
        }
        .top-navbar { transition: background 0.3s ease; }

        /* ── 3. Page fade-up animation ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(22px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .aiz-main-wrapper > *:not(.position-fixed):not(.position-absolute) {
            animation: fadeUp 0.55s cubic-bezier(0.22,1,0.36,1) both;
        }
        .aiz-main-wrapper > *:nth-child(2) { animation-delay: 0.05s; }
        .aiz-main-wrapper > *:nth-child(3) { animation-delay: 0.10s; }

        /* ── Gold custom scrollbar ── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: #FFF8F0; }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg,#E8A800,#C68A00);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover { background: #C68A00; }
    </style>

    @if (get_setting('google_analytics_activation') == 1)
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ env('GOOGLE_ANALYTICS_TRACKING_ID') }}"></script>

        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());
            gtag('config', '{{ env('GOOGLE_ANALYTICS_TRACKING_ID') }}');
        </script>
    @endif

    @if (get_setting('facebook_pixel_activation') == 1)
        <!-- Facebook Pixel Code -->
        <script>
            ! function(f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function() {
                    n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s)
            }(window, document, 'script',
                'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', {{ env('FACEBOOK_PIXEL_ID') }});
            fbq('track', 'PageView');
        </script>
        <noscript>
            <img height="1" width="1" style="display:none"
                src="https://www.facebook.com/tr?id={{ env('FACEBOOK_PIXEL_ID') }}/&ev=PageView&noscript=1" />
        </noscript>
        <!-- End Facebook Pixel Code -->
    @endif

    {!! get_setting('header_script') !!}

</head>

<body class="text-left @yield('body_class')">

    <div class="aiz-main-wrapper d-flex flex-column position-relative bg-white">

        @include('frontend.inc.header')
        {{-- Spacer pushes content below the fixed header; height set by JS --}}
        <div id="header-spacer"></div>

        @yield('content')

        @include('frontend.inc.footer')
    </div>

    @if (get_setting('show_cookies_agreement') == 'on')
        <div class="aiz-cookie-alert shadow-xl">
            <div class="p-3 bg-dark rounded">
                <div class="text-white mb-3">
                    {{strip_tags(get_setting('cookies_agreement_text')) }}
                </div>
                <button class="btn btn-primary aiz-cookie-accepet">
                    {{ translate('Ok. I Understood') }}
                </button>
            </div>
        </div>
    @endif

    @yield('modal')

    <div class="modal fade account_status_change_modal" id="modal-zoom">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <form class="form-horizontal member-block" action="{{ route('member.account_deactivation') }}"
                        method="POST">
                        @csrf
                        <input type="hidden" name="deacticvation_status" id="deacticvation_status" value="">
                        <h4 class="modal-title h6 mb-3" id="confirmation_note" value=""></h4>
                        <hr>
                        <button type="submit" class="btn btn-primary mt-2">{{ translate('Yes') }}</button>
                        <button type="button" class="btn btn-danger mt-2"
                            data-dismiss="modal">{{ translate('No') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade account_delete_modal" id="modal-zoom">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <form class="form-horizontal member-block" action="{{ route('member.account_delete') }}"
                        method="POST">
                        @csrf                      
                        <h4 class="modal-title h6 mb-3" id="delete_confirmation_note" value=""></h4>
                        <hr>
                        <button type="submit" class="btn btn-primary mt-2">{{ translate('Yes') }}</button>
                        <button type="button" class="btn btn-danger mt-2"
                            data-dismiss="modal">{{ translate('No') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if (get_setting('facebook_chat_activation') == 1)
        <script type="text/javascript">
            window.fbAsyncInit = function() {
                FB.init({
                    xfbml: true,
                    version: 'v3.3'
                });
            };

            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s);
                js.id = id;
                js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
        <div id="fb-root"></div>
        <!-- Your customer chat code -->
        <div class="fb-customerchat" attribution=setup_tool page_id="{{ env('FACEBOOK_PAGE_ID') }}">
        </div>
    @endif

    <script src="{{ static_asset('assets/js/vendors.js') }}"></script>
    <script src="{{ static_asset('assets/js/aiz-core.js?v=1.1') }}"></script>

    @if (get_setting('firebase_push_notification') == 1)
        {{-- fcm --}}
        <!-- The core Firebase JS SDK is always required and must be listed first -->
        <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js"></script>
        <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js"></script>

        <!-- TODO: Add SDKs for Firebase products that you want to use
        https://firebase.google.com/docs/web/setup#available-libraries -->

        <script>
            // Your web app's Firebase configuration
            var firebaseConfig = {
                apiKey: "{{ env('FCM_API_KEY') }}",
                authDomain: "{{ env('FCM_AUTH_DOMAIN') }}",
                projectId: "{{ env('FCM_PROJECT_ID') }}",
                storageBucket: "{{ env('FCM_STORAGE_BUCKET') }}",
                messagingSenderId: "{{ env('FCM_MESSAGING_SENDER_ID') }}",
                appId: "{{ env('FCM_APP_ID') }}",
            };

            // Initialize Firebase
            firebase.initializeApp(firebaseConfig);

            const messaging = firebase.messaging();

            function initFirebaseMessagingRegistration() {
                messaging.requestPermission()
                .then(function() {
                    return messaging.getToken()
                }).then(function(token) {
                    
                    $.ajax({
                        url: '{{ route('fcmToken') }}',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            fcm_token: token
                        },
                        dataType: 'JSON',
                        success: function (response) {
                            
                        },
                        error: function (err) {
                            console.log(" Can't do because: " + err);
                        },
                    });

                }).catch(function(err) {
                    console.log(`Token Error :: ${err}`);
                });
            }

            initFirebaseMessagingRegistration();        

            messaging.onMessage(function({
                data: {
                    body,
                    title
                }
            }) {
                new Notification(title, {
                    body
                });
            });
        </script>
        {{-- End of fcm --}}
    @endif

    @yield('script')

    <script type="text/javascript">
        @foreach (session('flash_notification', collect())->toArray() as $message)
            AIZ.plugins.notify('{{ $message['level'] }}', '{{ $message['message'] }}');
        @endforeach

        @if (Auth::check() && Auth::user()->user_type == 'member')
            function account_deactivation() {
                var status = {{ Auth::user()->deactivated }}
                $('.account_status_change_modal').modal('show');
                if (status == 0) {
                    $('#deacticvation_status').val(1);
                    $('#confirmation_note').html('{{ translate('Deactivating your account will prevent you from performing any actions. Are you sure you want to deactivate your account?') }}');
                } else {
                    $('#deacticvation_status').val(0);
                    $('#confirmation_note').html('{{ translate('Are You Sure To Reactive Your Account') }}');
                }
            }
        @endif
        @if (Auth::check() && Auth::user()->user_type == 'member')
            function account_delete() {
                var status = {{ Auth::user()->deactivated }}
                $('.account_delete_modal').modal('show');
                    $('#delete_confirmation_note').html('{{ translate('Do You Really Want To Delete Your Account') }}');
            }
        @endif
    </script>


    @if (env('DEMO_MODE') == 'On')
        <script type="text/javascript">
            // Login credentials autoFill for demo
            function autoFill1() {
                $('#email').val('user2@example.com');
                $('#password').val('12345678');
            }

            function autoFill2() {
                $('#email').val('user17@example.com');
                $('#password').val('12345678');
            }
        </script>
    @endif

    {!! get_setting('footer_script') !!}

    <script>
        // Glassmorphism header on scroll
        (function () {
            var header = document.querySelector('.aiz-header');
            if (!header) return;
            function onScroll() {
                if (window.scrollY > 50) {
                    header.classList.add('header-glass');
                } else {
                    header.classList.remove('header-glass');
                }
            }
            window.addEventListener('scroll', onScroll, { passive: true });
        })();
    </script>

    <script>
        (function () {
            var header = document.getElementById('site-header');
            var spacer = document.getElementById('header-spacer');
            function setSpacerHeight() {
                if (header && spacer) spacer.style.height = header.offsetHeight + 'px';
            }
            setSpacerHeight();
            window.addEventListener('resize', setSpacerHeight);
        })();
    </script>

</body>

</html>
