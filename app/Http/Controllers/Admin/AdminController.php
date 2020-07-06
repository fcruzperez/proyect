<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Request as Publish;
use App\Models\Technic;
use App\Models\Format;
use App\Models\Fabric;
use App\Models\RequestFabric;
use App\Models\RequestFormat;
use App\Models\RequestTechnic;
use App\Models\RequestImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function dashboard(Request $request) {
        $publishes = Publish::get();
        $data = ['publishes' => $publishes];
//        dd($data);

        return view('pages.admin.dashboard', $data);
    }

//    public function showPublishes(Request $request) {
//
//        $publishes = Publish::get();
//        $data = ['publishes' => $publishes];
////        dd($data);
//        return view('pages.admin.da', $data);
//    }

}
