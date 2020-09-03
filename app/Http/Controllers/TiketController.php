<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Http;
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
       
    public function index()
    {    
        if(session('infoUser')['LEVEL'] == 'admin')
        {
            $datas = Tiket::with(['layanan', 'service', 'subService'])->get();
        } else {            
            $datas = Tiket::with(['layanan', 'service', 'subService'])
                        ->where(['nikUser' => session('infoUser')['NIK']])
                        ->get();
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
        
        $layanan = Layanan::where(['status_layanan'=>'1'])->get();
        return view('tiket.create', ['layanan'=>$layanan]);
    }
    
    public function created($id)
    {        
        $service = Service::where(['ServiceStatus'=>'1', 'id_layanan'=>$id])->get();
        //dd($service);
        return view('tiket.created', ['service'=>$service]);
    }
    
    public function add($id,$id2)
    {
        $eselon = substr(session('infoUser')['ESELON'],0,1);
        $kode = "TIKET00001";    
        $service = Service::with(['layanan'])
                ->where(['ServiceStatus'=>'1', 'id'=>$id2, 'id_layanan'=>$id])->get();
        $subService = Subservice::where(['ServiceSubStatus'=>'1', 'ServiceIDf'=>$id2])->get();
        
        $urle = "http://172.20.145.36/tiketsilog/getKepala.php";
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
        //dd($request->all());
        if (Tiket::where([
                'layananId'=>$layananId, 
                'serviceId'=>$serviceId, 
                'nikUser'=>session('infoUser')['NIK'], 
                'subServiceId'=>$request->subServiceId, 
                'tiketStatus'=>'1'
            ])->where('created_at', '>=', date("Y-m-d"))->doesntExist()) { // Cek data apakah sudah ada atau belum di database 
            
            $request->request->add(['layananId'=>$layananId]);
            $request->request->add(['serviceId'=>$serviceId]);
            $request->request->add(['comp'=>session('infoUser')['PERUSAHAAN']]);
            $request->request->add(['unit'=>session('infoUser')['UNIT']]);
            $request->request->add(['biro'=>session('infoUser')['BIROBU']]);
            $request->request->add(['nikUser'=>session('infoUser')['NIK']]);
            $request->request->add(['tiketApprove'=>'W']);
            $request->request->add(['tiketNikAtasan'=> session('infoUser')['AL_NIK']]);
            $request->request->add(['tiketApproveService'=>'N']);
            $request->request->add(['tiketStatus'=>'1']);
            Tiket::create($request->all());
            
            
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
            $isiEmail.="<td>Keterangan</td>";
            $isiEmail.="<td>:</td>";
            $isiEmail.="<td>".$request->tiketKeterangan."</td>";
            $isiEmail.="</tr>";            
            $isiEmail.="</table><br />";
            $isiEmail.="Silakan akses tiket.silog.co.id dan gunakan user dan password anda untuk login ke aplikasi tersebut. <br />";
            $isiEmail.="<h5>Mohon untuk tidak membalas karena email ini dikirimkan secara otomatis oleh sistem</h5>";
            $isiEmail.= "</body>";
            $isiEmail.="</html>";
            
            $urle = "http://172.20.145.36/tiketsilog/sendEmail.php";
            $response = Http::withHeaders([
                            'Content-Type' => 'application/json',
                            'token' => 'tiketing.silog.co.id'
                        ])
                        ->post($urle,[
                            'tanggal' => date("Y-m-d H:i:s"),
                            #'recipients' => session('infoUser')['AL_EMAIL'],
                            'recipients' => 'triesutrisno@gmail.com',
                            'cc' => '',
                            'subjectEmail' => 'Permintaan Approve Tiket',
                            'isiEmail' => addslashes($isiEmail),
                            'status' => 'outbox',
                            'password' => 'sistem2017',
                            'contentEmail' => '0',
                            'sistem' => 'tiketSilog',
                    ]);
            $dtAPi = json_decode($response->getBody()->getContents(),true);  
            #$responStatus = $response->getStatusCode();
            //dd($dtAPi);
            
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
        $tiket = Tiket::with(['layanan', 'service', 'subService'])
                    ->where(['tiketId'=>$id])
                    ->get();        
        if($tiket[0]['tiketStatus']=='1'){
            $subService = Subservice::where(['ServiceSubStatus'=>'1', 'ServiceIDf'=>$tiket[0]['serviceId']])->get();
            
            $urle = "http://172.20.145.36/tiketsilog/getKepala.php";
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
            Tiket::where('tiketId', $id)
              ->update([
                  'tiketKeterangan' => $request->tiketKeterangan,
                  'subServiceId' => $request->subServiceId,
                  'tiketPrioritas' => $request->tiketPrioritas,
                  'tiketNikAtasanService' => $request->tiketNikAtasanService,
            ]);
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
}
