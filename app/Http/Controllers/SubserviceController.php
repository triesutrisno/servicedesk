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


class SubserviceController extends Controller
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

            $datas = DB::table('ticket_service_sub as a')
                ->select(
                    'a.id',
                    'a.ServiceIDf',
                    'a.ServiceSubName',
                    'a.ServiceSubStatus',
                    'b.ServiceName',
                    'u.nama_unit'
                )
                ->leftjoin('ticket_service as b', 'b.id', '=', 'a.ServiceIDf')
                ->leftjoin('m_unit as u', 'u.id', '=', 'a.id_unit')
                ->orderBy('a.id','desc')
                ->get();
        } else {
            $datas = DB::table('ticket_service_sub as a')
            ->select(
                'a.id',
                'a.ServiceIDf',
                'a.ServiceSubName',
                'a.ServiceSubStatus',
                'b.ServiceName',
                'u.nama_unit'
            )
            ->leftjoin('ticket_service as b', 'b.id', '=', 'a.ServiceIDf')
            ->leftjoin('m_unit as u', 'u.id', '=', 'a.id_unit')
            ->orderBy('a.id','desc')
            ->get();
        }
        //dd($datas);
        return view('subservice.index', ['datas'=>$datas, 'kode'=>'', 'pesan'=>'']);
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
                        ->from('ticket_service_sub');
                        //->whereRaw('anggota.user_id = users.id');
                     })->get();

        $service = service::where(['ServiceStatus'=>'1'])->get();
        $unit = DB::table('m_unit')->select('id', 'nama_unit', 'nik_atasan_service')->get();
        return view('subservice.create', compact('service', 'unit'));
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
            'ServiceSubName' => 'required|string|max:255',
            'ServiceSubStatus' => 'required|string|max:255',
            'ServiceIDf' => 'required|string|max:255',
           // 'npm' => 'required|string|max:20|unique:anggota'
        ]);

        Subservice::create($request->all());

        return redirect('/subservice')->with(['kode'=>'99', 'pesan'=>'Data berhasil disimpan !']);

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

        $data = Subservice::findOrFail($id);
        $users = User::get();

        $service = Service::where(['ServiceStatus'=>'1'])->get();
        $unit = DB::table('m_unit')->select('id', 'nama_unit', 'nik_atasan_service')->get();
        return view('subservice.edit', compact('data', 'users','service','unit'));
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
        Subservice::find($id)->update($request->all());

        return redirect('/subservice')->with(['kode'=>'99', 'pesan'=>'Data berhasil diupdate !']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Subservice::find($id)->delete();
        alert()->success('Berhasil.','Data Sub Service telah dihapus!');
        return redirect()->route('subservice.index');
    }
}
