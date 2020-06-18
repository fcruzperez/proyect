<?php

namespace App\Http\Controllers\Designer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DesignerController extends Controller
{
  public function home(Request $request) {
      return view('pages.designer.home');
  }
}
