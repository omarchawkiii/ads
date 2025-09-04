<?php

namespace App\Http\Controllers;

use App\Models\Gender;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GenderController extends Controller
{
    public function index(Request $request)
    {
        $genders = Gender::all();

        return view('genders.index', compact('genders'));
    }

    public function show(Request $request, Gender $gender)
    {
        return view('genders.show', compact('gender'));
    }
}
