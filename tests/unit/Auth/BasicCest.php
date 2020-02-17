<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @copyright 2020 Timon Kreis
 * @license http://www.opensource.org/licenses/mit-license.html
 */
declare(strict_types = 1);

use TimonKreis\Framework;

/**
 * @category tk-framework
 * @package http
 */
class BasicCest
{
	/**
	 * @param Framework\Test\UnitTester $I
	 */
	public function testUsernameWithAuthUser(Framework\Test\UnitTester $I) : void
	{
		$basic = new Framework\Http\Auth\Basic();

		$_SERVER['PHP_AUTH_USER'] = 'username';
		$_SERVER['PHP_AUTH_PW'] = 'password';

		$basic->auth();

		$I->assertEquals('username', $basic->getUsername());

		unset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
	}

	/**
	 * @param Framework\Test\UnitTester $I
	 */
	public function testUsernameWithAuthorization(Framework\Test\UnitTester $I) : void
	{
		$basic = new Framework\Http\Auth\Basic();

		$_SERVER['HTTP_AUTHORIZATION'] = 'Basic dXNlcm5hbWU6cGFzc3dvcmQ=';

		$basic->auth();

		$I->assertEquals('username', $basic->getUsername());

		unset($_SERVER['HTTP_AUTHORIZATION']);
	}

	/**
	 * @param Framework\Test\UnitTester $I
	 */
	public function testPassword(Framework\Test\UnitTester $I) : void
	{
		$basic = new Framework\Http\Auth\Basic();

		$_SERVER['PHP_AUTH_USER'] = 'username';
		$_SERVER['PHP_AUTH_PW'] = 'password';

		$basic->auth();

		$I->assertEquals('password', $basic->getPassword());

		unset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
	}

	/**
	 * @param Framework\Test\UnitTester $I
	 */
	public function testPasswordWithAuthorization(Framework\Test\UnitTester $I) : void
	{
		$basic = new Framework\Http\Auth\Basic();

		$_SERVER['HTTP_AUTHORIZATION'] = 'Basic dXNlcm5hbWU6cGFzc3dvcmQ=';

		$basic->auth();

		$I->assertEquals('password', $basic->getPassword());

		unset($_SERVER['HTTP_AUTHORIZATION']);
	}

	/**
	 * @param Framework\Test\UnitTester $I
	 */
	public function testSettingRealm(Framework\Test\UnitTester $I) : void
	{
		$basic = new Framework\Http\Auth\Basic();
		$realm = uniqid();
		$catched = false;

		set_error_handler(function() use ($I, $realm, &$catched) : void {
			if ($catched) {
				return;
			}

			$header = debug_backtrace(0, 2)[1]['args'][0] ?? '';
			$catched = true;

			$I->assertContains($realm, $header);
		});

		ob_start();

		$basic->setRealm($realm);
		$basic->auth();

		ob_end_clean();
		restore_error_handler();

		if (!$catched) {
			$I->fail('Failed catching expected warning from header function.');
		}
	}

	/**
	 * @param Framework\Test\UnitTester $I
	 */
	public function testSettingCancelText(Framework\Test\UnitTester $I) : void
	{
		$basic = new Framework\Http\Auth\Basic();
		$cancelText = uniqid();

		ob_start();

		$basic->setCancelText($cancelText);
		@$basic->auth();

		$response = ob_get_clean();

		$I->assertEquals($cancelText, $response);
	}
}
