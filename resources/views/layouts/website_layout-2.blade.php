<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Educino - Online Courses and Education HTML Template - Home Two</title>
    <meta name="author" content="Vecuro">
    <meta name="description" content="Educino - Online Courses and Education HTML Template">
    <meta name="keywords"
        content="academic, artist, center, club, coach, college, drive, driving, education, entertainment, gambling, golf, jackpot, knowledge, money, multipurpose, music, song, student">
    <meta name="robots" content="INDEX,FOLLOW">

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Favicons - Place favicon.ico in the root directory -->
    <link rel="shortcut icon" href="{{config('app.url')}}/website_assets/img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="{{config('app.url')}}/website_assets/img/favicon.ico" type="image/x-icon">

    <!--==============================
	  Google Fonts
	============================== -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">


    <!--==============================
	    All CSS File
	============================== -->
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{config('app.url')}}/website_assets/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="{{config('app.url')}}/website_assets/css/app.min.css"> -->
    <!-- Fontawesome Icon -->
    <link rel="stylesheet" href="{{config('app.url')}}/website_assets/css/fontawesome.min.css">
    <!-- Magnific Popup -->
    <link rel="stylesheet" href="{{config('app.url')}}/website_assets/css/magnific-popup.min.css">
    <!-- Slick Slider -->
    <link rel="stylesheet" href="{{config('app.url')}}/website_assets/css/slick.min.css">
    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="{{config('app.url')}}/website_assets/css/style.css">

</head>


<body>


    <!--[if lte IE 9]>
    	<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
  <![endif]-->



    <!--********************************
   		Code Start From Here 
	******************************** -->


    <!--==============================
     Preloader
  ==============================-->
    <div class="preloader  ">
        <button class="vs-btn preloaderCls">Cancel Preloader </button>
        <div class="preloader-inner">
            <div class="loader"></div>
        </div>
    </div>
    <!--==============================
    Mobile Menu
  ============================== -->
    <div class="vs-menu-wrapper">
        <div class="vs-menu-area text-center">
            <button class="vs-menu-toggle"><i class="fal fa-times"></i></button>
            <div class="mobile-logo">
                <a href="index.html"><img src="{{config('app.url')}}/website_assets/img/common_logo.png"
                        alt="Educino"></a>
            </div>
            <div class="vs-mobile-menu">
                <ul>
                    <li>
                        <a href="/">Home</a>
                    </li>
                    <li>
                        <a href="about.html">About Us</a>
                    </li>
                    <!-- <li class="menu-item-has-children">
                        <a href="course.html">Courses</a>
                        <ul class="sub-menu">
                            <li><a href="course.html">Courses 1</a></li>
                            <li><a href="courses-2.html">Courses 2</a></li>
                            <li><a href="course-details.html">Courses Details 1</a></li>
                            <li><a href="course-details-2.html">Courses Details 2</a></li>
                        </ul>
                    </li> -->

                    <li>
                        <a href="contact.html">Gallery</a>
                    </li>
                    <li>
                        <a href="contact.html">Result</a>
                    </li>
                    <li>
                        <a href="contact.html">Contact Us</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!--==============================
    Popup Search Box
    ============================== -->
    <div class="popup-search-box d-none d-lg-block  ">
        <button class="searchClose"><i class="fal fa-times"></i></button>
        <form action="#">
            <input type="text" class="border-theme" placeholder="What are you looking for">
            <button type="submit"><i class="fal fa-search"></i></button>
        </form>
    </div>
    <!--==============================
  Header Area
  ==============================-->

    <header class="vs-header header-layout2">
        <div class="header-top">
            <div class="container">
                <div class="row justify-content-between align-items-center gx-50">
                    <div class="col d-none d-xl-block">
                        <div class="header-links style2">
                            <ul>
                                <li><i class="fas fa-phone-alt"></i>Phone: <a href="+440076897888">+44 (0) 207 689
                                        7888</a></li>
                                <li><i class="fas fa-envelope"></i>Email: <a
                                        href="mailto:info@company.co.uk">info@company.co.uk</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-auto">
                        <a class="user-login style2" href="login-register.html"><i class="fas fa-user-circle"></i> Login
                            & Register</a>
                    </div>
                    <div class="col-auto">
                        <div class="header-social style2">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="sticky-wrapper">
            <div class="sticky-active">
                <div class="container position-relative z-index-common">
                    <div class="row gx-50 align-items-center justify-content-between">
                        <div class="col-auto col-xl align-self-stretch">
                            <div class="vs-logo style2">
                                <a href="index.html"><img src="{{config('app.url')}}/website_assets/img/common-logo.png"
                                        alt="logo" style="height: 150px; object-fit: contain;"></a>
                            </div>
                        </div>
                        <div class="col-auto">
                            <nav class="main-menu menu-style3 d-none d-lg-block">
                                <ul>
                                    <li>
                                        <a href="{{route('home')}}">Home</a>
                                    </li>
                                    <li> <a href="{{route('about')}}">About Us</a> </li>
                                    <!-- <li class="menu-item-has-children">
                                        <a href="course.html">Courses</a>
                                        <ul class="sub-menu">
                                            <li><a href="course.html">Courses 1</a></li>
                                            <li><a href="courses-2.html">Courses 2</a></li>
                                            <li><a href="course-details.html">Course Details 1</a></li>
                                            <li><a href="course-details-2.html">Course Details 2</a></li>
                                        </ul>
                                    </li> -->

                                    <li>
                                        <a href="{{route('gallery')}}">Gallery</a>
                                    </li>
                                    <li>
                                        <a href="{{route('result')}}">Result</a>
                                    </li>
                                    <li>
                                        <a href="{{route('contact-us')}}">Contact Us</a>
                                    </li>
                                </ul>
                            </nav>
                            <button class="vs-menu-toggle d-inline-block d-lg-none"><i class="fal fa-bars"></i></button>
                        </div>
                        <div class="col-auto d-none d-xl-block">
                            <div class="header-btns style2">
                                <button type="button" class="searchBoxTggler"><i class="far fa-search"></i></button>
                                <a href="{{route('find-courses')}}" class="vs-btn style6"><i
                                        class="fal fa-graduation-cap"></i> Find Courses</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    @yield('content')

    <!--==============================
    Footer Area
  ==============================-->
    <footer class="footer-wrapper footer-layout2  "
        data-bg-src="{{config('app.url')}}/website_assets/img/bg/footer-bg-2-1.jpg">
        <div class="widget-area">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-md-6 col-xl-3">
                        <div class="widget footer-widget">
                            <div class="vs-widget-about">
                                <div class="footer-logo"> <a href="index.html"><img
                                            src="{{config('app.url')}}/website_assets/img/logo-white.svg"
                                            alt="logo"></a> </div>
                                <p class="footer-text">Lorem ipsum dolor sit amet, conse ctet rem ipsdolor sit amet. sum
                                    do lor sit amet, consectet edolor sit amet, comod.</p>
                                <div class="footer-media">
                                    <div class="media-icon"><i class="fas fa-file-pdf"></i></div>
                                    <div class="media-body">
                                        <span class="media-title">Universty Prospects</span>
                                        <a href="#">Download.pdf</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="widget footer-widget">
                            <h3 class="widget_title">Find Campus</h3>
                            <div class="footer-campus">
                                <div class="campus-img mega-hover">
                                    <a href="#"><img class="w-100"
                                            src="{{config('app.url')}}/website_assets/img/about/about-2-5.jpg"
                                            alt="Campus Image"></a>
                                </div>
                                <p class="campus-address"><i class="far fa-map-marker"></i>1309 Beacon Street, Suite
                                    300, Brookline, MA, 02446</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="widget nav_menu footer-widget">
                            <h3 class="widget_title">Popular Subjects</h3>
                            <div class="menu-all-pages-container footer-menu">
                                <ul class="menu">
                                    <li><a href="course.html">Business and Management</a></li>
                                    <li><a href="course.html">Healthcare and Medicine</a></li>
                                    <li><a href="course.html">Teaching</a></li>
                                    <li><a href="course.html">Psychology and Mental Health</a></li>
                                    <li><a href="course.html">IT and Computer Science</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="widget footer-widget">
                            <div class="contact-style1">
                                <h4 class="contact-title">Looking to study with us?</h4>
                                <p class="contact-text">Speak to an adviser</p>
                                <a href="tel:+00123456789" class="contact-number h5"><i class="far fa-phone-alt"></i>
                                    (44) 123 456 789</a>
                                <a href="tel:+88123555787" class="contact-number h5"><i class="far fa-phone-alt"></i>
                                    (88) 123 555 787</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright-wrap">
            <div class="container">
                <div class="row justify-content-between align-items-center">
                    <div class="text-center col-lg-auto">
                        <p class="copyright-text">Copyright <i class="fal fa-copyright"></i> 2023 <a
                                href="{{route('home')}}">RHBP</a>. All Rights Reserved</p>
                    </div>
                    <div class="col-auto d-none d-lg-block">
                        <div class="social-style1">
                            <a href="#"><i class="fab fa-facebook-f"></i>Facebook</a>
                            <a href="#"><i class="fab fa-twitter"></i>Twitter</a>
                            <a href="#"><i class="fab fa-linkedin-in"></i>Linked In</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer> <!-- Scroll To Top -->
    <a href="#" class="scrollToTop scroll-btn"><i class="far fa-arrow-up"></i></a>

    <!--********************************
			Code End  Here 
	******************************** -->

    <!--==============================
        All Js File
    ============================== -->
    <!-- Jquery -->
    <script src="{{config('app.url')}}/website_assets/js/vendor/jquery-3.6.0.min.js"></script>
    <!-- Slick Slider -->
    <script src="{{config('app.url')}}/website_assets/js/slick.min.js"></script>
    <!-- <script src="{{config('app.url')}}/website_assets/js/app.min.js"></script> -->
    <!-- Bootstrap -->
    <script src="{{config('app.url')}}/website_assets/js/bootstrap.min.js"></script>
    <!-- Wow.js Animation -->
    <script src="{{config('app.url')}}/website_assets/js/wow.min.js"></script>
    <!-- Magnific Popup -->
    <script src="{{config('app.url')}}/website_assets/js/jquery.magnific-popup.min.js"></script>
    <!-- Main Js File -->
    <script src="{{config('app.url')}}/website_assets/js/main.js"></script>

</body>

</html>