<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tenants extends Model
{

    protected $table = 'tenants';

    protected $fillable = [
        'tenant_name', 'tenant_paid'
    ];
}
