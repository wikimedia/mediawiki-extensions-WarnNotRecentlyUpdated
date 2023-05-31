<?php

namespace MediaWiki\Extension\WarnNotRecentlyUpdated\Tests;

use LogicException;
use MediaWiki\Extension\WarnNotRecentlyUpdated\Utilities\StringOption;

/**
 * @coversDefaultClass \MediaWiki\Extension\WarnNotRecentlyUpdated\Utilities\StringOption
 */
class StringOptionTest extends \MediaWikiUnitTestCase {

	/**
	 * @covers ::fromNone
	 * @covers ::isNone
	 */
	public function testFromNoneAndIsNone(): void {
		$option = StringOption::fromNone();

		$this->assertTrue( $option->isNone() );
		$this->assertFalse( $option->isSome() );
	}

	/**
	 * @covers ::from
	 * @covers ::isSome
	 */
	public function testFromAndIsSome(): void {
		$option = StringOption::from( "This is Sparta." );

		$this->assertTrue( $option->isSome() );
		$this->assertFalse( $option->isNone() );
	}

	/**
	 * @covers ::get
	 */
	public function testGetForSome(): void {
		$option = StringOption::from( "This is Sparta." );

		$this->assertEquals( "This is Sparta.", $option->get() );
	}

	/**
	 * @covers ::get
	 */
	public function testGetForNone(): void {
		$option = StringOption::fromNone();

		$this->expectException( LogicException::class );
		$result = $option->get();
	}

}
