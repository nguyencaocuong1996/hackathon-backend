<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/9/2017
 * Time: 11:11 AM
 */

namespace wind\auth;
use fooco\DB;
use Exception;
trait AuthServiceProvider
{
    private $hash_key = 'a7755cd818360b342677a94b3f4e20383481fcc459d02cd89f557c8a0985304c';
    private $hash_algorithm = 'sha3-256';
    public function registerAuth($user = null)
    {
        if (isset($user->{DB::COLUMN_USER_ID}) || isset(Auth::getUser()->{DB::COLUMN_USER_ID})){
            $authId = isset($user) ? $user->{DB::COLUMN_USER_ID} : Auth::getUser()->{DB::COLUMN_USER_ID};
            $remember_token = hash_hmac($this->hash_algorithm, time(), $authId);
            $this->{'rememberToken'} = $remember_token;
            $_SESSION['remember_token'] = $remember_token;
            $this->_connection->where($this->getPrimaryFields()[0], $authId);
            $this->_connection->update($this->getTable(), array('rememberToken'=>$remember_token));
            if (isset($user)) Auth::setUser($user);
        } else {
            throw new Exception('Không tìm thấy userId khi đăng kí xác thực!');
        }

    }
}