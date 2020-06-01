<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'category_id', 'description', 'price', 'amount'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['category_id', 'updated_at', 'created_at'];

    /**
     * Get the post that owns the comment.
     */
    public function category()
    {
        return $this->belongsTo('App\Category');
    }
}
