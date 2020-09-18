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
        #$datas = Tiket::with(['layanan', 'service', 'subService', 'tiketDetail'])->get();
        $statClose = "20";
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
                #'b.tglSelesaiMengerjakan',
                #'max(i.created_at) as tglSelesaiMengerjakan',
                DB::raw('MAX(i.created_at) as tglSelesaiMengerjakan'),
                'h.created_at as tglClose',
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
            ->leftjoin('tb_histori as h', function ($join) use($statClose) {
                $join->on('h.tiketDetailId', '=', 'b.tiketDetailId')#->on('h.progresId', '=', $statClose);
                        ->where('h.progresId', '=', 20);
            })
            ->leftjoin('tb_histori as i', function ($join2) {
                $join2->on('i.tiketDetailId', '=', 'b.tiketDetailId')
                        ->whereIn('i.progresId', [11,19]);
            })
            ->groupBy('a.tiketId')
            ->orderBy('a.tiketStatus', 'asc')
            ->orderBy('a.kode_tiket', 'asc')
            ->get();
        //dd($datas);
        $tikets = Tiket::get();
        
        return view('home', ['datas'=>$datas,'tikets'=>$tikets, 'kode'=>'', 'pesan'=>'']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function detail($id)
    {
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
                DB::raw('MAX(i.created_at) as tglSelesaiMengerjakan'),
                'h.created_at as tglClose',
                'a.tiketNikAtasan',          
                'a.tiketPrioritas',          
                'a.tiketStatus',          
                'a.created_at',
                'a.updated_at',
                'b.nikTeknisi',
                'f.progresProsen'
            )
            ->leftjoin('tiket_detail as b', 'b.tiketId', '=', 'a.tiketId')
            ->leftjoin('m_layanan as c', 'c.id', '=', 'a.layananId')
            ->leftjoin('ticket_service as d', 'd.id', '=', 'a.serviceId')
            ->leftjoin('ticket_service_sub as e', 'e.id', '=', 'a.subServiceId')
            ->leftjoin('m_progres as f', 'f.progresId', '=', 'b.progresId')
            ->leftjoin('users as g', 'g.username', '=', 'a.nikUser')
            ->leftjoin('tb_histori as h', function ($join) {
                $join->on('h.tiketDetailId', '=', 'b.tiketDetailId')
                        ->where('h.progresId', '=', 20);
            })
            ->leftjoin('tb_histori as i', function ($join2) {
                $join2->on('i.tiketDetailId', '=', 'b.tiketDetailId')
                        ->whereIn('i.progresId', [11,19]);
            });
            #->groupBy('a.tiketId')
            #->orderBy('a.tiketStatus', 'asc')
            #->orderBy('a.kode_tiket', 'asc')
            #->get();
            #
            if($id=='1'){
                $datas->where('a.created_at', '>=', date("Y-m-d"));
            }elseif($id=='2'){
                $datas->where('a.updated_at', '>=', date("Y-m-d"))->where('tiketStatus', '=', '7');
            }elseif($id=='3'){
                $datas->where('a.updated_at', '>=', date("Y-m-d"))->where('tiketStatus', '=', '8');
            }elseif($id=='4'){
                $datas->where('a.updated_at', '>=', date("Y-m-d"))->where('tiketStatus', '<', '7');
            }elseif($id=='5'){
                $datas->where('a.created_at', '>=', date("Y-m-01"));
            }elseif($id=='6'){
                $datas->where('a.updated_at', '>=', date("Y-m-01"))->where('tiketStatus', '=', '7');
            }elseif($id=='7'){
                $datas->where('a.updated_at', '>=', date("Y-m-01"))->where('tiketStatus', '=', '8');
            }elseif($id=='8'){
                $datas->where('a.updated_at', '>=', date("Y-m-01"))->where('tiketStatus', '<', '7');
            }elseif($id=='9'){
                $datas->where('a.created_at', '>=', date("Y-01-01"));
            }elseif($id=='10'){
                $datas->where('a.updated_at', '>=', date("Y-01-01"))->where('tiketStatus', '=', '7');
            }elseif($id=='11'){
                $datas->where('a.updated_at', '>=', date("Y-01-01"))->where('tiketStatus', '=', '8');
            }elseif($id=='12'){
                $datas->where('a.updated_at', '>=', date("Y-01-01"))->where('tiketStatus', '<', '7');
            }
            
            $result = $datas->groupBy('a.tiketId')
                            ->orderBy('a.tiketStatus', 'asc')
                            ->orderBy('a.kode_tiket', 'asc')
                            ->get();
        #dd($result);
        
        return view('detail', ['datas'=>$result]);
    }
}
