<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Generate AI images with the help of cutting-edge algorithms that produce high-quality, realistic visuals. Enhance your projects with advanced image generation technology, delivering stunning and impressive results efficiently.">
    <meta name="keywords" content="image, AI, Artificial Intelligence, ML, Machine Learning">
    <title>Phopixel</title>
    <meta content="og:title" name="Phopixel">
    <meta property="og:description" content="Be featured on the front page with the most upvoted generated image">
    <meta property="og:url" content="https://phopixel.com">
    <meta property="og:type" content="website">
    <meta name="google-adsense-account" content="ca-pub-7469058557975267">
    <link rel="icon" href="https://cruskip.s3.us-east-2.amazonaws.com/assets/images/phopix/logos/p_1081x1080_transparent_v2.1.jpg">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7469058557975267" crossorigin="anonymous"></script>

    <!-- Vendor CSS Files -->
    <link href="{{ asset('build/assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('build/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('build/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('build/assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('build/assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('build/assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('build/assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    @vite(['resources/css/style.css'])
</head>

<body>

@include('partials.header')

<!-- ======= Hero Section ======= -->
<section id="hero" class="d-flex align-items-center justify-content-center">
    <div class="container" data-aos="fade-up">

        <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="150">
            <div class="col-xl-6 col-lg-8">
                <h1 data-cy="description-1">Generate unique images with text prompts</h1>
                <h2 data-cy="description-2">Share the generated images with your friends and gain the most likes to be featured on the front page</h2>
            </div>
            <br>
        </div>

        <br>
        <br>
        <br>

        <button type="button" class="btn btn-dark" ><a href="/hall-of-fame"><span class="text-white">Check out the Hall of Fame!</span></a></button>

        <div class="row gy-4 mt-5 justify-content-center" data-aos="zoom-in" data-aos-delay="250">
            <div data-cy="create-account-icon" class="col-xl-2 col-md-4">
                <a href="/login">
                <div class="icon-box">
                    <i class="ri-account-box-line text-black"></i>
                    <h3 class="text-white">Create Account</h3><h1> > </h1>
                </div>
                </a>
            </div>
            <div data-cy="image-grid-icon" class="col-xl-2 col-md-4">
                <div class="icon-box">
                    <i class="ri-bar-chart-box-line text-black"></i>
                    <h3 class="text-white">Describe Your Image</h3><h1> > </h1>
                </div>
            </div>
            <div data-cy="upload-photo-icon" class="col-xl-2 col-md-4">
                <div class="icon-box">
                    <i class="bi-camera text-black"></i>
                    <h3 class="text-white">Click Submit</h3><h1> > </h1>
                </div>
            </div>
            <div data-cy="gain-likes-icon" class="col-xl-2 col-md-4">
                <div class="icon-box">
                    <i class="bi-hand-thumbs-up text-black"></i>
                    <h3 class="text-white">Gain Upvotes</h3><h1> > </h1>
                </div>
            </div>
            <div data-cy="win-prize-icon" class="col-xl-2 col-md-4">
                <div class="icon-box">
                    <i class="ri-gift-2-line text-black"></i>
                    <h3 class="text-white">Get Featured In Hall Of Fame</h3><h1> > </h1>
                </div>
            </div>
        </div>
    </div>
</section><!-- End Hero -->


    <!-- ======= About Section ======= -->
    <section id="about" class="about">
        <div class="container" data-aos="fade-up">

            <div class="row">
                <div class="col-lg-6 order-1 order-lg-2" data-aos="fade-left" data-aos-delay="100">
                    <img src="https://cruskip.s3.us-east-2.amazonaws.com/assets/images/phopix/logos/p_1081x1080_transparent_v2.1.jpg" class="img-fluid" alt="">
                </div>
                <div data-cy="phopixel-description" class="col-lg-6 pt-4 pt-lg-0 order-2 order-lg-1 content" data-aos="fade-right" data-aos-delay="100">
                    <h3>What is Phopixel?</h3>
                    <p>
                        Phopixel is the first website that ensures a fair and amiable opportunity for all to compete for the hall of fame based on how well liked their generated images are.
                        <br>
                        <br>
                        Here are a few things to note:
                    </p>
                    <ul>
                        <li><i class="ri-check-double-line"></i>Available worldwide</li>
                        <li><i class="ri-check-double-line"></i>It's free to join and participate</li>
                        <li><i class="ri-check-double-line"></i>Rankings of most upvoted images are determined each week and reset each week</li>
                        <li><i class="ri-check-double-line"></i>Inappropriate prompts are not allowed and will be rejected by the algorithm</li>
                        <li><i class="ri-check-double-line"></i>You may only generate an image once a day</li>
                        <li><i class="ri-check-double-line"></i>Visit the <a data-cy="faq-description-link" href="/faq">FAQ</a> section for more info!</li>
                    </ul>

                </div>
            </div>

        </div>
    </section><!-- End About Section -->

    <!-- ======= Features Section ======= -->
    <section id="features" class="features">
        <div class="container" data-aos="fade-up">
            <div class="row">
                <div class="image col-lg-6" style='background-image: url("https://phopixel.s3.amazonaws.com/landingPage/anime_robot.png"); background-size: 150%;' data-aos="fade-right"></div>
                <div data-cy="engineering-description" class="col-lg-6" data-aos="fade-left" data-aos-delay="100">
                    <div class="icon-box mt-5 mt-lg-0" data-aos="zoom-in" data-aos-delay="150">
                        <i class="bx bx-check-shield text-black"></i>
                        <h4>Engineering</h4>
                        <p>Explore the frontiers of creativity by harnessing the power of AI-driven image synthesis. Input your vision and watch as cutting-edge algorithms bring your vision into reality!</p>
                    </div>
                    <div data-cy="engineering-description2" class="icon-box mt-5" data-aos="zoom-in" data-aos-delay="150">
                        <i class="bx bx-user text-black"></i>
                        <h4>User Experience</h4>
                        <p>Engineered on top of AWS which means your experience will be nothing but smooth and seamless</p>
                    </div>
                    <div data-cy="engineering-description3" class="icon-box mt-5" data-aos="zoom-in" data-aos-delay="150">
                        <i class="bx ri-computer-line text-black"></i>
                        <h4>System</h4>
                        <p>We're continuously upgrading our systems and adding new features.  Feel free to <a data-cy="contact-us-description-link" href="/contact-us">contact us</a> if you have any suggestions!</p>
                    </div>
                </div>
            </div>

        </div>
    </section><!-- End Features Section -->

    <!-- ======= Services Section ======= -->
{{--    <section id="prizes" class="services">--}}
{{--        <div class="container" data-aos="fade-up">--}}

{{--            <div class="section-title">--}}
{{--                <p data-cy="prizes-text" class="text-center">Be featured on the front page!</p>--}}
{{--            </div>--}}

{{--            <div class="row">--}}

{{--                <div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4 mt-lg-0" data-aos="zoom-in" data-aos-delay="300">--}}
{{--                    <div class="icon-box">--}}
{{--                        <img src="https://cruskip.s3.us-east-2.amazonaws.com/assets/images/giftCards/50_visa_gc.jpg" class="img-fluid" alt="">--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4 mt-md-0" data-aos="zoom-in" data-aos-delay="200">--}}
{{--                    <div class="icon-box">--}}
{{--                        <img src="https://cruskip.s3.us-east-2.amazonaws.com/assets/images/giftCards/25_amazon_gc.jpg" class="img-fluid" alt="">--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="col-lg-4 col-md-6 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="100">--}}
{{--                    <div class="icon-box">--}}
{{--                        <img src="https://cruskip.s3.us-east-2.amazonaws.com/assets/images/giftCards/10_walmart_gc.jpg" class="img-fluid" alt="">--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--    </section><!-- End Services Section -->--}}
{{--    <h1 class="text-center pb-5">And more...</h1>--}}


<!-- ======= Footer ======= -->
<footer id="footer">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 footer-links">
                    <ul>
                        <li><i class="bx bx-chevron-right"></i> <a data-cy="faq-footer-link" href="/faq">FAQ</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a data-cy="terms-footer-link" href="/terms-and-conditions">Terms & Conditions</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a data-cy="privacy-footer-link" href="/privacy-policy">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div data-cy="copyright-text" class="copyright">
             Copyright &copy; <strong><span>{{ date('Y') }} Phopixel</span></strong>
            <br>
            All Rights Reserved.
        </div>
    </div>
</footer><!-- End Footer -->

<div id="preloader"></div>
<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->


<script src="{{ asset('build/assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
<script src="{{ asset('build/assets/vendor/aos/aos.js') }}"></script>
<script src="{{ asset('build/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('build/assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
<script src="{{ asset('build/assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('build/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('build/assets/vendor/php-email-form/validate.js') }}"></script>

@vite(['resources/js/main.js'])
<!-- Template Main JS File -->


</body>

</html>
