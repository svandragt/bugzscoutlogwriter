# silverstripe-bugzscoutlogwriter
Log handler to send log events to FogBugz instance.

# Setup

Add the following code to your mysite/_config.php::

// Log writer
$writer = new BugzScoutLogWriter(array(
	'host' => 'http://fogbugz.instance.domain.name',
	'scoutUserName' => 'Username',
	'scoutProject' => 'Project',
	'scoutArea' => 'Area' ,
	));
SS_Log::add_writer($writer, SS_Log::NOTICE, '<=');

/dev/build, then test by doing a /dev/tasks/BugzScoutLogWriterTestTask