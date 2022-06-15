<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'professor';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'person_id',
        'career',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'person_id','created_at', 'updated_at',
    ];
    
    public function person(){
        return $this->belongsTo( Person::class )->where('status', '=', true);
    }
}
