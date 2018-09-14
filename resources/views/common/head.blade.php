<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{config('app.name')}} - @yield('title')</title>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <meta name="csrf-token" content="{{csrf_token()}}">

    <link rel="icon" href="{{asset('img/favicon-32x32.png')}}" sizes="32x32" />
    <link rel="icon" href="{{asset('img/favicon-192x192.png')}}" sizes="192x192" />
    <link rel="apple-touch-icon-precomposed" href="{{asset('img/favicon-180x180.png')}}" />
    <meta name="msapplication-TileImage" content="{{asset('img/favicon-270x270.png')}}" />

    @yield('styles')
    @yield('scripts-head')

    <!-- Global Spark Object -->
    <script>
        window.Spark = <?php echo json_encode(array_merge(
            Spark::scriptVariables(), []
        )); ?>;
    </script>
</head>
