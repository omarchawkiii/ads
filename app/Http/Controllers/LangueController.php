<?php

namespace App\Http\Controllers;

use App\Models\Langue;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LangueController extends Controller
{
    public function index(Request $request)
    {
        $langues = Langue::all();

        return view('langues.index', compact('langues'));
    }

    public function show(Request $request, Langue $langue)
    {
        return view('langues.show', compact('langue'));
    }
}
