<?php
/*
* Defining functions in your helpers class is the easy part,
* although, there are a few caveats. All of the Laravel helper
* files are wrapped in a check to avoid function definition collisions:
* 
* if (! function_exists('env')) {
*     function env($key, $default = null) {
*         // ...
*     }
* }
* 
* This can get tricky, because you can run into situations where you
* are using a function definition that you did not expect based on
* which one was defined first.
* 
* I prefer to use function_exists checks in my application helpers, but
* if you are defining helpers within the context of your application, you
* could forgo the function_exists check.
* 
* By skipping the check, you’d see collisions any time your helpers are
* redefining functions, which could be useful.
* 
* In practice, collisions don’t tend to happen as often as you’d think,
* and you should make sure you’re defining function names that aren’t overly
* generic. You can also prefix your function names to make them less likely
* to collide with other dependencies.
*/


/*
 *   https://laracasts.com/discuss/channels/eloquent/hierarchical-database-table
 *
 */
function buildTree(array $elements, $parentId = 0) {
    $branch = array();
    
    foreach ($elements as $element) {
        if ($element['parent_id'] == $parentId) {
            $children = buildTree($elements, $element['id']);

            if ($children) {
                $element['children'] = $children;
            }
        
            $branch[] = $element;
        }
    }
    return $branch;
}