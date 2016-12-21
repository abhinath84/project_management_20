<?php
    /*-- 
	File	: inc/cipher.inc.php
	Author	: Abhishek Nath
	Date	: 01-Jan-2015
	Desc	: Implement Cipher class.
	--*/

	/*-- 
	01-Jan-15   V1-01-00   abhishek   $$1   Created.
	17-Jul-15   V1-01-00   abhishek   $$2   File header comment added.
	--*/

	/**
	 * @abstract	cipher
	 * @author  	Abhishek Nath <abhi.ece.sit@gmail.com>
	 * @version 	1.0
	 *
	 * @section LICENSE
	 *
	 * This program is free software; you can redistribute it and/or
	 * modify it under the terms of the GNU General Public License as
	 * published by the Free Software Foundation; either version 2 of
	 * the License, or (at your option) any later version.
	 *
	 * This program is distributed in the hope that it will be useful, but
	 * WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
	 * General Public License for more details at
	 * http://www.gnu.org/copyleft/gpl.html
	 *
	 * @section DESCRIPTION
	 *
	 * The time class represents a moment of time.
	 */
	
	
	/* 
	 * Provide encrypted, decrypted, hashed value of the input string. 
	 */
	class Cipher
	{
		private $cipherName;
		private $securekey;
		private $mode;
		private $iv;
		
		public function __construct($key, $cipher="", $mode="", $iv="")
		{
			// check validity of cipher name
			// check validity of mode
			// check validity of IV
			
			$this->securekey	= hash('sha256',$key,TRUE);
			$this->cipherName	= ($cipher == "") ? MCRYPT_RIJNDAEL_256 : $cipher;
			$this->mode			= ($mode == "") ? MCRYPT_MODE_ECB : $mode;
			$this->iv			= ($iv == "") ? mcrypt_create_iv(32) : $iv;
		}
		
		function encrypt($string)
		{
			// $string = rtrim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $string, MCRYPT_MODE_ECB)));
			$encryptedStr = "";
			if($string <> "")
				$encryptedStr = rtrim(base64_encode(mcrypt_encrypt($this->cipherName, $this->securekey, $string, $this->mode, $this->iv)));
			
			return($encryptedStr);
		}
		
		function decrypt($string)
		{
			//$string = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($tring), MCRYPT_MODE_ECB));
			$decryptedStr = "";
			if($string <> "")
				$decryptedStr= rtrim(mcrypt_decrypt($this->cipherName, $this->securekey, base64_decode($string), $this->mode, $this->iv));
				
			return($decryptedStr);
		}
		
		function hashword($string)
		{
			$string = crypt($string, '$1$' . $this->securekey . '$');
			return($string);
		}
		
		function display()
		{
			return('cipherName : ' . $this->cipherName . ', mode : ' . $this->mode . ', IV : ' . $this->iv);
		}
	}
?>
