<?php

namespace App\Admin\Controllers;

use App\Post;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PostController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Post';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Post);

        $grid->column('id', __('Id'))->sortable();
        $grid->column('title', __('Title'))->display(function($title, $column){
            if($this->hidden == 1){
                return $title;
            }
            return $column->editable();
        });

        //2 way to write
            //way 1:
            // $grid->hidden('Hidden?')->display(function ($hidden) {
            //     return $hidden ? 'YEs' : 'No';
            // });

        //way 2: 
        $grid->column('hidden', 'Hidden?')->display(function ($hidden) {
            return $hidden ? 'yes' : 'no';
        });

        //
        $grid->column('hidden')->replace([ 0 => '-']);
        

        // column not in table
        $grid->column('full_name')->display(function () {
            return str_limit($this->title.' '.$this->description, 50, "...");
        });

        $grid->column('description', __('Description'))->display(function($str){
            return str_limit($str, 30, "...");
        });

        $grid->column('description')->view('content');
        $grid->column('created_at', __('Created at'))->display(function($str){
            return "<span style='color:blue'>$str</span>";;
        });
        $grid->column('updated_at', __('Updated at'));

        //show pagination 
        $grid->paginate(1);

        //disable Create Button
        $grid->disableCreateButton();

        //disable Pagination selectors
        //$grid->disablePagination();

        //Set option per page for pagination
        $grid->perPages([10, 20, 30, 40, 55,99]);

        //Disable filter  button
        $grid->disableFilter();

        //Disable Export button
        $grid->disableExport();

        //Disable Rowselector checkbox
        $grid->disableRowSelector();

        //Disable action column
        $grid->disableActions();

        //Disable column selector
        $grid->disableColumnSelector();

        // $grid->fixColumns(1, -2);

        //display filter
        $grid->filter(function ($filter) {
            $filter->title();
            // Sets the range query for the created_at field
            $filter->between('created_at', 'Created Time')->datetime();
        });
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Post::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('description', __('Description'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Post);

        $form->text('title', __('Title'));
        $form->text('description', __('Description'));

        return $form;
    }
}
