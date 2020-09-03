<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tiket;

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
        
        return view('persetujuantiket.index', ['datas'=>$datas, 'kode'=>'', 'pesan'=>'']);
    }

    
    public function approve($id)
    {
        //
    }

    
    public function reject($id)
    {
        //
    }
}
