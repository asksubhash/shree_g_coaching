@extends('layouts.website_layout')

@section('content')
<!--==============================
    Breadcumb
============================== -->
<div class="bg-body-tertiary">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb p-3 bg-body-tertiary rounded-3">
                <li class="breadcrumb-item">
                    <a class="link-body-emphasis" href="#">
                        <i class="fa fa-home"></i>
                        <span class="visually-hidden">Home</span>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Contact Us
                </li>
            </ol>
        </nav>
    </div>
</div>
<!--==============================
  Contact Area
  ==============================-->
<section class="pt-3">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-xl-6 mb-30 mb-lg-0">
                <h2 class="">Get in Touch to Learn About Programmes</h2>
                <p class="fs-md mb-4 pb-2">become a partner school, or discover more about our work.</p>
                <h3 class="border-title2 h5">Regional Office</h3>
                <!-- <p class="contact-info">
                    <i class="fas fa-clock"></i>
                    Office hours are 9am – 5pm <br> Monday-Thursday and 9am – 4.30pm on Friday.
                </p> -->
                <p class="contact-info">
                    <i class="fas fa-map-marker-alt"></i>
                    General Secretary, <br>
                    Odisha Rastrabhasa Parishad, Jagannath Dham, <br>
                    At/Po- Kalikadebi Sahi, Puri, <br>
                    Odisha-752001.
                </p>
                <p class="contact-info">
                    <i class="fas fa-phone-alt"></i>
                    <a class="text-inherit" href="#">7853980684</a>
                </p>
                <p class="contact-info">
                    <i class="fas fa-phone-alt"></i>
                    <a class="text-inherit" href="#">7683882331</a>
                </p>
                <p class="contact-info">
                    <i class="fab fa-whatsapp text-success"></i>
                    <a class="text-inherit" href="#">7853980684</a>,
                    <a class="text-inherit" href="#">7683882331</a>,
                    <a class="text-inherit" href="#">9777930545</a>
                </p>
                <p class="contact-info">
                    <i class="fas fa-envelope"></i>
                    <a class="text-inherit" href="mailto:odisharastrabhasa@gmail.com">odisharastrabhasa@gmail.com</a>
                </p>
            </div>
            <div class="col-lg-6 col-xl-6">
                <form action="#" class="form-style2" id="enquiryForm">
                    @csrf
                    <div class="form-inner">
                        <h3 class="form-title h5 mb-3">Join over <span class="text-theme">50,000 students</span>
                            who’ve
                            now
                            registered for their courses. Don’t miss out.</h3>
                        <div class="">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control rounded-3" id="floatingFullName" placeholder="Full Name" name="enqName">
                                <label for="floatingFullName">Full Name</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="email" class="form-control rounded-3" id="floatingInput" placeholder="name@example.com" name="enqEmailId">
                                <label for="floatingInput">Email address</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control rounded-3" id="floatingPhoneNumber" placeholder="Phone Number" name="enqPhoneNumber">
                                <label for="floatingPhoneNumber">Phone Number</label>
                            </div>

                            <div class="form-floating mb-3">
                                <textarea class="form-control rounded-3" id="floatingMessage" placeholder="Message" rows="3" style="height: 100px;" name="enqMessage"></textarea>
                                <label for="floatingMessage">Message</label>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <span class="text-center mr-2 captchaSpan">
                                        {!! captcha_img('inverse') !!}
                                    </span>
                                    <button type="button" class="btn btn-refresh-captcha ml-1" id="reloadCaptcha">
                                        <span class="fa fa-sync"></span>
                                    </button>
                                </div>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control rounded-3" id="floatingCaptcha" placeholder="Captcha" name="enqCaptcha">
                                <label for="floatingCaptcha">Captcha</label>
                            </div>

                            <div class="text-center">
                                <button class="mb-2 btn btn-lg rounded-3 btn-custom" type="submit">
                                    <i class="fa fa-paper-plane"></i> Send
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="vs-circle color2"></div>
                </form>
            </div>
        </div>
    </div>
</section>


<div class="pt-4 pb-0">
    <div class="">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3753.887634803562!2d85.81741857499686!3d19.802360981557598!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a19c426517f1c15%3A0x7233e4ba9b72658f!2z4KST4KSh4KS_4KS24KS-IOCksOCkvuCkt-CljeCkn-CljeCksOCkreCkvuCkt-CkviDgpKrgpLDgpL_gpLfgpKYgSGVhZCBPZmZpY2UgLE9kaXNoYQ!5e0!3m2!1sen!2sin!4v1703574278756!5m2!1sen!2sin" height="450" style="border:0; width: 100%;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

    </div>
</div>
@endsection