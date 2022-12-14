<?php

namespace App\DataTables;

use App\Models\Term;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CategoryDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('checkbox', function($query){
                return '<div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input category_checkbox" id="checkbox'.$query->id.'" name="category_checkbox[]" value="'.$query->id.'"><label class="custom-control-label" for="checkbox'.$query->id.'"></label>
                </div>';
            })
            ->addColumn('action', function($query){
                $name = ($query->taxonomy->parent == 0) ? '0' : Term::find($query->taxonomy->parent)->name;
                return view('layouts.partials._action',[
                    'table' => 'category-table',
                    'model' => collect($query)->merge(['parent_name' => $name]),
                    'del_url' => route('categories.destroy', $query->id),
                    'edit_url' => route('categories.update', $query->id)
                ]);
            })
            ->rawColumns(['action','checkbox']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param Term $model
     * @return Builder
     */
    public function query(Term $model)
    {
        return Term::whereHas("taxonomy", function($query){
            $query->where("taxonomy", "category");
        })->with("taxonomy")->latest()->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('category-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" .
                "<'row'<'col-sm-12'tr>>" .
                "<'row'<'col-sm-12 col-md-6'i><'col-sm-12 col-md-6'p>>")
            ->orderBy(1)
            ->parameters([
                'drawCallback' => 'function() {
                    "use strict";

                    $(".link-edit").on("click", function(e) {
                        e.preventDefault()
                        let editurl = $(this).attr("href");
                        let query = $(this).data("model");

                        $("#name").val(query.name);
                        $("form#insert").attr("href", editurl);
                        $(".card-form.card-title").html("Update Category");

                        $("#btn-reset").removeAttr("hidden");
                        $("#btn-submit").attr("id","btn-submit-update");
                        $("button[type=submit]").html("Update");

                        $("#name").removeClass("is-invalid");
                        $(".msg-error-name").html("");
                    });

                    $(".delete").on("click", function() {
                        let table = $(this).data("table");
                        let url = $(this).data("url");
                        sweetalert2(table, url);
                    })

                    $("#bulk_delete").on("click", function() {
                        let url = $(this).data("url");
                        let table = "category-table";
                        let selectClass = "category_checkbox";
                        multiDelCheckbox(table, url, selectClass);
                    })

                    $("#selectAll").on( "click", function(e) {
                        if ($(this).is( ":checked" )) {
                            $(".category_checkbox").prop("checked",true);
                        } else {
                            $(".category_checkbox").prop("checked",false);
                        }
                    })
                }'
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('checkbox')
                ->title('')
                ->footer('<div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="selectAll"><label class="custom-control-label" for="selectAll"></label></div>')
                ->addClass('text-center')
                ->orderable(false)
                ->searchable(false)
                ->width(3),
            Column::make('id')->title('ID')
                ->footer('<button type="button" name="bulk_delete" id="bulk_delete" class="btn btn btn-xs btn-danger" data-url="'.route('categories.massdestroy').'">Delete</button>'),
            Column::make('name'),
            Column::make('slug'),
            Column::computed('action')
                ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Category_' . date('YmdHis');
    }
}
