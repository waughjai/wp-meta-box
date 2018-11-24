<?php

use PHPUnit\Framework\TestCase;
use WaughJ\WPMetaBox\WPMetaBox;

require_once( 'MockWordPress.php' );

class WPMetaBoxTest extends TestCase
{
	public function testObjectWorks()
	{
		$object = new WPMetaBox( 'name', 'Name' );
		$this->assertTrue( is_object( $object ) );
	}

	public function testInputContent()
	{
		$meta_box = new WPMetaBox( 'scrum-drum', 'Sassafrass' );
		$post = getDemoPost();
		$this->assertEquals( $meta_box->getInputContent( $post ), '<input type="text" id="scrum-drum" name="scrum-drum" placeholder="Sassafrass" size="100%" value="' . get_post_meta( $post->ID, 'scrum-drum', true ) . '">' );
	}

	public function testTextareaContent()
	{
		$meta_box = new WPMetaBox( 'name', 'Name', [ 'input-type' => 'textarea' ] );
		$post = getDemoPost();
		$this->assertEquals( $meta_box->getInputContent( $post ), '<textarea id="name" name="name" placeholder="Name" cols="100%" rows="6">' . get_post_meta( $post->ID, 'scrum-drum', true ) . '</textarea>' );
	}

	public function testCheckboxContent()
	{
		$meta_box = new WPMetaBox( 'scrum-drum', 'Sassafrass', [ 'input-type' => 'checkbox' ] );
		$post = getDemoPost();
		$checked = ( get_post_meta( $post->ID, 'scrum-drum', true ) ) ? ' checked="true"' : '';
		$this->assertEquals( $meta_box->getInputContent( $post ), '<input type="checkbox" id="scrum-drum" name="scrum-drum" size="100%"' . $checked . '>' );
	}

	public function testDayOTheWeekContent()
	{
		$meta_box = new WPMetaBox( 'scrum-drum', 'Sassafrass', [ 'input-type' => 'day-of-the-week' ] );
		$post = getDemoPost();
		$this->assertEquals( $meta_box->getInputContent( $post ), '<select id="scrum-drum" name="scrum-drum"><option value="0" label="Monday" selected="selected">Monday</option><option value="1" label="Tuesday">Tuesday</option><option value="2" label="Wednesday">Wednesday</option><option value="3" label="Thursday">Thursday</option><option value="4" label="Friday">Friday</option><option value="5" label="Saturday">Saturday</option><option value="6" label="Sunday">Sunday</option></select>' );
	}

	public function testSelectContent()
	{
		$meta_box = new WPMetaBox( 'scrum-drum', 'Sassafrass', [ 'input-type' => 'select', 'values' => [[ 'id' => '0', 'name' => 'Red' ], [ 'id' => '1', 'name' => 'Blue' ], [ 'id' => '2', 'name' => 'Green' ]] ] );
		$post = getDemoPost();
		$this->assertEquals( $meta_box->getInputContent( $post ), '<select id="scrum-drum" name="scrum-drum"><option value="0" label="Red">Red</option><option value="1" label="Blue">Blue</option><option value="2" label="Green">Green</option></select>' );
	}
}
