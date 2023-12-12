<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersShift extends Model
{
    use HasFactory;
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'substitute_user_id',
        'temp_changes',
        'date_from',
        'date_to',
    ];

    protected $casts = [
        'temp_changes' => 'array'
    ];

    public function setTempChangesAttribute($value)
    {
        $this->attributes['temp_changes'] = $value ? json_encode($value) : null;
    }

    public function getTempChangesAttribute($value)
    {
        return $value ? json_decode($value) : [];
    }
}
