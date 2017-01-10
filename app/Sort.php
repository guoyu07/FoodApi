<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Request;

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

    ];

    /* 类别添加API */
    public function sortInsert(){
        $rules = [ 'sort_name' => 'required|max:45' ];
        $messages = [ 'sort_name.required' => trans('类别名称必填') ];
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
}
