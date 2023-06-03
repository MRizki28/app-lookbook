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

    public function users()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function getUsers($id_user)
    {
        $data = $this->join('users' , 'tb_lookbook.id_user', '=' , 'users.id')
        ->select('users.email' , 'users.name')
        ->where('tb_lookbook.id_user' , '=' , $id_user)
        ->first();
        return $data;
    }
}
