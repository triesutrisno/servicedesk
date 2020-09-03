<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        //
    }

    
    public function reject($id)
    {
        dd($id);
    }
}
