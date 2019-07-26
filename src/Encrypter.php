<?php
/**
 * src/Encryption.php.
 *
 * @author      JoeselDuazo <http://joeselduazo.com/>
 */
namespace DustApplication\Encryption;
use Illuminate\Support\Facades\Crypt;
class Encrypter
{
    
    /**
     * @param $value
     * @return string
     */
    public static function encrypt($value)
    {
        return $value ? Crypt::encrypt($value) : '';
    }

    /**
     * @param $value
     * @return string
     */
    public static function decrypt($value)
    {
        return $value ? Crypt::decrypt($value) : '';
    }
}