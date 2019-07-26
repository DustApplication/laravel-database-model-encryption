<?php
/**
 * src/Builders/EncryptionEloquentBuilder.php.
 *
 * @author      JoeselDuazo <http://joeselduazo.com/>
 */
namespace DustApplication\Encryption\Builders;
use Illuminate\Database\Eloquent\Builder;

class EncryptionEloquentBuilder extends Builder
{
    public function whereEncrypted($param1, $param2, $param3 = null)
    {
      /* 
        whereEncrypted
        Use for comparing encrypted fields to requested value with where condition

        *IMPLEMENTATION*
        User::whereEncrypt('email','app_user@gmail.com.mt')->get(); // Normal equal condition
        User::whereEncrypt('email','!=','app_user@gmail.com.mt')->get(); // Custom Operation
      */
      // if (!in_array($param1, $this->encryptable) && (!is_null($value)))
      // {
      //   return self::where($param1, $param2, $param3);
      // }
      $filter            = new \stdClass();
      $filter->field     = $param1;
      $filter->operation = isset($param3) ? $param2 : '==';
      $filter->value     = isset($param3) ? $param3 : $param2;
          
      $matchIds = self::get()->filter(function ($item) use ($filter) {
        $itemValue   = $item->{$filter->field};
        if($filter->operation == '=='){
          return $itemValue == $filter->value;
        }elseif(in_array($filter->operation,['!=','!==','<>'])){
          return $itemValue != $filter->value || $itemValue !== $filter->value || $itemValue <> $filter->value;
        }elseif(strtolower($filter->operation) == 'like'){
          $firstChar = substr($filter->value, 0, 1);
          $lastChar  = substr($filter->value, -1);
          $filterValue = str_replace('%',$filter->value);


          if($firstChar == '%' && $lastChar == '%'){
            return strripos($itemValue,$filterValue) === false ? false : true;
          }elseif($firstChar !== '%'){
            return strripos($itemValue,$filterValue) === 0;
          }elseif($lastChar !== '%'){
            return strripos($itemValue,$filterValue) > 0;
          }else{
            return strripos($itemValue,$filterValue) === false ? false : true;
          }
        }
      })->pluck('id');

      return self::whereIn('id',$matchIds);
    }

    public function orWhereEncrypted($param1, $param2, $param3 = null)
    {
      /* 
        orWhereEncrypted
        Use for comparing encrypted fields to requested value with orWhere function

        *IMPLEMENTATION*
        User::whereEncrypt('email','app_user@gmail.com.mt')->get(); // Normal equal condition
        User::whereEncrypt('email','!=','app_user@gmail.com.mt')->get(); // Custom Operation
      */
      $filter            = new \stdClass();
      $filter->field     = $param1;
      $filter->operation = isset($param3) ? $param2 : '==';
      $filter->value     = isset($param3) ? $param3 : $param2;
          
      $matchIds = self::get()->filter(function ($item) use ($filter) {          
        $itemValue   = $item->{$filter->field};
        if($filter->operation == '=='){
          return $itemValue == $filter->value;
        }elseif(in_array($filter->operation,['!=','!==','<>'])){
          return $itemValue != $filter->value || $itemValue !== $filter->value || $itemValue <> $filter->value;
        }elseif(strtolower($filter->operation) == 'like'){
          $firstChar = substr($filter->value, 0, 1);
          $lastChar  = substr($filter->value, -1);
          $filterValue = str_replace('%',$filter->value);

          if($firstChar == '%' && $lastChar == '%'){
            return strripos($itemValue,$filterValue) === false ? false : true;
          }elseif($firstChar !== '%'){
            return strripos($itemValue,$filterValue) === 0;
          }elseif($lastChar !== '%'){
            return strripos($itemValue,$filterValue) > 0;
          }else{
            return strripos($itemValue,$filterValue) === false ? false : true;
          }
        }
      })->pluck('id');

      return self::orWhereIn('id',$matchIds);
    }
}