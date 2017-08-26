<?php
/**
 * Created by PhpStorm.
 * User: WINDNCC
 * Date: 4/11/2017
 * Time: 10:08 AM
 */

namespace football\database\model\owners;

use fooco\database\model\owners\Owner;
use wind\database\Model;
use fooco\database\DB;
use Exception;

/**
 * @property array  _tableFields
 * @property array  _primaryFields
 * @property string _table
 */
class OwnerManagement extends Model
{
    protected $_ownerPitch = DB::TABLE_OWNER;
    protected $_pitchProperties;
    protected $_pitchTree = false;
    protected $_ownerProperties;

    function __construct()
    {
        parent::__construct();
        $this->_table = DB::TABLE_OWNER;
        $this->_tableFields = array( DB::COL_OWNER_ID );
        $this->_primaryFields = array( DB::COL_OWNER_ID );
    }

    public function getById($ownerId): Owner
    {
        $this->_getSuccess = false;
        $ownerCheck = new Owner(array( DB::COL_OWNER_ID => $ownerId ));
        if ($this->isRecordExists($ownerCheck)) {
            if (isset($this->_properties)) {
                $ownerData = $this->getOne(DB::COL_OWNER_ID, $ownerId);
                $this->_getSuccess = true;
                $owner = new Owner($ownerData);
                return $owner;
            }
        } else {
            throw new Exception("Owner không tồn tại!");
        }
        return new Owner();
    }


}