<?php

namespace App\Http\Controllers;

use App\Models\AboutPage;

class NosotrosController extends Controller
{
    public function index()
    {
        $about = AboutPage::first();

        return view('nosotros', compact('about'));
    }

}
