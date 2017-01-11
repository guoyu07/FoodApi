<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Request;
use Illuminate\Support\Facades\Storage;
use Validator;

class Menu extends Model
{
    public $primaryKey = 'menu_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'menu_name','menu_description','menu_price','menu_pictrue','menu_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at','deleted_at'
    ];

    /* 菜单添加API */
    public function menuInsert(){

        $rules = [ 'menu_name' => 'required|max:45|unique:menus','menu_price' => 'required|max:45','menu_pictrue' => 'required|max:255' ];
        $messages = [ 'menu_name.required' => trans('菜单名称必填') ,'menu_name.unique' => trans('菜单名称已经存在'),'menu_price.required' => trans('价格必填'),'menu_pictrue.required' => trans('图片必填') ];
        $validator = Validator::make(Request::all(), $rules, $messages);

        if ($validator->fails())
            return $validator->errors();

        $file = Request::file('menu_pictrue');
        if($file)
            $ext = $file->getClientOriginalExtension();
            $realPath = $file->getRealPath();
            $filename = date('Y-m-d-H-i-s').'-'.uniqid().'.'.$ext;
            Storage::disk('uploads')->put($filename,file_get_contents($realPath));
            $pic_url = 'http://'.Request::getHttpHost().'/images/'.$filename;

        $menu = Menu::create([
            'menu_name' => Request::get('menu_name'),
            'menu_description' => Request::get('menu_description'),
            'menu_price' =>Request::get('menu_price'),
            'menu_pictrue' => $pic_url,
            'menu_type' => Request::get('menu_type')
        ]);

        if($menu)
            return response(['status' => '1','msg' => '添加成功']);
        return response(['status' => '0','msg' => '添加失败']);

    }

    /* 菜单列表API */
    public function menuList(){
        return $this->menuSorts();
    }

    protected function menuSorts(){
        $menu_sorts= Sort::all();
        $sort[]='';
        foreach($menu_sorts as $list){
            $menu = Menu::whereMenuType($list['sort_id'])->get();
            foreach ($menu as $menu_list){
                $sort[$list['sort_name']]=[
                    'menu_id'=>$menu_list['menu_id'],
                    'menu_name'=>$menu_list['menu_name'],
                    'menu_description'=>$menu_list['menu_description'],
                    'menu_price'=>$menu_list['menu_price'],
                    'menu_order'=>$menu_list['menu_order'],
                    'menu_pictrue'=>$menu_list['menu_pictrue'],
                    'menu_type'=>$list['sort_name']
                ];
            }
        }
        return array_filter($sort);
    }

}
