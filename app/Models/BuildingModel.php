<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Llama\Database\Eloquent\ModelTrait;

class BuildingModel extends Model
{

    use ModelTrait;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $visible = [
        'id',
        'style_id',
        'name',
        'description',
        'width',
        'wall_height',
        'length',
        'shell_price',
        'is_active',

        'created_at',
        'updated_at',
        'deleted_at',

        // relations (jsonable)

        'buildings',
        'style',
        'model_building_status',
        'option_packages',
        'allowable_options',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'style_id',
        'name',
        'description',
        'width',
        'wall_height',
        'length',
        'shell_price',
        'is_active'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at'
    ];

    //protected $appends = array('allowable_options_id'); // dont do this. Memory leak/loop

    public static $rules = [
        'id' => ['numeric'],
        'style_id' => ['numeric'],
        'name' => ['string'],
        'description' => ['string'],
        'width' => ['numeric', 'min:0'],
        'wall_height' => ['numeric', 'min:0'],
        'length' => ['numeric', 'min:0'],
        'shell_price' => ['numeric', 'min:0'],
        'is_active' => ['in:yes,no'],
    ];

    public static $isActive = [
        'yes' => [
            'id' => 'yes',
            'name' => 'Yes',
        ],
        'no' => [
            'id' => 'no',
            'name' => 'No',
        ]
    ];

    /**
     * Get the list of allowable options IDs for model.
     *
     * @param  string  $value
     * @return string
     */
    public function getAllowableOptionsIdAttribute($value)
    {
        return $this->allowable_options->pluck('id')->toArray();
    }

    /**
     * Get size short code based on sizes
     *
     * @param  string  $value
     * @return string
     */
    public function getSizeShortCodeAttribute($value)
    {
        $width = str_pad($this->width, 2, '0', STR_PAD_LEFT);
        $length = str_pad($this->length, 2, '0', STR_PAD_LEFT);
        $height = str_pad($this->wall_height, 2, '0', STR_PAD_LEFT);
        return $width.$length.$height;
    }

    /**
     * A building model belongs to one style
     * @return \App\Models\Style
     */
    public function style()
    {
        return $this->belongsTo('App\Models\Style');
    }

    /**
     * A building model has many buildings
     * @return \App\Models\Building
     */
    public function buildings()
    {
        return $this->hasMany('App\Models\Building');
    }

    /**
     * A building model has many building statuses
     * @return \App\Models\ModelBuildingStatus
     */
    public function model_building_status()
    {
        return $this->hasMany('App\Models\ModelBuildingStatus', 'model_id', 'id');
    }

    /**
     * A building model has many option_packages
     * @return \App\Models\OptionPackage
     */
    public function option_packages()
    {
        return $this->belongsToMany('App\Models\OptionPackage', 'option_package_allowable_models', 'building_model_id')->withTimestamps();
    }

    /**
     * A building model belongs to many option
     * @return \App\Models\Option
     */
    public function allowable_options()
    {
        return $this->belongsToMany(Option::class, 'option_allowable_models', 'building_model_id')->withTimestamps();
    }

    /**
     * TODO: deprecate
     * A building model belongs to many materials
     * @return \App\Models\Color
     */
    public function allowable_materials()
    {
        return $this->belongsToMany(Material::class, 'material_allowable_models', 'building_model_id')->withTimestamps();
    }

    /**
     * Scope a query to only include active building model.
     *
     * @param $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 'yes');
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
            $query->where('name', 'like', '%' . $filter . '%')
                  ->orWhere('description', 'like', '%' . $filter . '%');
        }

        return $query->paginate($count);
    }
}
