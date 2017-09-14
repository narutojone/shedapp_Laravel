<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Llama\Database\Eloquent\ModelTrait;

class OrderContact extends Model
{
    use ModelTrait;

    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    protected $table = 'order_contacts';

    protected $visible = [
        'id',
        'order_id',
        'initial_contact',
        'order_submit',
        'created_at',
        'updated_at',
    ];
}
