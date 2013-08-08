<?php

namespace Wikibase\Test;

use Wikibase\Item;

/**
 * Tests for the Wikibase\Item class.
 *
 * @file
 * @since 0.1
 *
 * @ingroup WikibaseLib
 * @ingroup Test
 *
 * @group Wikibase
 * @group WikibaseItem
 *
 * @group Database
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 * @author John Erling Blad < jeblad@gmail.com >
 */
class ItemNewEmptyTest extends \PHPUnit_Framework_TestCase {

	//@todo: make this a baseclass to use with all types of entitites.

	/**
	 * @var Item
	 */
	protected $item;
	
	/**
	 * This is to set up the environment.
	 */
	protected function setUp() {
  		parent::setUp();
		$this->item = Item::newEmpty();
	}
	
	/**
	 * Tests @see Item::newEmpty
	 */
	public function testNewEmpty() {
		$this->assertInstanceOf(
			'\Wikibase\Item',
			$this->item,
			'After creating an empty Item it should be a WikibaseItem'
		);
		$this->assertEquals(
			null,
			$this->item->getId(),
			'Calling getId on a newly created Item should return null'
		);
		$this->assertCount(
			0,
			$this->item->getLabels(),
			'Calling count on labels for a newly created Item should return zero'
		);
		$this->assertCount(
			0,
			$this->item->getdescriptions(),
			'Calling count on descriptions for a newly created Item should return zero'
		);
	}
	
	/**
	 * Tests @see Item::getLabel
	 * Tests @see Item::setLabel
	 * Tests @see Item::getLabels
	 */
	public function testSetGetLabel() {
		$arr = array(
			'no' => 'Norge',
			'nn' => 'Noreg'
		);
		foreach ($arr as $key => $val) {
			$this->item->setLabel( $key, $val );
		}
		foreach ($arr as $key => $val) {
			$this->assertEquals(
				$val,
				$this->item->getLabel( $key, $val ),
				'Testing setLabel-getLabel pair with "{$key} => {$val}" a new empty WikibaseItem after creating the item'
			);
		}
		$this->assertEquals(
			$arr,
			$this->item->getLabels(),
			'Testing if getLabels reconstructs the whole structure after it is built with setLabel'
		);
	}
	
	/**
	 * Tests @see WikibaseItem::getDescription
	 * Tests @see WikibaseItem::setDescription
	 * Tests @see WikibaseItem::getDescriptions
	 */
	public function testSetGetDescription() {
		$arr = array(
			'no' => 'Norge mitt eget land',
			'nn' => 'Noreg mitt eige land'
		);
		foreach ($arr as $key => $val) {
			$this->item->setDescription( $key, $val );
		}
		foreach ($arr as $key => $val) {
			$this->assertEquals(
				$val,
				$this->item->getDescription( $key ),
				'Testing setDescription-getDescription pair with "{$key} => {$val}" a new empty WikibaseItem after creating the item'
			);
		}
		$this->assertEquals(
			$arr,
			$this->item->getDescriptions(),
			'Testing if getDescriptions reconstructs the whole structure after it is built with setDescription'
		);
	}
	
}
