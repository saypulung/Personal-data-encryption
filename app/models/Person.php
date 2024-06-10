<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model {
    protected $fillable = [
        'nik',
        'name',
        'cecar',
        'idx_nik',
        'idx_name',
        'idx_cc',
    ];
}