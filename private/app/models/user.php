<?php

class user extends model {
	
	function insert($array = array(), $replace = false) {
		if (isset($array["password"])) {
			$array["password"] = user::getPasswordHash( user::getPasswordSalt(), $array["password"] );
		} else {
			$array["password"] = null;
		}
		$hash = user::getPasswordHash( user::getPasswordSalt(), $password );
		parent::insert($array, $replace);
	}
	
	function isTestpanel() {
		
	}
	
	function isAdministrator() {
		
	}
	
	// http://www.richardlord.net/blog/php-password-security
	//
	// get a new salt - 8 hexadecimal characters long
	// current PHP installations should not exceed 8 characters
	// on dechex( mt_rand() )
	// but we future proof it anyway with substr()
	static function getPasswordSalt() {
	    return substr( str_pad( dechex( mt_rand() ), 8, '0', STR_PAD_LEFT ), -8 );
	}
	
	// calculate the hash from a salt and a password
	static function getPasswordHash( $salt, $password ) {
	    return $salt . ( hash( 'whirlpool', $salt . $password ) );
	}
		
	// compare a password to a hash
	static function comparePassword( $password, $hash ) {
	    $salt = substr( $hash, 0, 8 );
	    return $hash == self::getPasswordHash( $salt, $password );
	}
	
	/**
	 * http://www.linuxjournal.com/article/9585
	 * Validate an email address.
	 * Provide email address (raw input)
	 * Returns true if the email address has the email 
	 * address format and the domain exists.
	*/
	static function validateEmail ($email) {
	   $isValid = true;
	   $atIndex = strrpos($email, "@");
	   if (is_bool($atIndex) && !$atIndex)
	   {
	      $isValid = false;
	   }
	   else
	   {
	      $domain = substr($email, $atIndex+1);
	      $local = substr($email, 0, $atIndex);
	      $localLen = strlen($local);
	      $domainLen = strlen($domain);
	      if ($localLen < 1 || $localLen > 64)
	      {
	         // local part length exceeded
	         $isValid = false;
	      }
	      else if ($domainLen < 1 || $domainLen > 255)
	      {
	         // domain part length exceeded
	         $isValid = false;
	      }
	      else if ($local[0] == '.' || $local[$localLen-1] == '.')
	      {
	         // local part starts or ends with '.'
	         $isValid = false;
	      }
	      else if (preg_match('/\\.\\./', $local))
	      {
	         // local part has two consecutive dots
	         $isValid = false;
	      }
	      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
	      {
	         // character not valid in domain part
	         $isValid = false;
	      }
	      else if (preg_match('/\\.\\./', $domain))
	      {
	         // domain part has two consecutive dots
	         $isValid = false;
	      }
	      else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local)))
	      {
	         // character not valid in local part unless 
	         // local part is quoted
	         if (!preg_match('/^"(\\\\"|[^"])+"$/',
	             str_replace("\\\\","",$local)))
	         {
	            $isValid = false;
	         }
	      }
	      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
	      {
	         // domain not found in DNS
	         $isValid = false;
	      }
	   }
	   return $isValid;
	}
	
}

?>