<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'produto';

    // Adicione os campos que você deseja permitir a atribuição em massa
    protected $fillable = [
        'descricao',
        'codigo_barras',
        'sap',
        'caixaria',
        'cod_fornecedor',
        'cnpj_fornecedor',
    ];
}
