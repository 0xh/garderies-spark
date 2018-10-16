<!doctype html>
<html lang="{{ app()->getLocale() }}">
    @include('common.head')
    <body class="@yield('body-class')">
        <div class="wrapper" id="spark-app" v-cloak>
            <div class="main">
                @yield('hook-vue')
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
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