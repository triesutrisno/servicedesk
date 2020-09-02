<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Auth;
use App\Tiket;
use App\Layanan;
use App\Transaksiot;
use App\Service;
use App\Subservice;

class TiketController extends Controller
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
        if(Auth::user()->level == 'user')
        {
            $datas = Tiket::with(['layanan', 'service', 'subService'])
                        ->where(['nikUser', Auth::user()->anggota->id])
                        ->get();
        } else {
            $datas = Tiket::with(['layanan', 'service', 'subService'])->get();
        }
        
        return view('tiket.index', ['datas'=>$datas, 'kode'=>'', 'pesan'=>'']);
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
        
        $layanan = Layanan::where(['status_layanan'=>'1'])->get();
        return view('tiket.create', ['layanan'=>$layanan]);
    }
    
    public function created($id)
    {
        if(Auth::user()->level == 'user') {
            Alert::info('Oopss..', 'Anda dilarang masuk ke area ini.');
            return redirect()->to('/');
        }
        
        $service = Service::where(['ServiceStatus'=>'1', 'id_layanan'=>$id])->get();
        //dd($service);
        return view('tiket.created', ['service'=>$service]);
    }
    
    public function add($id,$id2)
    {
        if(Auth::user()->level == 'user') {
            Alert::info('Oopss..', 'Anda dilarang masuk ke area ini.');
            return redirect()->to('/');
        }
        $kode = "TIKET00001";    
        $service = Service::with(['layanan'])
                ->where(['ServiceStatus'=>'1', 'id'=>$id2, 'id_layanan'=>$id])->get();
        $subService = Subservice::where(['ServiceSubStatus'=>'1', 'ServiceIDf'=>$id2])->get();
        //dd($service);
        return view('tiket.add', ['service'=>$service, 'subService'=>$subService, 'id_layanan'=>$id, 'id_service'=>$id2, 'kode'=>$kode]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$layananId,$serviceId)
    {
        //dd($request->all());
        if (Tiket::where(['layananId'=>$layananId, 'serviceId'=>$serviceId, 'subServiceId'=>$request->subServiceId, 'tiketStatus'=>'1'])->doesntExist()) { // Cek data apakah sudah ada atau belum di database            
            $request->request->add(['layananId'=>$layananId]);
            $request->request->add(['serviceId'=>$serviceId]);
            $request->request->add(['comp'=>'10100']);
            $request->request->add(['unit'=>'unit']);
            $request->request->add(['biro'=>'biro']);
            $request->request->add(['nikUser'=>'02008']);
            $request->request->add(['tiketApprove'=>'N']);
            $request->request->add(['tiketNikAtasan'=>'01300']);
            $request->request->add(['tiketApproveService'=>'W']);
            $request->request->add(['tiketNikAtasanService'=>'01300']);
            $request->request->add(['tiketStatus'=>'1']);
            Tiket::create($request->all());
            return redirect('/tiket')->with(['kode'=>'99', 'pesan'=>'Data berhasil disampan !']);
        }else{
            return redirect('/tiket')->with(['kode'=>'90', 'pesan'=>'Data sudah ada !']);
        }
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
