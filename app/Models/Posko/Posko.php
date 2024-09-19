<?php

namespace App\Models\Posko;

use App\Models\Pengguna\Pengguna;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posko extends Model
{
    use HasFactory;
    protected $table = 'posko';
    protected $guarded = [];
    public $timestamps = false;

    public function pengguna()
    {
        return $this->hasOne(Pengguna::class,'IDPengguna','Ketua');
    }
}
