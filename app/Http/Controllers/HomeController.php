<?php

namespace App\Http\Controllers;

use App\Models\OldMarkEntry;

class HomeController extends Controller
{
    public function home()
    {
        return view('website.home');
    }
    public function about()
    {
        return view('website.about');
    }
    public function gallery()
    {
        return view('website.gallery');
    }
    public function result()
    {
        $examTypes = OldMarkEntry::getExamTypes();
        return view('website.result', compact('examTypes'));
    }
    public function contactUs()
    {
        return view('website.contact-us');
    }
    public function findCourses()
    {
        return view('website.find-courses');
    }
}
