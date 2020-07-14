<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DesignerRate;
use App\Models\User;
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

    public function settings(Request $request){

        $formats = Format::all();
        $fabrics = Fabric::all();
        $technics = Technic::all();

        $data = [
            'formats' => $formats,
            'fabrics' => $fabrics,
            'technics' => $technics,
        ];
        return view('pages.admin.settings', $data);
    }

//    public function saveSettings(Request $request){
//
//        $inputs = $request->all();
////        dd($inputs);
//
//        return redirect('/admin/dashboard');
//    }


    public function formatNew(Request $request) {
        $input = $request->all();
        $validator = Validator::make($input, [
            'format' => 'required|unique:formats,name'
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator);
        }

        try{
            Format::create(['name' => $input['format']]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->to('admin/settings');
    }

    public function formatUpdate(Request $request) {

        $input = $request->all();
        $validator = Validator::make($input, [
            'format_id' => 'required',
            'format_name' => 'required|unique:formats,name'
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator);
        }

        try{
            $format = Format::find($input['format_id']);
            $format->name = $input['format_name'];
            $format->save();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->to('admin/settings');
    }

    public function formatDelete(Request $request, $id)
    {
        Format::find($id)->delete();

        return redirect()->to('admin/settings');
    }

    public function fabricNew(Request $request) {

        $inputs = $request->all();
        $validator = Validator::make($inputs, [
            'fabric' => 'required|unique:fabrics,name'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            Fabric::create(['name' => $inputs['fabric']]);
        }
        catch(\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->to('admin/settings');
    }

    public function fabricUpdate(Request $request) {
        $inputs = $request->all();
        $validator = Validator::make($inputs, [
           'fabric_id' => 'required',
           'fabric_name' => 'required|unique:fabrics,name',
        ]);
        if($validator->fails()){
            return back()->withErrors($validator);
        }

        try{
            $fabric = Fabric::find($inputs['fabric_id']);
            $fabric->name = $inputs['fabric_name'];
            $fabric->save();
        } catch (\Exception $e) {
            return back()->withErrors(['errors' => $e->getMessage()]);
        }

        return redirect()->to('admin/settings');
    }

    public function fabricDelete(Request $request, $id)
    {

        Fabric::find($id)->delete();
        return redirect()->to('admin/settings');
    }

    public function technicNew(Request $request) {

        $inputs = $request->all();
        $validator = Validator::make($inputs, [
            'technic' => 'required|unique:technics,name'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            Technic::create(['name' => $inputs['technic']]);
        }
        catch(\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->to('admin/settings');
    }

    public function technicUpdate(Request $request) {
        $inputs = $request->all();
        $validator = Validator::make($inputs, [
            'technic_id' => 'required',
            'technic_name' => 'required|unique:technics,name',
        ]);
        if($validator->fails()){
            return back()->withErrors($validator);
        }

        try{
            $technic = Technic::find($inputs['technic_id']);
            $technic->name = $inputs['technic_name'];
            $technic->save();
        } catch (\Exception $e) {
            return back()->withErrors(['errors' => $e->getMessage()]);
        }

        return redirect()->to('admin/settings');
    }

    public function technicDelete(Request $request, $id) {

        Technic::find($id)->delete();
        return redirect()->to('admin/settings');
    }

    public function score(Request $request) {

        $designers = User::where('role', 'designer')->get();
        $designer_ids = array();
        foreach ($designers as $designer) {
            array_push($designer_ids, $designer['id']);
        }

        $data = ['designer_ids' => $designer_ids];

        return view('pages.admin.score', $data);


    }

    public function updateScore(Request $request) {

        $input = $request->all();
        $validator = Validator::make($input, [
            'designer_id' => 'required',
            'designer_rate' => 'required',
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator);
        }

        try{
            $id = DesignerRate::where('designer_id', $input['designer_id'])->get()[0]['id'];
            $item = DesignerRate::find($id);
//            dd($item);
            $item->rate = $input['designer_rate'];
            $item->save();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->to('admin/score');
    }
}
