<?php

namespace App\Http\Controllers;

use App\Tiketdetail;
use Illuminate\Http\Request;
use DB;
use App\Progres;
use App\Tiket;
use App\Histori;

class TiketdetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = DB::table('tiket_detail as a')
            ->select(
                'a.tiketDetailId',
                'a.tiketId',
                'a.nikTeknisi',
                'a.keterangan',
                'a.tiketDetailStatus',
                'a.namaAkun',                
                'a.passwordAkun',           
                'a.tglWawancara',           
                'a.tglMulaiMengerjakan',           
                'a.tglSelesaiMengerjakan',          
                'a.tglImplementasi',          
                'a.tglPelatihan',          
                'b.kode_tiket',          
                'b.comp',          
                'b.unit',          
                'b.nikUser',          
                'b.layananId',         
                'c.nama_layanan',          
                'b.serviceId',             
                'd.ServiceName',          
                'b.subServiceId',            
                'e.ServiceSubName',           
                'b.tiketKeterangan',          
                'b.file',          
                'b.tiketApprove',          
                'b.tiketTglApprove',          
                'b.tiketNikAtasan',          
                'b.tiketPrioritas',          
                'b.tiketStatus',          
                'b.created_at'
            )
            ->join('tiket as b', 'b.tiketId', '=', 'a.tiketId')
            ->leftjoin('m_layanan as c', 'c.id', '=', 'b.layananId')
            ->leftjoin('ticket_service as d', 'd.id', '=', 'b.serviceId')
            ->leftjoin('ticket_service_sub as e', 'e.id', '=', 'b.subServiceId')
            ->where(['nikTeknisi'=>session('infoUser')['NIK']])
            ->get();
        
        return view('tiket_detail.index', ['datas'=>$datas, 'kode'=>'', 'pesan'=>'']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $datas = DB::table('tiket_detail as a')
            ->select(
                'a.tiketDetailId',
                'a.tiketId',
                'a.progresId',
                'a.nikTeknisi',
                'a.keterangan',
                'a.tiketDetailStatus',
                'a.namaAkun',                
                'a.passwordAkun',           
                'a.tglWawancara',           
                'a.tglMulaiMengerjakan',           
                'a.tglSelesaiMengerjakan',          
                'a.tglImplementasi',          
                'a.tglPelatihan',          
                'b.kode_tiket',          
                'b.comp',          
                'b.unit',          
                'b.nikUser',          
                'b.layananId',         
                'c.nama_layanan',          
                'b.serviceId',             
                'd.ServiceName',          
                'b.subServiceId',            
                'e.ServiceSubName',           
                'b.tiketKeterangan',          
                'b.file',          
                'b.tiketApprove',          
                'b.tiketTglApprove',          
                'b.tiketNikAtasan',          
                'b.tiketPrioritas',          
                'b.tiketStatus',          
                'b.created_at'
            )
            ->join('tiket as b', 'b.tiketId', '=', 'a.tiketId')
            ->leftjoin('m_layanan as c', 'c.id', '=', 'b.layananId')
            ->leftjoin('ticket_service as d', 'd.id', '=', 'b.serviceId')
            ->leftjoin('ticket_service_sub as e', 'e.id', '=', 'b.subServiceId')
            ->where(['tiketDetailId'=>$id])
            ->get();
        //dd($datas);
        
        if($datas[0]->nikTeknisi == session('infoUser')['NIK']){
            $progres = Progres::where(['progresStatus'=>'1'])->get();
            return view('tiket_detail.create', ['datas'=>$datas, 'progres'=>$progres]);
        }else{
            return redirect('/tugasku')->with(['kode'=>'90', 'pesan'=>'Tiket nomer '.$datas[0]->kode_tiket.' tidak ditugaskan ke anda !']);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $tktDetail = Tiketdetail::where(['tiketDetailId'=>$id])->get();
        if($request->progres=='12'){ // Ketika tiket di pending
            Tiketdetail::where('tiketDetailId', $id)
                    ->update([
                        'keterangan' => $request->keterangan,
                        'progresId' => $request->progres,
                        'namaAkun' => $request->namaAkun,
                        'passwordAkun' => $request->passwordAkun,
                        'tglWawancara' => $request->tglWawancara,
                        'tglMulaiMengerjakan' => $request->tglMulaiMengerjakan,
                        'tglSelesaiMengerjakan' => $request->tglSelesaiMengerjakan,
                        'tglImplementasi' => $request->tglImplementasi,
                        'tglPelatihan' => $request->tglPelatihan,
                        'tiketDetailStatus' => '3', // status dipending
                ]);

                Tiket::where('tiketId', $tktDetail[0]['tiketId'])
                    ->update(['tiketStatus' => '9']);
        }elseif($request->progres=='13'){ // Ketika tiket di cancel
            Tiketdetail::where('tiketDetailId', $id)
                    ->update([
                        'keterangan' => $request->keterangan,
                        'progresId' => $request->progres,
                        'namaAkun' => $request->namaAkun,
                        'passwordAkun' => $request->passwordAkun,
                        'tglWawancara' => $request->tglWawancara,
                        'tglMulaiMengerjakan' => $request->tglMulaiMengerjakan,
                        'tglSelesaiMengerjakan' => $request->tglSelesaiMengerjakan,
                        'tglImplementasi' => $request->tglImplementasi,
                        'tglPelatihan' => $request->tglPelatihan,
                        'tiketDetailStatus' => '4', // status dicancel
                ]);

                Tiket::where('tiketId', $tktDetail[0]['tiketId'])
                    ->update(['tiketStatus' => '10']);
        }elseif(in_array($request->progres,array('11','19'))){ // Ketika tiket di statusnya Go Live dan Finish Pengerjaan
            Tiketdetail::where('tiketDetailId', $id)
                    ->update([
                        'keterangan' => $request->keterangan,
                        'progresId' => $request->progres,
                        'namaAkun' => $request->namaAkun,
                        'passwordAkun' => $request->passwordAkun,
                        'tglWawancara' => $request->tglWawancara,
                        'tglMulaiMengerjakan' => $request->tglMulaiMengerjakan,
                        'tglSelesaiMengerjakan' => $request->tglSelesaiMengerjakan,
                        'tglImplementasi' => $request->tglImplementasi,
                        'tglPelatihan' => $request->tglPelatihan,
                        'tiketDetailStatus' => '5', // status dicancel
                ]);

                Tiket::where('tiketId', $tktDetail[0]['tiketId'])
                    ->update(['tiketStatus' => '7']);
        }else{
            Tiketdetail::where('tiketDetailId', $id)
                    ->update([
                        'keterangan' => $request->keterangan,
                        'progresId' => $request->progres,
                        'namaAkun' => $request->namaAkun,
                        'passwordAkun' => $request->passwordAkun,
                        'tglWawancara' => $request->tglWawancara,
                        'tglMulaiMengerjakan' => $request->tglMulaiMengerjakan,
                        'tglSelesaiMengerjakan' => $request->tglSelesaiMengerjakan,
                        'tglImplementasi' => $request->tglImplementasi,
                        'tglPelatihan' => $request->tglPelatihan,
                        'tiketDetailStatus' => '2', // status dikerjakan
                ]);

                Tiket::where('tiketId', $tktDetail[0]['tiketId'])
                    ->update(['tiketStatus' => '6']);
        }
        $histori = new Histori();
        $histori->keterangan = $request->keterangan;
        $histori->progresId = $request->progres;
        $histori->tiketDetailId = $id;
        $histori->save();
        
        return redirect('/tugasku')->with(['kode'=>'99', 'pesan'=>'solusi berhasil ditambahkan !']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tiketdetail  $tiketdetail
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tiketdetail  $tiketdetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tiketdetail $tiketdetail)
    {
        //
    }
}
