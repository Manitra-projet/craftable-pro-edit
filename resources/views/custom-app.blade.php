<!DOCTYPE html>
<html class="h-full bg-gray-100/50" lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <title>{{ ___('custom-app', 'Custom App') }}</title>

    @routes

    @vite(['resources/js/custom-app/index.ts', 'resources/css/custom-app.css'])

    @inertiaHead
</head>

<body class="h-full">
    @inertia
</body>

</html>
