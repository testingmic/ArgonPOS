<?php
// ensure this file is being included by a parent file
if( !defined( 'SITE_URL' ) && !defined( 'SITE_DATE_FORMAT' ) ) die( 'Restricted access' );

class Accesslevel {

    private $_status = false;
    public $userId;
    public $currentPage;

    private $_message = '';

    public function __construct(){
        global $pos;
        $this->db = $pos;
    }

    /**
     * A method to fetch access level details from DB
     *
     * @param String $accessLevel Pass level id to fetch details
     *
     * @return String $this->_message
     */
    public function getPermissions($accessLevel = false)
    {
        $this->_message = false;

        $condition = ($accessLevel == false) ? "1" : " (id = '{$accessLevel}' OR access_name = '{$accessLevel}')";

        $stmt = $this->db->prepare(
            "SELECT * FROM access_levels WHERE {$condition}"
        );

        if ($stmt->execute()) {
            $this->_message = $stmt->fetchAll(PDO::FETCH_OBJ);
        }

        return $this->_message;
    }

    /**
     * A method to fetch access level details from DB
     *
     * @param String $accessLevel Pass level user_id to fetch details
     *
     * @return String $this->_message
     */
    public function getUserPermissions()
    {
        $this->_message = false;

        $stmt = $this->db->prepare(
            "SELECT * FROM user_roles WHERE user_id = '{$this->userId}'"
        );

        if ($stmt->execute()) {
            $this->_message = $stmt->fetchAll(PDO::FETCH_OBJ);
        }

        return $this->_message;
    }

    /**
     * A method to save SMS Into history
     *
     * @param String $message Pass message to send
     * @param String $to      Pass recipients number
     *
     * @return String $this->_message
     */
    public function assignUserRole($userID, $accessLevel, $permissions = false)
    {
        $this->_message = false;

        if ($permissions == false) {

            // Get Default Permissions
            $permissions = ($this->getPermissions($accessLevel) != false) ? 
                $this->getPermissions($accessLevel)[0]->default_permissions : null;

            $stmt = $this->db->prepare(
                "INSERT INTO user_roles SET user_id = '{$userID}', permissions = '{$permissions}'"
            );

            if ($stmt->execute()) {
                $this->_message = true;
            }
        } else {

            $stmt = $this->db->prepare(
                "UPDATE user_roles SET permissions = '".(is_array($permissions) ? json_encode($permissions) : $permissions)."' WHERE user_id = '{$userID}'"
            );

            if ($stmt->execute()) {
                $this->_message = true;
            }
        }

        return $this->_message;
    }

    public function hasAccess($role, $currentPage = null) {

        // Check User Roles Table
        if ($this->userPermission($role, $currentPage) == false) {

            return false;

        }

        return true;
    }

    protected function userPermission($role, $currentPage = null) {

        // force the setting of the current page
        if(!empty($currentPage)) {
            $this->currentPage = $currentPage;
        }

        $permissions = $this->getUserPermissions();

        if ($permissions != false) {

            $permissions = json_decode($permissions[0]->permissions, true);

            $i = 0;
            $permissions = $permissions['permissions'];
            $arrKeys = array_keys($permissions);
            
            // confirm that the requested page exists
            if(!isset($permissions[$this->currentPage])) {

                return false;

            } else {

                if(isset($permissions[$this->currentPage][$role])) {
                    if($permissions[$this->currentPage][$role] == 1) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
                
            }

        }

    }
}