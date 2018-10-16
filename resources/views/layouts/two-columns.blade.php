<!doctype html>
<html lang="{{ app()->getLocale() }}">
    @include('common.head')
    <body class="@yield('body-class')">
        <div class="wrapper" id="spark-app" v-cloak>
            @include('common.header')
            <div class="main">
                @yield('hook-vue')
                <div class="container">
                    <div class="row">
                        <div class="col-md-3 col-xl-2 mb-4 mb-lg-0 d-print-none">
                            @yield('nav-lateral')
                        </div>
                        <div class="col-md-9 col-xl-10 content d-print-block">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
            @include('common.footer')

            <!-- Application Level Modals -->
            @if (Auth::check())
                @include('spark::modals.notifications')
                @include('spark::modals.support')
                @include('spark::modals.session-expired')
            @endif
        </div>
        @include('common.scripts')
    </body>
</html>