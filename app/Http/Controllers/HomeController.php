<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Transaksi;


//use App\Transaksi;
use App\Tiket;
use App\Tiketdetail;
use App\Histori;
use App\Nextnumber;
use App\Layanan;
use App\Transaksiot;
use App\Service;
use App\Subservice;

use DB;
use Auth;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {    
        if(session('infoUser')['LEVEL'] == 'admin')
        {
            #$datas = Tiket::with(['layanan', 'service', 'subService', 'tiketDetail'])->get();
            $datas = DB::table('tiket as a')
                ->select(
                    'a.tiketId',
                    'a.kode_tiket',          
                    'a.comp',          
                    'a.unit',          
                    'a.nikUser',
                    'g.name',
                    'a.layananId',         
                    'c.nama_layanan',          
                    'a.serviceId',             
                    'd.ServiceName',          
                    'a.subServiceId',            
                    'e.ServiceSubName',           
                    'a.tiketKeterangan',          
                    'a.tiketApprove',
                    'a.created_at',          
                    'a.tiketTglApprove',
					'a.tiketTglApproveService',
                    'b.tglMulaiMengerjakan',
                    'b.tglSelesaiMengerjakan',
                    'a.tiketNikAtasan',          
                    'a.tiketPrioritas',          
                    'a.tiketStatus',          
                    'a.created_at',
                    'b.nikTeknisi',
                    'f.progresProsen'
                )
                ->leftjoin('tiket_detail as b', 'b.tiketId', '=', 'a.tiketId')
                ->leftjoin('m_layanan as c', 'c.id', '=', 'a.layananId')
                ->leftjoin('ticket_service as d', 'd.id', '=', 'a.serviceId')
                ->leftjoin('ticket_service_sub as e', 'e.id', '=', 'a.subServiceId')
                ->leftjoin('m_progres as f', 'f.progresId', '=', 'b.progresId')
                ->leftjoin('users as g', 'g.username', '=', 'a.nikUser')
                ->orderBy('a.tiketStatus', 'asc')
                ->orderBy('a.kode_tiket', 'asc')
                ->get();
        } else {
            $datas = DB::table('tiket as a')
                ->select(
                    'a.tiketId',
                    'a.kode_tiket',          
                    'a.comp',          
                    'a.unit',          
                    'a.nikUser',
                    'g.name',
                    'a.layananId',         
                    'c.nama_layanan',          
                    'a.serviceId',             
                    'd.ServiceName',          
                    'a.subServiceId',            
                    'e.ServiceSubName',           
                    'a.tiketKeterangan',          
                    'a.tiketApprove',
                    'a.created_at',          
                    'a.tiketTglApprove',
					'a.tiketTglApproveService',
                    'b.tglMulaiMengerjakan',
                    'b.tglSelesaiMengerjakan',
                    'a.tiketNikAtasan',          
                    'a.tiketPrioritas',          
                    'a.tiketStatus',          
                    'a.created_at',
                    'b.nikTeknisi',
                    'f.progresProsen'
                )
                ->leftjoin('tiket_detail as b', 'b.tiketId', '=', 'a.tiketId')
                ->leftjoin('m_layanan as c', 'c.id', '=', 'a.layananId')
                ->leftjoin('ticket_service as d', 'd.id', '=', 'a.serviceId')
                ->leftjoin('ticket_service_sub as e', 'e.id', '=', 'a.subServiceId')
                ->leftjoin('m_progres as f', 'f.progresId', '=', 'b.progresId')
                ->leftjoin('users as g', 'g.username', '=', 'a.nikUser')
                ->where(['a.nikUser' => session('infoUser')['NIK']])
                ->orderBy('a.tiketStatus', 'asc')
                ->orderBy('a.kode_tiket', 'asc')
                ->get();
        }
        //dd($datas);
        return view('home', ['datas'=>$datas, 'kode'=>'', 'pesan'=>'']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
}
