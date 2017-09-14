<?php

namespace App\Models;

use App\Validators\MaterialValidator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Llama\Database\Eloquent\ModelTrait;

class Material extends Model
{
    use SoftDeletes;
    use ModelTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $visible = [
        'id',
        'name',
        'description',
        
        // dates
        'created_at',
        'updated_at',
        'deleted_at',

        // relations (jsonable)
        'allowable_models', // TODO: not used now, deprecate?
        'allowable_colors', // TODO: not used now, deprecate?
        'material_categories' // TODO: not used now, deprecate?
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at'
    ];

    public static $validator = MaterialValidator::class;

    public static $rules = [
        'id' => ['numeric'],
        'name' => ['required', 'string', 'max:50'],
        'description' => ['required', 'string', 'min:3'],
    ];

    public static $isActive = [
        1 => [
            'id' => 'yes',
            'name' => 'Yes',
        ],
        0 => [
            'id' => 'no',
            'name' => 'No',
        ]
    ];

    /**
     * Get the label attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getLabelAttribute($value)
    {
        return $this->getOriginal('name');
    }

    /**
     * Get the list of material category IDs for material.
     *
     * @param  string  $value
     * @return string
     */
    /*
    public function getMaterialCategoriesIdAttribute($value)
    {
        return $this->material_categories->pluck('id')->toArray();
    }*/

    /**
     * Get the list of allowable models IDs for material.
     *
     * @param  string  $value
     * @return string
     */
    /*
    public function getAllowableModelsIdAttribute($value)
    {
        return $this->allowable_models->pluck('id')->toArray();
    }*/

    /**
     * Get the list of allowable color IDs for material.
     *
     * @param  string  $value
     * @return string
     */
    /*
    public function getAllowableColorsIdAttribute($value)
    {
        return $this->allowable_colors->pluck('id')->toArray();
    }*/

    /**
     * A material has many material categories
     * @return \App\Models\MaterialCategory
     */
    /*
    public function material_categories()
    {
        return $this->belongsToMany(MaterialCategory::class)->withTimestamps();
    }*/

    /**
     * A material has many allowable building models
     * @return \App\Models\BuildingModel
     */
    /*
    public function allowable_models()
    {
        return $this->belongsToMany(BuildingModel::class, 'material_allowable_models', 'material_id', 'building_model_id')->withTimestamps();
    }*/

    /**
     * A material has many allowable colors
     * @return \App\Models\Color
     */
    /*
    public function allowable_colors()
    {
        return $this->belongsToMany(Color::class, 'material_allowable_colors', 'material_id', 'color_id')->withTimestamps();
    }*/

    /**
     * Filtered & Paginated scope
     * @param  [type]  $query
     * @param  string  $filter
     * @param  integer $count
     * @return [type]
     */
    public function scopeFilteredPaginate($query, $filter = '', $count = 10)
    {
        if (trim($filter) !== '')
        {
            $query->where('name', 'like', '%' . $filter . '%')
                ->orWhere('description', 'like', '%' . $filter . '%');
        }

        return $query->paginate($count);
    }
}
