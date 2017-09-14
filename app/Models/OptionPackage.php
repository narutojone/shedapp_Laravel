<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OptionPackage extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'option_packages';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description'];

    /**
     * A option package has many options
     * @return \App\Models\Option
     */
    public function options()
    {
        return $this->belongsToMany('App\Models\Option', 'option_package_options', 'option_package_id')->withPivot('unit_price')->withTimestamps();
    }

    /**
     * A option package has many allowable building models
     * @return \App\Models\BuildingModel
     */
    public function allowable_models()
    {
        return $this->belongsToMany('App\Models\BuildingModel', 'option_package_allowable_models', 'option_package_id')->withTimestamps();
    }

    /**
     * Filtered & Paginated scope
     * @param  [type]  $query
     * @param  string  $filter
     * @param  integer $count
     * @return [type]
     */
    public function scopeFilteredPaginate($query, $filter = '', $count = 10)
    {
        if ($filter !== '')
        {
            $query->whereNull('deleted_at')
                ->where(function ($query) use($filter) {
                    $query->where('name', 'like', '%' . $filter . '%')
                        ->orWhere('description', 'like', '%' . $filter . '%');
                });
        }

        return $query->paginate($count);
    }
}