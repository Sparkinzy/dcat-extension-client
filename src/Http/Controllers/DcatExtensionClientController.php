<?php

namespace Sparkinzy\DcatExtensionClient\Http\Controllers;


use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Admin;
use Sparkinzy\DcatExtensionClient\Actions\Grid\AdminExtensionInstallAction;
use Sparkinzy\DcatExtensionClient\Repositories\AdminExtensionRepository;
use Sparkinzy\DcatExtensionClient\Displayers\TextActions;

class DcatExtensionClientController extends AdminController
{
    public function index(Content $content)
    {
        return $content
            ->title('Dcat扩展商店')
            ->description('主要收集一些符合dcat扩展格式的包')
            ->body($this->grid());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new AdminExtensionRepository(), function (Grid $grid) {
            $grid->disableRowSelector();
            $grid->setActionClass(TextActions::class);
            $grid->disableCreateButton();
            $grid->column('title','包名')
                 ->display(function ($title) {
                     return <<<HTML
<a target="_blank" href="{$this->home_page}">{$title}</a>
HTML;
                 })
                 ->width(300);
            $grid->column('detail','描述')->width(400);
            $grid->column('version','当前版本');

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                if (Admin::extension()->has($actions->row->title)){
                    $title = '重新安装&nbsp;&nbsp;';
                }else{
                    $title = '安装&nbsp;&nbsp;';
                }
                $actions->prepend(AdminExtensionInstallAction::make($title));

                $actions->disableDelete();
                $actions->disableQuickEdit();
                $actions->disableView();
                $actions->disableEdit();

            });
            $grid->filter(function (Grid\Filter $filter) {
                $filter->like('title','包名');
                $filter->panel()->expand();
            });
        });
    }




}
