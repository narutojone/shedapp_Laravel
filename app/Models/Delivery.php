<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Llama\Database\Eloquent\ModelTrait;

class Delivery extends Model
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
        'status_id',
        'user_id',
        'building_id',
        'sale_id',
        'location_start_id',
        'location_end_id',

        'length',
        'price',
        'cost',
        'invoice',
        'notes',

        'ready_date',
        'scheduled_date',
        'confirmed_date',
        'date_start',
        'date_end',

        'created_at',
        'updated_at',
        'deleted_at',

        // custom attributes
        'status',

        //relations
        'user',
        'building',
        'sale',
        'start_location',
        'end_location',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status_id',
        'user_id',
        'building_id',
        'sale_id',
        'location_start_id',
        'location_end_id',
        
        'length',
        'price',
        'cost',
        'invoice',
        'notes',
        
        'ready_date',
        'scheduled_date',
        'confirmed_date',
        'date_start',
        'date_end',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public static $statuses = [
        'pending' => [
            'id' => 'pending',
            'name' => 'Pending',
        ],
        'scheduled' => [
            'id' => 'scheduled',
            'name' => 'Scheduled',
        ],
        'completed' => [
            'id' => 'completed',
            'name' => 'Completed',
        ]
    ];

    protected $appends = array('status');

    public static $rules = [
        'id' => ['numeric'],
        'status_id' => ['string', 'in:pending,scheduled,completed'],
        'user_id' => ['numeric'],
        'sale_id' => ['numeric'],
        'building_id' => ['numeric'],

        'location_start_id' => ['nullable', 'numeric'],
        'location_end_id' => ['nullable', 'numeric'],

        'ready_date' => ['nullable', 'date_format:Y-m-d'],
        'scheduled_date' => ['nullable', 'date_format:Y-m-d'],
        'confirmed_date' => ['nullable', 'date_format:Y-m-d'],
        'date_start' => ['nullable', 'date_format:Y-m-d'],
        'end_start' => ['nullable', 'date_format:Y-m-d'],

        'length' => ['nullable', 'numeric'],
        'price' => ['nullable', 'numeric'],
        'cost' => ['nullable', 'numeric'],
        'invoice' => ['nullable', 'string'],
        'notes' => ['nullable', 'string'],
    ];

    /**
     * Get the catogory attrs.
     *
     * @param  string  $value
     * @return string
     */
    public function getStatusAttribute($value)
    {
        if ($this->status_id && array_key_exists($this->status_id, self::$statuses)) {
            return self::$statuses[$this->status_id];
        }
        return null;
    }
    
    /**
     * A delivery belongs to user
     * @return \App\Models\User
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    /**
     * A delivery belongs to a building
     * @return \App\Models\Building
     */
    public function building()
    {
        return $this->belongsTo('App\Models\Building', 'building_id', 'id');
    }

    /**
     * A delivery belongs to a sale
     * @return \App\Models\Building
     */
    public function sale()
    {
        return $this->belongsTo('App\Models\Sale', 'sale_id', 'id');
    }

    /**
     * A delivery has one to a start location
     * @return \App\Models\Location
     */
    public function start_location()
    {
        return $this->hasOne('App\Models\Location', 'id', 'location_start_id');
    }

    /**
     * A delivery has one to a end location
     * @return \App\Models\Location
     */
    public function end_location()
    {
        return $this->hasOne('App\Models\Location', 'id', 'location_end_id');
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
            $query->where('status', 'like', '%' . $filter . '%')
                  ->orWhere('user_id', 'like', '%' . $filter . '%');
        }

        return $query->paginate($count);
    }
}
