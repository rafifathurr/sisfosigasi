<?php

namespace App\Models\Barang;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $primaryKey = 'IDBarang';
    protected $guarded = [];
    public $timestamps = false;

    public function jenisBarang()
    {
        return $this->belongsTo(JenisBarang::class, 'IDJenisBarang', 'IDJenisBarang');
    }
}
