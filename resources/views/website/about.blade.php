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
                    About Us
                </li>
            </ol>
        </nav>
    </div>
</div>
<!--==============================
    About Area
==============================-->
<section>
    <div class="container">

        <div class="row row-cols-1 row-cols-md-2">
            <div class="col d-flex flex-column align-items-start gap-2 pt-5">
                <h2 class="fw-bold text-body-emphasis">Welcome to Odisha Rastravasa Parisad, Puri</h2>
                <p class="text-body-secondary text-justify">
                    Odisha Rastravasa Parisad, Jagannath Dham, Puri is an Voluntary Hindi Organisation and Permanent Recognition by Ministry of Human Resource Development (MHRD), Govt. of India (Regd. No. 7632/73-74 old Regd No 10 of 1954). Odisha Rastrabhasa Parisad is accredited with Quality Council of India (QCI) and ISO 9001:2015 Certification. <br /> The examinations conduct by the Organisation S.L.C. (Vinod), Inter (Praveen), BA (Shastri). The main objective of this Organisation are to propagate Hindi in the Devanagari Script, to co-ordinate individual efforts for the propagation of the language in India, to start Hindi Vidyalayas wherever necessary, to affiliate private vidyalayas, to organise seminars, symposiums and workshops in Hindi Language, literature and teaching of Hindi etc., to encourage the staging of Dramas and other performances in Hindi, to conduct Hindi Examinations under prescribed syllabus and text books, to publish text books, other books and journals and to improve the financial conditions of Hindi Pracharaks etc. The main thrust of all these programmes is to bring about linguistic harmony and to instil a spirit of nationalism in the students.
                </p>
                <a href="{{route('contact-us')}}" class="btn btn-custom btn-lg"><i class="fa fa-phone"></i> Contact Us</a>
            </div>

            <div class="col">
                <img src="https://img.freepik.com/free-vector/learning-concept-illustration_114360-6186.jpg?w=740&t=st=1698233841~exp=1698234441~hmac=8efe1af11ae974a7211f9be81f4ee21e43cb90c0e18fe261d8be839d4ef8d9d8" alt="" style="width: 100%;">
            </div>
        </div>
    </div>
</section>

<section class="pt-4 pb-5">
    <div class="container">
        <div class="row row-cols-1 row-cols-sm-3 g-4">
            <div class="col d-flex flex-column gap-2 text-center">
                <div class="shadow-sm rounded px-3 py-4">
                    <div class="feature-icon-small d-inline-flex align-items-center justify-content-center text-bg-danger bg-gradient fs-4 rounded-3">
                        <i class="fa fa-thumbs-up"></i>
                    </div>
                    <h4 class="fw-semibold mb-2 text-body-emphasis mt-3">Our Mission</h4>
                    <p class="text-body-secondary text-justify">Fostering the propagation of Hindi in the Devanagari Script, coordinating nationwide efforts, establishing Hindi Vidyalayas, organizing educational events, and promoting linguistic harmony to strengthen our cultural fabric.</p>
                </div>
            </div>

            <div class="col d-flex flex-column gap-2 text-center">
                <div class="shadow-sm rounded px-3 py-4">
                    <div class="feature-icon-small d-inline-flex align-items-center justify-content-center text-bg-danger bg-gradient fs-4 rounded-3">
                        <i class="fa fa-eye"></i>
                    </div>
                    <h4 class="fw-semibold mb-2 text-body-emphasis mt-3">Our Vision</h4>
                    <p class="text-body-secondary text-justify">To be a leading organization dedicated to advancing Hindi language, literature, and education, creating a harmonious linguistic environment, and instilling a sense of national pride among our students.</p>
                </div>
            </div>

            <div class="col d-flex flex-column gap-2 text-center">
                <div class="shadow-sm rounded px-3 py-4">
                    <div class="feature-icon-small d-inline-flex align-items-center justify-content-center text-bg-danger bg-gradient fs-4 rounded-3">
                        <i class="fa fa-user"></i>
                    </div>
                    <h4 class="fw-semibold mb-2 text-body-emphasis mt-3">Students</h4>
                    <p class="text-body-secondary text-justify">Empowering students with linguistic proficiency, cultural awareness, and a deep sense of national identity, preparing them to contribute to a harmonious and culturally rich society.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="pt-4 pb-5">
    <div class="container">
        <h2 class="fw-bold text-body-emphasis">Our Vision</h2>
        <p class="text-justify">
            The main objective of this Organisation are to propagate Hindi in the Devanagari Script, to co-ordinate individual efforts for the propagation of the language in India, to start Hindi Vidyalayas wherever necessary, to affiliate private vidyalayas, to organise seminars, symposiums and workshops in Hindi Language, literature and teaching of Hindi etc., to encourage the staging of Dramas and other performances in Hindi, to conduct Hindi Examinations under prescribed syllabus and text books, to publish text books, other books and journals and to improve the financial conditions of Hindi Pracharaks etc. The main thrust of all these programmes is to bring about linguistic harmony and to instill a spirit of nationalism in the students.
        </p>
    </div>
</section>

@endsection