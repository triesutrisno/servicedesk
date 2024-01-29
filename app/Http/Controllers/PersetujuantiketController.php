<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Tiket;
use App\Tiketdetail;
use DB;
use App\Histori;
use App\User;
use App\Forward;

class PersetujuantiketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // \DB::enableQueryLog();
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
                'a.flagFeedback',
                'a.tiketSeverity',
                'a.tiketMaindays'
            )
            ->leftjoin('tiket_detail as b', 'b.tiketId', '=', 'a.tiketId')
            ->leftjoin('m_layanan as c', 'c.id', '=', 'a.layananId')
            ->leftjoin('ticket_service as d', 'd.id', '=', 'a.serviceId')
            ->leftjoin('ticket_service_sub as e', 'e.id', '=', 'a.subServiceId')
            ->leftjoin('m_progres as f', 'f.progresId', '=', 'b.progresId')
            ->leftjoin('users as g', 'g.username', '=', 'a.nikUser')
            ->where(['a.tiketNikAtasanService' => session('infoUser')['NIK'], 'a.tiketApproveService' => 'W'])
            ->whereIn('a.tiketStatus', ['2', '11'])
            ->orderBy('a.tiketStatus', 'asc')
            ->orderBy('a.kode_tiket', 'asc')
            ->get();
        // dd(\DB::getQueryLog()); 
        $urle = env('API_BASE_URL') . "/getAnakBuah.php";
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'token' => 'tiketing.silog.co.id'
        ])
            ->post($urle, [
                'idPegawai' => session('infoUser')['IDE'],
                'parentId' => session('infoUser')['PROFIT_CTR_ID']
            ]);
        #dd(session('infoUser'));
        $dtAPi = json_decode($response->getBody()->getContents(), true);
        $responStatus = $response->getStatusCode();
        //dd($dtAPi["data"]);
        if ($responStatus == '200') {
            $dtAtasanService = $dtAPi["data"];
        } else {
            $dtAtasanService = $dtAPi["data"];
        }

        return view('persetujuantiket.index', ['datas' => $datas, 'dtAtasanService' => $dtAtasanService, 'kode' => '', 'pesan' => '']);
    }

    public function approve($id)
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

        if ($datas[0]->tiketNikAtasanService == session('infoUser')['NIK']) {
            $urle = env('API_BASE_URL') . "/getAnakBuah.php";
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'token' => 'tiketing.silog.co.id'
            ])
                ->post($urle, [
                    'idPegawai' => session('infoUser')['IDE'],
                    'parentId' => session('infoUser')['PROFIT_CTR_ID']
                ]);
            #dd(session('infoUser'));
            $dtAPi = json_decode($response->getBody()->getContents(), true);
            $responStatus = $response->getStatusCode();
            //dd($dtAPi["data"]);
            if ($responStatus == '200') {
                $dtAtasanService = $dtAPi["data"];
            } else {
                $dtAtasanService = $dtAPi["data"];
            }
            return view('persetujuantiket.approve', ['data' => $datas, 'dtAtasanService' => $dtAtasanService]);
        } else {
            return redirect('/persetujuantiket')->with(['kode' => '90', 'pesan' => 'Tiket nomer ' . $datas[0]->kode_tiket . ' tidak ditugaskan ke anda !']);
        }
    }

    public function saveapprove(Request $request, $id)
    {
        //dd($request->namaTeknisi);
        if ($request->namaTeknisi == '') {
            return redirect('/persetujuantiket')->with(['kode' => '90', 'pesan' => 'Harap pilih teknisi terlebih dahulu !']);
        } else {
            $tiket = Tiket::with(['layanan', 'service', 'subService'])
                ->where(['tiketId' => $id])
                ->get();
            //dd($tiket);
            if ($tiket[0]['tiketStatus'] == 2 || $tiket[0]['tiketStatus'] == 11) {
                Tiket::where('tiketId', $tiket[0]['tiketId'])
                    ->update([
                        'tiketTglApproveService' => date("Y-m-d H:i:s"),
                        'tiketApproveService' => "A",
                        'tiketStatus' => "4",
                        'tiketSeverity' => $request->tiketSeverity,
                        'sort' => "1",
                        'remark' => $request->remark == '' ? '' : $request->remark
                    ]);

                if (Tiketdetail::where(['tiketId' => $id])->doesntExist()) { // Cek data apakah sudah ada atau belum di database
                    $tiketDetail = new Tiketdetail();
                    $tiketDetail->tiketId = $tiket[0]['tiketId'];
                    $tiketDetail->nikTeknisi = $request->nikTeknisi;
                    $tiketDetail->tiketDetailStatus = "1";
                    $tiketDetail->save();
                } else {
                    Tiketdetail::where('tiketId', $id)
                        ->update([
                            'nikTeknisi' => $request->nikTeknisi,
                            'tiketDetailStatus' => "1",
                        ]);
                }

                $histori = new Histori();
                $histori->keterangan    = "Approve atasan Unit Service - Nama Teknisi " . $request->namaTeknisi;
                $histori->progresId     = '0';
                $histori->tiketId       = $id;
                $histori->save();

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
                        'recipients' => $request->emailTeknisi,
                        #'recipients' => 'triesutrisno@gmail.com',
                        'cc' => '',
                        'subjectEmail' => 'Info Pengerjaan Tiket',
                        'isiEmail' => addslashes($isiEmail),
                        'status' => 'outbox',
                        'password' => 'Veteran1974!@Gs',
                        'contentEmail' => '0',
                        'sistem' => 'tiketSilog',
                    ]);

                $users = User::where(['username' => $request->nikTeknisi])->get();
                //dd($users);
                if ($users[0]['idTelegram'] != "") {
                    $isiTelegram = "Saat ini anda diminta untuk mengerjakan tiket dengan: \n";
                    $isiTelegram .= "Nomer : " . $tiket[0]['kode_tiket'] . " \n";
                    $isiTelegram .= "Layanan : " . $tiket[0]['layanan'][0]['nama_layanan'] . " \n";
                    $isiTelegram .= "Service : " . $tiket[0]['service']['ServiceName'] . " \n";
                    $isiTelegram .= "Subservice : " . $tiket[0]['subService']['ServiceSubName'] . " \n";
                    $isiTelegram .= "Keterangan : " . $tiket[0]['tiketKeterangan'] . " \n";
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
                return redirect('/persetujuantiket')->with(['kode' => '99', 'pesan' => 'Data berhasil disetujui !']);
            } else {
                return redirect('/persetujuantiket')->with(['kode' => '90', 'pesan' => 'Data tidak bisa disetujui !']);
            }
        }
    }


    public function reject($id, Request $request)
    {
        $reject_reason = $request->get('reject_reason');

        $tiket = Tiket::with(['layanan', 'service', 'subService'])
            ->where(['tiketId' => $id])
            ->get();
        //dd($tiket);
        if ($tiket[0]['tiketStatus'] == 2 || $tiket[0]['tiketStatus'] == 11) {
            Tiket::where('tiketId', $id)
                ->update([
                    'tiketApproveService' => "R",
                    'tiketTglApproveService' => date("Y-m-d H:i:s"),
                    'tiketStatus' => "5",
                    'reject_reason' => $reject_reason,
                    'sort' => "10"
                ]);

            $histori = new Histori();
            $histori->keterangan    = "Reject atasan Unit Service : " . $reject_reason;
            $histori->progresId     = '0';
            $histori->tiketId       = $id;
            $histori->save();

            $isiEmail = "<html>";
            $isiEmail .= "<html>";
            $isiEmail .= "<body>";
            $isiEmail .= "Saat ini tiket dengan: <br />";
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
            $isiEmail .= "</table><br />";
            $isiEmail .= "Ditolak karena alasan suatu hal<br />";
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
                    'recipients' => $tiket[0]['tiketEmail'],
                    #'recipients' => 'triesutrisno@gmail.com',
                    'cc' => $tiket[0]['tiketEmailAtasan'],
                    'subjectEmail' => 'Info Pengerjaan Tiket',
                    'isiEmail' => addslashes($isiEmail),
                    'status' => 'outbox',
                    'password' => 'Veteran1974!@Gs',
                    'contentEmail' => '0',
                    'sistem' => 'tiketSilog',
                ]);

            $users = User::where(['username' => $tiket[0]['nikUser']])->get();
            //dd($users);
            if ($users[0]['idTelegram'] != "") {
                $isiTelegram = "Saat ini tiket dengan: \n";
                $isiTelegram .= "Nomer : " . $tiket[0]['kode_tiket'] . " \n";
                $isiTelegram .= "Layanan : " . $tiket[0]['layanan'][0]['nama_layanan'] . " \n";
                $isiTelegram .= "Service : " . $tiket[0]['service']['ServiceName'] . " \n";
                $isiTelegram .= "Subservice : " . $tiket[0]['subService']['ServiceSubName'] . " \n";
                $isiTelegram .= "Keterangan : " . $tiket[0]['tiketKeterangan'] . " \n";
                $isiTelegram .= "Ditolak karena suatu hal. \n";
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
            return response('Sukses Reject', 200);
            // return redirect('/persetujuantiket')->with(['kode'=>'99', 'pesan'=>'Data berhasil reject !']);
        } else {
            return response('Data tidak bisa diapprove !', 500);
            // return redirect('/persetujuantiket')->with(['kode'=>'90', 'pesan'=>'Data tidak bisa direject !']);
        }
    }

    public function feedback(Request $request)
    {
        $tiket = Tiket::with(['layanan', 'service', 'subService'])
            ->where(['tiketId' => $request->tiketId])
            ->get();
        //dd($tiket);
        if ($tiket[0]['tiketStatus'] == 2 || $tiket[0]['tiketStatus'] == 11) {
            $serviceSAP = ['18', '19', '20'];
            if (in_array($tiket[0]['serviceId'], $serviceSAP)) {
                Tiket::where('tiketId', $request->tiketId)
                    ->update([
                        'tiketTglApproveService' => NULL,
                        'tiketApproveService' => "W",
                        'tiketStatus' => "1",
                        'flagFeedback' => "1",
                        'remarkFeedback' => $request->remark,
                        'sort' => "11"
                    ]);
            } else {
                Tiket::where('tiketId', $request->tiketId)
                    ->update([
                        'tiketTglApprove' => NULL,
                        'tiketApprove' => "W",
                        'tiketTglApproveService' => NULL,
                        'tiketApproveService' => "N",
                        'tiketStatus' => "1",
                        'flagFeedback' => "1",
                        'remarkFeedback' => $request->remark,
                        'sort' => "11"
                    ]);
            }

            $histori = new Histori();
            $histori->keterangan    = $request->remark;
            $histori->progresId     = '1';
            $histori->tiketId = $request->tiketId;
            $histori->save();

            $isiEmail = "<html>";
            $isiEmail .= "<html>";
            $isiEmail .= "<body>";
            $isiEmail .= "Saat ini tiket dengan: <br />";
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
            $isiEmail .= "</table><br />";
            $isiEmail .= $request->remark . "<br />";
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
                    'recipients' => $tiket[0]['tiketEmail'],
                    #'recipients' => 'triesutrisno@gmail.com',
                    'cc' => $tiket[0]['tiketEmailAtasan'],
                    'subjectEmail' => 'Info Pengerjaan Tiket',
                    'isiEmail' => addslashes($isiEmail),
                    'status' => 'outbox',
                    'password' => 'Veteran1974!@Gs',
                    'contentEmail' => '0',
                    'sistem' => 'tiketSilog',
                ]);

            $users = User::where(['username' => $tiket[0]['nikUser']])->get();
            //dd($users);
            if ($users[0]['idTelegram'] != "") {
                $isiTelegram = "Saat ini tiket dengan: \n";
                $isiTelegram .= "Nomer : " . $tiket[0]['kode_tiket'] . " \n";
                $isiTelegram .= "Layanan : " . $tiket[0]['layanan'][0]['nama_layanan'] . " \n";
                $isiTelegram .= "Service : " . $tiket[0]['service']['ServiceName'] . " \n";
                $isiTelegram .= "Subservice : " . $tiket[0]['subService']['ServiceSubName'] . " \n";
                $isiTelegram .= "Keterangan : " . $tiket[0]['tiketKeterangan'] . " \n";
                $isiTelegram .= $request->remark . " \n";
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
            return redirect('/persetujuantiket')->with(['kode' => '99', 'pesan' => 'Feedback berhasil !']);
        } else {
            return redirect('/persetujuantiket')->with(['kode' => '90', 'pesan' => 'Feedback tidak bisa !']);
        }
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
                'a.tiketMaindays',
                'a.reject_reason'
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
        //dd($histori);

        $urle = env('API_BASE_URL') . "/getAnakBuah.php";
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'token' => 'tiketing.silog.co.id'
        ])
            ->post($urle, [
                'idPegawai' => session('infoUser')['IDE'],
                'parentId' => session('infoUser')['PROFIT_CTR_ID']
            ]);
        $dtAPi = json_decode($response->getBody()->getContents(), true);
        $responStatus = $response->getStatusCode();
        //dd($dtAPi["data"]);
        if ($responStatus == '200') {
            $dtAtasanService = $dtAPi["data"];
        } else {
            $dtAtasanService = $dtAPi["data"];
        }
        return view('persetujuantiket.show', ['data' => $datas, 'dtAtasanService' => $dtAtasanService, 'histori' => $histori2]);
    }

    public function forward($id)
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

        if ($datas[0]->tiketNikAtasanService == session('infoUser')['NIK']) {
            $urle = env('API_BASE_URL') . "/getSetara.php";
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'token' => 'tiketing.silog.co.id'
            ])
                ->post($urle, [
                    //'nikAtasan' => session('infoUser')['AL_NIK'],
                    'kodeBiro' => session('infoUser')['BIROBU'],
                ]);
            $dtAPi = json_decode($response->getBody()->getContents(), true);
            $responStatus = $response->getStatusCode();
            //dd(session('infoUser')['KODEPARENTUNIT']);
            if ($responStatus == '200') {
                $dtAtasanService = $dtAPi["data"];
            } else {
                $dtAtasanService = $dtAPi["data"];
            }
            return view('persetujuantiket.forward', ['data' => $datas, 'dtAtasanService' => $dtAtasanService]);
        } else {
            return redirect('/persetujuantiket')->with(['kode' => '90', 'pesan' => 'Tiket nomer ' . $datas[0]->kode_tiket . ' tidak ditugaskan ke anda !']);
        }
    }

    public function saveforward(Request $request, $tiketId)
    {
        $tiket = Tiket::where(['tiketId' => $tiketId])
            ->get();

        $addKet = "Tiket dari " . session('infoUser')['NAMA'] . " diforward ke " . $request->namaTeknisi;
        if (session('infoUser')['NIK'] == $tiket[0]['tiketNikAtasanService']) {
            Tiket::where('tiketId', $tiketId)
                ->update([
                    'tiketNikAtasanService' => $request->nikTeknisi,
                    'tiketEmailAtasanService' => $request->emailTeknisi,
                    'flagForward' => '1', // flag forward
                    'tiketStatus' => '11', // status Forward
                    'sort' => "2"
                ]);

            $forward = new Forward();
            $forward->tiketId       = $tiketId;
            $forward->nik           = $request->nikTeknisi;
            $forward->save();

            $histori = new Histori();
            $histori->keterangan    = $addKet . ". " . $request->keterangan;
            $histori->progresId     = "21";
            $histori->tiketId = $tiketId;
            $histori->save();

            $isiEmail = "<html>";
            $isiEmail .= "<html>";
            $isiEmail .= "<body>";
            $isiEmail .= "Saat ini anda mendapatkan foward tiket dengan: <br />";
            $isiEmail .= "<table style=\"border:0;bordercolor=#ffffff\" width=\"100%\">";
            $isiEmail .= "<tr>";
            $isiEmail .= "<td width=\"40\">Nomer</td>";
            $isiEmail .= "<td width=\"10\">:</td>";
            $isiEmail .= "<td>" . $tiket[0]['kode_tiket'] . "</td>";
            $isiEmail .= "</tr>";
            $isiEmail .= "<tr>";
            $isiEmail .= "<td>Keterangan</td>";
            $isiEmail .= "<td>:</td>";
            $isiEmail .= "<td>" . $tiket[0]['tiketKeterangan'] . "</td>";
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
                    'recipients' => $request->emailTeknisi,
                    //'recipients' => 'triesutrisno@gmail.com',
                    #'cc' => $request->tiketEmailAtasanService,
                    'subjectEmail' => 'Info Pengerjaan Tiket',
                    'isiEmail' => addslashes($isiEmail),
                    'status' => 'outbox',
                    'password' => 'Veteran1974!@Gs',
                    'contentEmail' => '0',
                    'sistem' => 'tiketSilog',
                ]);

            $users = User::where(['username' => $request->nikTeknisi])->get();
            if ($users[0]['idTelegram'] != "") {
                $isiTelegram = "Saat ini anda mendapatkan foward tiket dengan: \n";
                $isiTelegram .= "Nomer : " . $tiket[0]['kode_tiket'] . " \n";
                $isiTelegram .= "Keterangan : " . $tiket[0]['tiketKeterangan'] . " \n";
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

            return redirect('/persetujuantiket')->with(['kode' => '99', 'pesan' => 'forward tiket berhasil ditambahkan !']);
        } else {
            return redirect('/persetujuantiket')->with(['kode' => '90', 'pesan' => 'Tiket ini tidak ditugaskan ke anda !']);
        }
    }

    public function requestApproval($id)
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

        if ($datas[0]->tiketNikAtasanService == session('infoUser')['NIK']) {
            $urle = env('API_BASE_URL') . "/getSetara2.php";
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'token' => 'tiketing.silog.co.id'
            ])
                ->post($urle, [
                    //'nikAtasan' => session('infoUser')['AL_NIK'],
                    'kodeBiro' => session('infoUser')['BIROBU'],
                ]);
            $dtAPi = json_decode($response->getBody()->getContents(), true);
            $responStatus = $response->getStatusCode();
            //dd(session('infoUser')['KODEPARENTUNIT']);
            // dd(session('infoUser')['BIROBU']);
            if ($responStatus == '200') {
                $dtAtasanService = $dtAPi["data"];
            } else {
                $dtAtasanService = $dtAPi["data"];
            }
            return view('persetujuantiket.requestApproval', ['data' => $datas, 'dtAtasanService' => $dtAtasanService]);
        } else {
            return redirect('/persetujuantiket')->with(['kode' => '90', 'pesan' => 'Tiket nomer ' . $datas[0]->kode_tiket . ' tidak ditugaskan ke anda !']);
        }
    }

    public function saverequestApproval(Request $request, $tiketId)
    {
        $tiket = Tiket::where(['tiketId' => $tiketId])
            ->get();

        // $addKet = "Saat ini anda diminta untuk melakukan approve Tiket dari " . session('infoUser')['NAMA'] . " diforward ke " . $request->namaTeknisi;
        $addKet = "Saat ini anda diminta untuk melakukan approve Tiket dari " . session('infoUser')['NAMA'];
        if (session('infoUser')['NIK'] == $tiket[0]['tiketNikAtasanService']) {
            Tiket::where('tiketId', $tiketId)
                ->update([
                    'tiketTglApproveService' => date("Y-m-d H:i:s"),
                    'tiketApproveService' => "A",
                    'tiketStatus' => "4",
                    'sort' => "1",
                    'remark' => $request->remark == '' ? '' : $request->remark
                ]);
            if (Tiketdetail::where(['tiketId' => $tiketId])->doesntExist()) { // Cek data apakah sudah ada atau belum di database
                $tiketDetail = new Tiketdetail();
                $tiketDetail->tiketId = $tiketId;
                $tiketDetail->nikTeknisi = $request->nikTeknisi;
                $tiketDetail->tiketDetailStatus = "1";
                $tiketDetail->save();
            } else {
                Tiketdetail::where('tiketId', $tiketId)
                    ->update([
                        'nikTeknisi' => $request->nikTeknisi,
                        'tiketDetailStatus' => "1",
                    ]);
            }

            $forward = new Forward();
            $forward->tiketId       = $tiketId;
            $forward->nik           = $request->nikTeknisi;
            $forward->save();

            $histori = new Histori();
            $histori->keterangan    = $addKet . ". " . $request->keterangan;
            $histori->progresId     = "21";
            $histori->tiketId = $tiketId;
            $histori->save();

            $isiEmail = "<html>";
            $isiEmail .= "<html>";
            $isiEmail .= "<body>";
            $isiEmail .= "Saat ini anda diminta untuk melakukan approve tiket: <br />";
            $isiEmail .= "<table style=\"border:0;bordercolor=#ffffff\" width=\"100%\">";
            $isiEmail .= "<tr>";
            $isiEmail .= "<td width=\"40\">Nomer</td>";
            $isiEmail .= "<td width=\"10\">:</td>";
            $isiEmail .= "<td>" . $tiket[0]['kode_tiket'] . "</td>";
            $isiEmail .= "</tr>";
            $isiEmail .= "<tr>";
            $isiEmail .= "<td>Keterangan</td>";
            $isiEmail .= "<td>:</td>";
            $isiEmail .= "<td>" . $tiket[0]['tiketKeterangan'] . "</td>";
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
                    'recipients' => $request->emailTeknisi,
                    //'recipients' => 'triesutrisno@gmail.com',
                    #'cc' => $request->tiketEmailAtasanService,
                    'subjectEmail' => 'Info Pengerjaan Tiket',
                    'isiEmail' => addslashes($isiEmail),
                    'status' => 'outbox',
                    'password' => 'Veteran1974!@Gs',
                    'contentEmail' => '0',
                    'sistem' => 'tiketSilog',
                ]);

            $users = User::where(['username' => $request->nikTeknisi])->get();
            if ($users[0]['idTelegram'] != "") {
                $isiTelegram = "Saat ini anda mendapatkan foward tiket dengan: \n";
                $isiTelegram .= "Nomer : " . $tiket[0]['kode_tiket'] . " \n";
                $isiTelegram .= "Keterangan : " . $tiket[0]['tiketKeterangan'] . " \n";
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

            return redirect('/persetujuantiket')->with(['kode' => '99', 'pesan' => 'Request Approve tiket berhasil ditambahkan !']);
        } else {
            return redirect('/persetujuantiket')->with(['kode' => '90', 'pesan' => 'Tiket ini tidak ditugaskan ke anda !']);
        }
    }
}
