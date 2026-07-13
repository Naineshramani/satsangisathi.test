<div class="position-fixed w-100 top-0 z-1020" id="site-header">
    <header class="aiz-header shadow-md bg-white border-gray-300">
        <div class="aiz-navbar position-relative">
            <div class="container">
                <div class="d-lg-flex justify-content-between align-items-center text-center text-lg-left">
                    <div class="logo flex-shrink-0">
                        <a href="{{ route('home') }}" class="d-inline-block py-4px">
                            @if(get_setting('header_logo') != null)
                            <img src="{{ uploaded_asset(get_setting('header_logo')) }}" alt="{{ env('APP_NAME') }}"
                                style="max-width:100%; height:72px;">
                            @else
                            <img src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}"
                                style="max-width:100%; height:72px;">
                            @endif
                        </a>
                    </div>
                    <ul class="mb-0 pl-0 ml-lg-auto d-lg-flex align-items-stretch justify-content-center justify-content-lg-start mobile-hor-swipe">
                        <li class="d-inline-block d-lg-flex pb-1 {{ areActiveRoutes(['home'],'bg-primary-grad') }}">
                            <a class="nav-link text-uppercase fw-700 fs-14 d-flex align-items-center py-1"
                                href="{{ route('home') }}">
                                <span class="text-primary-grad mb-n1">{{ translate('Home') }}</span>
                            </a>
                        </li>
                        <li class="d-inline-block d-lg-flex pb-1 {{ areActiveRoutes(['member.listing'],'bg-primary-grad') }}">
                            <a class="nav-link text-uppercase fw-700 fs-14 d-flex align-items-center py-1"
                                href="{{ route('member.listing') }}">
                                <span class="text-primary-grad mb-n1">{{ translate('Active Members') }}</span>
                            </a>
                        </li>
                        <li class="d-inline-block d-lg-flex pb-1 {{ areActiveRoutes(['packages'],'bg-primary-grad') }}">
                            <a class="nav-link text-uppercase fw-700 fs-14 d-flex align-items-center py-1"
                                href="{{ route('packages') }}">
                                <span class="text-primary-grad mb-n1">{{ translate('Premium Plans') }}</span>
                            </a>
                        </li>
                        <li class="d-inline-block d-lg-flex pb-1 {{ areActiveRoutes(['happy_stories'],'bg-primary-grad') }}">
                            <a class="nav-link text-uppercase fw-700 fs-14 d-flex align-items-center py-1"
                                href="{{ route('happy_stories') }}">
                                <span class="text-primary-grad mb-n1">{{ translate('Happy Stories') }}</span>
                            </a>
                        </li>
                        <li class="d-inline-block d-lg-flex pb-1 {{ areActiveRoutes(['contact_us'],'bg-primary-grad') }}">
                            <a class="nav-link text-uppercase fw-700 fs-14 d-flex align-items-center py-1"
                                href="{{ route('contact_us') }}">
                                <span class="text-primary-grad mb-n1">{{ translate('Contact Us') }}</span>
                            </a>
                        </li>

                        {{-- Auth items in main nav --}}
                        @if (Auth::check())
                            @php
                                $notifications = \App\Models\Notification::latest()->where('notifiable_id', Auth()->user()->id)->take(10)->get();
                                $unseen_notification = \App\Models\Notification::where('notifiable_id', Auth()->user()->id)->where('read_at', null)->count();
                                $unseen_chat_threads = chat_threads();
                                $unseen_chat_thread_count = count($unseen_chat_threads);
                            @endphp
                            <li class="d-inline-block d-lg-flex align-items-center pb-1 dropdown ml-2">
                                <a href="javascript:void(0)" class="dropdown-toggle text-reset no-arrow nav-link py-1 px-2"
                                    data-toggle="dropdown" data-display="static">
                                    <i class="las la-bell fs-20 opacity-60"></i>
                                    @if($unseen_notification > 0)
                                        <span class="badge badge-dot badge-sm badge-status no-border badge-primary"></span>
                                    @endif
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg py-0">
                                    <div class="p-3 bg-light border-bottom">
                                        <h6 class="mb-0">{{ translate('Notifications') }}</h6>
                                    </div>
                                    <ul class="list-group list-group-raw c-scrollbar-light" style="overflow-y:auto;max-height:300px;">
                                        @include('frontend.inc.notification')
                                    </ul>
                                    <div class="border-top">
                                        <a href="{{ route('frontend.notifications') }}" class="btn text-reset btn-block">{{ translate('View All Notifications') }}</a>
                                    </div>
                                </div>
                            </li>
                            <li class="d-inline-block d-lg-flex align-items-center pb-1 dropdown">
                                <a href="javascript:void(0)" class="dropdown-toggle text-reset no-arrow nav-link py-1 px-2"
                                    data-toggle="dropdown" data-display="static">
                                    <i class="las la-envelope fs-20 opacity-60"></i>
                                    @if($unseen_chat_thread_count > 0)
                                        <span class="badge badge-dot badge-sm badge-status no-border badge-primary"></span>
                                    @endif
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg py-0">
                                    <div class="p-3 bg-light border-bottom">
                                        <h6 class="mb-0">{{ translate('Messages') }}</h6>
                                    </div>
                                    <div class="c-scrollbar-light" style="overflow-y:auto;max-height:300px;">
                                        @forelse ($unseen_chat_threads as $key => $chat_thread_id)
                                            @php
                                                $chat = \App\Models\Chat::where('chat_thread_id', $chat_thread_id)->latest()->first();
                                                $current_user = Auth::user()->id;
                                            @endphp
                                            @if ($chat != null)
                                                <a href="{{ route('all.messages') }}" class="chat-user-item p-3 d-block text-inherit hov-bg-soft-primary">
                                                    <div class="media">
                                                        <span class="avatar avatar-sm mr-3 flex-shrink-0">
                                                            @if($current_user == $chat->chatThread->sender->id)
                                                                @php $user_to_show = 'receiver'; @endphp
                                                            @else
                                                                @php $user_to_show = 'sender'; @endphp
                                                            @endif
                                                            <img src="{{ $chat->chatThread->$user_to_show->photo ? uploaded_asset($chat->chatThread->$user_to_show->photo) : static_asset('assets/img/avatar-place.png') }}">
                                                        </span>
                                                        <div class="media-body minw-0">
                                                            <h6 class="mt-0 mb-1 fs-14 text-truncate">
                                                                {{ $chat->chatThread->$user_to_show->first_name.' '.$chat->chatThread->$user_to_show->last_name }}
                                                            </h6>
                                                            <div class="fs-12 text-truncate opacity-60">
                                                                {{ $chat->message ?? translate('Attachments') }}
                                                            </div>
                                                        </div>
                                                        <div class="ml-2 text-right">
                                                            <div class="opacity-60 fs-10 mb-1">{{ Carbon\Carbon::parse($chat->created_at)->diffForHumans() }}</div>
                                                        </div>
                                                    </div>
                                                </a>
                                            @endif
                                        @empty
                                            <div class="text-center py-4">
                                                <i class="las la-frown la-4x mb-2 opacity-40"></i>
                                                <h4 class="h6">{{ translate('No New Messages') }}</h4>
                                            </div>
                                        @endforelse
                                    </div>
                                    <div class="border-top">
                                        <a href="{{ route('all.messages') }}" class="btn text-reset btn-block">{{ translate('View All Messages') }}</a>
                                    </div>
                                </div>
                            </li>
                            <li class="d-inline-block d-lg-flex align-items-center pb-1 ml-2">
                                <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-reset nav-link py-1 px-2">
                                    <img src="{{ uploaded_asset(Auth::user()->photo) }}"
                                        class="size-30px rounded-circle img-fit mr-2"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                    <span class="opacity-60 mr-1">{{ translate('Hi') }},</span>
                                    <span class="text-primary-grad fw-700">{{ Auth::user()->first_name }}</span>
                                </a>
                            </li>
                            <li class="d-inline-block d-lg-flex align-items-center pb-1 ml-1">
                                <a href="{{ route('user.logout') }}"
                                    class="btn btn-sm bg-primary-grad text-white fw-600 py-1 border round-btn">{{ translate('Logout') }}</a>
                            </li>
                        @else
                            <li class="d-inline-block d-lg-flex align-items-center pb-1 ml-3">
                                <a class="btn btn-sm bg-primary-grad text-white fw-600 border round-btn nav-auth-btn"
                                    href="{{ route('login') }}">{{ translate('Log In') }}</a>
                            </li>
                            <li class="d-inline-block d-lg-flex align-items-center pb-1 ml-2">
                                <a class="btn btn-sm bg-primary-grad text-white fw-600 border round-btn nav-auth-btn"
                                    href="{{ route('register') }}">{{ translate('Registration') }}</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        @if (Auth::check() && auth()->user()->user_type == 'member')
            @php $hdrApproved = Auth::user()->approved == 1; @endphp
            <div class="border-top d-none d-lg-block">
                <div class="container">
                    <ul class="list-inline d-flex align-items-center mb-0">
                        <li class="list-inline-item">
                            <a href="{{ route('dashboard') }}"
                                class="text-reset d-inline-block px-3 py-2 fw-600 {{ areActiveRoutes(['dashboard'],'text-primary-grad opacity-100') }}">
                                <span>{{ translate('Dashboard') }}</span>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="{{ route('profile_settings') }}"
                                class="text-reset d-inline-block px-3 py-2 fw-600 {{ areActiveRoutes(['profile_settings'],'text-primary-grad opacity-100') }}">
                                <span>{{ translate('My Profile') }}</span>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="{{ $hdrApproved ? route('my_interests.index') : 'javascript:void(0)' }}"
                                class="text-reset d-inline-block px-3 py-2 fw-600 {{ $hdrApproved ? areActiveRoutes(['my_interests.index','express-interest.index'],'text-primary-grad opacity-100') : 'opacity-40' }}"
                                @if(!$hdrApproved) title="{{ translate('Your account is pending admin approval') }}" @endif>
                                <span>{{ translate('Interest Sent') }}</span>
                                @if(!$hdrApproved)<i class="las la-lock ml-1"></i>@endif
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="{{ $hdrApproved ? route('interest_requests') : 'javascript:void(0)' }}"
                                class="text-reset d-inline-block px-3 py-2 fw-600 {{ $hdrApproved ? areActiveRoutes(['interest_requests'],'text-primary-grad opacity-100') : 'opacity-40' }}"
                                @if(!$hdrApproved) title="{{ translate('Your account is pending admin approval') }}" @endif>
                                <span>{{ translate('Interest Received') }}</span>
                                @if(!$hdrApproved)<i class="las la-lock ml-1"></i>@endif
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="{{ $hdrApproved ? route('my_shortlists') : 'javascript:void(0)' }}"
                                class="text-reset d-inline-block px-3 py-2 fw-600 {{ $hdrApproved ? areActiveRoutes(['my_shortlists'],'text-primary-grad opacity-100') : 'opacity-40' }}"
                                @if(!$hdrApproved) title="{{ translate('Your account is pending admin approval') }}" @endif>
                                <span>{{ translate('Shortlist') }}</span>
                                @if(!$hdrApproved)<i class="las la-lock ml-1"></i>@endif
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="{{ $hdrApproved ? route('all.messages') : 'javascript:void(0)' }}"
                                class="text-reset d-inline-block px-3 py-2 fw-600 {{ $hdrApproved ? areActiveRoutes(['all.messages'],'text-primary-grad opacity-100') : 'opacity-40' }}"
                                @if(!$hdrApproved) title="{{ translate('Your account is pending admin approval') }}" @endif>
                                <span>{{ translate('Messaging') }}</span>
                                @if(!$hdrApproved)<i class="las la-lock ml-1"></i>@endif
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="{{ $hdrApproved ? route('profile-viewers.index') : 'javascript:void(0)' }}"
                                class="text-reset d-inline-block px-3 py-2 fw-600 {{ $hdrApproved ? areActiveRoutes(['profile-viewers.index'],'text-primary-grad opacity-100') : 'opacity-40' }}"
                                @if(!$hdrApproved) title="{{ translate('Your account is pending admin approval') }}" @endif>
                                <span>{{ translate('Profile Viewers') }}</span>
                                @if(!$hdrApproved)<i class="las la-lock ml-1"></i>@endif
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        @endif
    </header>
</div>
