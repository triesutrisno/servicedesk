<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Transaksi;


//use App\Transaksi;
use App\Tiket;
use App\Tiketdetail;
use App\Infouser;
use Illuminate\Support\Facades\Http;


use DateTime;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    //public function __construct()
    //{
    //    $this->middleware('auth');
    //}

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        #$tikets = Tiket::get();
        $tiketMasukHariIni = Tiket::where('created_at', '>=', date('Y-m-d'))->count();
        $tiketMasukBulanIni = Tiket::where('created_at', '>=', date('Y-m-01'))->count();
        $tiketMasukTahunIni = Tiket::where('created_at', '>=', date('Y-01-01'))->count();

        $tiketCloseHariIni = Tiket::where('updated_at', '>=', date('Y-m-d'))->whereIn('tiketStatus', ['7',  '8',])->count();
        $tiketCloseBulanIni = Tiket::where('updated_at', '>=', date('Y-m-01'))->whereIn('tiketStatus', ['7',  '8',])->count();
        $tiketCloseTahunIni = Tiket::where('updated_at', '>=', date('Y-01-01'))->whereIn('tiketStatus', ['7',  '8',])->count();
        $dataTiketStatus = Tiket::selectRaw("count(*) as total, MONTH(created_at) month, tiketStatus, serviceId")
            ->groupBy('month', 'tiketStatus', 'serviceId')->get();

        $dataTiket = Tiket::where('tiket.created_at', '>=', date('Y-01-01'))
            ->selectRaw("count(*) as total, MONTH(tiket.created_at) month,
            tiketStatus, serviceId, ticket_service.ServiceName,
            CASE
                WHEN tiket.tiketStatus = 1 THEN 'Baru'
                WHEN tiket.tiketStatus = 2 THEN 'Approve Atasan'
                WHEN tiket.tiketStatus = 3 THEN 'Ditolak Atasan'
                WHEN tiket.tiketStatus = 4 THEN 'Approve Ka.Unit Service'
                WHEN tiket.tiketStatus = 5 THEN 'Ditolak Ka.Unit Service'
                WHEN tiket.tiketStatus = 6 THEN 'Dikerjakan'
                WHEN tiket.tiketStatus = 7 THEN 'Selesai'
                WHEN tiket.tiketStatus = 8 THEN 'Close'
                WHEN tiket.tiketStatus = 9 THEN 'Pending'
                WHEN tiket.tiketStatus = 10 THEN 'Cancel'
                WHEN tiket.tiketStatus = 11 THEN 'Diforward'
            END as namaStatus")
            ->leftJoin('ticket_service', 'tiket.serviceId', '=', 'ticket_service.id')
            ->groupBy('month', 'tiketStatus', 'serviceId', 'ServiceName', 'namaStatus')->get();

        $tiketOpen = $dataTiketStatus->whereIn('tiketStatus', ['4', '6', '11'])->sum('total');
        $tiketCancelReject = $dataTiket->whereIn('tiketStatus', ['3', '5', '10'])->sum('total');
        $tiketPending = $dataTiketStatus->whereIn('tiketStatus', [9])->sum('total');
        $tiketBlmApprove = $dataTiketStatus->whereIn('tiketStatus', [1])->sum('total');

        // dd($dataTiket->groupBy('month'));
        $dataGraph1 = $dataTiket->groupBy('month')->mapWithKeys(function ($data, $key) {
            return [
                $key => $data->sum('total')
            ];
        });

        $dataGraphByStatus = $dataTiket->groupBy('namaStatus')->mapWithKeys(function ($data, $key) {
            return [
                $key => $data->sum('total')
            ];
        });

        $totalDataTiket = $dataTiket->sum('total');
        $dataGraphByStatusPct = $dataGraphByStatus->mapWithKeys(function ($data, $key) use ($totalDataTiket) {
            return [
                $key => round(($data / $totalDataTiket) * 100, 1)
            ];
        });


        $dataGraphByService = $dataTiket->groupBy('ServiceName')->mapWithKeys(function ($data, $key) {
            return [
                $key => $data->sum('total')
            ];
        });

        $dataGraphByServicePct = $dataGraphByService->mapWithKeys(function ($data, $key) use ($totalDataTiket) {
            return [
                $key => round(($data / $totalDataTiket) * 100, 1)
            ];
        });

        // dd($dataGraph1);

        return view('home2', [
            #'tikets'=>$tikets,
            'tiketMasukHariIni' => $tiketMasukHariIni,
            'tiketCloseHariIni' => $tiketCloseHariIni,
            'tiketMasukBulanIni' => $tiketMasukBulanIni,
            'tiketCloseBulanIni' => $tiketCloseBulanIni,
            'tiketMasukTahunIni' => $tiketMasukTahunIni,
            'tiketCloseTahunIni' => $tiketCloseTahunIni,
            'tiketOpen' => $tiketOpen,
            'tiketCancelReject' => $tiketCancelReject,
            'tiketPending' => $tiketPending,
            'tiketBlmApprove' => $tiketBlmApprove,
            'dataTiket' => $dataTiket,
            'dataGraph1' => $dataGraph1,
            'dataGraphByStatus' => $dataGraphByStatus,
            'dataGraphByStatusPct' => $dataGraphByStatusPct,
            'dataGraphByService' => $dataGraphByService,
            'dataGraphByServicePct' => $dataGraphByServicePct,
            'kode' => '',
            'pesan' => ''
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function detail(Request $request, $id)
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
                'j.name as nameAtasanService',
                'a.serviceId',
                'd.ServiceName',
                'a.subServiceId',
                'e.ServiceSubName',
                'a.tiketKeterangan',
                'a.tiketApprove',
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
                //(DB::raw('select name from users where username=a.tiketNikAtasan as namaAtasanTeknisi'))
            )
            ->leftjoin('tiket_detail as b', 'b.tiketId', '=', 'a.tiketId')
            ->leftjoin('m_layanan as c', 'c.id', '=', 'a.layananId')
            ->leftjoin('ticket_service as d', 'd.id', '=', 'a.serviceId')
            ->leftjoin('ticket_service_sub as e', 'e.id', '=', 'a.subServiceId')
            ->leftjoin('m_progres as f', 'f.progresId', '=', 'b.progresId')
            ->leftjoin('users as g', 'g.username', '=', 'a.nikUser')
            ->leftjoin('users as j', 'j.username', '=', 'a.tiketNikAtasanService')
            ->leftjoin('tb_histori as h', function ($join) {
                $join->on('h.tiketDetailId', '=', 'b.tiketDetailId')
                    ->where('h.progresId', '=', 20);
            })
            ->leftjoin('tb_histori as i', function ($join2) {
                $join2->on('i.tiketDetailId', '=', 'b.tiketDetailId')
                    ->whereIn('i.progresId', [11, 19]);
            });
        #->groupBy('a.tiketId')
        #->orderBy('a.tiketStatus', 'asc')
        #->orderBy('a.kode_tiket', 'asc')
        #->get();
        #
        if ($id == '1') {
            $datas->where('a.created_at', '>=', date("Y-m-d"));
        } elseif ($id == '2') {
            $datas->where('a.updated_at', '>=', date("Y-m-d"))->whereIn('tiketStatus', ['7', '10']);
        } elseif ($id == '3') {
            $datas->where('a.updated_at', '>=', date("Y-m-d"))->where('tiketStatus', '=', '8');
        } elseif ($id == '4') {
            $datas->where('a.created_at', '>=', date("Y-m-d"))->where('tiketStatus', '<', '7');
        } elseif ($id == '5') {
            $datas->where('a.created_at', '>=', date("Y-m-01"));
        } elseif ($id == '6') {
            $datas->where('a.updated_at', '>=', date("Y-m-01"))->whereIn('tiketStatus', ['7', '10']);
        } elseif ($id == '7') {
            $datas->where('a.updated_at', '>=', date("Y-m-01"))->where('tiketStatus', '=', '8');
        } elseif ($id == '8') {
            $datas->where('a.created_at', '>=', date("Y-m-01"))->where('tiketStatus', '<', '7');
        } elseif ($id == '9') {
            $datas->where('a.created_at', '>=', date("Y-01-01"));
        } elseif ($id == '10') {
            $datas->where('a.updated_at', '>=', date("Y-01-01"))->whereIn('tiketStatus', ['7', '10']);
        } elseif ($id == '11') {
            $datas->where('a.updated_at', '>=', date("Y-01-01"))->where('tiketStatus', '=', '8');
        } elseif ($id == '12') {
            $datas->where('a.created_at', '>=', date("Y-01-01"))->where('tiketStatus', '<', '7');
        }
        $nomer = $request->post("nomer") != NULL ? $request->post("nomer") : "";
        $status = $request->post("status") != NULL ? $request->post("status") : "";
        $nama = $request->post("nama") != NULL ? $request->post("nama") : "";
        $namaAtasanService = $request->post("namaAtasanService") != NULL ? $request->post("namaAtasanService") : "";
        $param["nomer"] = $nomer;
        $param["status"] = $status;
        $param["nama"] = $nama;
        $param["namaAtasanService"] = $namaAtasanService;

        $result = $datas->when($nomer, function ($query, $nomer) {
            return $query->where('kode_tiket', $nomer);
        })
            ->when($status, function ($query, $status) {
                return $query->where('tiketStatus', $status);
            })
            ->when($nama, function ($query, $nama) {
                return $query->where('g.name', 'LIKE', '%' . $nama . '%');
            })
            ->when($namaAtasanService, function ($query, $namaAtasanService) {
                return $query->where('j.name', 'LIKE', '%' . $namaAtasanService . '%');
            })
            ->groupBy('a.tiketId')
            ->orderBy('a.tiketStatus', 'asc')
            ->orderBy('a.kode_tiket', 'asc')
            ->paginate(50);
        #->get();

        return view('detail', ['datas' => $result, 'id' => $id, 'param' => $param]);
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
        //dd($datas);

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
        //dd($histori2);
        return view('show', ['data' => $datas, 'histori' => $histori2]);
    }

    public function approve($kode)
    {
        $cekKode = $datas = DB::table('tb_approve')
            ->select('*')
            ->where('kunci', '=', $kode)
            ->get();
        $datetime1 = new DateTime($cekKode[0]->created_at);
        $datetime2 = new DateTime(date("Y-m-d H:i:s"));
        $difference = $datetime1->diff($datetime2);
        if ($difference->s <= 53) {
            return 'Mohon maaf, untuk sementera approve belum bisa dilakukan';
        }
        $jml = count($cekKode);
        if ($jml > 0) { // Jika Kode ada
            $date = date("Y-m-d H:i:s");
            if ($cekKode[0]->aktif_sampai >= $date) {
                $tiket = Tiket::with(['layanan', 'service', 'subService', 'userBy'])
                    ->where(['tiketId' => $cekKode[0]->tiketId])
                    ->get();
                #dd($tiket[0]['userBy']['name']);
                if ($tiket[0]['tiketStatus'] == 1) {
                    Tiket::where('tiketId', $cekKode[0]->tiketId)
                        ->update([
                            'tiketApprove' => "A",
                            'tiketTglApprove' => date("Y-m-d H:i:s"),
                            'tiketApproveService' => "W",
                            'tiketStatus' => "2",
                        ]);

                    $updFlag = DB::update('update tb_approve set flag = 2, updated_at = ? where appId = ?', [date("Y-m-d H:i:s"), $cekKode[0]->appId]);

                    $isiEmail = "<html>";
                    $isiEmail .= "<html>";
                    $isiEmail .= "<body>";
                    $isiEmail .= "Saat ini ada mendapatkan permintaan tiket dengan: <br />";
                    $isiEmail .= "<table style=\"border:0;bordercolor=#ffffff\" width=\"100%\">";
                    $isiEmail .= "<tr>";
                    $isiEmail .= "<td width=\"40\">Nomer</td>";
                    $isiEmail .= "<td width=\"10\">:</td>";
                    $isiEmail .= "<td>" . $tiket[0]['kode_tiket'] . "</td>";
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

                    if ($tiket[0]['tiketEmailAtasanService'] != "") {
                        $urle = env('API_BASE_URL') . "/sendEmail.php";
                        $response = Http::withHeaders([
                            'Content-Type' => 'application/json',
                            'token' => 'tiketing.silog.co.id'
                        ])
                            ->post($urle, [
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
                    $users = Infouser::where(['username' => $tiket[0]['tiketNikAtasanService']])->get();
                    if ($users[0]['idTelegram'] != "") {
                        $isiTelegram = "Saat ini ada mendapatkan permintaan tiket dengan: \n";
                        $isiTelegram .= "Nomer : " . $tiket[0]['kode_tiket'] . " \n";
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
                    return "<center><b>Tiket nomer " . $tiket[0]['kode_tiket'] . " berhasil diapprove !<br />PT Semen Indonesia Logistik</b></center>";
                } else {
                    return "<center><b>Nomer Tiket " . $tiket[0]['kode_tiket'] . " tidak bisa diapprove !<br />PT Semen Indonesia Logistik</b></center>";
                }
            } else {
                #echo $cekKode[0]->aktif_sampai."<=".$date;
                return "<center><b>Maaf Kode sudah kadaluarsa !<br />PT Semen Indonesia Logistik</b></center>";
            }
        } else {
            return "<center><b>Maaf Kode tidak terdaftar !<br />PT Semen Indonesia Logistik</b></center>";
        }
    }

    public function reject($kode)
    {
        $cekKode = $datas = DB::table('tb_approve')
            ->select('*')
            ->where('kunci', '=', $kode)
            ->get();
        $datetime1 = new DateTime($cekKode[0]->created_at);
        $datetime2 = new DateTime(date("Y-m-d H:i:s"));
        $difference = $datetime1->diff($datetime2);
        if ($difference->s <= 53) {
            return 'Mohon maaf, untuk sementera Reject belum bisa dilakukan';
        }
        $jml = count($cekKode);
        if ($jml > 0) { // Jika Kode ada
            $date = date("Y-m-d H:i:s");
            if ($cekKode[0]->aktif_sampai >= $date) {
                $tiket = Tiket::with(['layanan', 'service', 'subService'])
                    ->where(['tiketId' => $cekKode[0]->tiketId])
                    ->get();
                //dd($tiket[0]['subService'][0]['ServiceSubName']);
                if ($tiket[0]['tiketStatus'] == 1) {
                    Tiket::where('tiketId', $cekKode[0]->tiketId)
                        ->update([
                            'tiketApprove' => "R",
                            'tiketTglApprove' => date("Y-m-d H:i:s"),
                            'tiketApproveService' => "N",
                            'tiketStatus' => "3",
                        ]);

                    $updFlag = DB::update('update tb_approve set flag = 3, updated_at = ? where appId = ?', [date("Y-m-d H:i:s"), $cekKode[0]->appId]);

                    return "<center><b>Tiket nomer " . $tiket[0]['kode_tiket'] . " berhasil direject !<br />PT Semen Indonesia Logistik</b></center>";
                } else {
                    return "<center><b>Nomer Tiket " . $tiket[0]['kode_tiket'] . " tidak bisa direject !<br />PT Semen Indonesia Logistik</b></center>";
                }
            } else {
                #echo $cekKode[0]->aktif_sampai."<=".$date;
                return "<center><b>Maaf Kode sudah kadaluarsa !<br />PT Semen Indonesia Logistik</b></center>";
            }
        } else {
            return "<center><b>Maaf Kode tidak terdaftar !<br />PT Semen Indonesia Logistik</b></center>";
        }
    }

    public function approve2($kode)
    {
        $cekKode = $datas = DB::table('tb_approve')
            ->select('*')
            ->where('kunci', '=', $kode)
            ->get();
        $datetime1 = new DateTime($cekKode[0]->created_at);
        $datetime2 = new DateTime(date("Y-m-d H:i:s"));
        $difference = $datetime1->diff($datetime2);
        if ($difference->s <= 53) {
            return 'Mohon maaf, untuk sementera Approve belum bisa dilakukan';
        }
        $jml = count($cekKode);
        if ($jml > 0) { // Jika Kode ada
            $date = date("Y-m-d H:i:s");
            if ($cekKode[0]->aktif_sampai >= $date) {
                $tiket = Tiket::with(['layanan', 'service', 'subService', 'userBy'])
                    ->where(['tiketId' => $cekKode[0]->tiketId])
                    ->get();
                //dd($tiket[0]['subService'][0]['ServiceSubName']);
                if ($tiket[0]['tiketStatus'] == 2) {
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

                    $updFlag = DB::update('update tb_approve set flag = 2, updated_at = ? where appId = ?', [date("Y-m-d H:i:s"), $cekKode[0]->appId]);

                    $isiEmail = "<html>";
                    $isiEmail .= "<html>";
                    $isiEmail .= "<body>";
                    $isiEmail .= "Saat ini anda diminta untuk mengerjakan tiket dengan: <br />";
                    $isiEmail .= "<table style=\"border:0;bordercolor=#ffffff\" width=\"100%\">";
                    $isiEmail .= "<tr>";
                    $isiEmail .= "<td width=\"40\">Nomer</td>";
                    $isiEmail .= "<td width=\"10\">:</td>";
                    $isiEmail .= "<td>" . $tiket[0]['kode_tiket'] . "</td>";
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

                    $urle = env('API_BASE_URL') . "/sendEmail.php";
                    $response = Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'token' => 'tiketing.silog.co.id'
                    ])
                        ->post($urle, [
                            'tanggal' => date("Y-m-d H:i:s"),
                            'recipients' => $cekKode[0]->emailTeknisi,
                            #'recipients' => 'triesutrisno@gmail.com',
                            'cc' => '',
                            'subjectEmail' => 'Info Pengerjaan Tiket',
                            'isiEmail' => addslashes($isiEmail),
                            'status' => 'outbox',
                            'password' => 'sistem2017',
                            'contentEmail' => '0',
                            'sistem' => 'tiketSilog',
                        ]);

                    $users = Infouser::where(['username' => $cekKode[0]->nikTeknisi])->get();
                    //dd($users);
                    if ($users[0]['idTelegram'] != "") {
                        $isiTelegram = "Saat ini anda diminta untuk mengerjakan tiket dengan: \n";
                        $isiTelegram .= "Nomer : " . $tiket[0]['kode_tiket'] . " \n";
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
                    return "<center><b>Tiket nomer " . $tiket[0]['kode_tiket'] . " berhasil disetujuai !<br />PT Semen Indonesia Logistik</b></center>";
                } else {
                    return "<center><b>Nomer Tiket " . $tiket[0]['kode_tiket'] . " tidak bisa disetujuai !<br />PT Semen Indonesia Logistik</b></center>";
                }
            } else {
                #echo $cekKode[0]->aktif_sampai."<=".$date;
                return "<center><b>Maaf Kode sudah kadaluarsa !<br />PT Semen Indonesia Logistik</b></center>";
            }
        } else {
            return "<center><b>Maaf Kode tidak terdaftar !<br />PT Semen Indonesia Logistik</b></center>";
        }
    }

    public function reject2($kode)
    {
        $cekKode = $datas = DB::table('tb_approve')
            ->select('*')
            ->where('kunci', '=', $kode)
            ->get();
        $datetime1 = new DateTime($cekKode[0]->created_at);
        $datetime2 = new DateTime(date("Y-m-d H:i:s"));
        $difference = $datetime1->diff($datetime2);
        if ($difference->s <= 53) {
            return 'Mohon maaf, untuk sementera Reject belum bisa dilakukan';
        }
        $jml = count($cekKode);
        if ($jml > 0) { // Jika Kode ada
            $date = date("Y-m-d H:i:s");
            if ($cekKode[0]->aktif_sampai >= $date) {
                $tiket = Tiket::with(['layanan', 'service', 'subService'])
                    ->where(['tiketId' => $cekKode[0]->tiketId])
                    ->get();
                //dd($tiket[0]['subService'][0]['ServiceSubName']);
                if ($tiket[0]['tiketStatus'] == 2) {
                    Tiket::where('tiketId', $cekKode[0]->tiketId)
                        ->update([
                            'tiketApproveService' => "R",
                            'tiketTglApproveService' => date("Y-m-d H:i:s"),
                            'tiketStatus' => "5",
                        ]);

                    $updFlag = DB::update('update tb_approve set flag = 3, updated_at = ? where appId = ?', [date("Y-m-d H:i:s"), $cekKode[0]->appId]);

                    return "<center><b>Tiket nomer " . $tiket[0]['kode_tiket'] . " berhasil direject !<br />PT Semen Indonesia Logistik</b></center>";
                } else {
                    return "<center><b>Nomer Tiket " . $tiket[0]['kode_tiket'] . " tidak bisa direject !<br />PT Semen Indonesia Logistik</b></center>";
                }
            } else {
                #echo $cekKode[0]->aktif_sampai."<=".$date;
                return "<center><b>Maaf Kode sudah kadaluarsa !<br />PT Semen Indonesia Logistik</b></center>";
            }
        } else {
            return "<center><b>Maaf Kode tidak terdaftar !<br />PT Semen Indonesia Logistik</b></center>";
        }
    }

    public function approve3($kode)
    {
        $cekKode = $datas = DB::table('tb_approve')
            ->select('*')
            ->where('kunci', '=', $kode)
            ->get();
        $datetime1 = new DateTime($cekKode[0]->created_at);
        $datetime2 = new DateTime(date("Y-m-d H:i:s"));
        $difference = $datetime1->diff($datetime2);
        if ($difference->s <= 5) {
            return 'Mohon maaf, untuk sementera approve belum bisa dilakukan';
        }
        $jml = count($cekKode);
        if ($jml > 0) { // Jika Kode ada
            $date = date("Y-m-d H:i:s");
            if ($cekKode[0]->aktif_sampai >= $date) {
                $tiket = Tiket::with(['layanan', 'service', 'subService', 'userBy'])
                    ->where(['tiketId' => $cekKode[0]->tiketId])
                    ->get();
                #dd($tiket[0]['userBy']['name']);
                if ($tiket[0]['tiketStatus'] == 1) {
                    Tiket::where('tiketId', $cekKode[0]->tiketId)
                        ->update([
                            'tiketApprove' => "A",
                            'tiketTglApprove' => date("Y-m-d H:i:s"),
                            'tiketApproveService' => "W",
                            'tiketStatus' => "2",
                        ]);

                    $updFlag = DB::update('update tb_approve set flag = 2, updated_at = ? where appId = ?', [date("Y-m-d H:i:s"), $cekKode[0]->appId]);

                    $isiEmail = "<html>";
                    $isiEmail .= "<html>";
                    $isiEmail .= "<body>";
                    $isiEmail .= "Saat ini ada mendapatkan permintaan tiket dengan: <br />";
                    $isiEmail .= "<table style=\"border:0;bordercolor=#ffffff\" width=\"100%\">";
                    $isiEmail .= "<tr>";
                    $isiEmail .= "<td width=\"40\">Nomer</td>";
                    $isiEmail .= "<td width=\"10\">:</td>";
                    $isiEmail .= "<td>" . $tiket[0]['kode_tiket'] . "</td>";
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

                    if ($tiket[0]['tiketEmailAtasanService'] != "") {
                        $urle = env('API_BASE_URL') . "/sendEmail.php";
                        $response = Http::withHeaders([
                            'Content-Type' => 'application/json',
                            'token' => 'tiketing.silog.co.id'
                        ])
                            ->post($urle, [
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
                    $users = Infouser::where(['username' => $tiket[0]['tiketNikAtasanService']])->get();
                    if ($users[0]['idTelegram'] != "") {
                        $isiTelegram = "Saat ini ada mendapatkan permintaan tiket dengan: \n";
                        $isiTelegram .= "Nomer : " . $tiket[0]['kode_tiket'] . " \n";
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
                    return "<center><b>Tiket nomer " . $tiket[0]['kode_tiket'] . " berhasil diapprove !<br />PT Semen Indonesia Logistik</b></center>";
                } else {
                    return "<center><b>Nomer Tiket " . $tiket[0]['kode_tiket'] . " tidak bisa diapprove !<br />PT Semen Indonesia Logistik</b></center>";
                }
            } else {
                #echo $cekKode[0]->aktif_sampai."<=".$date;
                return "<center><b>Maaf Kode sudah kadaluarsa !<br />PT Semen Indonesia Logistik</b></center>";
            }
        } else {
            return "<center><b>Maaf Kode tidak terdaftar !<br />PT Semen Indonesia Logistik</b></center>";
        }
    }

    public function reject3($kode)
    {
        $cekKode = $datas = DB::table('tb_approve')
            ->select('*')
            ->where('kunci', '=', $kode)
            ->get();
        $datetime1 = new DateTime($cekKode[0]->created_at);
        $datetime2 = new DateTime(date("Y-m-d H:i:s"));
        $difference = $datetime1->diff($datetime2);
        if ($difference->s <= 5) {
            return 'Mohon maaf, untuk sementera Reject belum bisa dilakukan';
        }
        $jml = count($cekKode);
        if ($jml > 0) { // Jika Kode ada
            $date = date("Y-m-d H:i:s");
            if ($cekKode[0]->aktif_sampai >= $date) {
                $tiket = Tiket::with(['layanan', 'service', 'subService'])
                    ->where(['tiketId' => $cekKode[0]->tiketId])
                    ->get();
                //dd($tiket[0]['subService'][0]['ServiceSubName']);
                if ($tiket[0]['tiketStatus'] == 1) {
                    Tiket::where('tiketId', $cekKode[0]->tiketId)
                        ->update([
                            'tiketApprove' => "R",
                            'tiketTglApprove' => date("Y-m-d H:i:s"),
                            'tiketApproveService' => "N",
                            'tiketStatus' => "3",
                        ]);

                    $updFlag = DB::update('update tb_approve set flag = 3, updated_at = ? where appId = ?', [date("Y-m-d H:i:s"), $cekKode[0]->appId]);

                    return "<center><b>Tiket nomer " . $tiket[0]['kode_tiket'] . " berhasil direject !<br />PT Semen Indonesia Logistik</b></center>";
                } else {
                    return "<center><b>Nomer Tiket " . $tiket[0]['kode_tiket'] . " tidak bisa direject !<br />PT Semen Indonesia Logistik</b></center>";
                }
            } else {
                #echo $cekKode[0]->aktif_sampai."<=".$date;
                return "<center><b>Maaf Kode sudah kadaluarsa !<br />PT Semen Indonesia Logistik</b></center>";
            }
        } else {
            return "<center><b>Maaf Kode tidak terdaftar !<br />PT Semen Indonesia Logistik</b></center>";
        }
    }
}
