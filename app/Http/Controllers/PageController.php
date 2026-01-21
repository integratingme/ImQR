<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display Terms and Conditions page
     */
    public function termsAndConditions()
    {
        return view('pages.terms-and-conditions');
    }

    /**
     * Display Privacy Policy page
     */
    public function privacyPolicy()
    {
        return view('pages.privacy-policy');
    }

    /**
     * Display AUP page
     */
    public function aup()
    {
        return view('pages.aup');
    }

    /**
     * Display Cookie Policy page
     */
    public function cookiePolicy()
    {
        return view('pages.cookie-policy');
    }

    /**
     * Display Disclaimer page
     */
    public function disclaimer()
    {
        return view('pages.disclaimer');
    }
}
