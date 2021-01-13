<?php

namespace Sheenazien8\Hascrudactions\Abstracts;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Responsable;
use Yajra\DataTables\Services\DataTable;

abstract class LaTable extends DataTable implements Responsable
{
    protected $query;

    /**
     * Raw Columns For
     * @var rawColumns
     */
    protected $rawColumns = [];

    /**
     * Raw Columns For
     * @var defaultRawColumns
     */
    protected $defaultRawColumns = [
        'created_at', 'action'
    ];

    /**
     * @param $query
     */
    public function __construct($query)
    {
        $this->query = $query;
    }

    public function create()
    {
        return $this->newTable();
    }

    /**
     * render the table
     *
     * @return DataTable
     */
    public function newTable()
    {
        return datatables($this->query)
            ->addColumn('checkbox', function ($model) {
                return view('hascrudactions::partials.table.checkbox', compact('model'));
            })
            ->addColumn('created_at', function ($model) {
                $date = (new Carbon($model->created_at))->diffForHumans();
                return view('hascrudactions::partials.table.date')->with('date', $date);
            })
            ->setRowId(function ($model) {
                return $model->id;
            })
            ->addColumn('action', function ($model) {
                $resources = explode('.', request()->route()->action['as'])[0];
                return view('hascrudactions::partials.table.action', [
                    'delete' => route("{$resources}.destroy", $model->id),
                    'model' => $model,
                    'resources' => $resources
                ]);
            })
            ->rawColumns(array_merge($this->defaultRawColumns, $this->rawColumns));
    }


    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        return $this->create()->toJson();
    }
}
