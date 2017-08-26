<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 8/26/2017
 * Time: 11:21 AM
 */

namespace fooco\database\model\user;


use wind\database\Model;
use fooco\database\DB;

/**
 * @property string _table
 * @property array  _tableFields
 * @property array  _primaryFields
 * @property array  _uniqueFields
 * @property bool   _AI
 */
class UserManagement extends Model
{

    protected $_ownerPitch = DB::TABLE_USER;
    protected $_pitchProperties;
    protected $_pitchTree = false;
    protected $_ownerProperties;

    function __construct()
    {
        parent::__construct();
        $this->_table = DB::TABLE_USER;
        $this->_tableFields = array( DB::COL_USER_ID );
        $this->_primaryFields = array( DB::COL_USER_ID );
        $this->_uniqueFields = array( DB::COL_USER_NAME );
        $this->_AI = true;
    }

    public function login(string $userName, string $userPass): array
    {
        $userPass = md5($userPass);
        $this->_connection->where(DB::COL_USER_NAME, $userName);
        $this->_connection->where(DB::COL_USER_PASSWORD, $userPass);
        $check = $this->_connection->getOne(DB::TABLE_USER);
        if (!empty($check)){
            return $check;
        }
        return array();
    }

}