<?php

namespace App\DataTables;

use App\Saran;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SaranDataTable extends DataTable
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
            ->editColumn('created_at', function (Saran $saran) {
                return $saran->created_at->toDateString();
            })
            ->editColumn('updated_at', function (Saran $saran) {
                return $saran->updated_at->toDateString();
            })
            ->editColumn('userId', function ($saran) {
                return $saran->user != null ? $saran->user->name : "";
            })
            ->editColumn('uraian', function (Saran $saran) {
                return "<span style='line-height:normal'>"
                    . wordwrap($saran->uraian, 50, "<br>\n")
                    . "</span>";
            })
            ->escapeColumns([]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\App\Saran $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Request $request, Saran $model)
    {
        // dd($model->newQuery());

        $infoUser = $request->session()->get('infoUser');
        if ($infoUser['BIROBU'] == 'B31050000') {
            return $model->with('user')
                ->newQuery();
        } else {
            return $model->with('user')->where('userId', Auth::user()->id)
                ->newQuery();
        }
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('saran-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->lengthMenu([10, 25, 50, 75, 100])
            ->orderBy(1)
            ->buttons(
                'colvis',
                'pageLength',
                'excel'
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
            Column::make('created_at')->title('Tgl Create'),
            Column::make('userId')->title('User')->width(50),
            Column::make('uraian')->title('Keterangan'),
            // Column::make('updated_at')->title('Tgl Update'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Saran_' . date('YmdHis');
    }
}
