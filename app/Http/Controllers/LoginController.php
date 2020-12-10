<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Auth;
use App\User;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
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
        $urle = env('API_BASE_URL')."/getLogin.php";
        $response = Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'token' => 'tiketing.silog.co.id'
                    ])
                    ->post($urle,[
                        'username' => $request->email,
                        'password' => $request->password,
                ]);
        $dtAPi = json_decode($response->getBody()->getContents(),true);  
        $responStatus = $response->getStatusCode();
        //dd($dtAPi);
        if($responStatus=='200'){
            $getUser = User::where(['username' => $request->email])->first();
            //dd($getUser);
            if ($getUser === null) {
                return redirect('/')->with('pesan', 'User tidak terdaftar aplikasi ini !');
            } else {
                if($dtAPi['ResponseCode']=='1'){
                    if (Auth::loginUsingId($getUser->id)) {
                        $dtAPi['data']['ID'] = $getUser->id;
                        $dtAPi['data']['LEVEL'] = $getUser->level;
                        //dd($dtAPi);
                        $request->session()->put('infoUser', $dtAPi['data']);
                        return redirect('/home');
                    }
                }else{
                    return redirect('/')->with('pesan', $dtAPi['ResponseMessage']);
                }
            }
        }else{
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
