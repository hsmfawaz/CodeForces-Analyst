<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{url('css/style.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div id="app">
    <navbar></navbar>
    <home v-if="Pagename == 'home'"></home>
    <report v-if="Pagename == 'report'"></report>
    <latest v-if="Pagename == 'latest'"></latest>
    <pagefooter></pagefooter>
</div>
</body>
<script>
    Window.pagename = '{{ $name }}';
    @if($name == 'report')
        Window.ReportId = {
        'id': {{ $data->id }},
    };
    @endif
        @if($name == 'latest')
        Window.LatestItem = JSON.parse('{!! $data !!}');
    @endif
</script>
<script src="{{ mix('js/app.js') }}"></script>
</html>