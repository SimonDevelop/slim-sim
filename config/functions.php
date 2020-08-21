<?php

// Don't remove this function
function slim_env($var = null)
{
    if (!is_null($var)) {
        if ($var === 'CACHE') {
            return filter_var($_ENV[$var], FILTER_VALIDATE_BOOLEAN);
        } elseif ($var === 'ENV') {
            if ($_ENV[$var] === 'dev') {
                return true;
            } else {
                return false;
            }
        }
    } else {
        return false;
    }
}
