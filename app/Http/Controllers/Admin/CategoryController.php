<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CategoryModel as MainModel;
use App\Http\Requests\CategoryRequest as MainRequest ;
use NodeTrait;
class CategoryController extends AdminController
{
    public function __construct()
    {
        $this->pathViewController = 'admin.pages.category.';
        $this->controllerName = 'category';
        $this->model = new MainModel();
        parent::__construct();
    }
    public function save(MainRequest $request)
    {
        if ($request->method() == 'POST') {
            $params = $request->all();

            $task   = "add-item";
            $notify = "Thêm phần tử thành công!";

            if($params['id'] !== null) {
                $task   = "edit-item";
                $notify = "Cập nhật phần tử thành công!";
            }
            $this->model->saveItem($params, ['task' => $task]);
            return redirect()->route($this->controllerName)->with("zvn_notify", $notify);
        }
    }

    public function isHome(Request $request)
    {
        $params["currentIsHome"]  = $request->isHome;
        $params["id"]             = $request->id;
        $this->model->saveItem($params, ['task' => 'change-is-home']);
        return redirect()->route($this->controllerName)->with('zvn_notify', 'Cập nhật trạng thái hiển thị trang chủ thành công!');
    }

    public function display(Request $request) {
        $params["currentDisplay"]   = $request->display;
        $params["id"]               = $request->id;
        $result = $this->model->saveItem($params, ['task' => 'change-display']);
        echo json_encode($result);
    }

    public function ordering(Request $request)
    {
        $this->params['ordering'] = $request->ordering;
        $this->params['id'] = $request->id;
        $result = $this->model->saveItem($this->params, ['task' => 'change-ordering']);
        echo json_encode($result);
    }
    public function downNode(Request $request)
    {
        $id = $request->id;
        $node = MainModel::find($id);
        if($node->getNextSibling()){
            $result = $node->down();
        }
        if($result)
        {
            return redirect()->route($this->controllerName)->with('zvn_notify', 'Cập nhật vị trí thành công!');
        }

    }
    public function upNode(Request $request)
    {
        $id = $request->id;
        $node = MainModel::find($id);
        if($node->getPrevSiblings()->toArray()){
            $result = $node->up();
        }
        if($result)
        {
            return redirect()->route($this->controllerName)->with('zvn_notify', 'Cập nhật vị trí thành công!');
        }

    }
    public function form(Request $request)
    {
        $item = null;
        if($request->id !== null ) {
            $params["id"] = $request->id;
            $item = $this->model->getItem( $params, ['task' => 'get-item']);
        }
        $items = MainModel::pluck('name', 'id');
        return view($this->pathViewController .  'form', [
            'item'        => $item,
            'items'       => $items
        ]);
    }
}
