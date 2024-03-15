<!DOCTYPE html>
<!--
Template Name: Metronic - Bootstrap 4 HTML, React, Angular 9 & VueJS Admin Dashboard Theme
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: https://1.envato.market/EA4JP
Renew Support: https://1.envato.market/EA4JP
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en">
<!--begin::Head-->

<head>
    <base href="">
    <meta charset="utf-8" />
    <title>Ride-with-Passenger|{{ __('messages.dashboard') }} </title>
    <meta name="description" content="Updates and statistics" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Page Vendors Styles(used by this page)-->
    <link href="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.css?v=7.0.5') }}" rel="stylesheet"
        type="text/css" />
    <!--end::Page Vendors Styles-->
    <!--begin::Global Theme Styles(used by all pages)-->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css?v=7.0.5') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/custom/prismjs/prismjs.bundle.css?v=7.0.5') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css?v=7.0.5') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles-->
    <!--begin::Layout Themes(used by all pages)-->
    <!--end::Layout Themes-->
    <link rel="shortcut icon" href="{{ asset('assets/ridewithpassngers.png') }}" />
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    @php
        if (session()->get('locale') == 'ar') {
            echo '<style>
        body {
            transform: scaleX(-1);
            filter: FlipH;
        }

        .counter-mirror {
            transform: scaleX(-1);
            display: inline-block;
        }
    </style>';
        }
    @endphp

    <script>
        const firebaseConfig = {
            apiKey: "{{ env('API_KEY') }}",
            authDomain: "{{ env('AUTH_DOMAIN') }}",
            projectId: "{{ env('PROJECT_ID') }}",
            storageBucket: "{{ env('STORAGE_BUCKET') }}",
            messagingSenderId: "{{ env('MESSAGING_SEND_ID') }}",
            appId: "{{ env('APP_ID') }}",
            databaseURL: "{{ env('FIREBASE_DATABASE_URL') }}"
        };

        const app = firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();

        if ({{ Session::get('token') }} === 0 ? true : false) {

            //Registering Service worker file for FCM messages
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', function() {
                    navigator.serviceWorker.register("{{ asset('firebase-messaging-sw.js') }}").then(function(
                        registration) {
                        //messaging.useServiceWorker(registration);
                        retreiveToken(messaging);

                        console.log('Service Worker registration successful with scope: ', registration
                            .scope);
                    }, function(err) {
                        console.log('Service Worker registration failed: ', err);
                    });
                });

            }

            function retreiveToken(messaging) {

                messaging.requestPermission()
                    .then(function() {
                        return messaging.getToken()
                    })
                    .then(function(token) {
                        if (token) {
                            saveTokenToServer(token);
                        } else {
                            alert('You should allow notification!');
                        }
                    }).catch(function(err) {
                        console.log('User Chat Token Error' + err);
                    });


            }

            function saveTokenToServer(token) {

                $.ajax({
                    url: '{{ route('save.token') }}',
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    data: {
                        token: token,
                        admin_id: {{ auth()->user() != null ? auth()->user()->id : '-1' }},

                    },
                    dataType: 'JSON',
                    success: function(response) {
                        console.log(response);
                    },
                    error: function(err) {
                        console.log('User Chat Token Error' + err);
                    },
                });
            }

        }
        messaging.onMessage((payload) => {
            var audio;
            if (payload.data.sound == "anychange.mp3") {
                audio = new Audio("{{ asset('anychange.mp3') }}");
            }
            if (payload.data.sound == "newtrip.mp3") {
                audio = new Audio("{{ asset('newtrip.mp3') }}");
            }
            if (payload.data.sound == "notificationfromplatform.mp3") {
                audio = new Audio("{{ asset('notificationfromplatform.mp3') }}");
            }
            if (payload.data.sound == "remindertrip.mp3") {
                audio = new Audio("{{ asset('remindertrip.mp3') }}");
            }
            toastr.success('<div class="counter-mirror">New notification<br>' + payload.data.title + '<br></div>' +
                payload.data.body);
            audio.play();
        });
    </script>
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="header-fixed header-mobile-fixed page-loading ">

    <!--begin::Main-->
    <!--begin::Header Mobile-->
    <div id="kt_header_mobile" class="header-mobile bg-primary header-mobile-fixed">
        <!--begin::Logo-->
        <a href="index.html">
            <img alt="Logo" src="{{ asset('assets/ridewithpassngers.png') }}" class="max-h-50px counter-mirror"
                style="border-radius: 0.82rem" />
        </a>
        <!--end::Logo-->
        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <button class="btn p-0 burger-icon burger-icon-left ml-4" id="kt_header_mobile_toggle">
                <span></span>
            </button>
            <button class="btn p-0 ml-2" id="kt_header_mobile_topbar_toggle">
                <span class="svg-icon svg-icon-xl">
                    <!--begin::Svg Icon | path:assets/media/svg/icons/General/User.svg-->
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                        height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <polygon points="0 0 24 0 24 24 0 24" />
                            <path
                                d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z"
                                fill="#000000" fill-rule="nonzero" opacity="0.3" />
                            <path
                                d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z"
                                fill="#000000" fill-rule="nonzero" />
                        </g>
                    </svg>
                    <!--end::Svg Icon-->
                </span>
            </button>
        </div>
        <!--end::Toolbar-->
    </div>
    <!--end::Header Mobile-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="d-flex flex-row flex-column-fluid page">
            <!--begin::Wrapper-->
            <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
                <!--begin::Header-->
                <div id="kt_header" class="header flex-column header-fixed">
                    <!--begin::Top-->
                    <div class="header-top">
                        <!--begin::Container-->
                        <div class="container-fluid">
                            <!--begin::Left-->
                            <div class="d-none d-lg-flex align-items-center mr-3">
                                <!--begin::Logo-->
                                <a href="{{ url('dashboard') }}" class="mr-20">
                                    <img alt="Logo" src="{{ asset('assets/ridewithpassngers.png') }}"
                                        class="max-h-60px counter-mirror" style="border-radius: 0.82rem" />
                                </a>
                                <!--end::Logo-->
                                <!--begin::Tab Navs(for desktop mode)-->
                                <ul class="header-tabs nav align-self-end font-size-lg" role="tablist">
                                    <!--begin::Item-->
                                    <li class="nav-item">
                                        <a href="#" class="nav-link py-4 px-6 home-nav counter-mirror"
                                            data-toggle="tab" data-target="#kt_header_tab_1"
                                            role="tab">{{ __('messages.home') }}</a>
                                    </li>
                                    <!--end::Item-->
                                    <!--begin::Item-->

                                    @canany(['view_driver', 'view_employee', 'view_role'])
                                        <li class="nav-item mr-3">
                                            <a href="#" class="nav-link py-4 px-6 users-nav counter-mirror"
                                                data-toggle="tab" data-target="#kt_header_tab_2"
                                                role="tab">{{ __('messages.user_management') }}</a>
                                        </li>
                                    @endcanany

                                    @canany(['view_trip', 'create_trip'])
                                        <li class="nav-item mr-3">
                                            <a href="#" class="nav-link py-4 px-6 trips-nav counter-mirror"
                                                data-toggle="tab" data-target="#kt_header_tab_3"
                                                role="tab">{{ __('messages.trips_management') }}</a>
                                        </li>
                                    @endcanany
                                    @canany(['live_tracking', 'playback'])
                                        <li class="nav-item mr-3">
                                            <a href="#" class="nav-link py-4 px-6 map-nav counter-mirror"
                                                data-toggle="tab" data-target="#kt_header_tab_4"
                                                role="tab">{{ __('messages.map') }}</a>
                                        </li>
                                    @endcanany

                                    <!--end::Item-->
                                </ul>
                                <!--begin::Tab Navs-->
                            </div>
                            <!--end::Left-->
                            <!--begin::Topbar-->
                            <div class="topbar bg-primary">
                                <div class="topbar-item">
                                    <a class="topbar-item" href="{{ url('/profile/personal') }}">

                                        <div class="btn btn-icon btn-hover-transparent-white w-auto d-flex align-items-center btn-lg px-2"
                                            id="kt_quick_user_toggle">
                                            <div class="d-flex flex-column text-right pr-3">
                                                <span
                                                    class="text-white opacity-50 font-weight-bold font-size-sm d-none d-md-inline counter-mirror">{{ __('messages.hi') }},
                                                    {{ explode(' ', session('name'))[0] }}</span>
                                            </div>
                                            <span class="symbol symbol-35">
                                                <span
                                                    class="symbol-label font-size-h5 font-weight-bold text-white bg-white-o-30 counter-mirror">{{ substr(session('name'), 0, 1) }}</span>
                                            </span>
                                        </div>
                                    </a>
                                    <a class="topbar-item" href="{{ url('/all/notifications') }}">

                                        <div class="btn btn-icon btn-hover-transparent-white w-auto d-flex align-items-center btn-lg px-2 position-relative"
                                            id="kt_quick_user_toggle">
                                            <div class="d-flex flex-column text-right pr-3">
                                                <span style="cursor:pointer"
                                                    class=" svg-icon svg-icon-warning svg-icon-2x ">
                                                    <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo8/dist/../src/media/svg/icons/General/Notifications1.svg--><svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                        height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                            fill-rule="evenodd">
                                                            <path
                                                                d="M17,12 L18.5,12 C19.3284271,12 20,12.6715729 20,13.5 C20,14.3284271 19.3284271,15 18.5,15 L5.5,15 C4.67157288,15 4,14.3284271 4,13.5 C4,12.6715729 4.67157288,12 5.5,12 L7,12 L7.5582739,6.97553494 C7.80974924,4.71225688 9.72279394,3 12,3 C14.2772061,3 16.1902508,4.71225688 16.4417261,6.97553494 L17,12 Z"
                                                                fill="#000000" />
                                                            <rect fill="#000000" opacity="0.3" x="10" y="16"
                                                                width="4" height="4" rx="2" />
                                                        </g>
                                                    </svg>
                                                    @php
                                                        $notificationCount = DB::table('notifications')
                                                            ->where('type', '=', 'web')
                                                            ->where('seen', '=', '0')
                                                            ->count();
                                                    @endphp
                                                    <span class="counter-mirror"
                                                        style="position: absolute; top: 0; right: 0; background-color: red; color: white; border-radius: 50%; padding: 2px 6px; font-size: 10px;">
                                                        {{ $notificationCount }}
                                                    </span>
                                                </span>
                                            </div>

                                        </div>
                                    </a>

                                    <select class="form-control Langchange counter-mirror" id="Langchange"
                                        style="margin-left: 9px;" onchange="changeLang(this);">
                                        <option value="en"
                                            {{ session()->get('locale') == 'en' ? 'selected' : '' }}>
                                            {{ __('messages.eng') }}</option>
                                        <option value="ar"
                                            {{ session()->get('locale') == 'ar' ? 'selected' : '' }}>
                                            {{ __('messages.arb') }}</option>
                                    </select>

                                    <a href="{{ url('/logout') }}" style="width: 200px"
                                        class="btn btn-secondary mx-2 font-weight-bold text-dark counter-mirror">{{ __('messages.sign_out') }}</a>
                                </div>
                                <!--end::User-->
                            </div>
                            <!--end::Topbar-->
                        </div>
                        <!--end::Container-->
                    </div>
                    <!--end::Top-->
                    <!--begin::Bottom-->
                    <div class="header-bottom">
                        <!--begin::Container-->
                        <div class="container">
                            <!--begin::Header Menu Wrapper-->
                            <div class="header-navs header-navs-left" id="kt_header_navs">
                                <!--begin::Tab Navs(for tablet and mobile modes)-->
                                <ul class="header-tabs p-5 p-lg-0 d-flex d-lg-none nav nav-bold nav-tabs"
                                    role="tablist">
                                    <!--begin::Item-->
                                    <li class="nav-item mr-2">
                                        <a href="#" class="nav-link btn btn-clean home-nav counter-mirror"
                                            data-toggle="tab" data-target="#kt_header_tab_1"
                                            role="tab">{{ __('messages.home') }}</a>
                                    </li>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    @canany(['view_driver', 'view_employee', 'view_role'])
                                        <li class="nav-item mr-2">
                                            <a href="#" class="nav-link btn btn-clean users-nav counter-mirror"
                                                data-toggle="tab" data-target="#kt_header_tab_2"
                                                role="tab">{{ __('messages.user_management') }}</a>
                                        </li>
                                    @endcanany

                                    @canany(['view_trip', 'create_trip'])
                                        <li class="nav-item mr-2">
                                            <a href="#" class="nav-link btn btn-clean trips-nav counter-mirror"
                                                data-toggle="tab" data-target="#kt_header_tab_3"
                                                role="tab">{{ __('messages.trips_management') }}</a>
                                        </li>
                                    @endcanany

                                    @canany(['live_tracking', 'playback'])
                                        <li class="nav-item mr-2">
                                            <a href="#" class="nav-link btn btn-clean map-nav counter-mirror"
                                                data-toggle="tab" data-target="#kt_header_tab_4"
                                                role="tab">{{ __('messages.map') }}</a>
                                        </li>
                                    @endcanany
                                    <!--end::Item-->
                                </ul>
                                <!--begin::Tab Navs-->
                                <!--begin::Tab Content-->
                                <div class="tab-content">
                                    <!--begin::Tab Pane-->
                                    <div class="tab-pane py-5 p-lg-0 show active" id="kt_header_tab_1">
                                        <!--begin::Menu-->
                                        <div id="kt_header_menu"
                                            class="header-menu header-menu-mobile header-menu-layout-default">
                                            <!--begin::Nav-->
                                            <ul class="menu-nav">
                                                <li class="menu-item dashboard-nav" aria-haspopup="true">
                                                    <a href="{{ url('dashboard') }}" class="menu-link">
                                                        <span
                                                            class="menu-text counter-mirror">{{ __('messages.dashboard') }}</span>
                                                    </a>
                                                </li>
                                            </ul>
                                            <!--end::Nav-->
                                        </div>
                                        <!--end::Menu-->
                                    </div>
                                    <!--begin::Tab Pane-->
                                    <!--begin::Tab Pane-->
                                    <div class="tab-pane p-5 p-lg-0 justify-content-between" id="kt_header_tab_2">
                                        <div class="header-menu header-menu-mobile header-menu-layout-default">
                                            <!--begin::Actions-->

                                            <ul class="menu-nav">
                                                @can('view_role')
                                                    <li class="menu-item roles-nav" aria-haspopup="true">
                                                        <a href="{{ url('roles') }}" class="menu-link">
                                                            <span
                                                                class="menu-text counter-mirror">{{ __('messages.roles') }}</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('view_employee')
                                                    <li class="menu-item employees-nav" aria-haspopup="true">
                                                        <a href="{{ url('employees') }}" class="menu-link">
                                                            <span
                                                                class="menu-text counter-mirror">{{ __('messages.employees') }}</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('view_driver')
                                                    <li class="menu-item drivers-nav" aria-haspopup="true">
                                                        <a href="{{ url('drivers') }}" class="menu-link">
                                                            <span
                                                                class="menu-text counter-mirror">{{ __('messages.drivers') }}</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('view_customer')
                                                    <li class="menu-item customer-nav" aria-haspopup="true">
                                                        <a href="{{ url('customer') }}" class="menu-link">
                                                            <span
                                                                class="menu-text counter-mirror">{{ __('messages.customer') }}</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="tab-pane p-5 p-lg-0 justify-content-between" id="kt_header_tab_3">
                                        <div class="header-menu header-menu-mobile header-menu-layout-default">
                                            <!--begin::Actions-->
                                            <ul class="menu-nav">
                                                @can('create_trip')
                                                    <li class="menu-item new-trip-nav" aria-haspopup="true">
                                                        <a href="{{ url('new/trip') }}" class="menu-link">
                                                            <span class="menu-text counter-mirror ">+
                                                                {{ __('messages.new_trip') }}</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('view_trip')
                                                    <li class="menu-item trips-nav" aria-haspopup="true">
                                                        <a href="{{ url('trips') }}" class="menu-link">
                                                            <span
                                                                class="menu-text counter-mirror">{{ __('messages.available') }}</span>
                                                        </a>
                                                    </li>
                                                    <li class="menu-item active-trips-nav" aria-haspopup="true">
                                                        <a href="{{ url('active/trips') }}" class="menu-link">
                                                            <span
                                                                class="menu-text counter-mirror">{{ __('messages.active') }}</span>
                                                        </a>
                                                    </li>
                                                    <li class="menu-item completed-trips-nav" aria-haspopup="true">
                                                        <a href="{{ url('completed/trips') }}" class="menu-link">
                                                            <span
                                                                class="menu-text counter-mirror">{{ __('messages.completed') }}</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                <li class="menu-item sharing-links-nav" aria-haspopup="true">
                                                    <a href="{{ url('links') }}" class="menu-link">
                                                        <span
                                                            class="menu-text counter-mirror">Tracking Links</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="tab-pane p-5 p-lg-0 justify-content-between" id="kt_header_tab_4">
                                        <div class="header-menu header-menu-mobile header-menu-layout-default">
                                            <!--begin::Actions-->
                                            <ul class="menu-nav">
                                                @can('live_tracking')
                                                    <li class="menu-item live-nav" aria-haspopup="true">
                                                        <a href="{{ url('live/location/0') }}" class="menu-link">
                                                            <span
                                                                class="menu-text counter-mirror">{{ __('messages.live_tracking') }}</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('playback')
                                                    <li class="menu-item playback-nav" aria-haspopup="true">
                                                        <a href="{{ url('playback/index/0') }}" class="menu-link">
                                                            <span
                                                                class="menu-text counter-mirror">{{ __('messages.play_back') }}</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </div>
                                    <!--begin::Tab Pane-->
                                </div>
                                <!--end::Tab Content-->
                            </div>
                            <!--end::Header Menu Wrapper-->
                        </div>
                        <!--end::Container-->
                    </div>
                    <!--end::Bottom-->
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="syncEventsModal" tabindex="-1" role="dialog" aria-labelledby="syncEventsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content counter-mirror">
            <div class="modal-header">
                <h5 class="modal-title" id="syncEventsModalLabel">Sync Events</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group col-md-12">
                    <label>From:</label>
                    <input type="date" value="{{ date('Y-m-d', strtotime('now')) }}" id="sync_event_from"
                        class="form-control " required placeholder="Enter license expiry" />
                </div>
                <div class="form-group col-md-12">
                    <label>To:</label>
                    <input type="date" id="sync_Event_to" value="{{ date('Y-m-d', strtotime('-1 day')) }}"
                        class="form-control " required placeholder="Enter license expiry" />
                </div>
            </div>
            <div class="modal-footer">
                <button id="sync_events_button"
                    class="btn btn-primary font-weight-bold">Sync</button>
                <button type="button" class="btn btn-light-primary font-weight-bold"
                    data-dismiss="modal">{{ __('messages.no') }}</button>
            </div>
        </div>
    </div>
</div>
</body>

</html>
