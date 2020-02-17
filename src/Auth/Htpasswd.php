<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @copyright 2020 Timon Kreis
 * @license http://www.opensource.org/licenses/mit-license.html
 */
declare(strict_types = 1);

namespace TimonKreis\Framework\Http\Auth;

/**
 * @category tk-framework
 * @package http
 */
class Htpasswd
{
	/**
	 * Encode the password
	 *
	 * @param string $password Password to encode
	 * @return string
	 */
	public function encode(string $password) : string
	{
		return crypt($password, base64_encode($password));
	}

	/**
	 * Check if the given password matches the given hash
	 *
	 * @param string $password Password
	 * @param string $hash Hash
	 * @return bool
	 */
	public function check(string $password, string $hash) : bool
	{
		return $this->encode($password) == $hash;
	}
}
