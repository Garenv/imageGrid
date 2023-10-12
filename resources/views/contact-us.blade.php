<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <title>Contact Form</title>

    @include('partials.styles')
</head>


@include('partials.header')

<body class="container bg-gray-100 py-10 md:py-20">

<div class="max-w-md mx-auto bg-white p-6 rounded-md shadow-md mt-10 md:mt-20">
    <h2 class="text-2xl font-bold mb-5 text-gray-700">Contact Us</h2>
    <form action="your_action_url" method="post">
        @csrf  <!-- If you're using Laravel, add CSRF token -->

        <!-- Full Name Field -->
        <div class="mb-4">
            <label for="fullname" class="block text-sm font-semibold text-gray-600">Full Name</label>
            <input type="text" id="fullname" name="fullname" class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600" required>
        </div>

        <!-- Email Field -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-semibold text-gray-600">Email</label>
            <input type="email" id="email" name="email" class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600" required>
        </div>

        <!-- Message Field -->
        <div class="mb-4">
            <label for="message" class="block text-sm font-semibold text-gray-600">Message</label>
            <textarea id="message" name="message" rows="4" class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600" required></textarea>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full px-4 py-2 font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700">Send Message</button>
    </form>
</div>

</body>
</html>
