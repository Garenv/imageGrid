<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <link rel="canonical" href="https://www.phopixel.com/faq">
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
                    It's simple! All you need to do is describe the image you want in the text prompt and click submit. This image will be generated and will enter the competition.
                </p>
            </details>
        </div>
        <div class="py-3">
            <details class="group">
                <summary class="flex justify-between items-center font-medium cursor-pointer list-none">
                    <span>Am I allowed to generate more than one image?</span>
                    <span class="transition group-open:rotate-180">
                <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path>
</svg>
              </span>
                </summary>
                <p class="text-neutral-600 mt-3 group-open:animate-fadeIn">
                    You may only generate up to one image per day with 3 attempts so ensure that you've picked your best one!
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
                    <span>What can I win?</span>
                    <span class="transition group-open:rotate-180">
                <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path></svg>
              </span>
                </summary>
                <p class="text-neutral-600 mt-3 group-open:animate-fadeIn">
                    At the time of writing this, the top 100 images will be displayed on the front page for the week. Gain the admiration of your fellow Phopixelers and reach the top spot!
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
                    Our goal is to host a fun and interactive place that gives you the power to generate whatever image you desire which in turn hope to generate laughs, inspiration, bonds and more amongst your fellow Phopixelers!
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
    </div>
</body>
</html>
