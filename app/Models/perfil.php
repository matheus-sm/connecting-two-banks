<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\administradora;

class perfil extends Model
{
    use HasFactory;

    protected $table = 'perfil';

    // No modelo UserProfile.php
    public function databaseConnection()
    {
        return $this->belongsTo(administradora::class, 'idAdministradora');
    }
}
