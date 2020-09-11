<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mprogress;


use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Redirect;
use Auth;
use DB;
use RealRashid\SweetAlert\Facades\Alert;

class MprogressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        if(Auth::user()->level == 'user') {
            Alert::info('Oopss..', 'Anda dilarang masuk ke area ini.');
            return redirect()->to('/');
        }

        $datas = Mprogress::get();
        return view('mprogress.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */



    public function create()
    {
        if(Auth::user()->level == 'user') {
            Alert::info('Oopss..', 'Anda dilarang masuk ke area ini.');
            return redirect()->to('/');
        }
             
        return view('mprogress.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */




    public function store(Request $request)
    {
        // $count = Anggota::where('npm',$request->input('npm'))->count();
 
        /*
         if($count>0){
             Session::flash('message', 'Already exist!');
             Session::flash('message_type', 'danger');
             return redirect()->to('anggota');
         }
         */    
         $this->validate($request, [
            'progresNama' => 'required|string|max:5',
             'progresProsen' => 'required|string|max:255',
             'progresStatus' => 'required|string|max:255',
            // 'npm' => 'required|string|max:20|unique:anggota'
         ]);
             
         mprogress::create($request->all());
 
         return redirect('/mprogress')->with(['kode'=>'99', 'pesan'=>'Data berhasil disimpan !']);
 
     }
 
     /**
      * Display the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */


    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
