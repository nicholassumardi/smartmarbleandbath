<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model {

    use HasFactory, SoftDeletes;

    protected $table      = 'categories';
    protected $primaryKey = 'id';
    protected $dates      = ['deleted_at'];
    protected $fillable   = [
        'name',
        'slug',
        'parent_id',
        'type',
        'status'
    ];

    public function types() 
    {
        switch($this->type) {
            case '1':
                $type = 'Product';
                break;
            case '2':
                $type = 'News';
                break;
            default:
                $type = 'Invalid';
                break;
        }

        return $type;
    }

    public function status() 
    {
        switch($this->status) {
            case '1':
                $status = '<span class="text-success font-weight-bold">Active</span>';
                break;
            case '2':
                $status = '<span class="text-danger font-weight-bold">Not Active</span>';
                break;
            default:
                $status = '<span class="text-warning font-weight-bold">Invalid</span>';
                break;
        }

        return $status;
    }

    public function parent()
    {
        $query = Category::find($this->parent_id);
        return $query;
    }

    public function sub()
    {
        $query = Category::where('parent_id', $this->id)->get();
        return $query;
    }

    public function type()
    {
        return $this->hasMany('App\Models\Type');
    }

    public function product() 
    {
        $data = Product::whereHas('type', function($query) {
                $query->where('category_id', $this->id)
				->where('show_in_store','1');
            })
            ->whereHas('productShading', function($query) {
                $query->havingRaw('SUM(qty) > ?', [0]);
            })
            ->where('status', 1)
            ->groupBy('id')
            ->limit(12)
            ->get();

        return $data;
    }

}
