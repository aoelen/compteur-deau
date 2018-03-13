<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    protected $dates = ['created_at', 'updated_at'];
	protected function serializeDate(\DateTimeInterface $date)
{
    return $date->format('d-m H:m');
}
}
