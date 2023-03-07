<?php

namespace App\Http\Controllers;

use App\DataTables\SaranDataTable;
use App\Saran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return redirect('/saran')->with(['kode' => '99', 'pesan' => 'Data berhasil disimpan']);
    }
}
