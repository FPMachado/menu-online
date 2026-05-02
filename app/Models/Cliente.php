<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'telefone',
    ];

    public function enderecos()
    {
        return $this->hasMany(Endereco::class);
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
}
