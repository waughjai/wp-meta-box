<?php

declare( strict_types = 1 );
namespace WaughJ\WPMetaBox
{
	use function WaughJ\TestHashItem\TestHashItemString;
	use function WaughJ\TestHashItem\TestHashItemExists;

	class WPMetaBox
	{
		//
		//  PUBLIC
		//
		/////////////////////////////////////////////////////////

			public function __construct( string $slug, string $title, array $extra_attributes = [] )
			{
				$this->slug = $slug;
				$this->title = $title;
				$this->post_type = TestHashItemExists( $extra_attributes, 'post-type', [ 'page' ] );

				// If singular, make it an array with singlular as only child.
				if ( !is_array( $this->post_type ) )
				{
					$this->post_type = [ $this->post_type ];
				}

				$this->input_type = TestHashItemString( $extra_attributes, 'input-type', 'text' );
				$this->other_arguments = $extra_attributes;
				$this->addActions();
			}

			public function register() : void
			{
				add_meta_box
				(
					$this->slug,
					__( $this->title, 'cesi' ),
					[ $this, 'drawGUI' ],
					$this->post_type,
					'normal',
					'high'
				);
			}

			public function drawGUI( \WP_Post $post ) : void
			{
				$this->generateNonce();
				$this->drawInput( $post );
			}

			public function save( int $post_id ) : void
			{
				if
				(
					!$this->testIsAutosaving()          &&
					$this->testUserCanEdit( $post_id ) &&
					$this->testPostIsRightType()       &&
					$this->testIsValidNonce()
				)
				{
					update_post_meta( $post_id, $this->slug, ( isset( $_POST[ $this->slug ] ) ) ? $_POST[ $this->slug ] : null );
				}
			}

			public function getSlug() : string
			{
				return $this->slug;
			}

			public function getTitle() : string
			{
				return $this->title;
			}

			public function getInputContent( \WP_Post $post ) : string
			{
				ob_start();
				$this->drawInput( $post );
				return ob_get_clean();
			}



		//
		//  PRIVATE
		//
		/////////////////////////////////////////////////////////

			private function addActions() : void
			{
				add_action( 'add_meta_boxes', [ $this, 'Register' ] );
				add_action( 'save_post',      [ $this, 'Save'     ] );
			}

			private function generateNonce() : void
			{
				wp_nonce_field( plugin_basename( __FILE__ ), $this->getTypeNonce() );
			}

			private function drawInput( \WP_Post $post ) : void
			{
				switch ( $this->input_type )
				{
					case ( 'textarea' ):
					{
						?><textarea <?php $this->printCommonAttributes(); ?> cols="100%" rows="6"><?= $this->getPostValue( $post ); ?></textarea><?php
					}
					break;

					case ( 'checkbox' ):
					{
						$checked_text = ( $this->getPostValue( $post ) === '' ) ? '' : ' checked="true"';
						?><input <?php $this->printTypeAttribute(); ?> <?php $this->printIDAttribute(); ?> <?php $this->printNameAttribute(); ?> size="100%"<?= $checked_text; ?>><?php
					}
					break;

					case ( 'select' ):
					{
						if ( isset( $this->other_arguments[ 'values' ] ) && is_array( $this->other_arguments[ 'values' ] ) )
						{
							$this->generateSelectInput( $this->other_arguments[ 'values' ], $post );
						}
					}
					break;

					case ( 'day-of-the-week' ):
					{
						$this->generateSelectInput( self::getDaysOfTheWeekValues(), $post );
					}
					break;

					default:
					{
						?><input <?php $this->printTypeAttribute(); ?> <?php $this->printCommonAttributes(); ?> size="100%" value="<?= $this->getPostValue( $post ); ?>"><?php
					}
					break;
				}
			}

			private function generateSelectInput( array $values, \WP_Post $post ) : void
			{
				?><select <?php $this->printIDAttribute(); ?> <?php $this->printNameAttribute(); ?>><?php
				if ( is_array( $values ) )
				{
					foreach( $values as $value )
					{
						if ( isset( $value[ 'id' ] ) && isset( $value[ 'name' ] ) )
						{
							$selected = $this->getPostValue( $post ) == $value[ 'id' ];
							$selected_text = ( $selected ) ? ' selected="selected"' : '';
							?><option value="<?= $value[ 'id' ]; ?>" label="<?= $value[ 'name' ]; ?>"<?= $selected_text; ?>><?= $value[ 'name' ]; ?></option><?php
						}
					}
				}
				?></select><?php
			}

			private function getTypeNonce() : string
			{
				return $this->slug . '-nonce-';
			}

			private function printCommonAttributes() : void
			{
				$this->printIDAttribute(); ?> <?php $this->printNameAttribute(); ?> <?php $this->printPlaceholderAttribute();
			}

			private function printIDAttribute() : void
			{
				?>id="<?= $this->slug; ?>"<?php
			}

			private function printNameAttribute() : void
			{
				?>name="<?= $this->slug; ?>"<?php
			}

			private function printPlaceholderAttribute() : void
			{
				?>placeholder="<?= ucwords( $this->title ); ?>"<?php
			}

			private function printTypeAttribute() : void
			{
				?>type="<?= $this->input_type; ?>"<?php
			}

			private function getPostValue( \WP_Post $post ) : string
			{
				return ( get_post_meta( $post->ID, $this->slug, true ) ) ? ( string )( get_post_meta( $post->ID, $this->slug, true ) ) : '';
			}

			private function testIsAutosaving() : bool
			{
				return defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE;
			}

			private function testUserCanEdit( int $post_id ) : bool
			{
				return current_user_can( 'edit_post', $post_id );
			}

			private function testPostIsRightType() : bool
			{
				return isset( $_POST[ 'post_type' ] ) && in_array( $_POST[ 'post_type' ], $this->post_type );
			}

			private function testIsValidNonce() : bool
			{
				$nonce_id = $this->getTypeNonce();
				return isset( $_POST[ $nonce_id ] ) && wp_verify_nonce( $_POST[ $nonce_id ], plugin_basename( __FILE__ ) );
			}

			private static function getDaysOfTheWeekValues() : array
			{
				$values = [];
				$num_of_days_of_the_week = count( self::DAYS_OF_THE_WEEK );
				for ( $i = 0; $i < $num_of_days_of_the_week; $i++ )
				{
					array_push( $values, [ 'id' => $i, 'name' => self::DAYS_OF_THE_WEEK[ $i ] ] );
				}
				return $values;
			}

			private $slug;
			private $name;
			private $post_type;
			private $input_type;
			private $other_arguments;

			const DAYS_OF_THE_WEEK =
			[
				'Monday',
				'Tuesday',
				'Wednesday',
				'Thursday',
				'Friday',
				'Saturday',
				'Sunday'
			];
	}
}
