<?php

declare(strict_types=1);

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $user_id
 * @property string $network
 * @property string $identity
 */
class Network extends Model
{
    public $timestamps = false;
    protected $table = 'user_networks';

    protected $fillable = ['network', 'identity'];
}
