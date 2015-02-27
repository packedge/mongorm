<?php
/**
 * Created by PhpStorm.
 * User: ashleyclarke
 * Date: 22/02/15
 * Time: 19:04
 */

require __DIR__ . '/vendor/autoload.php';

class User extends \Packedge\Mongorm\Eloquent\Model
{

}

$user = new User;
$user->name = "Bob";
$user->age = 22;
$user->save();

var_dump(User::all());