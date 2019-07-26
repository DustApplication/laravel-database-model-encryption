<?php

namespace DustApplication\Encryption\Tests;

use Illuminate\Database\Eloquent\Model;
use DustApplication\Encryption\Traits\EncryptableAttribute;

class TestUser extends Model
{
    use EncryptableAttribute;

    protected $fillable = ['email', 'name', 'password'];
    protected $encryptable = ['email', 'name'];
    protected $camelcase = ['name'];

    public function phones()
    {
        return $this->hasMany(TestPhone::class);
    }

}