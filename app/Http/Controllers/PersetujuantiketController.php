<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Tiket;
use App\Tiketdetail;

class PersetujuantiketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = Tiket::with(['layanan', 'service', 'subService'])
                        ->where(['tiketNikAtasanService' => session('infoUser')['NIK'], 'tiketApproveService'=>'W'])
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
            $isiEmail.="<tr>";
            $isiEmail.="<td>Service</td>";
            $isiEmail.="<td>:</td>";
            $isiEmail.="<td>".$tiket[0]['service'][0]['ServiceName']."</td>";
            $isiEmail.="<tr>";
            $isiEmail.="<td>Subservice</td>";
            $isiEmail.="<td>:</td>";
            $isiEmail.="<td>".$tiket[0]['subService'][0]['serviceSubName']."</td>";
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
                            #'recipients' => session('infoUser')['AL_EMAIL'],
                            'recipients' => 'triesutrisno@gmail.com',
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
}
