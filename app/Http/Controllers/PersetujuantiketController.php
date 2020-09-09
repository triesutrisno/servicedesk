<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Tiket;
use App\Tiketdetail;
use DB;

class PersetujuantiketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
                ->where(['a.tiketNikAtasanService' => session('infoUser')['NIK'], 'a.tiketApproveService'=>'W'])
                ->orderBy('a.tiketStatus', 'asc')
                ->orderBy('a.kode_tiket', 'asc')
                ->get();
        
        $urle = env('API_BASE_URL')."/getAnakBuah.php";
        $response = Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'token' => 'tiketing.silog.co.id'
                    ])
                    ->post($urle,[
                        'idPegawai' => session('infoUser')['IDE'],
                        'parentId' => session('infoUser')['PROFIT_CTR_ID']
                ]);
        $dtAPi = json_decode($response->getBody()->getContents(),true);  
        $responStatus = $response->getStatusCode();
        //dd($dtAPi["data"]);
        if($responStatus=='200'){
            $dtAtasanService = $dtAPi["data"];
        }else{
            $dtAtasanService = $dtAPi["data"];
        }
        
        return view('persetujuantiket.index', ['datas'=>$datas, 'dtAtasanService'=>$dtAtasanService, 'kode'=>'', 'pesan'=>'']);
    }

    
    public function approve(Request $request)
    {
        //dd($request->tiketId);
        $tiket = Tiket::with(['layanan', 'service', 'subService'])
                    ->where(['tiketId'=>$request->tiketId])
                    ->get(); 
        //dd($tiket[0]['kode_tiket']);
        if($tiket[0]['tiketStatus']==2){
            Tiket::where('tiketId', $tiket[0]['tiketId'])
                ->update([
                    'tiketTglApproveService' => date("Y-m-d H:i:s"),
                    'tiketApproveService' => "A",
                    'tiketStatus' => "4",
            ]);
            
            $tiketDetail = new Tiketdetail();
            $tiketDetail->tiketId = $tiket[0]['tiketId'];
            $tiketDetail->nikTeknisi = $request->nikTeknisi;            
            $tiketDetail->tiketDetailStatus = "1";
            $tiketDetail->save();

            $isiEmail="<html>";
            $isiEmail.="<html>";
            $isiEmail.="<body>";           
            $isiEmail.="Saat ini ada diminta untuk mengerjakan tiket dengan: <br />";
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
            $isiEmail.="<td>".$tiket[0]['subService'][0]['serviceSubName']."</td>";
            $isiEmail.="</tr>";
            $isiEmail.="<tr>";
            $isiEmail.="<td>Keterangan</td>";
            $isiEmail.="<td>:</td>";
            $isiEmail.="<td>".$tiket[0]['tiketKeterangan']."</td>";
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
                            'recipients' => $request->emailTeknisi,
                            #'recipients' => 'triesutrisno@gmail.com',
                            'cc' => '',
                            'subjectEmail' => 'Info Pengerjaan Tiket',
                            'isiEmail' => addslashes($isiEmail),
                            'status' => 'outbox',
                            'password' => 'sistem2017',
                            'contentEmail' => '0',
                            'sistem' => 'tiketSilog',
                    ]);
            return redirect('/persetujuantiket')->with(['kode'=>'99', 'pesan'=>'Data berhasil disetujui !']);
        }else{
            return redirect('/persetujuantiket')->with(['kode'=>'90', 'pesan'=>'Data tidak bisa disetujui !']);
        }
    }

    
    public function reject($id)
    {
        Tiket::where('tiketId', $id)
          ->update([
              'tiketApproveService' => "R",
              'tiketTglApproveService' => date("Y-m-d H:i:s"),
              'tiketStatus' => "5",
        ]);
        return redirect('/persetujuantiket')->with(['kode'=>'99', 'pesan'=>'Data berhasil reject !']);
    }
    
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
}
