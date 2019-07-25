<?php
/**
 * src/Traits/EncryptedAttribute.php.
 *
 * @author      JoeselDuazo <http://joeselduazo.com/>
 * @version     v0.3.18
 */

namespace Dust\Encryption\Traits;


use Dust\Encryption\Builders\EncryptionBuilder;
use Illuminate\Support\Facades\Crypt;
trait EncryptableAttribute {

    public function getAttribute($key)
    {
      $value = parent::getAttribute($key);
      if (in_array($key, $this->encryptable) && (!is_null($value) && $value != ''))
      {
        try {
          $value = Crypt::decrypt($value);
        } catch (\Exception $th) {}
      }
      return $value;
    }

    public function setAttribute($key, $value)
    {
      if (in_array($key, $this->encryptable))
      {
        try {
          $value = Crypt::encrypt($value);
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
                $attributes[$key] = Crypt::decrypt($value);
              } catch (\Exception $th) {}
            }
          }
        }
        return $attributes;
    }
    
    // Extend EncryptionBuilder
    public function newEloquentBuilder($query)
    {
        return new EncryptionBuilder($query);
    }
}
