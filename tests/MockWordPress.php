<?php

	global $metas;
	$metas =
	[
		'blim',
		'safdjs',
		'qihfoahga',
		false
	];

	class WP_Post
	{
		public function __construct()
		{
			$this->ID = rand( 0, 3 );
		}

		public $ID;
	}

	function getDemoPost()
	{
		return new WP_Post();
	}

	function add_action( $name, $function )
	{
		// Don't need to worry 'bout what this does.
	}

	function get_post_meta( $id, $type, $array )
	{
		global $metas;
		return $metas[ $id ];
	}
