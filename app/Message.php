<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public const TYPE_MESSAGE = 0;
    public const TYPE_COACH = 1;
    public const TYPE_CALL = 2;
    public const TYPE_NOTE = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dialog_id', 'author_id', 'receiver_id', 'message', 'type',
        'author_read', 'receiver_read', 'author_remove', 'receiver_remove',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'author_read', 'receiver_read', 'author_remove', 'receiver_remove',
    ];

    /**Start Relations */
    public function dialog()
    {
        return $this->belongsTo('App\Dialog');
    }

    public function author()
    {
        return $this->belongsTo('App\User', 'author_id');
    }

    public function receiver()
    {
        return $this->belongsTo('App\User', 'receiver_id');
    }
    /**End Relations */
}
