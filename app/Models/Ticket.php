<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\TicketComments;

class Ticket extends Model
{
   
   protected $table = 'ticket';
   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','ticket_number','user_id','problem_description','additional_notes','received_date','is_delete',
        'created_at','updated_at'
    ];


    //Relationships

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function comments(){
        return $this->hasMany(TicketComments::class);
    }
}
