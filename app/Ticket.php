<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ticket';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'student_id',
        'professor_id',
        'menu_id',
        'consumed',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'student_id',
        'professor_id',
        'menu_id',
        'created_at', 'updated_at',
    ];

    public function menu(){
        return $this->belongsTo( Menu::class )->where('status', '=', true);
    }

    public function student(){
        return $this->belongsTo( Student::class )->where('status', '=', true);
    }

    public function professor(){
        return $this->belongsTo( Professor::class )->where('status', '=', true);
    }
}
