<?php
namespace App\Models;  // Change this from namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'tbluser';
    protected $primaryKey = 'userid';
    public $timestamps = false;
    
    protected $fillable = [
        'username',
        'password',
        'gender'
    ];
}