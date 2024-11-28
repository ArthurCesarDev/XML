<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XmlFile extends Model
{
    use HasFactory;

    // Especifica a tabela associada ao modelo
    protected $table = 'xml_files';

    // Permite a atribuição em massa dos seguintes campos
    protected $fillable = ['file_name', 'user_id'];

    // Relação entre XmlFile e User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
