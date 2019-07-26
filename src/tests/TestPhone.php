<?php

namespace DustApplication\Encryption\Tests;

use Illuminate\Database\Eloquent\Model;
use DustApplication\Encryption\Traits\EncryptableAttribute;

class TestPhone extends Model
{
    use EncryptableAttribute;

    protected $fillable = ['phone_number'];
    protected $encryptable = ['phone_number'];

    public function user()
    {
        return $this->belongsTo(TestUser::class);
    }
}