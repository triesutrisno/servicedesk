<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Tiket;

class ApprovetiketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = Tiket::with(['layanan', 'service', 'subService'])
                        ->where(['tiketNikAtasan' => session('infoUser')['NIK'], 'tiketApprove'=>'W'])
                        ->get();
        
        return view('approvetiket.index', ['datas'=>$datas, 'kode'=>'', 'pesan'=>'']);
    }

    public function approve($id)
    {
        $tiket = Tiket::with(['layanan', 'service', 'subService'])
                    ->where(['tiketId'=>$id])
                    ->get(); 
        //dd($tiket[0]['kode_tiket']);
        if($tiket[0]['tiketStatus']==1){
            Tiket::where('tiketId', $id)
                ->update([
                    'tiketApprove' => "A",
                    'tiketTglApprove' => date("Y-m-d H:i:s"),
                    'tiketApproveService' => "W",
                    'tiketStatus' => "2",
            ]);

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
            return redirect('/approvetiket')->with(['kode'=>'99', 'pesan'=>'Data berhasil diapprove !']);
        }else{
            return redirect('/approvetiket')->with(['kode'=>'90', 'pesan'=>'Data tidak bisa diapprove !']);
        }        
    }

    
    public function reject($id)
    {
        Tiket::where('tiketId', $id)
          ->update([
              'tiketApprove' => "R",
              'tiketTglApprove' => date("Y-m-d H:i:s"),
              'tiketApproveService' => "N",
              'tiketStatus' => "3",
        ]);
        return redirect('/approvetiket')->with(['kode'=>'99', 'pesan'=>'Data berhasil reject !']);
    }
}
