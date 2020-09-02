<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Anggota;
use App\Subservice;
use App\Service;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Redirect;
use Auth;
use DB;
use RealRashid\SweetAlert\Facades\Alert;

class ServiceController extends Controller
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

        $datas = Service::get();
        return view('service.index', compact('datas'));
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

        $users = User::WhereNotExists(function($query) {
                        $query->select(DB::raw(1))
                        ->from('ticket_service');
                        //->whereRaw('anggota.user_id = users.id');
                     })->get();
        return view('service.create', compact('users'));
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
             'ServiceName' => 'required|string|max:255',
            // 'npm' => 'required|string|max:20|unique:anggota'
         ]);
             
         Service::create($request->all());
 
         alert()->success('Berhasil.','Data telah ditambahkan!');
         return redirect()->route('service.index');
 
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

        $data = Service::findOrFail($id);
        $users = User::get();
        return view('service.edit', compact('data', 'users'));
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
        Service::find($id)->update($request->all());

        alert()->success('Berhasil.','Data Master Service telah diubah!');
        return redirect()->to('service');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function destroy($id)
    {
        Service::find($id)->delete();
        alert()->success('Berhasil.','Data Master Service telah dihapus!');
        return redirect()->route('service.index');
    }
}
