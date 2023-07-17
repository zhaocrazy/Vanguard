<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class PotentialProducts extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */

    protected $table = 'potential_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'rank','thumbnail','url','price','original_title','original_description', 'chinese_title','chinese_description','image'
    ];


    /**
     * 该模型是否被自动维护时间戳
     *
     * @var bool
     */
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

}
