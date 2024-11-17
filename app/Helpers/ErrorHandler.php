<?php
function prettierError($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting, so let it fall
        // through to the standard PHP error handler
        return false;
    }
    // $errstr may need to be escaped:
    $errstr = htmlspecialchars($errstr);

    switch ($errno) {
    case E_USER_ERROR:
        echo "<h1>ERROR</h1>
        <b>{$errstr}</b><br><br />
        <b>line:</b> {$errline} <br>
        <b>File:</b> {$errfile}<br><br><hr>";
        break;
        exit(1);

    case E_USER_WARNING:
        echo "<h1>Warning</h1>
        <b>{$errstr}</b><br><br />
        <b>line:</b> {$errline} <br>
        <b>File:</b> {$errfile}<br><br><hr>";
        break;

    case E_USER_NOTICE:
        echo "<h1>Notice</h1>
        <b>{$errstr}</b><br><br />
        <b>line:</b> {$errline} <br>
        <b>File:</b> {$errfile}<br><br><hr>";
        break;

    default:
    echo "<h1>Default error</h1>
    <b>{$errstr}</b><br><br />
    <b>line:</b> {$errline} <br>
    <b>File:</b> {$errfile}<br><br><hr>";
    break;
    }
    $aTrace = debug_backtrace();
    unset($aTrace[0]);
    print_arr($aTrace);
    /* Don't execute PHP internal error handler */
    return true;
}
function print_arr($array){
    echo "<pre>".print_r($array,true)."</pre>";
    return true;
}
set_error_handler("prettierError");
?>