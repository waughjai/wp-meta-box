WP Meta Box
=========================

Simple class for easily adding meta boxes for WordPress admin editors.

## Use

You just need to create an instance o' this class before the admin loads & the constructor will automatically call all the WordPress functions needed to set a meta box up.

1st 2 mandatory arguments to constructor are the identifying slug & public title shown in editor. The optional 3rd argument is a hash map o' extra arguments.

* "post-type": Array o' post types this should show up in. Default is array with just "page".
* "input-type": String specifying type o' input HTML to form in the editor:
	* The default is "text".
	* 'Cept for the following exceptions, the any value will create an input tag with the type set to the given type. Thus, "number", "tel", "email", "password", & other HTML input types will work.
	* "textarea" will create a textarea tag.
	* "select" will create a select tag. Also requires optional argument "values", which should be an array o' hash maps with "id" & "name" key values. When used, the "id" value will be the actual value saved into the database, while the "name" value will show in the select box.
	* "day-of-the-week" will automatically create a select without needing the "values" argument. Values will be set to the 7 days o' week, whose IDs will be the #s 0 to 6 for Monday to Sunday.

## Example

	use WaughJ\WPMetaBox\WPMetaBox;

	new WPMetaBox
	(
		'color',
		'Color',
		[
			'post-type' => 'news-post',
			'input-type' => 'select',
			'values' =>
			[
				[ 'id' => '0', 'name' => 'Red' ],
				[ 'id' => '1', 'name' => 'Blue' ],
				[ 'id' => '2', 'name' => 'Green' ]
			]
		]
	);

## Changelog

### 0.2.0
* Add getValue Method

### 0.1.0
* Initial Version
