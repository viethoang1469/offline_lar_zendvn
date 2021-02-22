<?php

namespace App\Models;

use App\Helpers\Template;
use App\Models\AdminModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use DB;
use Kalnoy\Nestedset\NodeTrait;
class CategoryModel extends AdminModel
{
    use NodeTrait;
    protected $fillable            = ['id', 'name', 'status', 'created', 'created_by', 'modified', 'modified_by'];
    protected $table               = 'category';
    protected $folderUpload        = 'category' ;
    protected $fieldSearchAccepted = ['id', 'name'];
    protected $crudNotAccepted     = ['_token', 'parent'];

    public function listItems($params = null, $options = null) {

        $result = null;

        if($options['task'] == "admin-list-items") {
            $query = self::withDepth();
            if ($params['filter']['status'] !== "all")  {
                $query->where('status', '=', $params['filter']['status'] );

            }

            if ($params['search']['value'] !== "")  {
                if($params['search']['field'] == "all") {
                    $query->where(function($query) use ($params){
                        foreach($this->fieldSearchAccepted as $column){
                            $query->orWhere($column, 'LIKE',  "%{$params['search']['value']}%" );
                        }
                    });
                } else if(in_array($params['search']['field'], $this->fieldSearchAccepted)) {
                    $query->where($params['search']['field'], 'LIKE',  "%{$params['search']['value']}%" );
                }
            }
            $result = $query->orderBy('_rgt')->get()->toFlatTree();


        }

        if($options['task'] == 'news-list-items') {
            // $query = $this->select('id', 'name')
            //             ->where('status', '=', 'active' )
            //             ->limit(8);

            // $result = $query->get()->toArray();
            $result = self::where('status', '=', 'active' )->get()->toTree()->toArray()[0]['children'];
            // echo '<pre>';
            // print_r($result);
            // echo '</pre>';
            // die();
        }

        if($options['task'] == 'news-list-items-is-home') {
            $query = $this->select('id', 'name', 'display')
                ->where('status', '=', 'active' )
                ->where('is_home', '=', 'yes' );

            $result = $query->get()->toArray();

        }

        if($options['task'] == "admin-list-items-in-selectbox") {
            $query = $this->select('id', 'name')
                        ->orderBy('name', 'asc')
                        ->where('status', '=', 'active' );

            $result = $query->pluck('name', 'id')->toArray();

        }
        return $result;
    }

    public function countItems($params = null, $options  = null) {

        $result = null;

        if($options['task'] == 'admin-count-items-group-by-status') {

            $query = $this::groupBy('status')
                        ->select( DB::raw('status , COUNT(id) as count') );

            if ($params['search']['value'] !== "")  {
                if($params['search']['field'] == "all") {
                    $query->where(function($query) use ($params){
                        foreach($this->fieldSearchAccepted as $column){
                            $query->orWhere($column, 'LIKE',  "%{$params['search']['value']}%" );
                        }
                    });
                } else if(in_array($params['search']['field'], $this->fieldSearchAccepted)) {
                    $query->where($params['search']['field'], 'LIKE',  "%{$params['search']['value']}%" );
                }
            }

            $result = $query->get()->toArray();


        }

        return $result;
    }

    public function getItem($params = null, $options = null) {
        $result = null;

        if($options['task'] == 'get-item') {
            $result = self::select('id', 'name', 'status', 'parent_id')->where('id', $params['id'])->first();
        }

        if($options['task'] == 'news-get-item') {
            $result = self::select('id', 'name', 'display')->where('id', $params['category_id'])->first();

            if($result) $result = $result->toArray();
        }

        return $result;
    }

    public function saveItem($params = null, $options = null) {
        if($options['task'] == 'change-status') {
            $status = ($params['currentStatus'] == "active") ? "inactive" : "active";
            $modifiedBy = session('userInfo')['username'];
            $modified   = date('Y-m-d H:i:s');
            self::where('id', $params['id'])->update(['status' => $status, 'modified' => $modified, 'modified_by' => $modifiedBy]);

            $result = [
                'id' => $params['id'],
                'modified' => Template::showItemHistory($modifiedBy, $modified),
                'status' => ['name' => config("zvn.template.status.$status.name"), 'class' => config("zvn.template.status.$status.class")],
                'link' => route($params['controllerName'] . '/status', ['status' => $status, 'id' => $params['id']]),
                'message' => config('zvn.notify.success.update')
            ];

            return $result;
        }

        if($options['task'] == 'change-is-home') {
            $isHome = ($params['currentIsHome'] == "yes") ? "no" : "yes";
            self::where('id', $params['id'])->update(['is_home' => $isHome ]);
        }

        if($options['task'] == 'change-display') {
            $display = $params['currentDisplay'];
            $modifiedBy = session('userInfo')['username'];
            $modified   = date('Y-m-d H:i:s');
            self::where('id', $params['id'])->update(['display' => $display, 'modified' => $modified, 'modified_by' => $modifiedBy]);

            return [
                'id' => $params['id'],
                'modified' => Template::showItemHistory($modifiedBy, $modified),
                'message' => config('zvn.notify.success.update')
            ];
        }
        if($options['task'] == 'add-item') {


            $parent = self::find($params['parent']);
            $params['created_by'] = session('userInfo')['username'];
            $params['created']    = date('Y-m-d');
            self::create($this->prepareParams($params), $parent);

        }

        if($options['task'] == 'edit-item') {
            $params['modified_by']   = session('userInfo')['username'];
            $params['modified']      = date('Y-m-d');
            $parent = self::find($params['parent']);
            self::where('id', $params['id'])->update($this->prepareParams($params));
            $node = self::find($params['id']);
            $node->parent_id = $parent->id;
            $node->save();

        }

        if ($options['task'] == 'change-ordering') {
            $ordering   = $params['ordering'];
            $modifiedBy = session('userInfo')['username'];
            $modified   = date('Y-m-d H:i:s');

            self::where('id', $params['id'])->update(['ordering' => $ordering, 'modified' => $modified, 'modified_by' => $modifiedBy]);

            $result = [
                'id' => $params['id'],
                'modified' => Template::showItemHistory($modifiedBy, $modified),
                'message' => config('zvn.notify.success.update')
            ];

            return $result;
        }
    }

    public function deleteItem($params = null, $options = null)
    {
        if($options['task'] == 'delete-item') {
            $node = self::find($params['id']);
            $node->delete();
        }
    }
    public function prepareParams($params){
        return array_diff_key($params, array_flip($this->crudNotAccepted));
    }

}

