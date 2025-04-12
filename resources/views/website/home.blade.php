@extends('layouts.website_layout')

@section('content')

{{-- SLIDER --}}
<div id="carouselExampleIndicators" class="carousel slide">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="{{asset('assets/images/banner/banner1.jpg')}}" class="d-block w-100" alt="Banner">
        </div>
        <div class="carousel-item">
            <img src="{{asset('assets/images/banner/banner2.jpg')}}" class="d-block w-100" alt="Banner">
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

{{-- About Us --}}
<section>
    <div class="container">
        <div class="row flex-lg-row-reverse align-items-center g-5 py-4">
            <div class="col-10 col-sm-8 col-lg-6">
                <div class="p-4 shadow-sm rounded">
                    <img src="https://cdn.pixabay.com/photo/2018/07/05/16/59/students-3518726_1280.jpg" class="d-block mx-lg-auto img-fluid" alt="Bootstrap Themes" width="700" height="500" loading="lazy">
                </div>
            </div>
            <div class="col-lg-6">
                <p class="text-uppercase mb-1 text-custom fs-5">
                    Welcome to Odisha Rastravasa Parisad, Puri
                </p>
                <h4 class="fs-1 fw-bold text-body-emphasis lh-1 mb-3">Take Your Learning Organization to The Next
                    Level.</h4>
                <p class="mb-3 text-muted text-justify">
                    Odisha Rastravasa Parisad, Jagannath Dham, Puri is an Voluntary Hindi Organisation and Permanent Recognition by Ministry of Human Resource Development (MHRD), Govt. of India (Regd. No. 7632/73-74 old Regd No 10 of 1954). Odisha Rastrabhasa Parisad is accredited with Quality Council of India (QCI) and ISO 9001:2015 Certification. The examinations conduct by the Organisation S.L.C. (Vinod), Inter (Praveen), BA (Shastri).
                </p>
                <div class="d-grid gap-2 d-md-flex justify-content-md-start mt-4">
                    <a href="{{route('about')}}" class="btn btn-custom-outline btn-lg"><i class="fas fa-book"></i> Read More</a>
                    <a href="{{route('contact-us')}}" class="btn btn-custom btn-lg"><i class="fas fa-phone"></i> Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-light">
    <div class="container px-4 py-5" id="featured-3">

        <div class="title-area text-center"><span class="sec-subtitle text-custom">VIEW OUR INSTITUTE COURSES</span>
            <h2 class="sec-title h1">Our Courses</h2>
        </div>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            <div class="col">
                <div class="card shadow-sm border-0">
                    <img src="https://images.pexels.com/photos/1206101/pexels-photo-1206101.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" class="w-100" alt="Image">
                    <div class="card-body">
                        <h5 class="">Prathamika</h5>
                        <p class="card-text">Prathamika course is an introductory level program aimed at providing foundational knowledge and skills, often serving as the initial step in a broader educational curriculum. </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-custom">
                                    <i class="fa fa-paper-plane"></i> Apply Now
                                </button>
                            </div>
                            <small class="text-body-secondary">9 mins</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card shadow-sm border-0">
                    <img src="https://cdn.pixabay.com/photo/2014/03/12/18/45/boys-286245_1280.jpg" class="w-100" alt="Image">
                    <div class="card-body">
                        <h5 class="">Bodhini</h5>
                        <p class="card-text">Bodhini is an introductory or foundational course, offering basic knowledge and insights to initiate learners into a specific field of study.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-custom">
                                    <i class="fa fa-paper-plane"></i> Apply Now
                                </button>
                            </div>
                            <small class="text-body-secondary">9 mins</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card shadow-sm border-0">
                    <img src="https://cdn.pixabay.com/photo/2020/05/23/20/08/books-5211309_1280.jpg" class="w-100" alt="Image">
                    <div class="card-body">
                        <h5 class="">Madhyamika</h5>
                        <p class="card-text">Madhyamika represents an intermediate level course, providing a deeper exploration of subjects beyond the basics, building a solid understanding for advanced studies.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-custom">
                                    <i class="fa fa-paper-plane"></i> Apply Now
                                </button>
                            </div>
                            <small class="text-body-secondary">9 mins</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card shadow-sm border-0">
                    <img src="https://cdn.pixabay.com/photo/2018/09/04/10/27/never-stop-learning-3653430_1280.jpg" class="w-100" alt="Image">
                    <div class="card-body">
                        <h5 class="">Vinod(10th hindi equivalent)</h5>
                        <p class="card-text">Vinod(10th hindi equivalent) denotes an advanced level program, offering specialized and in-depth knowledge in a particular subject, often preparing students for professional or academic expertise.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-custom">
                                    <i class="fa fa-paper-plane"></i> Apply Now
                                </button>
                            </div>
                            <small class="text-body-secondary">9 mins</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card shadow-sm border-0">
                    <img src="https://cdn.pixabay.com/photo/2016/01/19/01/42/library-1147815_1280.jpg" class="w-100" alt="Image">
                    <div class="card-body">
                        <h5 class="">Prabina(12th hindi equivalent)</h5>
                        <p class="card-text">Prabina(12th hindi equivalent) is an expert or master's level course, emphasizing advanced research, critical thinking, and specialized skills in a specific field.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-custom">
                                    <i class="fa fa-paper-plane"></i> Apply Now
                                </button>
                            </div>
                            <small class="text-body-secondary">9 mins</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card shadow-sm border-0">
                    <img src="https://images.pexels.com/photos/8617970/pexels-photo-8617970.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" class="w-100" alt="Image">
                    <div class="card-body">
                        <h5 class="">Shastri(B.A. in Hindi equivalent)</h5>
                        <p class="card-text">Shastri(B.A. in Hindi equivalent) is an academic degree, usually at the undergraduate level, offering a comprehensive study of traditional Indian knowledge systems and associated disciplines.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-custom">
                                    <i class="fa fa-paper-plane"></i> Apply Now
                                </button>
                            </div>
                            <small class="text-body-secondary">9 mins</small>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="text-center mt-4">
            <button type="button" class="btn btn-custom-outline btn-lg"><i class="fa fa-eye"></i> View All
                Courses</button>
        </div>
    </div>
</section>

<section>
    <div class="container px-4 py-5" id="custom-cards">
        <div class="title-area text-center"><span class="sec-subtitle text-custom">VIEW OUR GALLERY IMAGES</span>
            <h2 class="sec-title h1">Our Gallery</h2>
        </div>

        <div class="row row-cols-1 row-cols-lg-4 align-items-stretch g-4 py-5">
            <div class="col">
                <div class="card text-bg-dark">
                    <img src="https://images.pexels.com/photos/1205651/pexels-photo-1205651.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" class="card-img" alt="...">
                    <div class="card-img-overlay">
                        <p class="card-text"><small>Last updated 3 mins ago</small></p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-bg-dark">
                    <img src="https://images.pexels.com/photos/2608517/pexels-photo-2608517.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" class="card-img" alt="...">
                    <div class="card-img-overlay">
                        <p class="card-text"><small>Last updated 3 mins ago</small></p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-bg-dark">
                    <img src="https://images.pexels.com/photos/159844/cellular-education-classroom-159844.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" class="card-img" alt="...">
                    <div class="card-img-overlay">
                        <p class="card-text"><small>Last updated 3 mins ago</small></p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-bg-dark">
                    <img src="https://images.pexels.com/photos/5676744/pexels-photo-5676744.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" class="card-img" alt="...">
                    <div class="card-img-overlay">
                        <p class="card-text"><small>Last updated 3 mins ago</small></p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-bg-dark">
                    <img src="https://images.pexels.com/photos/2608517/pexels-photo-2608517.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" class="card-img" alt="...">
                    <div class="card-img-overlay">
                        <p class="card-text"><small>Last updated 3 mins ago</small></p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-bg-dark">
                    <img src="https://images.pexels.com/photos/5676744/pexels-photo-5676744.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" class="card-img" alt="...">
                    <div class="card-img-overlay">
                        <p class="card-text"><small>Last updated 3 mins ago</small></p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-bg-dark">
                    <img src="https://images.pexels.com/photos/2608517/pexels-photo-2608517.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" class="card-img" alt="...">
                    <div class="card-img-overlay">
                        <p class="card-text"><small>Last updated 3 mins ago</small></p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-bg-dark">
                    <img src="https://images.pexels.com/photos/2608517/pexels-photo-2608517.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" class="card-img" alt="...">
                    <div class="card-img-overlay">
                        <p class="card-text"><small>Last updated 3 mins ago</small></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-2">
            <button type="button" class="btn btn-custom-outline btn-lg"><i class="fa fa-eye"></i> View All
                Images</button>
        </div>
    </div>
</section>

<section class="py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-7 text-center text-xl-start">
                <div class="title-area"><span class="sec-subtitle text-custom">TRAINING AND LEADERSHIP PROGRAMME</span>
                    <h2 class="sec-title h1">Training Programme</h2>
                </div>

                <div class="row g-4 py-5 row-cols-1 row-cols-lg-2">
                    <div class="feature col">
                        <div class="feature-icon d-inline-flex align-items-center justify-content-center text-danger bg-gradient fs-2 mb-3">
                            <i class="fa fa-book"></i>
                        </div>
                        <h3 class="fs-4 text-body-emphasis">Interactive Lessons</h3>
                        <p>
                            Engaging and dynamic educational sessions that encourage active participation, fostering a collaborative learning experience.
                        </p>
                    </div>
                    <div class="feature col">
                        <div class="feature-icon d-inline-flex align-items-center justify-content-center text-danger bg-gradient fs-2 mb-3">
                            <i class="fa fa-paper-plane"></i>
                        </div>
                        <h3 class="fs-4 text-body-emphasis">Courses</h3>
                        <p>
                            Structured programs offering comprehensive learning modules, designed to impart specific knowledge and skills within a particular subject or discipline.
                        </p>
                    </div>
                    <div class="feature col">
                        <div class="feature-icon d-inline-flex align-items-center justify-content-center text-danger bg-gradient fs-2 mb-3">
                            <i class="fa fa-user-plus"></i>
                        </div>
                        <h3 class="fs-4 text-body-emphasis">Trained & Experienced</h3>
                        <p>
                            Instructors possessing expertise and practical knowledge, ensuring a high-quality and well-informed educational environment.
                        </p>
                    </div>

                    <div class="feature col">
                        <div class="feature-icon d-inline-flex align-items-center justify-content-center text-danger bg-gradient fs-2 mb-3">
                            <i class="fa fa-question"></i>
                        </div>
                        <h3 class="fs-4 text-body-emphasis">Question & Quiz</h3>
                        <p>
                            Integration of questioning and quiz formats to assess understanding, promote critical thinking, and enhance the overall learning process.
                        </p>
                    </div>
                </div>


            </div>
            <div class="col-xl-5">
                <div class="position-relative px-5 py-5 shadow">
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

                                <button class="w-100 mb-2 btn btn-lg rounded-3 btn-custom" type="submit">
                                    <i class="fa fa-paper-plane"></i> Send
                                </button>
                                <small class="text-body-secondary">
                                    By clicking Sign up, you agree to the terms of
                                    use.</small>
                            </div>
                        </div>
                        <div class="vs-circle color2"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Map -->
<div class="pt-4 pb-0">
    <div class="">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3753.887634803562!2d85.81741857499686!3d19.802360981557598!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a19c426517f1c15%3A0x7233e4ba9b72658f!2z4KST4KSh4KS_4KS24KS-IOCksOCkvuCkt-CljeCkn-CljeCksOCkreCkvuCkt-CkviDgpKrgpLDgpL_gpLfgpKYgSGVhZCBPZmZpY2UgLE9kaXNoYQ!5e0!3m2!1sen!2sin!4v1703574278756!5m2!1sen!2sin" height="450" style="border:0; width: 100%;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</div>

@endsection