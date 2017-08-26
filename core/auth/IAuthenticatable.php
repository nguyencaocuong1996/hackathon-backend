<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/9/2017
 * Time: 10:49 AM
 */

namespace wind\auth;


interface IAuthenticatable
{

    public function getAuthId();

    public function getAuthPassword();

    public function getRememberToken();

    public function getAccessToken();

}