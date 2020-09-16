<?php

namespace App\Http\Controllers;

use App\Tiketdetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
    public function index(Request $request)
    {
        $nomer = $request->nomer != NULL ? $request->nomer : "";
        $service = $request->service != NULL ? $request->service : "";
        $subService = $request->subservice != NULL ? $request->subservice : "";
        $status = $request->status != NULL ? $request->status : "";
        $where= "";
        //if($nomer!=""){
        //    $where.="'kode_tiket'=>$nomer";
        //}
        
        //if($status!=""){
        //    $where.="'tiketStatus'=>$status";
       //}
        
        $datas = DB::table('tiket_detail as a')
            ->select(
                'a.tiketDetailId',
                'a.tiketId',
                'a.nikTeknisi',
                'h.name as namaTeknisi',
                'a.keterangan',
                'a.tiketDetailStatus',
                'a.namaAkun',                
                'a.passwordAkun',           
                'a.tglWawancara',           
                'a.tglMulaiMengerjakan',           
                'a.tglSelesaiMengerjakan',          
                'a.tglImplementasi',          
                'a.tglPelatihan',
                'f.progresProsen',      
                'b.kode_tiket',          
                'b.comp',          
                'b.unit',          
                'b.nikUser', 
                'g.name as userBy',         
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
            ->leftjoin('m_progres as f', 'f.progresId', '=', 'a.progresId')
            ->leftjoin('users as g', 'g.username', '=', 'b.nikUser')
            ->leftjoin('users as h', 'h.username', '=', 'a.nikTeknisi')
            #->where(['nikTeknisi'=>session('infoUser')['NIK']])
            ->where(function($query){
                $query->orWhere(['tiketNikAtasan'=>session('infoUser')['NIK']])
                      ->orWhere(['tiketNikAtasanService'=>session('infoUser')['NIK']])
                      ->orWhere(['nikTeknisi'=>session('infoUser')['NIK']]);
            })
            #->orWhere(['tiketNikAtasan'=>session('infoUser')['NIK']])
            #->orWhere(['tiketNikAtasanService'=>session('infoUser')['NIK']])            
            ->when($nomer, function ($query, $nomer) {
                    return $query->where('kode_tiket', $nomer);
                })
            ->when($status, function ($query, $status) {
                    return $query->where('tiketStatus', $status);
                })
            ->orderBy('b.tiketStatus', 'asc')
            ->orderBy('b.kode_tiket', 'asc')
            ->get();
        
        return view('tiket_detail.index', ['datas'=>$datas, 'kode'=>'', 'pesan'=>'', 'nomor'=>$nomer, 'status'=>$status]);
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
                'a.tglRTL',          
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
            $progres = Progres::where(['progresStatus'=>'1',])->where('progresId','<>','20')->get();
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
        $tktDetail = Tiketdetail::with(['tiket'])
                ->where(['tiketDetailId'=>$id])
                ->get();
        
        dd($tktDetail[0]['nikTeknisi']);
        if(session('infoUser')['NIK']==$tktDetail[0]['nikTeknisi']){
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
                            'tglRTL' => $request->tglRTL,
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
                            'tglRTL' => $request->tglRTL,
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
                            'tglRTL' => $request->tglRTL,
                            'tiketDetailStatus' => '5', // status selesai dikerjakan
                    ]);

                    Tiket::where('tiketId', $tktDetail[0]['tiketId'])
                        ->update(['tiketStatus' => '7']);

                    if($tktDetail[0]['tiket'][0]['tiketEmail']!=""){
                        $isiEmail="<html>";
                        $isiEmail.="<html>";
                        $isiEmail.="<body>";           
                        $isiEmail.="Permintaan tiket anda dengan: <br />";
                        $isiEmail.="<table style=\"border:0;bordercolor=#ffffff\" width=\"100%\">";
                        $isiEmail.="<tr>";
                        $isiEmail.="<td width=\"40\">Nomer</td>";
                        $isiEmail.="<td width=\"10\">:</td>";
                        $isiEmail.="<td>".$tktDetail[0]['tiket'][0]['kode_tiket']."</td>";
                        $isiEmail.="</tr>";
                        $isiEmail.="<tr>";
                        $isiEmail.="<td>Keterangan</td>";
                        $isiEmail.="<td>:</td>";
                        $isiEmail.="<td>".$tktDetail[0]['tiket'][0]['tiketKeterangan']."</td>";
                        $isiEmail.="</tr>";            
                        $isiEmail.="</table><br />";
                        $isiEmail.="Sudah selesai dikerjakan, silakan cek kembali serta lakukan close tiket anda di tiket.silog.co.id dan gunakan user dan password anda untuk login ke aplikasi tersebut. <br />";
                        $isiEmail.="<h5>Mohon untuk tidak membalas karena email ini dikirimkan secara otomatis oleh sistem</h5>";
                        $isiEmail.= "</body>";
                        $isiEmail.="</html>";
                        $urle = env('API_BASE_URL')."/sendEmail.php";
                        $response = Http::withHeaders([
                                        'Content-Type' => 'application/json',
                                        'token' => 'tiketing.silog.co.id'
                                    ])
                                    ->post($urle,[
                                        'tanggal' => date("Y-m-d H:i:s"),
                                        'recipients' => $tktDetail[0]['tiket'][0]['tiketEmail'],
                                        #'recipients' => 'triesutrisno@gmail.com',
                                        'cc' => '',
                                        'subjectEmail' => 'Informasi Penyelesaian Tiket',
                                        'isiEmail' => addslashes($isiEmail),
                                        'status' => 'outbox',
                                        'password' => 'sistem2017',
                                        'contentEmail' => '0',
                                        'sistem' => 'tiketSilog',
                                ]);
                        #$dtAPi = json_decode($response->getBody()->getContents(),true);  
                        #$responStatus = $response->getStatusCode();
                        //dd($dtAPi);
                    }
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
                            'tglRTL' => $request->tglRTL,
                            'tiketDetailStatus' => '2', // status dikerjakan
                    ]);

                    Tiket::where('tiketId', $tktDetail[0]['tiketId'])
                        ->update(['tiketStatus' => '6']);
            }

            $histori = new Histori();
            $histori->keterangan    = $request->keterangan;
            $histori->progresId     = $request->progres;
            $histori->tglRTL        = $request->tglRTL;
            $histori->tiketDetailId = $id;
            $histori->save();

            return redirect('/tugasku')->with(['kode'=>'99', 'pesan'=>'solusi berhasil ditambahkan !']); 
        }else{
            return redirect('/tugasku')->with(['kode'=>'90', 'pesan'=>'Tiket ini tidak ditugaskan ke anda !']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tiketdetail  $tiketdetail
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
                    'a.file',          
                    'a.tiketApprove',          
                    'a.tiketTglApprove',          
                    'a.tiketNikAtasan',  
                    'i.name as namaAtasan',  
                    'a.tiketApproveService',                             
                    'a.tiketTglApproveService',          
                    'a.tiketNikAtasanService', 
                    'j.name as namaPIC', 
                    'a.tiketPrioritas',          
                    'a.tiketStatus',          
                    'a.created_at',
                    'b.nikTeknisi',
                    'b.namaAkun',
                    'b.passwordAkun',
                    'b.tglWawancara',
                    'b.tglMulaiMengerjakan',
                    'b.tglSelesaiMengerjakan',
                    'b.tglImplementasi',
                    'b.tglPelatihan',
                    'h.name as namaTeknisi',
                    'f.progresProsen'
                )
                ->leftjoin('tiket_detail as b', 'b.tiketId', '=', 'a.tiketId')
                ->leftjoin('m_layanan as c', 'c.id', '=', 'a.layananId')
                ->leftjoin('ticket_service as d', 'd.id', '=', 'a.serviceId')
                ->leftjoin('ticket_service_sub as e', 'e.id', '=', 'a.subServiceId')
                ->leftjoin('m_progres as f', 'f.progresId', '=', 'b.progresId')
                ->leftjoin('users as g', 'g.username', '=', 'a.nikUser')
                ->leftjoin('users as h', 'h.username', '=', 'b.nikTeknisi')
                ->leftjoin('users as i', 'i.username', '=', 'a.tiketNikAtasan')
                ->leftjoin('users as j', 'j.username', '=', 'a.tiketNikAtasanService')
                ->where(['b.tiketDetailId' => $id])
                ->orderBy('a.tiketStatus', 'asc')
                ->orderBy('a.kode_tiket', 'asc')
                ->get();
        //dd($datas);
        
        $histori = DB::table('tb_histori as a')
                ->select(
                    'a.tiketDetailId',
                    'a.progresId',
                    'a.created_at',                     
                    'a.keterangan',
                    'a.tglRTL',
                    'c.progresNama',                   
                    'c.progresProsen'
                )
                ->leftjoin('tiket_detail as b', 'b.tiketDetailId', '=', 'a.tiketDetailId')
                ->leftjoin('m_progres as c', 'c.progresId', '=', 'a.progresId')
                ->where(['b.tiketDetailId' => $id])
                ->orderBy('a.historiId', 'desc')
                ->get();
        //dd($histori);
        return view('tiket_detail.show',['data'=>$datas, 'histori'=>$histori]);
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
