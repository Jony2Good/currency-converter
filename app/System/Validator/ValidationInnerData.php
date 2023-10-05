<?php

namespace App\System\Validator;

class ValidationInnerData
{
    /**
     * @param string $data
     * @return bool
     */
    public static function checkInnerData(string $data): bool
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        if (!filter_var($data, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
            return false;
        }
        return true;
    }

}