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
use App\User;
use Illuminate\Support\Facades\Http;

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
        $tikets = Tiket::get();
        
        return view('home', ['tikets'=>$tikets, 'kode'=>'', 'pesan'=>'']);
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
                $datas->where('a.updated_at', '>=', date("Y-m-d"))->whereIn('tiketStatus', ['7','10']);
            }elseif($id=='3'){
                $datas->where('a.updated_at', '>=', date("Y-m-d"))->where('tiketStatus', '=', '8');
            }elseif($id=='4'){
                $datas->where('a.updated_at', '>=', date("Y-m-d"))->where('tiketStatus', '<', '7');
            }elseif($id=='5'){
                $datas->where('a.created_at', '>=', date("Y-m-01"));
            }elseif($id=='6'){
                $datas->where('a.updated_at', '>=', date("Y-m-01"))->whereIn('tiketStatus', ['7','10']);
            }elseif($id=='7'){
                $datas->where('a.updated_at', '>=', date("Y-m-01"))->where('tiketStatus', '=', '8');
            }elseif($id=='8'){
                $datas->where('a.updated_at', '>=', date("Y-m-01"))->where('tiketStatus', '<', '7');
            }elseif($id=='9'){
                $datas->where('a.created_at', '>=', date("Y-01-01"));
            }elseif($id=='10'){
                $datas->where('a.updated_at', '>=', date("Y-01-01"))->whereIn('tiketStatus', ['7','10']);
            }elseif($id=='11'){
                $datas->where('a.updated_at', '>=', date("Y-01-01"))->where('tiketStatus', '=', '8');
            }elseif($id=='12'){
                $datas->where('a.updated_at', '>=', date("Y-01-01"))->where('tiketStatus', '<', '7');
            }
            
            $result = $datas->groupBy('a.tiketId')
                            ->orderBy('a.tiketStatus', 'asc')
                            ->orderBy('a.kode_tiket', 'asc')
                            ->get();
        
        return view('detail', ['datas'=>$result]);
    }
    
    public function approve($kode)
    {
        $cekKode = $datas = DB::table('tb_approve')
            ->select('*')
            ->where('kunci', '=', $kode)
            ->get();
        $jml = count($cekKode);
        if($jml>0){ // Jika Kode ada
            $date = date("Y-m-d H:i:s");
            if($cekKode[0]->aktif_sampai>=$date){
                $tiket = Tiket::with(['layanan', 'service', 'subService', 'userBy'])
                            ->where(['tiketId'=>$cekKode[0]->tiketId])
                            ->get(); 
                //dd($tiket[0]['subService'][0]['ServiceSubName']);
                if($tiket[0]['tiketStatus']==1){
                    Tiket::where('tiketId', $cekKode[0]->tiketId)
                        ->update([
                            'tiketApprove' => "A",
                            'tiketTglApprove' => date("Y-m-d H:i:s"),
                            'tiketApproveService' => "W",
                            'tiketStatus' => "2",
                    ]);
                    
                    $updFlag = DB::update('update tb_approve set flag = 2, updated_at = ? where appId = ?', [date("Y-m-d H:i:s"),$cekKode[0]->appId]);
                    
                    $isiEmail="<html>";
                    $isiEmail.="<html>";
                    $isiEmail.="<body>";           
                    $isiEmail.="Saat ini ada mendapatkan permintaan tiket dengan: <br />";
                    $isiEmail.="<table style=\"border:0;bordercolor=#ffffff\" width=\"100%\">";
                    $isiEmail.="<tr>";
                    $isiEmail.="<td width=\"40\">Nomer</td>";
                    $isiEmail.="<td width=\"10\">:</td>";
                    $isiEmail.="<td>".$tiket[0]['kode_tiket']."</td>";
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
                    $isiEmail.="<td>".$tiket[0]['userBy'][0]['name']."</td>";
                    $isiEmail.="</tr>";                        
                    $isiEmail.="</table><br />";
                    $isiEmail.="Silakan akses tiket.silog.co.id dan gunakan user dan password anda untuk login ke aplikasi tersebut. <br />";
                    $isiEmail.="<h5>Mohon untuk tidak membalas karena email ini dikirimkan secara otomatis oleh sistem</h5>";
                    $isiEmail.= "</body>";
                    $isiEmail.="</html>";

                    if($tiket[0]['tiketEmailAtasanService']!=""){
                        $urle = env('API_BASE_URL')."/sendEmail.php";
                        $response = Http::withHeaders([
                                       'Content-Type' => 'application/json',
                                       'token' => 'tiketing.silog.co.id'
                                   ])
                                    ->post($urle,[
                                        'tanggal' => date("Y-m-d H:i:s"),
                                        'recipients' => $tiket[0]['tiketEmailAtasanService'],
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
                    $users = User::where(['username'=>$tiket[0]['tiketNikAtasanService']])->get(); 
                    if($users[0]['idTelegram']!=""){
                        $isiTelegram="Saat ini ada mendapatkan permintaan tiket dengan: \n";
                        $isiTelegram.="Nomer : ".$tiket[0]['kode_tiket']." \n";
                        $isiTelegram.="Layanan : ".$tiket[0]['layanan'][0]['nama_layanan']." \n";
                        $isiTelegram.="Service : ".$tiket[0]['service'][0]['ServiceName']." \n";
                        $isiTelegram.="Subservice : ".$tiket[0]['subService'][0]['ServiceSubName']." \n";
                        $isiTelegram.="Keterangan : ".$tiket[0]['tiketKeterangan']." \n";                        
                        $isiTelegram.="UserBy : ".$tiket[0]['userBy'][0]['name']." \n\n";
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
                    return "<center><b>Tiket nomer ".$tiket[0]['kode_tiket']." berhasil diapprove !<br />PT Semen Indonesia Logistik</b></center>";
                }else{
                    return "<center><b>Nomer Tiket ".$tiket[0]['kode_tiket']." tidak bisa diapprove !<br />PT Semen Indonesia Logistik</b></center>";
                }
                
            }else{
                #echo $cekKode[0]->aktif_sampai."<=".$date;
                return "<center><b>Maaf Kode sudah kadaluarsa !<br />PT Semen Indonesia Logistik</b></center>";
            }
        }else{
            return "<center><b>Maaf Kode tidak terdaftar !<br />PT Semen Indonesia Logistik</b></center>";
        }
    }
    
    public function reject($kode)
    {
        $cekKode = $datas = DB::table('tb_approve')
            ->select('*')
            ->where('kunci', '=', $kode)
            ->get();
        $jml = count($cekKode);
        if($jml>0){ // Jika Kode ada
            $date = date("Y-m-d H:i:s");
            if($cekKode[0]->aktif_sampai>=$date){
                $tiket = Tiket::with(['layanan', 'service', 'subService'])
                            ->where(['tiketId'=>$cekKode[0]->tiketId])
                            ->get(); 
                //dd($tiket[0]['subService'][0]['ServiceSubName']);
                if($tiket[0]['tiketStatus']==1){
                    Tiket::where('tiketId', $cekKode[0]->tiketId)
                        ->update([
                            'tiketApprove' => "R",
                            'tiketTglApprove' => date("Y-m-d H:i:s"),
                            'tiketApproveService' => "N",
                            'tiketStatus' => "3",
                    ]);
                    
                    $updFlag = DB::update('update tb_approve set flag = 3, updated_at = ? where appId = ?', [date("Y-m-d H:i:s"),$cekKode[0]->appId]);              
                    
                    return "<center><b>Tiket nomer ".$tiket[0]['kode_tiket']." berhasil direject !<br />PT Semen Indonesia Logistik</b></center>";
                }else{
                    return "<center><b>Nomer Tiket ".$tiket[0]['kode_tiket']." tidak bisa direject !<br />PT Semen Indonesia Logistik</b></center>";
                }
                
            }else{
                #echo $cekKode[0]->aktif_sampai."<=".$date;
                return "<center><b>Maaf Kode sudah kadaluarsa !<br />PT Semen Indonesia Logistik</b></center>";
            }
        }else{
            return "<center><b>Maaf Kode tidak terdaftar !<br />PT Semen Indonesia Logistik</b></center>";
        }
    }
    
    public function approve2($kode)
    {
        $cekKode = $datas = DB::table('tb_approve')
            ->select('*')
            ->where('kunci', '=', $kode)
            ->get();
        $jml = count($cekKode);
        if($jml>0){ // Jika Kode ada
            $date = date("Y-m-d H:i:s");
            if($cekKode[0]->aktif_sampai>=$date){
                $tiket = Tiket::with(['layanan', 'service', 'subService', 'userBy'])
                            ->where(['tiketId'=>$cekKode[0]->tiketId])
                            ->get(); 
                //dd($tiket[0]['subService'][0]['ServiceSubName']);
                if($tiket[0]['tiketStatus']==2){
                    Tiket::where('tiketId', $tiket[0]['tiketId'])
                        ->update([
                            'tiketTglApproveService' => date("Y-m-d H:i:s"),
                            'tiketApproveService' => "A",
                            'tiketStatus' => "4",
                    ]);
                    
                    $tiketDetail = new Tiketdetail();
                    $tiketDetail->tiketId = $tiket[0]['tiketId'];
                    $tiketDetail->nikTeknisi = $cekKode[0]->nikTeknisi;            
                    $tiketDetail->tiketDetailStatus = "1";
                    $tiketDetail->save();
                    
                    $updFlag = DB::update('update tb_approve set flag = 2, updated_at = ? where appId = ?', [date("Y-m-d H:i:s"),$cekKode[0]->appId]);
                    
                    $isiEmail="<html>";
                    $isiEmail.="<html>";
                    $isiEmail.="<body>";           
                    $isiEmail.="Saat ini anda diminta untuk mengerjakan tiket dengan: <br />";
                    $isiEmail.="<table style=\"border:0;bordercolor=#ffffff\" width=\"100%\">";
                    $isiEmail.="<tr>";
                    $isiEmail.="<td width=\"40\">Nomer</td>";
                    $isiEmail.="<td width=\"10\">:</td>";
                    $isiEmail.="<td>".$tiket[0]['kode_tiket']."</td>";
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
                    $isiEmail.="<td>".$tiket[0]['userBy'][0]['name']."</td>";
                    $isiEmail.="</tr>";                    
                    $isiEmail.="</table><br />";
                    $isiEmail.="Silakan akses tiket.silog.co.id dan gunakan user dan password anda untuk login ke aplikasi tersebut. <br />";
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
                                    #'recipients' => $request->emailTeknisi,
                                    'recipients' => 'triesutrisno@gmail.com',
                                    'cc' => '',
                                    'subjectEmail' => 'Info Pengerjaan Tiket',
                                    'isiEmail' => addslashes($isiEmail),
                                    'status' => 'outbox',
                                    'password' => 'sistem2017',
                                    'contentEmail' => '0',
                                    'sistem' => 'tiketSilog',
                            ]);

                    $users = User::where(['username'=>$cekKode[0]->nikTeknisi])->get(); 
                    //dd($users);
                    if($users[0]['idTelegram']!=""){
                        $isiTelegram="Saat ini anda diminta untuk mengerjakan tiket dengan: \n";
                        $isiTelegram.="Nomer : ".$tiket[0]['kode_tiket']." \n";
                        $isiTelegram.="Layanan : ".$tiket[0]['layanan'][0]['nama_layanan']." \n";
                        $isiTelegram.="Service : ".$tiket[0]['service'][0]['ServiceName']." \n";
                        $isiTelegram.="Subservice : ".$tiket[0]['subService'][0]['ServiceSubName']." \n";
                        $isiTelegram.="Keterangan : ".$tiket[0]['tiketKeterangan']." \n";
                        $isiTelegram.="UserBy : ".$tiket[0]['userBy'][0]['name']." \n\n";
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
                    return "<center><b>Tiket nomer ".$tiket[0]['kode_tiket']." berhasil disetujuai !<br />PT Semen Indonesia Logistik</b></center>";
                }else{
                    return "<center><b>Nomer Tiket ".$tiket[0]['kode_tiket']." tidak bisa disetujuai !<br />PT Semen Indonesia Logistik</b></center>";
                }
                
            }else{
                #echo $cekKode[0]->aktif_sampai."<=".$date;
                return "<center><b>Maaf Kode sudah kadaluarsa !<br />PT Semen Indonesia Logistik</b></center>";
            }
        }else{
            return "<center><b>Maaf Kode tidak terdaftar !<br />PT Semen Indonesia Logistik</b></center>";
        }
    }
    
    public function reject2($kode)
    {
        $cekKode = $datas = DB::table('tb_approve')
            ->select('*')
            ->where('kunci', '=', $kode)
            ->get();
        $jml = count($cekKode);
        if($jml>0){ // Jika Kode ada
            $date = date("Y-m-d H:i:s");
            if($cekKode[0]->aktif_sampai>=$date){
                $tiket = Tiket::with(['layanan', 'service', 'subService'])
                            ->where(['tiketId'=>$cekKode[0]->tiketId])
                            ->get(); 
                //dd($tiket[0]['subService'][0]['ServiceSubName']);
                if($tiket[0]['tiketStatus']==2){
                    Tiket::where('tiketId', $cekKode[0]->tiketId)
                        ->update([
                            'tiketApproveService' => "R",
                            'tiketTglApproveService' => date("Y-m-d H:i:s"),
                            'tiketStatus' => "5",
                      ]);
                    
                    $updFlag = DB::update('update tb_approve set flag = 3, updated_at = ? where appId = ?', [date("Y-m-d H:i:s"),$cekKode[0]->appId]);              
                    
                    return "<center><b>Tiket nomer ".$tiket[0]['kode_tiket']." berhasil direject !<br />PT Semen Indonesia Logistik</b></center>";
                }else{
                    return "<center><b>Nomer Tiket ".$tiket[0]['kode_tiket']." tidak bisa direject !<br />PT Semen Indonesia Logistik</b></center>";
                }
                
            }else{
                #echo $cekKode[0]->aktif_sampai."<=".$date;
                return "<center><b>Maaf Kode sudah kadaluarsa !<br />PT Semen Indonesia Logistik</b></center>";
            }
        }else{
            return "<center><b>Maaf Kode tidak terdaftar !<br />PT Semen Indonesia Logistik</b></center>";
        }
    }
}
