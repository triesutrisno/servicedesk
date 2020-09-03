<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\User;
use App\Anggota;
use App\Subservice;
use App\Service;
use App\Masterlayanan;


use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Redirect;
use Auth;
use DB;
use RealRashid\SweetAlert\Facades\Alert;


class MasterlayananController extends Controller
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

        $datas = Masterlayanan::get();
        return view('Masterlayanan.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function create()
    {
        //
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
             'nama_layanan' => 'required|string|max:255',
            // 'npm' => 'required|string|max:20|unique:anggota'
         ]);
             
         masterlayanan::create($request->all());
 
         alert()->success('Berhasil.','Data telah ditambahkan!');
         return redirect()->route('masterlayanan.index');
 
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
        if((Auth::user()->level == 'user') && (Auth::user()->id != $id)) {
                Alert::info('Oopss..', 'Anda dilarang masuk ke area ini.');
                return redirect()->to('/');
        }

        $data = Masterlayanan::findOrFail($id);
        $users = User::get();
        return view('masterlayanan.edit', compact('data', 'users'));
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
        Masterlayanan::find($id)->update($request->all());

       // alert()->success('Berhasil.','Data Master Layanan telah diubah!');
        return redirect()->to('masterlayanan');
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
