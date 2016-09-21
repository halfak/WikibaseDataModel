<?php

namespace Wikibase\DataModel\Entity;

use InvalidArgumentException;
use RuntimeException;

/**
 * @since 0.5
 *
 * @license GPL-2.0+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ItemId extends EntityId implements Int32EntityId {

	/**
	 * @since 0.5
	 */
	const PATTERN = '/^Q[1-9]\d{0,9}\z/i';

	/**
	 * @param string $idSerialization
	 *
	 * @throws InvalidArgumentException
	 */
	public function __construct( $idSerialization ) {
		$serializationParts = self::splitSerialization( $idSerialization );
		$localId = strtoupper( $serializationParts[2] );
		$this->assertValidIdFormat( $localId );
		parent::__construct( self::joinSerialization(
			array( $serializationParts[0], $serializationParts[1], $localId ) )
		);
	}

	private function assertValidIdFormat( $idSerialization ) {
		if ( !is_string( $idSerialization ) ) {
			throw new InvalidArgumentException( '$idSerialization must be a string' );
		}

		if ( !preg_match( self::PATTERN, $idSerialization ) ) {
			throw new InvalidArgumentException( '$idSerialization must match ' . self::PATTERN );
		}

		if ( strlen( $idSerialization ) > 10
			&& substr( $idSerialization, 1 ) > Int32EntityId::MAX
		) {
			throw new InvalidArgumentException( '$idSerialization can not exceed '
				. Int32EntityId::MAX );
		}
	}

	/**
	 * @return int
	 *
	 * @throws RuntimeException if called on a foreign ID.
	 */
	public function getNumericId() {
		if ( $this->isForeign() ) {
			throw new RuntimeException( 'getNumericId must not be called on foreign ItemIds' );
		}

		return (int)substr( $this->getSerialization(), 1 );
	}

	/**
	 * @return string
	 */
	public function getEntityType() {
		return 'item';
	}

	/**
	 * @see Serializable::serialize
	 *
	 * @return string
	 */
	public function serialize() {
		return json_encode( array( 'item', $this->getSerialization() ) );
	}

	/**
	 * @see Serializable::unserialize
	 *
	 * @param string $serialized
	 */
	public function unserialize( $serialized ) {
		list( , $this->serialization ) = json_decode( $serialized );
	}

	/**
	 * Construct an ItemId given the numeric part of its serialization.
	 *
	 * CAUTION: new usages of this method are discouraged. Typically you
	 * should avoid dealing with just the numeric part, and use the whole
	 * serialization. Not doing so in new code requires special justification.
	 *
	 * @param int|float|string $numericId
	 *
	 * @return self
	 * @throws InvalidArgumentException
	 */
	public static function newFromNumber( $numericId ) {
		if ( !is_numeric( $numericId ) ) {
			throw new InvalidArgumentException( '$numericId must be numeric' );
		}

		return new self( 'Q' . $numericId );
	}

}
