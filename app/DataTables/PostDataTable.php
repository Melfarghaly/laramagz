<?php

namespace App\DataTables;

use App\Helpers\Settings;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PostDataTable extends DataTable
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
                    <input type="checkbox" class="custom-control-input post_checkbox" id="checkbox'.$query->id.'" name="post_checkbox[]" value="'.$query->id.'"><label class="custom-control-label" for="checkbox'.$query->id.'"></label>
                </div>';
            })
            ->editColumn('post_title', function ($query) {
                if ($query->post_visibility === 'private') {
                    $visibility = "&mdash; <i class='fas fa-lock text-info'></i>";
                } else {
                    $visibility = "";
                }
                return "<a href='" . Settings::getRoutePost($query) . "' target='_blank'>" . $query->post_title . "</a> $visibility";
            })
            ->editColumn('termtaxonomy.term.name', function ($query) {
                if ($query->termtaxonomy()->where('taxonomy', 'category')->exists()) {
                    return $query->termtaxonomy()->where('taxonomy', 'category')->with('term')->get()->map(function ($term) {
                        return $term->term->name;
                    })->implode(', ');
                }
            })
            ->addColumn('tag', function ($query) {
                if ($query->termtaxonomy()->where('taxonomy', 'tag')->exists()) {
                    return $query->termtaxonomy()->where('taxonomy', 'tag')->with('term')->get()->map(function ($term) {
                        return $term->term->name;
                    })->implode(', ');
                }
            })
            ->addColumn('action', function ($query) {
                $action = [
                    'table' => 'post-table',
                    'model' => $query,
                ];

                if ( Auth::user()->hasRole(['superadmin']) ) {
                    $action['del_url'] = route('posts.destroy', $query->id);
                    $action['edit_url'] = route('posts.edit', $query->id);
                }

                if( Auth::id() == $query->post_author ){
                    $action['del_url'] = route('posts.destroy', $query->id);
                    $action['edit_url'] = route('posts.edit', $query->id);
                }

                if ( User::findOrFail($query->post_author)->getRoleNames()->first() == 'admin' ) {
                    if (Auth::user()->can('delete-post-admin')) {
                        $action['del_url'] = route('posts.destroy', $query->id);
                    }

                    if (Auth::user()->can('edit-post-admin')) {
                        $action['edit_url'] = route('posts.edit', $query->id);
                    }
                }

                if ( User::findOrFail($query->post_author)->getRoleNames()->first() == 'member' ) {
                    if ( Auth::user()->can('delete-post-member') ) {
                        $action['del_url'] = route('posts.destroy', $query->id);
                    }

                    if ( Auth::user()->can('edit-post-member') ) {
                        $action['edit_url'] = route('posts.edit', $query->id);
                    }
                }

                return view('layouts.partials._action', $action);
            })
            ->rawColumns(['post_title','checkbox']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param Post $model
     * @return Builder
     */
    public function query(Post $model)
    {
        $q = $model::postType('post')
            ->with('termtaxonomy.term')
            ->with('user.roles')
            ->select('posts.*');

        if(Auth::user()->hasRole(['superadmin'])) {
            return $q->latest()->newQuery();
        }else{
            if (Auth::user()->can('read-private-post')) {
                if (Auth::user()->hasRole(['admin'])) {
                    return $q->where('post_visibility','public')
                        ->orWhere(function($query){
                            $query->whereHas('user', function($p){
                                $p->whereHas('roles', function($r){
                                    $r->where('name', 'admin');
                                });
                            })->where('post_visibility', 'private')->where('post_author', Auth::id());
                        })->orWhere(function($query){
                            $query->whereHas('user', function($p){
                                $p->whereHas('roles', function($r){
                                    $r->where('name', 'member');
                                });
                            })->where('post_visibility', 'private');
                        })->latest()->newQuery();

                }else{
                    return $q->latest()->newQuery();
                }
            } else {
                if(Auth::user()->hasRole(['member'])) {
                    return $q->where(function ($query) {
                        $q = $query->get();
                        foreach ($q as $t) {
                            if (User::findOrFail($t->post_author)->getRoleNames()->first() == 'member')
                            {
                                $query->where('post_visibility', 'public')
                                    ->orWhere(function($query) {
                                        $query->where('post_visibility', 'private')
                                            ->where('post_author', Auth::id());
                                    });
                            }
                        }
                    })->latest()->newQuery();;
                }else{
                    return $q->where('post_visibility', 'public')
                        ->orWhere(function($query) {
                            $query->where('post_visibility', 'private')
                                ->where('post_author', Auth::id());
                        })
                        ->latest()->newQuery();
                }
            }
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
            ->setTableId('post-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" .
                "<'row'<'col-sm-12'tr>>" .
                "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'i><'col-sm-12 col-md-4'p>>")
            ->orderBy(1)
            ->buttons(
                Button::make('create')->className('btn btn-sm btn-info')
            )
            ->parameters([
                'drawCallback' => 'function() {
                    "use strict";

                    $(".delete").on("click", function() {
                        let table = $(this).data("table");
                        let url = $(this).data("url");
                        sweetalert2(table, url);
                    })

                    $("#bulk_delete").on("click", function() {
                        let url = $(this).data("url");
                        let table = "post-table";
                        let selectClass = "post_checkbox";
                        multiDelCheckbox(table, url, selectClass);
                    })

                    $("#selectAll").on( "click", function(e) {
                        if ($(this).is( ":checked" )) {
                            $(".post_checkbox").prop("checked",true);
                        } else {
                            $(".post_checkbox").prop("checked",false);
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
                ->footer('<button type="button" name="bulk_delete" id="bulk_delete" class="btn btn btn-xs btn-danger" data-url="'.route('posts.massdestroy').'">Delete</button>'),
            Column::make('post_title')->title('Title'),
            Column::make('user.name')->title('Author'),
            Column::make('termtaxonomy.term.name')->title('Category'),
            Column::make('tag')->title('Tag'),
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
        return 'Post_' . date('YmdHis');
    }
}
