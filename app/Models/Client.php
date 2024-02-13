<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';
    protected $fillable = [
        'rz',
    ];

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}
