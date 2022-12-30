<?php

namespace App\DataTables;

use App\Tiket;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\SearchPane;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TiketDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        // dd(request()->all());
        return datatables()
            ->eloquent($query)
            ->editColumn('created_at', function (Tiket $tiket) {
                return $tiket->created_at->toDateString();
            })
            ->editColumn('updated_at', function (Tiket $tiket) {
                return $tiket->updated_at->toDateString();
            })
            ->editColumn('tiketKeterangan', function (Tiket $tiket) {
                return "<span style='line-height:normal'>"
                    . wordwrap($tiket->tiketKeterangan, 50, "<br>\n")
                    . "</span>";
            })
            ->editColumn('tiketStatus', 'tiket.column_status')
            ->editColumn('userBy', function ($tiket) {
                return $tiket->userBy->name;
            })
            ->editColumn('serviceId', function ($tiket) {
                return $tiket->service->ServiceName;
            })
            ->editColumn('subServiceId', function ($tiket) {
                return $tiket->subService->ServiceSubName;
            })
            ->editColumn('teknisi', function ($tiket) {
                return $tiket->tiketDetail != null ?
                    $tiket->tiketDetail->teknisi->name : "";
            })
            ->escapeColumns([])
            ->addColumn('action', 'tiket.column_action', 1)
            ->filter(function ($query) {
                if (request()->has('nomer')) {
                    $query->where('kode_tiket', 'like', "%" . request('nomer') . "%");
                }

                if (request()->has('nama')) {
                    $nama = request('nama');
                    $query->whereHas('userBy', function ($query) use ($nama) {
                        $query->where('name', 'like', '%' . $nama . '%');
                    });
                }

                if (request()->filled('tgl_create')) {
                    $param = explode(" - ", request('tgl_create'));
                    $start = Carbon::parse($param[0]);
                    $end = Carbon::parse($param[1])->addDay();
                    // dd($start);
                    $query->whereBetween('created_at', [$start, $end]);
                }
                if (request()->filled('tgl_update')) {
                    $param = explode(" - ", request('tgl_update'));
                    $start = Carbon::parse($param[0]);
                    $end = Carbon::parse($param[1])->addDay();
                    // dd($start);
                    $query->whereBetween('updated_at', [$start, $end]);
                }
                if (request()->filled('status')) {
                    $query->whereIn('tiketStatus', request('status'));
                }
                if (request()->filled('jenis')) {
                    $query->whereIn('serviceId', request('jenis'));
                }
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\App\Tiket $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Tiket $model)
    {
        // dd($model->newQuery());
        return $model->with('userBy', 'service', 'tiketDetail')
            ->orderBy('created_at', 'desc')
            ->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('tiket-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->lengthMenu([10, 25, 50, 75, 100])
            ->orderBy(1)
            ->buttons(
                'colvis',
                'pageLength',
                // Button::make('searchPanes')
            );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
            Column::make('tiketStatus')
                ->title('Status')
                ->width(60)
                ->addClass('text-left'),
            Column::make('kode_tiket'),
            Column::make('userBy')->title('User')->width(50),
            Column::make('tiketKeterangan')->title('Keterangan'),
            Column::make('created_at')->title('Tgl Create'),
            Column::make('updated_at')->title('Tgl Update'),
            Column::make('serviceId')->title('Jenis Service'),
            Column::make('subServiceId')->title('Sub Service'),
            Column::make('teknisi')->title('Teknisi'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Tiket_' . date('YmdHis');
    }
}
