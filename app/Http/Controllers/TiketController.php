<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Http;
use Auth;
use DB;
use Carbon\Carbon;
use App\Tiket;
use App\Tiketdetail;
use App\Histori;
use App\Nextnumber;
use App\Layanan;
use App\Transaksiot;
use App\Service;
use App\Subservice;
use App\User;
use App\Tbapprove;
use DateTime;

class TiketController extends Controller
{
    /**
     * Display a listing of the resource.
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
                    'a.file',          
                    'a.tiketApprove',          
                    'a.tiketTglApprove',          
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
                    'a.file',          
                    'a.tiketApprove',          
                    'a.tiketTglApprove',          
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
        return view('tiket.index', ['datas'=>$datas, 'kode'=>'', 'pesan'=>'']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $layanan = Layanan::where(['status_layanan'=>'1'])->get();
        return view('tiket.create', ['layanan'=>$layanan]);
    }
    
    public function created($id)
    {        
        $service = Service::where(['ServiceStatus'=>'1', 'id_layanan'=>$id])->orderBy('min_eselon', 'desc')->get();
        //dd($service);
        return view('tiket.created', ['service'=>$service]);
    }
    
    public function add($id,$id2)
    {   
        $eselon = substr(session('infoUser')['ESELON'],0,1);
        $dtNextnumber = Nextnumber::where([
            'tahun'=>date("Y"),
            'status'=>1,
        ])->get();
        $jmlNext = count($dtNextnumber);
        if($jmlNext>0){
            $nomer = sprintf("%05d",$dtNextnumber[0]['nextnumber']);
            $nextnumber = "TK".date("y").date("m").$nomer; //TK200900001
            //dd($nextnumber);
            if (tiket::where(['kode_tiket'=>$nextnumber])->doesntExist()){                
                $kode = $nextnumber;   
                $update = Nextnumber::where('id', $dtNextnumber[0]['id'])
                    ->update([
                        'nextnumber' => $dtNextnumber[0]['nextnumber']+1,
                  ]);
            }else{
                return redirect('/tiket')->with(['kode'=>'90', 'pesan'=>'Nextnumber '.$nextnumber.' sudah ada ditahun ini !']);
            }
        }else{
            return redirect('/tiket')->with(['kode'=>'90', 'pesan'=>'Nextnumber ditahun ini belum disetting !']);
        }
         
        $service = Service::with(['layanan'])
                ->where(['ServiceStatus'=>'1', 'id'=>$id2, 'id_layanan'=>$id])->get();
        $subService = Subservice::where(['ServiceSubStatus'=>'1', 'ServiceIDf'=>$id2])->orderBy('urutan', 'asc')->get();
        
        $urle = env('API_BASE_URL')."/getKepala.php";
        $response = Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'token' => 'tiketing.silog.co.id'
                    ])
                    ->post($urle,[
                        'biro' => $service[0]['layanan'][0]['kode_biro'],
                ]);
        $dtAPi = json_decode($response->getBody()->getContents(),true);  
        $responStatus = $response->getStatusCode();
        //dd($dtAPi);
        if($responStatus=='200'){
            $dtAtasanService = $dtAPi["data"];
        }else{
            $dtAtasanService = $dtAPi["data"];
        }
        
        //dd($eselon."<=".$service[0]['min_eselon']);
        if($eselon <= $service[0]['min_eselon']){
            return view('tiket.add', [
                        'service'=>$service, 
                        'subService'=>$subService, 
                        'dtAtasanService'=>$dtAtasanService, 
                        'id_layanan'=>$id, 
                        'id_service'=>$id2, 'kode'=>$kode
                   ]);
        }else{
            return redirect('/tiket')->with('pesan', 'Anda tidak diijinkan mengakses menu yang tadi !');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$layananId,$serviceId)
    {
        $request->validate([
               'tiketNikAtasanService' => 'required',
        ]);
        //dd($request->all());
        if($request->file('tiketFile') == '') {
            $gambar = NULL;
        } else {
            $file = $request->file('tiketFile');
            $dt = Carbon::now();
            $acak  = $file->getClientOriginalExtension();
            $fileName = $dt->format('YmdHis')."-".rand(11111,99999).'.'.$acak; 
            //dd($fileName);
            $request->file('tiketFile')->move("images/fileTiket", $fileName);
            $gambar = $fileName;
        }
        
        if (Tiket::where([
                'layananId'=>$layananId, 
                'serviceId'=>$serviceId, 
                'nikUser'=>session('infoUser')['NIK'], 
                'subServiceId'=>$request->subServiceId,  
                'tiketKeterangan'=>$request->tiketKeterangan, 
                'tiketStatus'=>'1'
            ])->where('created_at', '>=', date("Y-m-d"))->doesntExist()) { // Cek data apakah sudah ada atau belum di database 
            
            $request->request->add(['layananId'=>$layananId]);
            $request->request->add(['serviceId'=>$serviceId]);
            $request->request->add(['comp'=>session('infoUser')['PERUSAHAAN']]);
            $request->request->add(['unit'=>session('infoUser')['UNIT']]);
            $request->request->add(['biro'=>session('infoUser')['BIROBU']]);
            $request->request->add(['nikUser'=>session('infoUser')['NIK']]);
            $request->request->add(['tiketEmail'=>session('infoUser')['EMAIL']]);            
            $request->request->add(['file'=>$gambar]);
            if(session('infoUser')['AL_NIK'] !="" && session('infoUser')['ESELON']!='12'){
                $request->request->add(['tiketApprove'=>'W']);
                $request->request->add(['tiketNikAtasan'=> session('infoUser')['AL_NIK']]);
                $request->request->add(['tiketEmailAtasan'=> session('infoUser')['AL_EMAIL']]);
                $request->request->add(['tiketApproveService'=>'N']);
                $request->request->add(['tiketStatus'=>'1']);
            }else{
                $request->request->add(['tiketApprove'=>'A']);
                $request->request->add(['tiketTglApprove'=>date("Y-m-d H:i:s")]);
                $request->request->add(['tiketNikAtasan'=> '']);
                $request->request->add(['tiketEmailAtasan'=> '']);
                $request->request->add(['tiketApproveService'=>'W']);
                $request->request->add(['tiketStatus'=>'2']);
            }
            Tiket::create($request->all());
            
            $tiket = Tiket::with(['layanan', 'service', 'subService', 'userBy'])
                    ->where(['kode_tiket'=>$request->kode_tiket])
                    ->get(); 
            
            $kode = rand(11111, 99999);
            
            if(session('infoUser')['AL_NIK'] !=""){
                $isiEmail="<html>";
                $isiEmail.="<html>";
                $isiEmail.="<body>";           
                $isiEmail.="Mohon untuk segera diapprove permintaan tiket dengan: <br />";
                $isiEmail.="<table style=\"border:0;bordercolor=#ffffff\" width=\"100%\">";
                $isiEmail.="<tr>";
                $isiEmail.="<td width=\"40\">Nomer</td>";
                $isiEmail.="<td width=\"10\">:</td>";
                $isiEmail.="<td>".$request->kode_tiket."</td>";
                $isiEmail.="</tr>";
                $isiEmail.="<tr>";
                $isiEmail.="<td>Layanan</td>";
                $isiEmail.="<td>:</td>";
                $isiEmail.="<td>".$tiket[0]['layanan'][0]['nama_layanan']."</td>";
                $isiEmail.="</tr>";
                $isiEmail.="<tr>";
                $isiEmail.="<td>Service</td>";
                $isiEmail.="<td>:</td>";
                $isiEmail.="<td>".$tiket[0]['service'][0]['ServiceName']."</td>";
                $isiEmail.="</tr>";
                $isiEmail.="<tr>";
                $isiEmail.="<td>Subservice</td>";
                $isiEmail.="<td>:</td>";
                $isiEmail.="<td>".$tiket[0]['subService'][0]['ServiceSubName']."</td>";
                $isiEmail.="</tr>";
                $isiEmail.="<tr>";
                $isiEmail.="<td>Keterangan</td>";
                $isiEmail.="<td>:</td>";
                $isiEmail.="<td>".$tiket[0]['tiketKeterangan']."</td>";
                $isiEmail.="</tr>";
                $isiEmail.="<tr>";
                $isiEmail.="<td>UserBy</td>";
                $isiEmail.="<td>:</td>";
                $isiEmail.="<td>".$tiket[0]['userBy']['name']."</td>";
                $isiEmail.="</tr>";     
                $isiEmail.="</table><br />";
                $isiEmail.="Silakan akses tiket.silog.co.id dan gunakan user dan password anda untuk login ke aplikasi tersebut. <br />";
                $isiEmail.="<h5>Mohon untuk tidak membalas karena email ini dikirimkan secara otomatis oleh sistem</h5>";
                $isiEmail.= "</body>";
                $isiEmail.="</html>";

                if(session('infoUser')['AL_EMAIL']!=""){
                    $urle = env('API_BASE_URL')."/sendEmail.php";
                    $response = Http::withHeaders([
                                    'Content-Type' => 'application/json',
                                    'token' => 'tiketing.silog.co.id'
                                ])
                                ->post($urle,[
                                    'tanggal' => date("Y-m-d H:i:s"),
                                    'recipients' => session('infoUser')['AL_EMAIL'],
                                    #'recipients' => 'triesutrisno@silog.co.id',
                                    'cc' => '',
                                    'subjectEmail' => 'Permintaan Approve Tiket',
                                    'isiEmail' => addslashes($isiEmail),
                                    'status' => 'outbox',
                                    'password' => 'sistem2017',
                                    'contentEmail' => '0',
                                    'sistem' => 'tiketSilog',
                            ]);
                    #$dtAPi = json_decode($response->getBody()->getContents(),true);  
                    #$responStatus = $response->getStatusCode();
                    //dd($dtAPi);                    
                    
                    $users = User::where(['username'=>session('infoUser')['AL_NIK']])->get();
                    if($users[0]['idTelegram']!=""){
                        $cekApp = Tbapprove::where(['kunci'=>$kode, 'flag'=>'1'])->get();
                        $jmlCek = count($cekApp);
                        if($jmlCek==0){
                            $aktifSampai = date('Y-m-d H:i:s',strtotime('+1 hour',strtotime(date("Y-m-d H:i:s"))));
                            $del = DB::table('tb_approve')->where('tiketId', '=', $tiket[0]['tiketId'])->where('flag', '=', 1)->delete();
                            $app                = new Tbapprove();
                            $app->tiketId       = $tiket[0]['tiketId'];
                            $app->kunci         = $kode;
                            $app->idTelegram    = $users[0]['idTelegram'];
                            $app->aktif_sampai  = $aktifSampai;
                            $app->flag          = "1";
                            $app->save();
                        }
                        $isiTelegram="Mohon untuk segera diapprove permintaan tiket dengan: \n";
                        $isiTelegram.="Nomer : ".$request->kode_tiket." \n";
                        $isiTelegram.="Layanan : ".$tiket[0]['layanan'][0]['nama_layanan']." \n";
                        $isiTelegram.="Service : ".$tiket[0]['service'][0]['ServiceName']." \n";
                        $isiTelegram.="Subservice : ".$tiket[0]['subService'][0]['ServiceSubName']." \n";
                        $isiTelegram.="Keterangan : ".$tiket[0]['tiketKeterangan']." \n";
                        $isiTelegram.="UserBy : ".$tiket[0]['userBy']['name']." \n";
                        $isiTelegram2="\n \n Silakan akses tiket.silog.co.id dan gunakan user dan password anda untuk login ke aplikasi tersebut. \n";
                        
                        $urle2 = env('API_BASE_URL')."/sendTelegram.php";
                        $response2 = Http::withHeaders([
                                'Content-Type' => 'application/json',
                                'token' => 'tiketing.silog.co.id'
                            ])
                            ->post($urle2,[
                                'idTelegram' => $users[0]['idTelegram'],
                                #'idTelegram' => '939753653',
                                'pesan' => $isiTelegram.'
    - <a href="http://tiket.silog.co.id/ap3/approve/'.$kode.'">Approve</a> 

    - <a href="http://tiket.silog.co.id/ap3/reject/'.$kode.'">Reject</a>'.$isiTelegram2,
                                'parse_mode'=>'html'
                        ]);
                    }
                }
            }else{
                $isiEmail="<html>";
                $isiEmail.="<html>";
                $isiEmail.="<body>";           
                $isiEmail.="Saat ini ada mendapatkan permintaan tiket dengan: <br />";
                $isiEmail.="<table style=\"border:0;bordercolor=#ffffff\" width=\"100%\">";
                $isiEmail.="<tr>";
                $isiEmail.="<td width=\"40\">Nomer</td>";
                $isiEmail.="<td width=\"10\">:</td>";
                $isiEmail.="<td>".$request->kode_tiket."</td>";
                $isiEmail.="</tr>";
                $isiEmail.="<tr>";
                $isiEmail.="<td>Layanan</td>";
                $isiEmail.="<td>:</td>";
                $isiEmail.="<td>".$tiket[0]['layanan'][0]['nama_layanan']."</td>";
                $isiEmail.="</tr>";
                $isiEmail.="<tr>";
                $isiEmail.="<td>Service</td>";
                $isiEmail.="<td>:</td>";
                $isiEmail.="<td>".$tiket[0]['service'][0]['ServiceName']."</td>";
                $isiEmail.="</tr>";
                $isiEmail.="<tr>";
                $isiEmail.="<td>Subservice</td>";
                $isiEmail.="<td>:</td>";
                $isiEmail.="<td>".$tiket[0]['subService'][0]['ServiceSubName']."</td>";
                $isiEmail.="</tr>";
                $isiEmail.="<tr>";
                $isiEmail.="<td>Keterangan</td>";
                $isiEmail.="<td>:</td>";
                $isiEmail.="<td>".$tiket[0]['tiketKeterangan']."</td>";
                $isiEmail.="</tr>";
                $isiEmail.="<tr>";
                $isiEmail.="<td>UserBy</td>";
                $isiEmail.="<td>:</td>";
                $isiEmail.="<td>".$tiket[0]['userBy']['name']."</td>";
                $isiEmail.="</tr>";              
                $isiEmail.="</table><br />";
                $isiEmail.="Silakan akses tiket.silog.co.id dan gunakan user dan password anda untuk login ke aplikasi tersebut. <br />";
                $isiEmail.="<h5>Mohon untuk tidak membalas karena email ini dikirimkan secara otomatis oleh sistem</h5>";
                $isiEmail.= "</body>";
                $isiEmail.="</html>";

                if($request->tiketEmailAtasanService!=""){
                    $urle = env('API_BASE_URL')."/sendEmail.php";
                    $response = Http::withHeaders([
                                   'Content-Type' => 'application/json',
                                   'token' => 'tiketing.silog.co.id'
                               ])
                                ->post($urle,[
                                    'tanggal' => date("Y-m-d H:i:s"),
                                    'recipients' => $request->tiketEmailAtasanService,
                                    #'recipients' => 'triesutrisno@gmail.com',
                                    'cc' => '',
                                    'subjectEmail' => 'Info Permintaan Tiket',
                                    'isiEmail' => addslashes($isiEmail),
                                    'status' => 'outbox',
                                    'password' => 'sistem2017',
                                    'contentEmail' => '0',
                                    'sistem' => 'tiketSilog',
                            ]);
                }                
                
                $users = User::where(['username'=>$request->tiketNikAtasanService])->get(); 
                if($users[0]['idTelegram']!=""){
                    $isiTelegram="Mohon untuk segera diapprove permintaan tiket dengan: \n";
                    $isiTelegram.="Nomer : ".$request->kode_tiket." \n";
                    $isiTelegram.="Layanan : ".$tiket[0]['layanan'][0]['nama_layanan']." \n";
                    $isiTelegram.="Service : ".$tiket[0]['service'][0]['ServiceName']." \n";
                    $isiTelegram.="Subservice : ".$tiket[0]['subService'][0]['ServiceSubName']." \n";
                    $isiTelegram.="Keterangan : ".$tiket[0]['tiketKeterangan']." \n";
                    $isiTelegram.="UserBy : ".$tiket[0]['userBy']['name']." \n\n";
                    $isiTelegram.="Silakan akses tiket.silog.co.id dan gunakan user dan password anda untuk login ke aplikasi tersebut. \n";

                    $urle2 = env('API_BASE_URL')."/sendTelegram.php";
                    $response2 = Http::withHeaders([
                            'Content-Type' => 'application/json',
                            'token' => 'tiketing.silog.co.id'
                        ])
                        ->post($urle2,[
                            'idTelegram' => $users[0]['idTelegram'],
                            'pesan' => $isiTelegram,
                    ]);
                }
            }
            
            return redirect('/tiket')->with(['kode'=>'99', 'pesan'=>'Data berhasil disimpan !']);
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
                    'h.name as namaTeknisi',
                    'b.namaAkun',
                    'b.passwordAkun',
                    'b.tglWawancara',
                    'b.tglMulaiMengerjakan',
                    'b.tglSelesaiMengerjakan',
                    'b.tglImplementasi',
                    'b.tglPelatihan',
                    'f.progresProsen',
                    'a.namaLengkap',
                    'a.nikLengkap',
                    'a.noHp'
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
                ->where(['a.tiketId' => $id])
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
                ->where(['b.tiketId' => $id])
                ->orderBy('a.historiId', 'desc')
                ->get();
        //dd($histori);
        return view('tiket.show',['data'=>$datas, 'histori'=>$histori]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tiket = Tiket::with(['layanan', 'service', 'subService'])
                    ->where(['tiketId'=>$id])
                    ->get();        
        if($tiket[0]['tiketStatus']=='1'){
            $subService = Subservice::where(['ServiceSubStatus'=>'1', 'ServiceIDf'=>$tiket[0]['serviceId']])->get();
            
            $urle = env('API_BASE_URL')."/getKepala.php";
            $response = Http::withHeaders([
                            'Content-Type' => 'application/json',
                            'token' => 'tiketing.silog.co.id'
                        ])
                        ->post($urle,[
                            'biro' => $tiket[0]['layanan'][0]['kode_biro'],
                    ]);
            $dtAPi = json_decode($response->getBody()->getContents(),true);  
            $responStatus = $response->getStatusCode();
            //dd($dtAPi);
            if($responStatus=='200'){
                $dtAtasanService = $dtAPi["data"];
            }else{
                $dtAtasanService = $dtAPi["data"];
            }
            return view('tiket.edit', ['tiket'=>$tiket, 'subService'=>$subService, 'dtAtasanService'=>$dtAtasanService]);
        }else{
            return redirect('/tiket')->with(['kode'=>'90', 'pesan'=>'Data tidak bisa diubah !']);
        }
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
        if (Tiket::where([
                ['tiketKeterangan', '=', $request->tiketKeterangan],
                ['nikUser', '=', session('infoUser')['NIK']],
                ['tiketStatus', '=', '1'],
                ['tiketId', '!=', $id]
        ])->doesntExist()) { // Cek data apakah sudah ada atau belum di database   
            if($request->file('tiketFile') == '') {
                $gambar = NULL;
                Tiket::where('tiketId', $id)
                    ->update([
                        'tiketKeterangan' => $request->tiketKeterangan,
                        'subServiceId' => $request->subServiceId,
                        'tiketPrioritas' => $request->tiketPrioritas,
                        'tiketNikAtasanService' => $request->tiketNikAtasanService,
                  ]);
            } else {
                $file = $request->file('tiketFile');
                $dt = Carbon::now();
                $acak  = $file->getClientOriginalExtension();
                $fileName = $dt->format('YmdHis')."-".rand(11111,99999).'.'.$acak; 
                //dd($fileName);
                $request->file('tiketFile')->move("images/fileTiket", $fileName);
                $gambar = $fileName;
                
                Tiket::where('tiketId', $id)
                    ->update([
                        'tiketKeterangan' => $request->tiketKeterangan,
                        'subServiceId' => $request->subServiceId,
                        'tiketPrioritas' => $request->tiketPrioritas,
                        'tiketNikAtasanService' => $request->tiketNikAtasanService,
                        'file' => $gambar,
                  ]);
            }
            
            return redirect('/tiket')->with(['kode'=>'99', 'pesan'=>'Data berhasil diubah !']);
        }else{
            return redirect('/tiket')->with(['kode'=>'90', 'pesan'=>'Data sudah ada !']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Tiket::destroy($id);
        return redirect('/tiket')->with(['pesan'=> 'Data berhasil dihapus !']);
    }
    
    public function close($id)
    {
                
        Tiket::where('tiketId', $id)
          ->update([
              'tiketStatus' => 8,
        ]);
        
        $tiketDetail = Tiketdetail::where('tiketId', '=', $id)->get();
        $jml = $tiketDetail->count();
        //dd($jml);
        if($jml) { // Cek data apakah sudah ada atau belum di database    
            //dd($tiketDetail);
            Tiketdetail::where('tiketDetailId', $tiketDetail[0]->tiketDetailId)
              ->update([
                  'tiketDetailStatus' => 6,
                  'keterangan' => 'Tiket Close',
            ]);

            $histori = new Histori();
            $histori->keterangan    = 'Tiket Close';
            $histori->progresId     = '20';
            $histori->tiketDetailId = $tiketDetail[0]->tiketDetailId;
            $histori->save();
        }
        
        return redirect('/tiket')->with(['kode'=>'99', 'pesan'=>'Tiket berhasil Close !']);
        
    }
}
