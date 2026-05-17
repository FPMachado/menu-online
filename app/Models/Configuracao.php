<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Configuracao extends Model
{
    use HasFactory;

    protected $table = 'configuracoes';

    protected $fillable = [
        'nome_restaurante',
        'logo',
        'chave_pix',
        'email_notificacao',
        'taxa_entrega',
        'aberto',
        'horario_abertura',
        'horario_fechamento',
        'tempo_entrega',
        'slogan',
        'descricao',
        'imagem_hero',
    ];

    protected $casts = [
        'aberto' => 'boolean',
        'taxa_entrega' => 'float',
    ];
}
