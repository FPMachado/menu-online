<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Cliente;
use App\Models\Endereco;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'endereco_id',
        'pagamento',
        'total',
        'status',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function endereco()
    {
        return $this->belongsTo(Endereco::class);
    }
}
