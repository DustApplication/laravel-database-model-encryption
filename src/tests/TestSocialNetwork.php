<?php

namespace DustApplication\Encryption\Tests;

use Illuminate\Database\Eloquent\Model;

class TestSocialNetwork extends Model
{
    public function users()
    {
        return $this->belongsToMany(TestUser::class);
    }
}