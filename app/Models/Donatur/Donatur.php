<?php

namespace App\Models\Donatur;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donatur extends Model
{
    use HasFactory;

    protected $table = 'donatur';
    protected $primaryKey = 'IDDonatur';
    protected $guarded = [];
    public $timestamps = false;
}
