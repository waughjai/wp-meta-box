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

### 0.3.0
* Change input IDs to have “-input” appended to them to prevent collision with outer box
	* WordPress automatically applies our the meta box slug to the outer box of the input box beyond our control, so we have to change the ID of the inner input box to prevent multiple elements with the same ID, which is invalid HTML

### 0.2.1
* Update TestHashItem dependency

### 0.2.0
* Add getValue Method

### 0.1.4
* Fix Saving

### 0.1.3
* Fix Missing Use Statement Bug
	* Missing "use" statement caused constructor to fail

### 0.1.2
* Fix Example in Readme
	* Example didn't use "use" statement to include full class name with namespace, which in most cases will cause an error in code if used. This adds the "use" statement to the example for better clarification

### 0.1.1
* Improve Argument Name & Readme
	* Change "page-type" argument to "post-type" to make it mo' consistent & predictable with WordPress name for concept
	* Also adds detailed instructions to readme

### 0.1.0
* Initial Version
