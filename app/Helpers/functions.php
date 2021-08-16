<?php

// Defining functions in your helpers class is the easy part,
// although, there are a few caveats. All of the Laravel helper
// files are wrapped in a check to avoid function definition collisions:

// if (! function_exists('env')) {
//     function env($key, $default = null) {
//         // ...
//     }
// }

// This can get tricky, because you can run into situations where you
// are using a function definition that you did not expect based on
// which one was defined first.

// I prefer to use function_exists checks in my application helpers, but
// if you are defining helpers within the context of your application, you
// could forgo the function_exists check.

// By skipping the check, you’d see collisions any time your helpers are
// redefining functions, which could be useful.

// In practice, collisions don’t tend to happen as often as you’d think,
// and you should make sure you’re defining function names that aren’t overly
// generic. You can also prefix your function names to make them less likely
// to collide with other dependencies.



/**
 * I have no idea what I wrote here.
 *
 * @access public
 * @param  array     $x_objects
 * @param  array     $y_objects
 * @return array
 */

function random_id_pairs($x_objects, $y_objects){

	$result = [];
	
	$y_ids = $y_objects->pluck('id')->toArray();

	foreach($x_objects as $x){

	    $subset_values = [];

	    // N.B.: array_rand retrieves random "keys" not random "values"
	    $subset_keys = array_rand($y_ids, rand(2,5));            

	    foreach($subset_keys as $key){
	        array_push($subset_values, $y_ids[$key]);
	    }

	    foreach($subset_values as $y_id){
	    	array_push($result, [$x->id, $y_id]);
	    }
	}

	return $result;
}