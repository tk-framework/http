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
class Basic
{
	/**
	 * @var string
	 */
	protected $realm = 'Authorization required';

	/**
	 * @var string
	 */
	protected $cancelText = 'Authorization required';

	/**
	 * @var string
	 */
	protected $username = null;

	/**
	 * @var string
	 */
	protected $password = null;

	/**
	 * Create an authentication request
	 *
	 * @return $this|null
	 */
	public function auth() : ?Basic
	{
		if (isset($_SERVER['PHP_AUTH_USER'])) {
			$this->username = $_SERVER['PHP_AUTH_USER'];
			$this->password = $_SERVER['PHP_AUTH_PW'];
		}
		elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
			$authorization = $_SERVER['HTTP_AUTHORIZATION'];

			if (strpos(strtolower($authorization), 'basic') === 0) {
				[$this->username, $this->password] = explode(':', base64_decode(substr($authorization, 6)), 2);
			}
		}
		else {
			header('WWW-Authenticate: Basic realm="' . $this->realm . '"');
			header('HTTP/1.0 401 Unauthorized');

			echo $this->cancelText;

			return null;
		}

		return $this;
	}

	/**
	 * Set the realm
	 *
	 * @param string $realm Realm
	 * @return $this
	 */
	public function setRealm(string $realm) : Basic
	{
		$this->realm = $realm;

		return $this;
	}

	/**
	 * Set the text to display when cancelled
	 *
	 * @param string $text Text
	 * @return $this
	 */
	public function setCancelText(string $text) : Basic
	{
		$this->cancelText = $text;

		return $this;
	}

	/**
	 * Get the username
	 *
	 * @return string
	 */
	public function getUsername() : string
	{
		return $this->username;
	}

	/**
	 * Get the password
	 *
	 * @return string
	 */
	public function getPassword() : string
	{
		return $this->password;
	}
}
