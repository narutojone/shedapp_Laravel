<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Llama\Database\Eloquent\ModelTrait;

class Dealer extends Model
{
    use SoftDeletes;
    use ModelTrait;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $visible = [
        'id',
        'business_name',
        'phone',
        'email',
        'tax_rate',
        'commission_rate',
        'cash_sale_deposit_rate',
        'location_id',
        'is_active',
        // dates
        'created_at',
        'updated_at',
        'deleted_at',
        // relations
        'location',
        'buildings',
        // custom attributes
        'active'
    ];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'business_name',
        'phone',
        'email',
        'tax_rate',
        'commission_rate',
        'cash_sale_deposit_rate',
        'location_id',
        'is_active',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at'
    ];

    public static $rules = [
        'id' => ['numeric'],
        'business_name' => ['string', 'max:255'],
        'phone' => ['string', 'max:255'],
        'email' => ['email'],
        'tax_rate' => ['numeric'],
        'commission_rate' => ['numeric'],
        'cash_sale_deposit_rate' => ['numeric'],
        'location_id' => ['numeric'],
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
    
    protected $appends = ['active'];
    
    public function getActiveAttribute() {
        if (empty($this->is_active)) return null;

        return collect(self::$isActive[$this->is_active]);
    }
    
    /**
     * A building has one last location
     * @return \App\Models\Location
     */
    public function location()
    {
        return $this->hasOne('App\Models\Location', 'id', 'location_id');
    }

    /**
     * A building belongs to a building
     * @return \App\Models\Building
     */
    public function buildings()
    {
        // return $this->hasMany('App\Models\Building', 'last_location_id', 'location_id');
        return $this->hasManyThrough('App\Models\Building', 'App\Models\BuildingLocation', 'location_id', 'building_id', 'location_id');
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
        if (trim($filter) !== '')
        {
            $query->where('business_name', 'like', '%' . $filter . '%')
                ->orWhere('phone', 'like', '%' . $filter . '%')
                ->orWhere('email', 'like', '%' . $filter . '%')
                ->orWhere('tax_rate', 'like', '%' . $filter . '%')
                ->orWhere('cash_sale_deposit_rate', 'like', '%' . $filter . '%');
        }

        return $query->paginate($count);
    }
}
