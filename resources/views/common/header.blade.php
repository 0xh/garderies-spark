<header class="header">
    <!-- Navigation -->
    @if (Auth::check())
        @include('spark::nav.user')
    @else
        @include('spark::nav.guest')
    @endif

    @include('common.nav-mobile')
</header>
