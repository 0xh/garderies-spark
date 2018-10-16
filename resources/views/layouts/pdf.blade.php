<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        @font-face {
            font-family: 'Nunito';
            font-weight: normal;
            src: url({{storage_path('app/public/fonts/Nunito-Regular.ttf')}}) format("truetype");
        }
        @font-face {
            font-family: 'Nunito';
            font-weight: 300;
            src: url({{storage_path('app/public/fonts/Nunito-Light.ttf')}}) format("truetype");
        }
        @font-face {
            font-family: 'Nunito';
            font-weight: bold;
            src: url({{storage_path('app/public/fonts/Nunito-Bold.ttf')}}) format("truetype");
        }

        body {
            font-family: 'Nunito', sans-serif;
            font-weight: normal;
            font-size: 14px;
            color: #333;
            margin: 0;
            padding: 0;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Nunito', sans-serif;
            margin: .2rem 0 .1rem 0;
            padding: 0;
        }
        p {
            margin: 0 0 1rem 0;
            text-align: justify;
        }
        table {
            border-collapse: collapse;
        }
        .table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            padding-top: 0.25rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody + tbody {
            border-top: 2px solid #dee2e6;
        }

        .table .table {
            background-color: #f8fafc;
        }

        .table-sm th,
        .table-sm td {
            padding: 0.3rem;
            padding-top: 0.05rem;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .table-bordered thead th,
        .table-bordered thead td {
            border-bottom-width: 2px;
        }

        .table-borderless th,
        .table-borderless td,
        .table-borderless thead th,
        .table-borderless tbody + tbody {
            border: 0;
        }

    </style>
</head>
<body>
@yield('content')
</body>
</html>