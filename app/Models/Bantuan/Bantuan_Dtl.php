<?php

namespace App\Models\Bantuan;

use App\Models\Barang\Barang;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bantuan_Dtl extends Model
{
    use HasFactory;
    protected $table = 'bantuan_dtl';
    protected $guarded = [];
    public $timestamps = false;

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'IDBarang', 'IDBarang');
    }
}
