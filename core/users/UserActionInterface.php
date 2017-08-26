<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/2/2017
 * Time: 1:04 AM
 */

namespace wind\users;


interface UserActionInterface
{
    function login();
    function logout();
}