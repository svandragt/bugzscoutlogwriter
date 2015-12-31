<?php
require_once 'Zend/Log/Formatter/Interface.php';

/**
 * Basically the email formatter converted to plain text
 * 
 * @package framework
 * @subpackage dev
 */
class LogErrorBugzScoutFormatter implements Zend_Log_Formatter_Interface
{

    public function format($event)
    {
        switch ($event['priorityName']) {
            case 'ERR':
            $errorType = 'Error';
            break;
            case 'WARN':
            $errorType = 'Warning';
            break;
            case 'NOTICE':
            $errorType = 'Notice';
            break;
            default:
            $errorType = $event['priorityName'];
        }

        if (!is_array($event['message'])) {
            return false;
        }

        $errno = $event['message']['errno'];
        $errstr = $event['message']['errstr'];
        $errfile = $event['message']['errfile'];
        $errline = $event['message']['errline'];
        $errcontext = $event['message']['errcontext'];

        $protocol = 'http';
        if (isset($_SERVER['HTTPS'])) {
            $protocol = 'https';
        }
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null;
        $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null;

        $relfile = Director::makeRelative($errfile);
        if ($relfile && $relfile[0] == '/') {
            $relfile = substr($relfile, 1);
        }
        
        $data = "[$errorType] in $relfile:{$errline} ({$protocol}://{$host}{$uri})\n";

        // Render the provided backtrace
        $data .= SS_Backtrace::get_rendered_backtrace($errcontext, true);

        // Compile extra data
        $blacklist = array('message', 'timestamp', 'priority', 'priorityName');
        $extras = array_diff_key($event, array_combine($blacklist, $blacklist));
        if ($extras) {
            $data .= "DETAILS\n\n";
            foreach ($extras as $k => $v) {
                if (is_array($v)) {
                    $v = var_export($v, true);
                }
                $data .= sprintf("[%s]\n%s\n\n", strtoupper($k), $v);
            }
        }



        $subject = "[$errorType] in $relfile:{$errline} ($host)";

        return array(
            'subject' => $subject,
            'data' => $data
            );
    }
}
