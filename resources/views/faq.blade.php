<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <title>Contact Form</title>

    <script>
        let APP_URL = <?php echo \Psy\Util\Json::encode(url('/')); ?>;
    </script>
    <script defer src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>

    @include('partials.styles')

</head>

@include('partials.header')

<body class="container pt-5">
<div class="max-w-screen-xl mx-auto px-5 bg-white min-h-sceen">
    <div class="flex flex-col items-center">
        <h2 data-cy="faq-header" class="font-bold text-5xl mt-5 tracking-tight">
            FAQ
        </h2>
    </div>
    <div class="grid divide-y divide-neutral-200 max-w-xl mx-auto mt-8">
        @foreach($faq as $i => $item)
            <div class="py-3">
                <details data-cy="details-{{$i}}" class="group">
                    <summary class="flex justify-between items-center font-medium cursor-pointer list-none">
                        <span data-cy="title-{{ $i }}">{{ $item["title"] }}</span>
                        <span class="transition group-open:rotate-180">
                            <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24">
                                <path d="M6 9l6 6 6-6"></path>
                            </svg>
                        </span>
                    </summary>
                    <p data-cy="content-{{ $i }}" class="text-neutral-600 mt-3 group-open:animate-fadeIn">{{ $item["content"] }}</p>
                </details>
            </div>
        @endforeach
    </div>
</div>
</body>
</html>
