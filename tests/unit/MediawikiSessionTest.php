<?php

namespace Mediawiki\Api\Test;

use Mediawiki\Api\SimpleRequest;

/**
 * @covers Mediawiki\Api\MediawikiSession
 */
class MediawikiSessionTest extends \PHPUnit_Framework_TestCase {

	public function testConstruction() {
		$mockApi = $this->getMockBuilder( '\Mediawiki\Api\MediawikiApi' )
			->disableOriginalConstructor()
			->getMock();
		$session = new \Mediawiki\Api\MediawikiSession( $mockApi );
		$this->assertInstanceOf( '\Mediawiki\Api\MediawikiSession', $session );
	}

	/**
	 * @dataProvider provideTokenTypes
	 */
	public function testGetToken( $tokenType ) {
		$mockApi = $this->getMockBuilder( '\Mediawiki\Api\MediawikiApi' )
			->disableOriginalConstructor()
			->getMock();
		$mockApi->expects( $this->exactly( 2 ) )
			->method( 'postRequest' )
			->with( $this->isInstanceOf( '\Mediawiki\Api\SimpleRequest' ) )
			->will( $this->returnValue( array(
				'query' => array(
					'tokens' => array(
					$tokenType => 'TKN-' . $tokenType,
					)
				)
			) ) );

		$session = new \Mediawiki\Api\MediawikiSession( $mockApi );

		//Although we make 2 calls to the method we assert the tokens method about is only called once
		$this->assertEquals( 'TKN-' . $tokenType, $session->getToken() );
		$this->assertEquals( 'TKN-' . $tokenType, $session->getToken() );
		//Then clearing the tokens and calling again should make a second call!
		$session->clearTokens();
		$this->assertEquals( 'TKN-' . $tokenType, $session->getToken() );
	}

	public function provideTokenTypes() {
		return array(
			array( 'csrf' ),
		);
	}

} 