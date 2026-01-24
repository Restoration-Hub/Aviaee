<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    /** @use HasFactory<\Database\Factories\MissionFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'mission_name',
        'status',
        'starting_location',
        'destination',
        'date_created',
        'date_delivered',
    ];
}