<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Anggota;
use App\Subservice;
use App\Service;
use App\Masterlayanan;
use App\Layanan;



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
        if(session('infoUser')['LEVEL'] == 'admin')
        {

            $datas = DB::table('ticket_service as a')
                ->select(
                    'a.id',
                    'a.ServiceName',          
                    'a.keterangan',    
                    'b.nama_layanan'
                )
                ->leftjoin('m_layanan as b', 'b.id', '=', 'a.id_layanan')
                ->get();
        } else {
            $datas = DB::table('ticket_service as a')
            ->select(
                'a.id',
                'a.ServiceName',          
                'a.keterangan',    
                'b.nama_layanan'
            )
            ->leftjoin('m_layanan as b', 'b.id', '=', 'a.id_layanan')
            ->get();
        }
        //dd($datas);
        return view('service.index', ['datas'=>$datas, 'kode'=>'', 'pesan'=>'']);
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


        $layanan = Layanan::where(['status_layanan'=>'1'])->get();             
        return view('service.create', compact('layanan'));
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
             'min_eselon' =>   'required|string|max:255',
             'keterangan' => 'required|string|max:255',
             'ServiceStatus' => 'required|string|max:255',
             'id_layanan' => 'required|string|max:255',
            // 'npm' => 'required|string|max:20|unique:anggota'
         ]);
             
         Service::create($request->all());
         return redirect('/service')->with(['kode'=>'99', 'pesan'=>'Data berhasil disimpan !']);
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

        $layanan = Layanan::where(['status_layanan'=>'1'])->get();
        $sservice = Service::with(['layanan'])
        ->where(['id'=>$id])
        ->get();   
        
        $sslayanan = Layanan::where(['status_layanan'=>'1', 'id'=>$sservice[0]['id_layanan']])->get();

        return view('service.edit', compact('data','users','layanan','sslayanan'));
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

        return redirect('/service')->with(['kode'=>'99', 'pesan'=>'Data berhasil diupdate !']);
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
