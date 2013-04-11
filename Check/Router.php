<?php
/**
 * $simpleRegex = array(
 * 'id' => '([0-9]+)',
 * 'slug' => '([a-zA-Z0-9-_]+)'
 * );
 *
 * $pattern = 'blog/view/[slug:categoryslug]/[id:postid].html';
 *
 * $match = preg_match_all('/\[(.*?)\]/', $pattern, $values);
 *
 * echo $pattern;
 *
 * echo '<br>';
 *
 * var_dump($match);
 *
 * echo '<br>';
 *
 * var_dump($values);
 *
 * echo '<br>';
 *
 * foreach($values[1] as $value)
 * {
 * $value = explode(':', $value, 2);
 * if(count($value) == 2) {
 * if($value[0] === '') $regex[] = '([0-9]+)';
 * elseif(isset($simpleRegex[$value[0]])) $regex[] = $simpleRegex[$value[0]];
 * else $regex[] = $simpleRegex['slug'];
 * }
 * else {
 * $regex[] = $value[0];
 * }
 * }
 *
 * $pattern = str_replace('/', '\\/', $pattern);
 * $linkpattern = '/' . str_replace($values[0], $regex, $pattern) . '/';
 *
 * echo $linkpattern;
 *
 * echo '<br>';
 *
 * echo 'blog/view/php/1.html';
 *
 * echo '<br>';
 *
 * $match1 = preg_match_all($linkpattern, 'blog/view/php/1.html', $values1);
 *
 * var_dump($match1);
 *
 * echo '<br>';
 *
 * var_dump($values1);
 *
 * echo '<br>';
 *
 * echo 'blog/view/php/a.html';
 *
 * echo '<br>';
 *
 * $match1 = preg_match($linkpattern, 'blog/view/php/a.html', $values1);
 *
 * var_dump($match1);
 *
 * echo '<br>';
 *
 * var_dump($values1);
 *
 * echo '<br>===============<br>';
 *
 * $pattern = 'contact/hq_london.html';
 *
 * $match = preg_match_all('/\[(.*?)\]/', $pattern, $values);
 *
 * echo $pattern;
 *
 * echo '<br>';
 *
 * var_dump($match);
 *
 * echo '<br>';
 *
 * var_dump($values);
 *
 * echo '<br>';
 */
require '../Vhmis/Network/RouteInterface.php';
require '../Vhmis/Network/Route.php';

$route = new Vhmis\Network\Route();

$route->setPattern('blog/view/[id:posid].html');
$route->setController('Blog');
$route->setAction('View');

$result = $route->check('blog/view/1.html');
var_dump($result);
echo "<br>";

$result = $route->check('blog/view/1dfdf.html');
var_dump($result);
echo "<br>";

$result = $route->check('blog/view/1546565.html');
var_dump($result);
echo "<br>";

$result = $route->check('blog/view/a.html');
var_dump($result);
echo "<br>";

$result = $route->check('blog/view/1.htmlfksksjblog/view/1.html');
var_dump($result);
echo "<br>";

$route->setRedirect('blog/xem/[posid].html');

$result = $route->check('blog/view/1546565.html');
var_dump($result);
echo "<br>";