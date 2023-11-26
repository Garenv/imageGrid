<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
<h1 class="text-center">Privacy Policy</h1>
<p class="text-center">Updated 10/13/2023</p>

    <body style="padding-top: 7rem;">
        <section class="bg-white p-6 rounded-md shadow-md max-w-3xl mx-auto pt-1">
            <h2 class="text-2xl font-semibold mb-4 pt-3">At Phopixel, we are committed to protecting your privacy. This privacy policy explains how we
                collect,
                use, and share information about you when you use our website, mobile application, and any other
                services provided by Phopixel (collectively, the "Services").</h2>
            <p class="mb-4">We collect information about you in several ways when you use the Services. This includes:</p>

            <h2 class="text-2xl font-semibold mb-4">Information We Collect</h2>
            <ul class="mb-4 list-disc pl-5">
                <li>Information you provide to us: We collect information you provide to us when you create an
                    account, upload photos, or participate in other features of the Services. This may include your
                    name, email address, and other personal information.
                </li>
                <li>
                    Information we collect automatically: We automatically collect certain information about you
                    when you use the Services, such as your IP address, device type, and browser type. We may also
                    collect information about your location and your interactions with the Services, such as the
                    photos you view and the actions you take.
                </li>
                <li>
                    Information from third parties: We may receive information about you from third parties, such as
                    social media platforms, in order to provide you with a better experience and to personalize your
                    use of the Services.
                </li>
            </ul>

            <h2 class="text-2xl font-semibold mb-4">How We Use Your Information</h2>
            <p class="mb-4">We use your information to provide, support, personalize, and develop our Services. Specific ways we use your information include:</p>
            <ul class="mb-4 list-disc pl-5">
                <li>
                    Providing, maintaining, and improving the Services: We use the information we collect to
                    provide, maintain, and improve the Services, including to personalize your experience, to allow
                    you to interact with other users, and to provide customer support.
                </li>

                <li>
                    Communicating with you: We may use the information we collect to send you updates about the
                    Services, to respond to your inquiries, and to notify you about changes to the Services.
                </li>

                <li>
                    Research and development: We may use the information we collect to conduct research and
                    development, to improve the Services, and to develop new products and features.
                </li>
            </ul>

            <h2 class="text-2xl font-semibold mb-4">Sharing of Your Information</h2>
            <p class="mb-4">We do not sell or rent your personal information to third parties for their marketing purposes
                without your explicit consent. However, we may share your information with third parties in the
                following circumstances:</p>
            <ul class="mb-4 list-disc pl-5">
                <li>
                    With service providers: We may share your information with third-party service providers who
                    perform services on our behalf, such as hosting, analytics, and customer support.
                </li>

                <li>
                    With business partners: We may share your information with our business partners for marketing
                    or other purposes.
                </li>

                <li>
                    For legal reasons: We may disclose your information if required to do so by law, or if we
                    believe in good faith that such action is necessary to comply with legal process, to protect the
                    rights or property of Phopixel, or to protect the personal safety of our users or the public.
                </li>
            </ul>

            <h2 class="text-2xl font-semibold mb-4">Security of Your Information</h2>
            <p class="mb-4">We take reasonable measures to protect the information we collect from loss, misuse, and
                unauthorized access, disclosure, alteration, and destruction. However, no internet or email
                transmission is ever fully secure or error-free, so you should take special care in deciding what
                information you send to us.</p>

            <h2 class="text-2xl font-semibold mb-4">Changes to This Privacy Policy</h2>
            <p class="mb-4">We may update this privacy policy from time to time. We will post any changes on this page and, if
                the changes are significant, we will provide a more prominent notice (including, for certain
                Services, email notification of privacy policy changes). We encourage you to review this privacy
                policy whenever you access the Services to stay informed about our information practices and your
                choices.</p>

            <h2 class="text-2xl font-semibold mb-4">Contact Us</h2>
            <p class="mb-4">If you have any questions or concerns about this privacy policy, please contact us at
                <a href="mailto:support@phopixel.com">support@phopixel.com</a>.
            </p>
        </section>
    </body>

</html>
