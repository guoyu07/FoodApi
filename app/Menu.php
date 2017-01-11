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
        'menu_name'
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

        $menu = $this;
        $menu->menu_name = Request::get('menu_name');
        $menu->menu_description = Request::get('menu_description');
        $menu->menu_price = Request::get('menu_price');
        $menu->menu_pictrue = $pic_url;
        $menu->menu_type = Request::get('menu_type');

        if($menu->save())
            return response(['status' => '1','msg' => '添加成功']);
        return response(['status' => '0','msg' => '添加失败']);

    }
}
