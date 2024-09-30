<?php

namespace App\Models\Bantuan;

use App\Models\Donatur\Donatur;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bantuan extends Model
{
    use HasFactory;
    protected $primaryKey = 'IDBantuan';
    protected $table = 'bantuan';
    protected $guarded = [];
    public $timestamps = false;

    public function donatur()
    {
        $this->belongsTo(Donatur::class, 'IDDonatur', 'IDDonatur');
    }

    public function BantuanDetail()
    {
        $this->hasMany(Bantuan_Dtl::class, 'IDBantuan', 'IDBantuan');
    }
}
