<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\CascadeDeleteTrait;
use Llama\Database\Eloquent\ModelTrait;

class Building extends Model
{
    use SoftDeletes;
    use CascadeDeleteTrait;
    use ModelTrait;

    protected $morphClass = 'building';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $visible = [
        'id',
        'order_type',
        'status_id',
        'plant_id',
        'order_id',
        'last_location_id',
        'building_model_id',
        'building_package_id',
        'serial_number',
        'width',
        'height',
        'length',
        'shell_price',
        'total_options',
        'total_price',
        'notes',
        // dates
        'created_at',
        'updated_at',
        'deleted_at',
        // custom attrs
        'security_deposit',
        'status',
        // relations
        'files',
        'plant',
        'building_model',
        'building_package',
        'first_status',
        'last_status',
        'last_location',
        'locations',
        'building_history',
        'building_locations',
        'building_options',
        'options',
        'photos',
        'order',
        'orders',
        'sales',
        'current_order',
        'sort_id',
        'manufacture_year'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_type',
        'plant_id',
        'status_id',
        'order_id',
        'last_location_id',
        'building_model_id',
        'building_package_id',
        'serial_number',
        'width',
        'height',
        'length',
        'shell_price',
        'total_options',
        'total_price',
        'notes',
        'sort_id',
        'manufacture_year'
        ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $appends = ['security_deposit'];

    public static $rules = [
        'id' => ['numeric'],
        'status_id' => ['numeric'],
        'plant_id' => ['numeric'],
        'last_location_id' => ['numeric'],
        'building_model_id' => ['numeric'],
        'building_package_id' => ['numeric'],
        'width' => ['numeric'],
        'height' => ['numeric'],
        'length' => ['numeric'],
        'shell_price' => ['numeric'],
        'total_options' => ['numeric'],
        'total_price' => ['numeric'],
        'serial_number' => ['string', 'max:255'],
        'notes' => ['string', 'max:255', 'nullable'],
    ];

    /**
     * Get the structure building options attrs.
     *
     * @param  string  $value
     * @return string
     */
    public function getStructureBuildingOptionsAttribute($value)
    {
        if ($this->status_id) {
            
        }
        
        return null;
    }

    /**
     * Get the full name attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getSecurityDepositAttribute($value)
    {
        $securityDeposit = 0;
        $width = $this->width;

        if ($width <= 8) $securityDeposit = 150;
        if ($width > 8 && $width <= 10) $securityDeposit = 200;
        if ($width > 10 && $width <= 12) $securityDeposit = 250;
        if ($width > 12 && $width <= 14) $securityDeposit = 300;

        return $securityDeposit;
    }

    /**
     * Get short code
     *
     * @return string
     */
    public function getSerialShortCodeAttribute()
    {
        if (!$this->building_model) return null;
        if (!$this->building_model->style) return null;
        if (!$this->building_model->style->short_code) return null;

        return $this->building_model->style->short_code;
    }

    /**
     * Get short code
     *
     * @return string
     */
    public function getSerialSizesAttribute()
    {
        if ($this->width === null) return null;
        if ($this->length === null) return null;
        if ($this->height === null) return null;

        $width = str_pad($this->width, 2, '0', STR_PAD_LEFT);
        $length = str_pad($this->length, 2, '0', STR_PAD_LEFT);
        $wallHeight = str_pad($this->height, 2, '0', STR_PAD_LEFT);

        return $width . $length . $wallHeight;
    }

    /**
     * Get short code
     *
     * @return string
     */
    public function getSerialIdentAttribute()
    {
        if ($this->serial_number === null) return null;
        $serial = explode('-', $this->serial_number);
        return $serial[2] ?? null;
    }

    /**
     * A building belongs to a Qrcode
     * @return \App\Models\Qrcode
     */
    public function qrcode()
    {
        return $this->belongsTo('App\Models\Qrcode');
    }

    /**
     * Get all of the building's files.
     * @return \App\Models\File
     */
    public function files()
    {
        return $this->morphMany('App\Models\File', 'storable');
    }

    /**
     * A building belongs to a plant
     * @return \App\Models\Plant
     */
    public function plant()
    {
        return $this->belongsTo('App\Models\Plant');
    }

    /**
     * A building belongs to a building model
     * @return \App\Models\BuildingModel
     */
    public function building_model()
    {
        return $this->belongsTo('App\Models\BuildingModel')->withTrashed();
    }

    /**
     * A building has one last building history (with build type)
     * @return \App\Models\BuildingHistory
     */
    public function first_status()
    {
        return $this->hasOne('App\Models\BuildingHistory', 'building_id', 'id')
            ->whereHas('building_status', function($query) {
                $query->where('is_active', '=', 'yes');
            })
            ->orderBy('id', 'asc');
    }

    /**
     * A building has one last building history (with build type)
     * @return \App\Models\BuildingHistory
     */
    public function last_status()
    {
        /*return $this->hasOne('App\Models\BuildingHistory', 'building_id', 'id')
            ->whereHas('building_status', function($query) {
                $query->where('is_active', '=', 'yes');
            })
            ->orderBy('id', 'desc');*/
        return $this->hasOne(BuildingHistory::class, 'id', 'status_id');
    }

    /**
     * A building has one last building location
     * @return \App\Models\BuildingLocation
     */
    public function last_location()
    {
        return $this->hasOne(BuildingLocation::class, 'id', 'last_location_id');
    }

    /**
     * A building has many locations
     * @return \App\Models\BuildingLocation
     */
    public function locations()
    {
        return $this->belongsToMany(Location::class, 'building_locations', 'building_id');
    }

    /**
     * A building has many building histories
     * @return \App\Models\BuildingHistory
     */
    public function building_history()
    {
        return $this->hasMany('App\Models\BuildingHistory', 'building_id', 'id');
    }

    /**
     * A building has many building locations
     * @return \App\Models\BuildingLocation
     */
    public function building_locations()
    {
        return $this->hasMany('App\Models\BuildingLocation', 'building_id', 'id');
    }

    /**
     * A building has many building options
     * @return \App\Models\BuildingOption
     */
    public function building_options()
    {
        return $this->hasMany('App\Models\BuildingOption');
    }

    /**
     * An building has one building package
     * @return \App\Models\BuildingPackage
     */
    public function building_package()
    {
        return $this->hasOne(BuildingPackage::class, 'id', 'building_package_id');
    }
    
    /**
     * A building has many options, through pivot table
     * @return \App\Models\Option
     */
    public function options()
    {
        return $this->belongsToMany('App\Models\Option', 'building_options', 'building_id')->withTimestamps()->withPivot('quantity', 'unit_price','total_price');
        /*
        return $this->hasManyThrough(
            'App\Models\Option', 'App\Models\BuildingOption',
            'building_id', 'id', 'id'
        );*/
    }

    /**
     * A building has many photos
     * @return \App\Models\Photo
     */
    public function photos()
    {
        return $this->morphMany('App\Models\Photo', 'imageable');
    }
    
    /**
     * A building has many orders
     * @return \App\Models\Order
     */
    public function orders()
    {
        return $this->hasMany('App\Models\Order', 'building_id', 'id');
    }
    
    /**
     * A building has many sales
     * @return \App\Models\Sale
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * A building has one current order
     * @return \App\Models\Order
     */
    public function order()
    {
        return $this->hasOne('App\Models\Order', 'id', 'order_id');
    }

    /**
     * A building has one current order
     * @return \App\Models\Order
     */
    public function current_order()
    {
        return $this->belongsTo('App\Models\Order', 'id', 'building_id')->latest();
    }
    
    /**
     * A building belongs to dealer
     * @return \App\Models\Dealer
     */
    /* public function dealer()
    {
        // TODO: it is wrong now. last_location_id related to last HISTORY location row
        return $this->belongsTo('App\Models\Dealer', 'last_location_id', 'location_id');
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
            $query->where('serial_number', 'like', '%' . $filter . '%')
                  ->orWhere('body_color', 'like', '%' . $filter . '%')
                  ->orWhere('trim_color', 'like', '%' . $filter . '%')
                  ->orWhere('roof_color', 'like', '%' . $filter . '%')
                  ->orWhere('notes', 'like', '%' . $filter . '%');
        }

        return $query->paginate($count);
    }
}
