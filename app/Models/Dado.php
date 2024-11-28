<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Dado extends Model
{
    protected $fillable = ['id', 'for', 'nome', 'data'];
}