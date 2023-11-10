<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Ensure you link Tailwind CSS in your HTML or have it configured in your build process -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Link Alpine.js for interactive functionality -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
    <title>Contact Form</title>

    <script>
        let APP_URL = <?php echo \Psy\Util\Json::encode(url('/')); ?>;
    </script>

    @vitereactrefresh
    @include('partials.styles')
</head>

    @include('partials.header')
    <body class="container bg-gray-100 py-10 pt-5">
        <div id="contact-us"></div>
        @include('partials.js')
    </body>

</html>
