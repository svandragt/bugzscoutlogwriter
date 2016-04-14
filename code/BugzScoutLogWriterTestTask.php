<?php
/**
 */
class BugzScoutLogWriterTestTask extends BuildTask
{

    protected $title = __CLASS__;

    protected $description = 'Test correct configuration by forcing an error.';

    public function run($request)
    {
        $writers = SS_Log::get_writers();
        print("<pre>The following log writers have been registered:" . PHP_EOL);
        foreach ($writers as $w) {
            printf(" * %s" . PHP_EOL, get_class($w));
            if (get_class($w) == 'BugzScoutLogWriter') {
                $lw = $w;
            }
        }
        if ($lw) {
            printf('</pre><p>This task forces an error. If configured correctly, a new bug will be opened in <a href="%s">%s</a>.</p>', $lw->host(), $lw->host());
            user_error('test error');
        } else {
            print("</pre><p>No BugzScoutLogWriter instances found. Please review your setup.</p>");
        }
    }
}
