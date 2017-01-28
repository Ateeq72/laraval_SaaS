<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMultiTenant extends Model
{
    protected $table = 'user_multi_tenant';

    protected $fillable = [
        'tenant_name', 'tenant_paid'
    ];
}
