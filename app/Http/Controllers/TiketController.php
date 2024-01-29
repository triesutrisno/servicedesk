<?php

namespace App\Http\Controllers;

use App\DataTables\TiketDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Tiket;
use App\Tiketdetail;
use App\Histori;
use App\Nextnumber;
use App\Layanan;
use App\Service;
use App\Subservice;
use App\User;
use App\Tbapprove;
use App\Userlevel;
use Illuminate\Support\Facades\DB;

class TiketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $nomer = $request->post("nomer") != NULL ? $request->post("nomer") : "";
        $status = $request->post("status") != NULL ? $request->post("status") : "";
        $nama = $request->post("nama") != NULL ? $request->post("nama") : "";
        $param["nomer"] = $nomer;
        $param["status"] = $status;
        $param["nama"] = $nama;
        #dd($request->all());
        if (session('infoUser')['LEVEL'] == 'admin') {
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
                    'f.progresProsen',
                    'a.flagFeedback'
                )
                ->leftjoin('tiket_detail as b', 'b.tiketId', '=', 'a.tiketId')
                ->leftjoin('m_layanan as c', 'c.id', '=', 'a.layananId')
                ->leftjoin('ticket_service as d', 'd.id', '=', 'a.serviceId')
                ->leftjoin('ticket_service_sub as e', 'e.id', '=', 'a.subServiceId')
                ->leftjoin('m_progres as f', 'f.progresId', '=', 'b.progresId')
                ->leftjoin('users as g', 'g.username', '=', 'a.nikUser')
                ->when($nomer, function ($query, $nomer) {
                    return $query->where('kode_tiket', $nomer);
                })
                ->when($status, function ($query, $status) {
                    return $query->where('tiketStatus', $status);
                })
                ->when($nama, function ($query, $nama) {
                    return $query->where('name', 'LIKE', '%' . $nama . '%');
                })
                ->orderBy('a.tiketStatus', 'asc')
                ->orderBy('a.kode_tiket', 'asc')
                ->paginate(50);
            #->get();
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
                ->when($nomer, function ($query, $nomer) {
                    return $query->where('kode_tiket', $nomer);
                })
                ->when($status, function ($query, $status) {
                    return $query->where('tiketStatus', $status);
                })
                ->when($nama, function ($query, $nama) {
                    return $query->where('name', 'LIKE', '%' . $nama . '%');
                })
                ->orderBy('a.tiketStatus', 'asc')
                ->orderBy('a.kode_tiket', 'asc')
                ->paginate(50);
            #->get();
        }
        //dd($datas);
        return view('tiket.index', ['datas' => $datas, 'kode' => '', 'pesan' => '', 'param' => $param]);
    }

    public function index2(TiketDataTable $dataTable)
    {

        $param = collect(request()->all());
        $param->put('jenis_opt', Service::pluck('serviceName', 'id'));
        // dd($param);exit;
        $layanan = Layanan::where(['status_layanan' => '1'])->get();
        return $dataTable
            ->render('tiket.index2', ['param' => $param, 'layanan' =>$layanan]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $layanan = Layanan::where(['status_layanan' => '1'])->get();
        return view('tiket.create', ['layanan' => $layanan]);
    }

    public function created($id)
    {
        $service = Service::where(['ServiceStatus' => '1', 'id_layanan' => $id])->orderBy('keterangan', 'asc')->get();
        $userLevel = Userlevel::where(['status' => '1', 'level' => '1'])->orderBy('nik', 'asc')->get()->toArray();
        foreach ($userLevel as $val) {
            $levelUser[] = $val['nik'];
        }
        #dd($levelUser);
        return view('tiket.created', ['service' => $service, 'userLevel' => $levelUser]);
    }

    public function add($id, $id2)
    {
        $serviceSAP = ['0'];
        $userLevel = Userlevel::where(['status' => '1', 'level' => '1'])->orderBy('nik', 'asc')->get()->toArray();
        foreach ($userLevel as $val) {
            $arrayNIK[] = $val['nik'];
        }
        if (in_array($id2, $serviceSAP) && !in_array(session('infoUser')['USERNAME'], $arrayNIK)) {
            return redirect('/tiket')->with(['kode' => '90', 'pesan' => 'Anda tidak diijinkan mengakses menu ini !']);
        } else {
            //testing aplikasi
            $eselon = substr(session('infoUser')['ESELON'], 0, 1);
            // print_r($eselon);exit;
            $dtNextnumber = Nextnumber::where([
                'tahun' => date("Y"),
                'status' => 1,
            ])->get();
            $jmlNext = count($dtNextnumber);
            if ($jmlNext > 0) {
                $nomer = sprintf("%05d", $dtNextnumber[0]['nextnumber']);
                $nextnumber = "TK" . date("y") . date("m") . $nomer; //TK200900001
                //dd($nextnumber);
                if (tiket::where(['kode_tiket' => $nextnumber])->doesntExist()) {
                    $kode = $nextnumber;
                    $update = Nextnumber::where('id', $dtNextnumber[0]['id'])
                        ->update([
                            'nextnumber' => $dtNextnumber[0]['nextnumber'] + 1,
                        ]);
                } else {
                    return redirect('/tiket')->with(['kode' => '90', 'pesan' => 'Nextnumber ' . $nextnumber . ' sudah ada ditahun ini !']);
                }
            } else {
                return redirect('/tiket')->with(['kode' => '90', 'pesan' => 'Nextnumber ditahun ini belum disetting !']);
            }

            $service = Service::with(['layanan'])
                ->where(['ServiceStatus' => '1', 'id' => $id2, 'id_layanan' => $id])->get();
            $subService = Subservice::where(['ServiceSubStatus' => '1', 'ServiceIDf' => $id2])->orderBy('urutan', 'asc')->get();

            $urle = env('API_BASE_URL') . "/getKepala.php";
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'token' => 'tiketing.silog.co.id'
            ])
                ->post($urle, [
                    'biro' => $service[0]['layanan'][0]['kode_biro'],
                    'serviceId' => $id2,
                ]);
            $dtAPi = json_decode($response->getBody()->getContents(), true);
            $responStatus = $response->getStatusCode();
            //dd($dtAPi);
            if ($responStatus == '200') {
                $dtAtasanService = $dtAPi["data"];
            } else {
                $dtAtasanService = $dtAPi["data"];
            }

            //dd($eselon."<=".$service[0]['min_eselon']);
            if ($eselon <= $service[0]['min_eselon']) {
                return view('tiket.add', [
                    'service' => $service,
                    'subService' => $subService,
                    'dtAtasanService' => $dtAtasanService,
                    'id_layanan' => $id,
                    'id_service' => $id2,
                    'kode' => $kode
                ]);
            } else {
                return redirect('/tiket')->with('pesan', 'Anda tidak diijinkan mengakses menu yang tadi !');
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $layananId, $serviceId)
    {
        // dd($request);exit;
        // $request->validate([
        //     'tiketNikAtasanService' => 'required',
        // ]);
        /// ATASAN NOW AUTOMATIC FROM SUB SERVICE SELECTED

        //dd($request->all());
        if ($request->file('tiketFile') == '') {
            $gambar = NULL;
        } else {
            $file = $request->file('tiketFile');
            $dt = Carbon::now();
            $acak  = $file->getClientOriginalExtension();
            $fileName = $dt->format('YmdHis') . "-" . rand(11111, 99999) . '.' . $acak;
            //dd($fileName);
            $request->file('tiketFile')->move("images/fileTiket", $fileName);
            $gambar = $fileName;
        }

        if (Tiket::where([
            'layananId' => $layananId,
            'serviceId' => $serviceId,
            'nikUser' => session('infoUser')['NIK'],
            'subServiceId' => $request->subServiceId,
            'tiketKeterangan' => $request->tiketKeterangan,
            'tiketStatus' => '1'
        ])->where('created_at', '>=', date("Y-m-d"))->doesntExist()) { // Cek data apakah sudah ada atau belum di database

            $request->request->add(['layananId' => $layananId]);
            $request->request->add(['serviceId' => $serviceId]);
            $request->request->add(['comp' => session('infoUser')['PERUSAHAAN']]);
            $request->request->add(['unit' => session('infoUser')['UNIT']]);
            $request->request->add(['biro' => session('infoUser')['BIROBU']]);
            // $request->request->add(['unit' => 'H1070200']);
            // $request->request->add(['biro' => 'H1070000']);
            $request->request->add(['nikUser' => session('infoUser')['NIK']]);
            $request->request->add(['tiketEmail' => session('infoUser')['EMAIL']]);
            $request->request->add(['file' => $gambar]);
            $serviceSAP = ['0'];
            if (in_array($serviceId, $serviceSAP)) {
                $request->request->add(['tiketApprove' => 'A']);
                $request->request->add(['tiketTglApprove' => date("Y-m-d H:i:s")]);
                $request->request->add(['tiketNikAtasan' => '']);
                $request->request->add(['tiketEmailAtasan' => '']);
                $request->request->add(['tiketApproveService' => 'W']);
                $request->request->add(['tiketStatus' => '2']);
                $request->request->add(['sort' => '9']);
            } else {
                if (session('infoUser')['AL_NIK'] != "") {
                    if (in_array(session('infoUser')['ESELON'], array('10', '11', '12')) || session('infoUser')['AL_ESELON'] == "D1") {
                        $request->request->add(['tiketApprove' => 'A']);
                        $request->request->add(['tiketTglApprove' => date("Y-m-d H:i:s")]);
                        $request->request->add(['tiketNikAtasan' => '']);
                        $request->request->add(['tiketEmailAtasan' => '']);
                        $request->request->add(['tiketApproveService' => 'W']);
                        $request->request->add(['tiketStatus' => '2']);
                        $request->request->add(['sort' => '9']);
                    } else {
                        $request->request->add(['tiketApprove' => 'W']);
                        $request->request->add(['tiketNikAtasan' => session('infoUser')['AL_NIK']]);
                        $request->request->add(['tiketEmailAtasan' => session('infoUser')['AL_EMAIL']]);
                        // $request->request->add(['tiketNikAtasan' => '942834']);
                        // $request->request->add(['tiketEmailAtasan' => 'tomi@silog.co.id']);
                        $request->request->add(['tiketApproveService' => 'N']);
                        $request->request->add(['tiketStatus' => '1']);
                        $request->request->add(['sort' => '10']);
                    }
                } else {
                    $request->request->add(['tiketApprove' => 'A']);
                    $request->request->add(['tiketTglApprove' => date("Y-m-d H:i:s")]);
                    $request->request->add(['tiketNikAtasan' => '']);
                    $request->request->add(['tiketEmailAtasan' => '']);
                    $request->request->add(['tiketApproveService' => 'W']);
                    $request->request->add(['tiketStatus' => '2']);
                    $request->request->add(['sort' => '9']);
                }
            }

            //set tiketNikAtasanService
            $subService = Subservice::find($request->get('subServiceId'));
            // dd($subService->unit->atasanUnit);
            $atasanUnit = $subService->unit->atasanUnit;
            $request->request->add(['tiketNikAtasanService' => $atasanUnit->username]);
            $request->request->add(['tiketEmailAtasanService' => $atasanUnit->email]);
            // $request->request->add(['tiketNikAtasanService' => '942834']);
            // $request->request->add(['tiketEmailAtasanService' => 'tomi@silog.co.id']);
            //

            Tiket::create($request->all());

            $tiket = Tiket::with(['layanan', 'service', 'subService', 'userBy'])
                ->where(['kode_tiket' => $request->kode_tiket])
                ->get();



            $kode = rand(11111, 99999);
            if (in_array($serviceId, $serviceSAP)) {
                $isiEmail = "<html>";
                $isiEmail .= "<html>";
                $isiEmail .= "<body>";
                $isiEmail .= "Saat ini ada mendapatkan permintaan tiket dengan: <br />";
                $isiEmail .= "<table style=\"border:0;bordercolor=#ffffff\" width=\"100%\">";
                $isiEmail .= "<tr>";
                $isiEmail .= "<td width=\"40\">Nomer</td>";
                $isiEmail .= "<td width=\"10\">:</td>";
                $isiEmail .= "<td>" . $request->kode_tiket . "</td>";
                $isiEmail .= "</tr>";
                $isiEmail .= "<tr>";
                $isiEmail .= "<td>Layanan</td>";
                $isiEmail .= "<td>:</td>";
                $isiEmail .= "<td>" . $tiket[0]['layanan'][0]['nama_layanan'] . "</td>";
                $isiEmail .= "</tr>";
                $isiEmail .= "<tr>";
                $isiEmail .= "<td>Service</td>";
                $isiEmail .= "<td>:</td>";
                $isiEmail .= "<td>" . $tiket[0]['service']['ServiceName'] . "</td>";
                $isiEmail .= "</tr>";
                $isiEmail .= "<tr>";
                $isiEmail .= "<td>Subservice</td>";
                $isiEmail .= "<td>:</td>";
                $isiEmail .= "<td>" . $tiket[0]['subService']['ServiceSubName'] . "</td>";
                $isiEmail .= "</tr>";
                $isiEmail .= "<tr>";
                $isiEmail .= "<td>Keterangan</td>";
                $isiEmail .= "<td>:</td>";
                $isiEmail .= "<td>" . $tiket[0]['tiketKeterangan'] . "</td>";
                $isiEmail .= "</tr>";
                $isiEmail .= "<tr>";
                $isiEmail .= "<td>UserBy</td>";
                $isiEmail .= "<td>:</td>";
                $isiEmail .= "<td>" . $tiket[0]['userBy']['name'] . "</td>";
                $isiEmail .= "</tr>";
                $isiEmail .= "</table><br />";
                $isiEmail .= "Silakan akses tiket.silog.co.id dan gunakan user dan password anda untuk login ke aplikasi tersebut. <br />";
                $isiEmail .= "<h5>Mohon untuk tidak membalas karena email ini dikirimkan secara otomatis oleh sistem</h5>";
                $isiEmail .= "</body>";
                $isiEmail .= "</html>";

                if ($request->tiketEmailAtasanService != "") {
                    $urle = env('API_BASE_URL') . "/sendEmail.php";
                    $response = Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'token' => 'tiketing.silog.co.id'
                    ])
                        ->post($urle, [
                            'tanggal' => date("Y-m-d H:i:s"),
                            'recipients' => $request->tiketEmailAtasanService,
                            // 'recipients' => 'tomi@silog.co.id',
                            'cc' => '',
                            'subjectEmail' => 'Info Permintaan Tiket',
                            'isiEmail' => addslashes($isiEmail),
                            'status' => 'outbox',
                            'password' => 'Veteran1974!@Gsk',
                            'contentEmail' => '0',
                            'sistem' => 'tiketSilog',
                        ]);
                }

                // $users = User::where(['username' => $request->tiketNikAtasanService])->get();
                $users = User::where(['username' => $tiket->subService->nik_atasan_service])->get();
                // $users = User::where(['username' => '942834'])->get();
                if ($users[0]['idTelegram'] != "") {
                    $isiTelegram = "Mohon untuk segera diapprove permintaan tiket dengan: \n";
                    $isiTelegram .= "Nomer : " . $request->kode_tiket . " \n";
                    $isiTelegram .= "Layanan : " . $tiket[0]['layanan'][0]['nama_layanan'] . " \n";
                    $isiTelegram .= "Service : " . $tiket[0]['service']['ServiceName'] . " \n";
                    $isiTelegram .= "Subservice : " . $tiket[0]['subService']['ServiceSubName'] . " \n";
                    $isiTelegram .= "Keterangan : " . $tiket[0]['tiketKeterangan'] . " \n";
                    $isiTelegram .= "UserBy : " . $tiket[0]['userBy']['name'] . " \n\n";
                    $isiTelegram .= "Silakan akses tiket.silog.co.id dan gunakan user dan password anda untuk login ke aplikasi tersebut. \n";

                    $urle2 = env('API_BASE_URL') . "/sendTelegram.php";
                    $response2 = Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'token' => 'tiketing.silog.co.id'
                    ])
                        ->post($urle2, [
                            'idTelegram' => $users[0]['idTelegram'],
                            'pesan' => $isiTelegram,
                        ]);
                }
            } else {
                if (session('infoUser')['AL_NIK'] != "") {
                    $isiEmail = "<html>";
                    $isiEmail .= "<html>";
                    $isiEmail .= "<body>";
                    $isiEmail .= "Mohon untuk segera diapprove permintaan tiket dengan: <br />";
                    $isiEmail .= "<table style=\"border:0;bordercolor=#ffffff\" width=\"100%\">";
                    $isiEmail .= "<tr>";
                    $isiEmail .= "<td width=\"40\">Nomer</td>";
                    $isiEmail .= "<td width=\"10\">:</td>";
                    $isiEmail .= "<td>" . $request->kode_tiket . "</td>";
                    $isiEmail .= "</tr>";
                    $isiEmail .= "<tr>";
                    $isiEmail .= "<td>Layanan</td>";
                    $isiEmail .= "<td>:</td>";
                    $isiEmail .= "<td>" . $tiket[0]['layanan'][0]['nama_layanan'] . "</td>";
                    $isiEmail .= "</tr>";
                    $isiEmail .= "<tr>";
                    $isiEmail .= "<td>Service</td>";
                    $isiEmail .= "<td>:</td>";
                    $isiEmail .= "<td>" . $tiket[0]['service']['ServiceName'] . "</td>";
                    $isiEmail .= "</tr>";
                    $isiEmail .= "<tr>";
                    $isiEmail .= "<td>Subservice</td>";
                    $isiEmail .= "<td>:</td>";
                    $isiEmail .= "<td>" . $tiket[0]['subService']['ServiceSubName'] . "</td>";
                    $isiEmail .= "</tr>";
                    $isiEmail .= "<tr>";
                    $isiEmail .= "<td>Keterangan</td>";
                    $isiEmail .= "<td>:</td>";
                    $isiEmail .= "<td>" . $tiket[0]['tiketKeterangan'] . "</td>";
                    $isiEmail .= "</tr>";
                    $isiEmail .= "<tr>";
                    $isiEmail .= "<td>UserBy</td>";
                    $isiEmail .= "<td>:</td>";
                    $isiEmail .= "<td>" . $tiket[0]['userBy']['name'] . "</td>";
                    $isiEmail .= "</tr>";
                    $isiEmail .= "</table><br />";
                    $isiEmail .= "Silakan akses tiket.silog.co.id dan gunakan user dan password anda untuk login ke aplikasi tersebut. <br />";
                    $isiEmail .= "<h5>Mohon untuk tidak membalas karena email ini dikirimkan secara otomatis oleh sistem</h5>";
                    $isiEmail .= "</body>";
                    $isiEmail .= "</html>";

                    if (session('infoUser')['AL_EMAIL'] != "") {
                        $urle = env('API_BASE_URL') . "/sendEmail.php";
                        $response = Http::withHeaders([
                            'Content-Type' => 'application/json',
                            'token' => 'tiketing.silog.co.id'
                        ])
                            ->post($urle, [
                                'tanggal' => date("Y-m-d H:i:s"),
                                'recipients' => session('infoUser')['AL_EMAIL'],
                                #'recipients' => 'triesutrisno@silog.co.id',
                                'cc' => '',
                                'subjectEmail' => 'Permintaan Approve Tiket',
                                'isiEmail' => addslashes($isiEmail),
                                'status' => 'outbox',
                                'password' => 'Veteran1974!@Gsk',
                                'contentEmail' => '0',
                                'sistem' => 'tiketSilog',
                            ]);
                        #$dtAPi = json_decode($response->getBody()->getContents(),true);
                        #$responStatus = $response->getStatusCode();
                        //dd($dtAPi);

                        $users = User::where(['username' => session('infoUser')['AL_NIK']])->get();
                        if ($users[0]['idTelegram'] != "") {
                            $cekApp = Tbapprove::where(['kunci' => $kode, 'flag' => '1'])->get();
                            $jmlCek = count($cekApp);
                            if ($jmlCek == 0) {
                                $aktifSampai = date('Y-m-d H:i:s', strtotime('+1 hour', strtotime(date("Y-m-d H:i:s"))));
                                $del = DB::table('tb_approve')->where('tiketId', '=', $tiket[0]['tiketId'])->where('flag', '=', 1)->delete();
                                $app                = new Tbapprove();
                                $app->tiketId       = $tiket[0]['tiketId'];
                                $app->kunci         = $kode;
                                $app->idTelegram    = $users[0]['idTelegram'];
                                $app->aktif_sampai  = $aktifSampai;
                                $app->flag          = "1";
                                $app->save();
                            }
                            $isiTelegram = "Mohon untuk segera diapprove permintaan tiket dengan: \n";
                            $isiTelegram .= "Nomer : " . $request->kode_tiket . " \n";
                            $isiTelegram .= "Layanan : " . $tiket[0]['layanan'][0]['nama_layanan'] . " \n";
                            $isiTelegram .= "Service : " . $tiket[0]['service']['ServiceName'] . " \n";
                            $isiTelegram .= "Subservice : " . $tiket[0]['subService']['ServiceSubName'] . " \n";
                            $isiTelegram .= "Keterangan : " . $tiket[0]['tiketKeterangan'] . " \n";
                            $isiTelegram .= "UserBy : " . $tiket[0]['userBy']['name'] . " \n";
                            $isiTelegram2 = "\n \n Silakan akses tiket.silog.co.id dan gunakan user dan password anda untuk login ke aplikasi tersebut. \n";

                            $urle2 = env('API_BASE_URL') . "/sendTelegram.php";
                            $response2 = Http::withHeaders([
                                'Content-Type' => 'application/json',
                                'token' => 'tiketing.silog.co.id'
                            ])
                                ->post($urle2, [
                                    'idTelegram' => $users[0]['idTelegram'],
                                    #'idTelegram' => '939753653',
                                    'pesan' => $isiTelegram . '
        - <a href="http://tiket.silog.co.id/ap3/approve/' . $kode . '">Approve</a>

        - <a href="http://tiket.silog.co.id/ap3/reject/' . $kode . '">Reject</a>' . $isiTelegram2,
                                    'parse_mode' => 'html'
                                ]);
                        }
                    }
                } else {
                    $isiEmail = "<html>";
                    $isiEmail .= "<html>";
                    $isiEmail .= "<body>";
                    $isiEmail .= "Saat ini ada mendapatkan permintaan tiket dengan: <br />";
                    $isiEmail .= "<table style=\"border:0;bordercolor=#ffffff\" width=\"100%\">";
                    $isiEmail .= "<tr>";
                    $isiEmail .= "<td width=\"40\">Nomer</td>";
                    $isiEmail .= "<td width=\"10\">:</td>";
                    $isiEmail .= "<td>" . $request->kode_tiket . "</td>";
                    $isiEmail .= "</tr>";
                    $isiEmail .= "<tr>";
                    $isiEmail .= "<td>Layanan</td>";
                    $isiEmail .= "<td>:</td>";
                    $isiEmail .= "<td>" . $tiket[0]['layanan'][0]['nama_layanan'] . "</td>";
                    $isiEmail .= "</tr>";
                    $isiEmail .= "<tr>";
                    $isiEmail .= "<td>Service</td>";
                    $isiEmail .= "<td>:</td>";
                    $isiEmail .= "<td>" . $tiket[0]['service']['ServiceName'] . "</td>";
                    $isiEmail .= "</tr>";
                    $isiEmail .= "<tr>";
                    $isiEmail .= "<td>Subservice</td>";
                    $isiEmail .= "<td>:</td>";
                    $isiEmail .= "<td>" . $tiket[0]['subService']['ServiceSubName'] . "</td>";
                    $isiEmail .= "</tr>";
                    $isiEmail .= "<tr>";
                    $isiEmail .= "<td>Keterangan</td>";
                    $isiEmail .= "<td>:</td>";
                    $isiEmail .= "<td>" . $tiket[0]['tiketKeterangan'] . "</td>";
                    $isiEmail .= "</tr>";
                    $isiEmail .= "<tr>";
                    $isiEmail .= "<td>UserBy</td>";
                    $isiEmail .= "<td>:</td>";
                    $isiEmail .= "<td>" . $tiket[0]['userBy']['name'] . "</td>";
                    $isiEmail .= "</tr>";
                    $isiEmail .= "</table><br />";
                    $isiEmail .= "Silakan akses tiket.silog.co.id dan gunakan user dan password anda untuk login ke aplikasi tersebut. <br />";
                    $isiEmail .= "<h5>Mohon untuk tidak membalas karena email ini dikirimkan secara otomatis oleh sistem</h5>";
                    $isiEmail .= "</body>";
                    $isiEmail .= "</html>";

                    if ($request->tiketEmailAtasanService != "") {
                        $urle = env('API_BASE_URL') . "/sendEmail.php";
                        $response = Http::withHeaders([
                            'Content-Type' => 'application/json',
                            'token' => 'tiketing.silog.co.id'
                        ])
                            ->post($urle, [
                                'tanggal' => date("Y-m-d H:i:s"),
                                'recipients' => $request->tiketEmailAtasanService,
                                // 'recipients' => 'tomi@silog.co.id',
                                'cc' => '',
                                'subjectEmail' => 'Info Permintaan Tiket',
                                'isiEmail' => addslashes($isiEmail),
                                'status' => 'outbox',
                                'password' => 'Veteran1974!@Gsk',
                                'contentEmail' => '0',
                                'sistem' => 'tiketSilog',
                            ]);
                    }

                    // $users = User::where(['username' => $request->tiketNikAtasanService])->get();
                    $users = User::where(['username' => $subService->unit->nik_atasan_service])->get();
                    if ($users[0]['idTelegram'] != "") {
                        $isiTelegram = "Mohon untuk segera diapprove permintaan tiket dengan: \n";
                        $isiTelegram .= "Nomer : " . $request->kode_tiket . " \n";
                        $isiTelegram .= "Layanan : " . $tiket[0]['layanan'][0]['nama_layanan'] . " \n";
                        $isiTelegram .= "Service : " . $tiket[0]['service']['ServiceName'] . " \n";
                        $isiTelegram .= "Subservice : " . $tiket[0]['subService']['ServiceSubName'] . " \n";
                        $isiTelegram .= "Keterangan : " . $tiket[0]['tiketKeterangan'] . " \n";
                        $isiTelegram .= "UserBy : " . $tiket[0]['userBy']['name'] . " \n\n";
                        $isiTelegram .= "Silakan akses tiket.silog.co.id dan gunakan user dan password anda untuk login ke aplikasi tersebut. \n";

                        $urle2 = env('API_BASE_URL') . "/sendTelegram.php";
                        $response2 = Http::withHeaders([
                            'Content-Type' => 'application/json',
                            'token' => 'tiketing.silog.co.id'
                        ])
                            ->post($urle2, [
                                'idTelegram' => $users[0]['idTelegram'],
                                'pesan' => $isiTelegram,
                            ]);
                    }
                }
            }

            return redirect('/tiket')->with(['kode' => '99', 'pesan' => 'Data berhasil disimpan dengan nomer tiket ' . $request->kode_tiket . ' !']);
        } else {
            return redirect('/tiket')->with(['kode' => '90', 'pesan' => 'Data sudah ada !']);
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
                'a.tiketEmail',
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
                'a.noHp',
                'a.tiketSeverity',
                'a.tiketMaindays'
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
        // dd($datas);

        $histori = DB::table('tb_histori as a')
            ->select(
                'a.historiId',
                'a.tiketDetailId',
                'a.progresId',
                'a.created_at',
                'a.keterangan',
                'a.tglRTL',
                'c.progresNama',
                'c.progresProsen',
                'a.file'
            )
            ->leftjoin('tiket_detail as b', 'b.tiketDetailId', '=', 'a.tiketDetailId')
            ->leftjoin('m_progres as c', 'c.progresId', '=', 'a.progresId')
            ->where(['b.tiketId' => $id]);

        $histori2 = DB::table('tb_histori as a')
            ->select(
                'a.historiId',
                'a.tiketId as tiketDetailId',
                'a.progresId',
                'a.created_at',
                'a.keterangan',
                'a.tglRTL',
                'c.progresNama',
                'c.progresProsen',
                'a.file'
            )
            ->leftjoin('tiket as b', 'b.tiketId', '=', 'a.tiketId')
            ->leftjoin('m_progres as c', 'c.progresId', '=', 'a.progresId')
            ->where(['b.tiketId' => $id])
            ->union($histori)
            ->orderBy('historiId', 'desc')
            ->get();
        //dd($histori);
        return view('tiket.show', ['data' => $datas, 'histori' => $histori2]);
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
            ->where(['tiketId' => $id])
            ->get();
        if ($tiket[0]['tiketStatus'] == '1' || $tiket[0]['flagFeedback'] == '1') {
            $subService = Subservice::where(['ServiceSubStatus' => '1', 'ServiceIDf' => $tiket[0]['serviceId']])->get();

            $urle = env('API_BASE_URL') . "/getKepala.php";
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'token' => 'tiketing.silog.co.id'
            ])
                ->post($urle, [
                    'biro' => $tiket[0]['layanan'][0]['kode_biro'],
                ]);
            $dtAPi = json_decode($response->getBody()->getContents(), true);
            $responStatus = $response->getStatusCode();
            //dd($dtAPi);
            if ($responStatus == '200') {
                $dtAtasanService = $dtAPi["data"];
            } else {
                $dtAtasanService = $dtAPi["data"];
            }
            return view('tiket.edit', ['tiket' => $tiket, 'subService' => $subService, 'dtAtasanService' => $dtAtasanService]);
        } else {
            return redirect('/tiket')->with(['kode' => '90', 'pesan' => 'Data tidak bisa diubah !']);
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
        #dd($request->all());
        if (Tiket::where([
            ['tiketKeterangan', '=', $request->tiketKeterangan],
            ['nikUser', '=', session('infoUser')['NIK']],
            ['tiketStatus', '=', '1'],
            ['tiketId', '!=', $id]
        ])->doesntExist()) { // Cek data apakah sudah ada atau belum di database
            if ($request->file('tiketFile') == '') {
                $gambar = NULL;
                $serviceSAP = ['0'];
                if (in_array($request->serviceId, $serviceSAP)) {
                    Tiket::where('tiketId', $id)
                        ->update([
                            'tiketKeterangan' => $request->tiketKeterangan,
                            'subServiceId' => $request->subServiceId,
                            'tiketPrioritas' => $request->tiketPrioritas,
                            'tiketNikAtasanService' => $request->tiketNikAtasanService,
                            'flagFeedback' => $request->flagFeedback,
                            'tiketStatus' => '2'
                        ]);
                } else {
                    Tiket::where('tiketId', $id)
                        ->update([
                            'tiketKeterangan' => $request->tiketKeterangan,
                            'subServiceId' => $request->subServiceId,
                            'tiketPrioritas' => $request->tiketPrioritas,
                            'tiketNikAtasanService' => $request->tiketNikAtasanService,
                            'flagFeedback' => $request->flagFeedback,
                        ]);
                }
            } else {
                $file = $request->file('tiketFile');
                $dt = Carbon::now();
                $acak  = $file->getClientOriginalExtension();
                $fileName = $dt->format('YmdHis') . "-" . rand(11111, 99999) . '.' . $acak;
                //dd($fileName);
                $request->file('tiketFile')->move("images/fileTiket", $fileName);
                $gambar = $fileName;

                $serviceSAP = ['18', '19', '20'];
                if (in_array($request->serviceId, $serviceSAP)) {
                    Tiket::where('tiketId', $id)
                        ->update([
                            'tiketKeterangan' => $request->tiketKeterangan,
                            'subServiceId' => $request->subServiceId,
                            'tiketPrioritas' => $request->tiketPrioritas,
                            'tiketNikAtasanService' => $request->tiketNikAtasanService,
                            'flagFeedback' => $request->flagFeedback,
                            'file' => $gambar,
                            'tiketStatus' => '2'
                        ]);
                } else {
                    Tiket::where('tiketId', $id)
                        ->update([
                            'tiketKeterangan' => $request->tiketKeterangan,
                            'subServiceId' => $request->subServiceId,
                            'tiketPrioritas' => $request->tiketPrioritas,
                            'tiketNikAtasanService' => $request->tiketNikAtasanService,
                            'flagFeedback' => $request->flagFeedback,
                            'file' => $gambar,
                        ]);
                }
            }

            return redirect('/tiket')->with(['kode' => '99', 'pesan' => 'Data berhasil diubah !']);
        } else {
            return redirect('/tiket')->with(['kode' => '90', 'pesan' => 'Data sudah ada !']);
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
        return redirect('/tiket')->with(['pesan' => 'Data berhasil dihapus !']);
    }

    public function close($id)
    {

        Tiket::where('tiketId', $id)
            ->update([
                'tiketStatus' => 8,
                'sort' =>'5'
            ]);

        $tiketDetail = Tiketdetail::where('tiketId', '=', $id)->get();
        $jml = $tiketDetail->count();
        //dd($jml);
        if ($jml) { // Cek data apakah sudah ada atau belum di database
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
            $histori->tiketId       = $id;
            $histori->save();
        }

        return redirect('/tiket')->with(['kode' => '99', 'pesan' => 'Tiket berhasil Close !']);
    }
}
