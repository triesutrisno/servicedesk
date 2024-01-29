<?php

namespace App\Http\Controllers;

use App\DataTables\SaranDataTable;
use App\Saran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


class SaranController extends Controller
{
    public function index(SaranDataTable $dataTable)
    {

        return $dataTable
            ->render('saran.index', []);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('saran.add', []);
    }

    public function store(Request $request)
    {
        $request->validate([]);

        $saran = Saran::create($request->all());
        $saran->userId = Auth::user()->id;
        $saran->save();

        //kirim email
        $isiEmail = "<html>";
        $isiEmail .= "<html>";
        $isiEmail .= "<body>";
        $isiEmail .= "<h3>Kritik dan Saran Baru : </h3><br />";
        $isiEmail .= "User : " . $saran->user->name . "<br />";
        $isiEmail .= "Uraian : " . $saran->uraian . "<br />";
        $isiEmail .= "<br><br>Silakan akses tiket.silog.co.id dan gunakan user dan password anda untuk login ke aplikasi tersebut. <br />";
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
                'recipients' => 'si@silog.co.id',
                #'recipients' => 'triesutrisno@gmail.com',
                'cc' => '',
                'subjectEmail' => 'Kritik Saran baru dari Tiket',
                'isiEmail' => addslashes($isiEmail),
                'status' => 'outbox',
                'password' => 'Veteran1974!@Gsk',
                'contentEmail' => '0',
                'sistem' => 'tiketSilog',
            ]);

        // $dtAPi = json_decode($response->getBody()->getContents(), true);
        // $responStatus = $response->getStatusCode();
        // dd($dtAPi);

        return redirect('/saran')->with(['kode' => '99', 'pesan' => 'Data berhasil disimpan']);
    }
}
