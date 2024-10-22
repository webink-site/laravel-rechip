<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Подключение ассетов через Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Подключение стилей Filament -->
    @filamentStyles
    @livewireStyles
</head>
<body>
{{ $slot }} <!-- Вставка содержимого страницы Filament -->

@livewireScripts
@filamentScripts
</body>
</html>