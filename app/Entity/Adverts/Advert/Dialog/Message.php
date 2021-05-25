<?php

namespace App\Entity\Adverts\Advert\Dialog;

use App\Entity\User\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $message
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Message extends Model
{
    protected $table = 'advert_dialog_messages';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
