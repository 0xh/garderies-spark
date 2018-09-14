<nav class="navbar navbar-light navbar-expand navbar-spark">
    <div class="container">
        <!-- Branding Image -->
        @include('spark::nav.brand')

        <div id="navbarSupportedContent" class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/login">{{__('Login')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/register">{{__('Register')}}</a>
                </li>
            </ul>
        </div>
    </div>
</nav>