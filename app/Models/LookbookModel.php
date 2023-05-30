<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LookbookModel extends Model
{
    use HasFactory;

    protected $table = 'tb_lookbook';
    protected $fillable  = [
        'id' , 'uuid' , 'id_user' , 'date' , 'description'
    ];
}
