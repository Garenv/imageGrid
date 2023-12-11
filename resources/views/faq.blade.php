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
        <h2 class="font-bold text-5xl mt-5 tracking-tight">
            FAQ
        </h2>
    </div>
    <div class="grid divide-y divide-neutral-200 max-w-xl mx-auto mt-8">
        <div class="py-3">
            <details class="group">
                <summary class="flex justify-between items-center font-medium cursor-pointer list-none">
                    <span>Is it free and is there a catch?</span>
                    <span class="transition group-open:rotate-180">
                <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path></svg>
              </span>
                </summary>
                <p class="text-neutral-600 mt-3 group-open:animate-fadeIn">
                    There's no catch at all, it's completely free to participate and always will be.  We'll never ask you for payment information simply because, again, it's completely free.
                </p>
            </details>
        </div>
        <div class="py-3">
            <details class="group">
                <summary class="flex justify-between items-center font-medium cursor-pointer list-none">
                    <span>How does it work?</span>
                    <span class="transition group-open:rotate-180">
                <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path>
</svg>
              </span>
                </summary>
                <p class="text-neutral-600 mt-3 group-open:animate-fadeIn">
                    It's simple! All you need to do is upload a photo and every Sunday at 12:00am EST there will be a 1st, 2nd and 3rd place winner based on the number of likes your photo accumulated over the week.  Each winner will receive a prize consistent with which place they came in.
                </p>
            </details>
        </div>
        <div class="py-3">
            <details class="group">
                <summary class="flex justify-between items-center font-medium cursor-pointer list-none">
                    <span>Where's Phopixel based?</span>
                    <span class="transition group-open:rotate-180">
                <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path></svg>
              </span>
                </summary>
                <p class="text-neutral-600 mt-3 group-open:animate-fadeIn">
                    We're based in the city that never sleeps, NYC!
                </p>
            </details>
        </div>
        <div class="py-3">
            <details class="group">
                <summary class="flex justify-between items-center font-medium cursor-pointer list-none">
                    <span>How many photos can the grid contain?</span>
                    <span class="transition group-open:rotate-180">
                <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path></svg>
              </span>
                </summary>
                <p class="text-neutral-600 mt-3 group-open:animate-fadeIn">
                    The grid can only hold 200 photos per week, so it's imperative to upload your photo early in the week so you have more time to accumulate likes and increase your chances of winning!
                </p>
            </details>
        </div>
        <div class="py-3">
            <details class="group">
                <summary class="flex justify-between items-center font-medium cursor-pointer list-none">
                    <span>What prizes can I win?</span>
                    <span class="transition group-open:rotate-180">
                <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path></svg>
              </span>
                </summary>
                <p class="text-neutral-600 mt-3 group-open:animate-fadeIn">
                    At the time of writing this, we're awarding gift cards to winners.  However, this will change overtime for more appealing prizes as the product grows.
                </p>
            </details>
        </div>
        <div class="py-3">
            <details class="group">
                <summary class="flex justify-between items-center font-medium cursor-pointer list-none">
                    <span>What's the goal of Phopixel?</span>
                    <span class="transition group-open:rotate-180">
                <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path>
</svg>
              </span>
                </summary>
                <p class="text-neutral-600 mt-3 group-open:animate-fadeIn">
                    Our goal is to give something back to the world and what better way to do that than giving out prizes to people who won them fair and square?
                </p>
            </details>
        </div>
        <div class="py-3">
            <details class="group">
                <summary class="flex justify-between items-center font-medium cursor-pointer list-none">
                    <span>What does Phopixel mean?</span>
                    <span class="transition group-open:rotate-180">
                <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path>
</svg>
              </span>
                </summary>
                <p class="text-neutral-600 mt-3 group-open:animate-fadeIn">
                    Phopixel is a combination of two words - Photo and Pixel which's what the product revolves around.
                </p>
            </details>
        </div>
        <div class="py-3">
            <details class="group">
                <summary class="flex justify-between items-center font-medium cursor-pointer list-none">
                    <span>Can international users win prizes?</span>
                    <span class="transition group-open:rotate-180">
                <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path>
</svg>
              </span>
                </summary>
                <p class="text-neutral-600 mt-3 group-open:animate-fadeIn">
                    Yes, international winners will receive prizes that are most suitable for the country in which they reside.  The support team will personally reach out to international winners to make the process smoother for them.
                </p>
            </details>
        </div>
</div>

</body>
</html>
