<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    public function switch($locale)
    {
        if (in_array($locale, ['ar', 'en'])) {
            Session::put('locale', $locale);
        }

        return redirect()->back();
    }
}
