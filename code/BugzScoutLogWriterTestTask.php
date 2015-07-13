<?php
/**
 */
class BugzScoutLogWriterTestTask extends BuildTask {

	protected $title = __CLASS__;

	protected $description = 'Test correct configuration by forcing an error.';

	public function run($request) {
		$writers = SS_Log::get_writers();
		foreach ($writers as $w) {
			if (get_class($w) == 'BugzScoutLogWriter') {
				break;
			}
		}
		Debug::dump($w);
		printf('This task forces an error. If configured correctly, a new bug will be opened in <a href="%s">%s</a>.', $w->host(), $w->host());
		user_error('test error');
	}

}
