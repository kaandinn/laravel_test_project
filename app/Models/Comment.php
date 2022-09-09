<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['text', 'ticket_id', 'user_id'];

    public function ticket(){
        return $this->belongTo(Ticket::class);
    }

    public function user(){
        return $this->belongTo(User::class);
    }

}
