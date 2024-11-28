<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = ['pedido', 'fornecedor', 'user_id']; // Adicione user_id

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Desabilita timestamps
    public $timestamps = false;
}