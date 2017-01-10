<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Request;
use App\Menu;

class Sort extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sort_name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'menu_id','updated_at','deleted_at'
    ];

    /* 类别添加API */
    public function sortInsert(){
        $rules = [ 'sort_name' => 'required|max:45|unique:sorts' ];
        $messages = [ 'sort_name.required' => trans('类别名称必填') ,'sort_name.unique' => trans('类别名称已经存在')];
        $validator = Validator::make(Request::all(), $rules, $messages);

        if ($validator->fails())
            return $validator->errors();
        $sort = Sort::create([
            'sort_name' => Request::get('sort_name'),
        ]);

        if($sort)
            return response(['status' => '1','msg' => '添加成功']);
        return response(['status' => '0','msg' => '添加失败']);
    }

    /* 类别列表API */
    public function sortList(){
        return $this->menuSorts();
    }

    protected function menuSorts(){
        $menu_sorts= Sort::all();
        $sort[]='';
        foreach($menu_sorts as $list){
            $sort[]=[
                'sort_id'=>$list['sort_id'],
                'sort_name'=>$list['sort_name'],
                'sort_order'=>$list['sort_order'],
                'menu_count'=>Menu::whereMenuType($list['sort_id'])->count(),
            ];
        }
        return array_filter($sort);
    }

    /* 类别更新API */
    public function sortUpdate(){

    }

}
