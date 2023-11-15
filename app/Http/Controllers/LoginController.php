<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Auth;
use App\User;
use Illuminate\Support\Facades\DB;
use DateTime;
use App\Tiketdetail;
use App\Tiket;
use App\Histori;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // tiket close setelah selesai dalam 3 hari
        $dateTime = new DateTime(date("Y-m-d H:i:s"));
        $dateTime2 = date("Y-m-d H:i:s");
        $datas = DB::table('tiket as a')
            ->select(
                'a.*',
                'b.*'
            )
            ->join('tiket_detail as b', 'a.tiketId', '=', 'b.tiketId')
            ->where('a.tiketStatus', '=', '7')
            ->get();
        $jml = $datas->count();
        // dd($jml);
        if ($jml > '0') {
            foreach ($datas as $dt) {
                $tglClose = new DateTime($dt->updated_at);
                $difference = $tglClose->diff($dateTime);
                //print_r($difference);exit;
                if ($difference->d >= '3') {
                    Tiket::where('tiketId', $dt->tiketId)
                        ->update([
                            'tiketStatus' => '8',
                            'updated_at' => $dateTime
                        ]);
                    Tiketdetail::where('tiketDetailId', $dt->tiketDetailId)
                        ->update([
                            'keterangan' => 'Tiket Close',
                            'updated_at' => $dateTime2,
                            'tiketDetailStatus' => '6'
                        ]);
                    DB::table('tb_histori')->insert(
                        array(
                            'tiketDetailId'     =>   $dt->tiketDetailId,
                            'keterangan'   =>   'Tiket Close',
                            'progresId' => '20',
                            'created_at' => $dateTime2,
                            'updated_at' => $dateTime2
                        )
                    );
                }
            }
        }
        // tiket close setelah tidak diapprove atasan selama 5 hari
        $date = date("Y-m-d", strtotime("-5 day"));
        $dateTime3 = date("Y-m-d H:i:s");
        $data2 = DB::table('tiket as a')
            ->select(
                'a.*'
            )
            ->where('a.tiketStatus', '=', '1')
            ->where('a.created_at', '<=', $date)
            ->where('a.flagfeedback', '<>', '1')
            ->get();
        $jml2 = $data2->count();
        // dd($jml);
        if ($jml2 > '0') {
            foreach ($data2 as $dt) {
                // $tglClose = new DateTime($dt->updated_at);
                // $difference = $tglClose->diff($dateTime);
                //print_r($difference);exit;
                // if ($difference->d >= '3') {
                    Tiket::where('tiketId', $dt->tiketId)
                        ->update([
                            'tiketStatus' => '8',
                            'updated_at' => $dateTime3
                        ]);

                    DB::table('tb_histori')->insert(
                        array(
                            'tiketId'     =>   $dt->tiketId,
                            'keterangan'   =>   'Tiket Close By System, tidak diapprove atasan user peminta lebih dari 5 hari',
                            'progresId' => '20',
                            'created_at' => $dateTime3,
                            'updated_at' => $dateTime3
                        )
                    );
                // }
            }
        }
        if (Auth::user()) {
            return view('layouts.index');
        }
        return view('login.index');
    }

    /**
     * the proses login inthe web aplication.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $urle = env('API_BASE_URL') . "/getLogin.php";
        // $urle = "http://172.20.145.36/tiketsilog/getLogin.php";
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'token' => 'tiketing.silog.co.id'
        ])
            ->post($urle, [
                'username' => $request->email,
                'password' => $request->password,
            ]);
        $dtAPi = json_decode($response->getBody()->getContents(), true);
        $responStatus = $response->getStatusCode();
        // dd($dtAPi);exit;
        if ($responStatus == '200') {
            $getUser = User::where(['username' => $request->email])->first();
            // dd($getUser);exit;
            if ($getUser === null) {
                return redirect('/')->with('pesan', 'User tidak terdaftar aplikasi ini !');
            } else {
                if ($dtAPi['ResponseCode'] == '1') {
                    if (Auth::loginUsingId($getUser->id)) {
                        $dtAPi['data']['ID'] = $getUser->id;
                        $dtAPi['data']['LEVEL'] = $getUser->level;
                        //dd($dtAPi);
                        $request->session()->put('infoUser', $dtAPi['data']);
                        return redirect('/home');
                    }
                } else {
                    return redirect('/')->with('pesan', $dtAPi['ResponseMessage']);
                }
            }
        } else {
            $model = array();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect('/');
    }
}
