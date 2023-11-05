<?php

namespace App\Libraries;


use Exception;


class QueryExceptionLibrary
{

    /**
     * @param Exception $e
     * @return string
     */
    public static function message(Exception $e) : string
    {
        if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1451) {
            return 'Cannot remove this resource permanently. It is related with another resource.';
        } else {
            return $e->getMessage();
        }
    }

}
