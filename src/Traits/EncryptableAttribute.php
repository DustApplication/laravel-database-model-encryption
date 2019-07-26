<?php
/**
 * src/Traits/EncryptedAttribute.php.
 *
 * @author      JoeselDuazo <http://joeselduazo.com/>
 * @version     v0.3.18
 */

namespace DustApplication\Encryption\Traits;


use DustApplication\Encryption\Builders\EncryptionEloquentBuilder;
use DustApplication\Encryption\Encrypter;
trait EncryptableAttribute {

    public function getAttribute($key)
    {
      $value = parent::getAttribute($key);
      if (in_array($key, $this->encryptable) && (!is_null($value) && $value != ''))
      {
        try {
          $value = Encrypter::decrypt($value);
        } catch (\Exception $th) {}
      }
      return $value;
    }

    public function setAttribute($key, $value)
    {
      if (in_array($key, $this->encryptable))
      {
        try {
          $value = Encrypter::encrypt($value);
        } catch (\Exception $th) {}
      }
      return parent::setAttribute($key, $value);
    }

    public function attributesToArray()
    {
        $attributes = parent::attributesToArray();
        if ($attributes) {
          foreach ($attributes as $key => $value)
          {
            if (in_array($key, $this->encryptable) && (!is_null($value)) && $value != '')
            {
              $attributes[$key] = $value;
              try {
                $attributes[$key] = Encrypter::decrypt($value);
              } catch (\Exception $th) {}
            }
          }
        }
        return $attributes;
    }
    
    // Extend EncryptionEloquentBuilder
    public function newEloquentBuilder($query)
    {
        return new EncryptionEloquentBuilder($query);
    }

    public function decryptAttribute($value)
    {
       return $value ? Encrypter::decrypt($value) : '';
    }

    public function encryptAttribute($value)
    {
        return $value ? Encrypter::encrypt($value) : '';
    }
}
