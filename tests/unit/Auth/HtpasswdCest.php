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
class HtpasswdCest
{
	/**
	 * @param Framework\Test\UnitTester $I
	 */
	public function testEncodePassword(Framework\Test\UnitTester $I) : void
	{
		$htpasswd = new Framework\Http\Auth\Htpasswd();

		$I->assertEquals('YXqbfNHXR9QRA', $htpasswd->encode('auth'));
	}

	/**
	 * @param Framework\Test\UnitTester $I
	 */
	public function testCheckPassword(Framework\Test\UnitTester $I) : void
	{
		$htpasswd = new Framework\Http\Auth\Htpasswd();

		$I->assertTrue($htpasswd->check('auth', 'YXqbfNHXR9QRA'));
		$I->assertFalse($htpasswd->check('auth', 'auth'));
	}
}
