<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;

    protected $table = 'solicitudes';

    protected $fillable = [
        'tipo',
        'descripcion',
        'fecha_creacion',
        'estado',
        'departamento_id',
        'creador',
        'historial',
        'folio',
    ];

    protected $casts = [
        'creador' => 'array',
        'historial' => 'array',
        'fecha_creacion' => 'datetime',
    ];

    
    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }
}
