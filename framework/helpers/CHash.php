<?php
/**
 * CHash is a helper class file that provides different encryption methods
 *
 * @project ApPHP Framework
 * @author ApPHP <info@apphp.com>
 * @link http://www.apphpframework.com/
 * @copyright Copyright (c) 2012 - 2015 ApPHP Framework
 * @license http://www.apphpframework.com/license/
 *
 * PUBLIC (static):			PROTECTED:					PRIVATE (static):
 * ----------               ----------                  ----------
 * create                                               _padKey
 * salt
 * equals
 * getRandomString
 * getSequentialString
 * encrypt
 * decrypt
 * getRandomOrIterationString
 * 
 */	  

class CHash
{
	
    /**
     * Creates hash for given password 
     * @param string $algorithm (md5, sha1, sha256, whirlpool, etc.)
     * @param string $data 
     * @param string $salt
     * @return string (hashed/salted data)
     */
    public static function create($algorithm, $data, $salt = '')
    {
		if(!empty($salt)){
			$context = hash_init($algorithm, HASH_HMAC, $salt);	
		}else{
			$context = hash_init($algorithm);	
		}
        
        hash_update($context, $data);
        
        return hash_final($context);        
    }

    /**
     * Generates salt
     * @return string
     */
    public static function salt()
    {
		return base64_encode(mcrypt_create_iv(24, MCRYPT_DEV_URANDOM));	
	}
	
    /**
     * Compares two strings $a and $b in length-constant time
     * @param string $a
     * @param string $b
     * @return bool
     */
    public static function equals($a, $b)
    {
		$diff = strlen($a) ^ strlen($b);
		for($i = 0; $i < strlen($a) && $i < strlen($b); $i++)
		{
			$diff |= ord($a[$i]) ^ ord($b[$i]);
		}
		return $diff === 0; 
	}

    /**
     * Creates random string
     * @param integer $length
     * @param array $params
     * type: 'numeric', 'positiveNumeric', 'alphanumeric', 'alpha'
     * case: 'upper', 'lower' (default)
     * @return string
     */
    public static function getRandomString($length = 10, $params = array())
    {
        $type = isset($params['type']) ? $params['type'] : '';
        $case = isset($params['case']) ? $params['case'] : '';
        
		if($type == 'numeric'){
            $template = '1234567890';    
        }else if($type == 'positiveNumeric'){
            $template = '123456789';    
        }else if($type == 'alpha'){
            $template = 'abcdefghijklmnopqrstuvwxyz';    
        }else{
            $template = '1234567890abcdefghijklmnopqrstuvwxyz';
        }
        
		if($case == 'upper') $template = strtoupper($template);
        settype($template, 'string');
        settype($length, 'integer');
        settype($output, 'string');
        settype($a, 'integer');
        settype($b, 'integer');           
        
		for($a = 0; $a < $length; $a++){
            $b = rand(0, strlen($template) - 1);
            $output .= $template[$b];
        }
		
        return $output;       
    }
    
    /**
     * Creates random string
     * @param integer $length
     * @param boolean $isRandom
     * @param integer $id
     * @return string
     */
    public static function getRandomOrIterationString($length = 10, $isRandom = true, $id = 0)
    {
        if($isRandom){
            $result = self::getRandomString($length);
        }else{
            $result = sprintf('%0'.(int)$length.'d',$id);
        }

        return $result;
    }

    /**
     * Creates sequential string
     * @param int $numeric
     * @param int $length
     * @return string
     */
    public static function getSequentialString($number = '', $length = 10)
    {
		str_pad($number, $length, '0', STR_PAD_LEFT);
	}

	/**
	 * Encrypt given value
	 * @param $value
	 * @param $secretKey
	 */
	public static function encrypt($value, $secretKey)
    {
        $secretKey = self::_padKey($secretKey);

		return trim(strtr(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $secretKey, $value, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))), '+/=', '-_,'));
    }
	
	/**
	 * Decrypt given value
	 * @param $value
	 * @param $secretKey
	 */
	public static function decrypt($value, $secretKey)
	{
        $secretKey = self::_padKey($secretKey);

        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $secretKey, base64_decode(strtr($value, '-_,', '+/=')), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
	}

	/**
	 * Pad key
	 * @param string $key
	 * @return string
	 */
    private static function _padKey($key)
    {
        // Key is too large
        if(strlen($key) > 32) return false;

        // Set sizes
        $sizes = array(16,24,32);

        // Loop through sizes and pad key
        foreach($sizes as $s){
            if($s > strlen($key)){
                $key = str_pad($key, $s, "\0");
                break;
            }
        }

        return $key;
    }
}
