# silverstripe-bugzscoutlogwriter
A SilverStripe Log writer to send log events to a FogBugz instance using BugzScout.

# What's great about it
Collect all errors, warnings and notices via FogBugz/BugzScout! One feature I like is that any issues are collated in one single ticket based on the subject.

## What's BugzScout?
BugzScout is a simple API which allows you to send new bugs directly to FogBugz simply by submitting an HTTP POST request [more info](http://help.fogcreek.com/7566/bugzscout-for-automatic-crash-reporting)

## What's Fogbugz?
FogBugz is an integrated web-based project management system featuring bug/issue tracking. [more info](http://www.fogcreek.com/fogbugz/)

# Setup

Add the following code to your mysite/_config.php, according to your setup

    // Log writer
    $writer = new BugzScoutLogWriter(array(
	    'host' => 'http://your.fogbugz.url',
	    'scoutUserName' => 'BugzScout',
	    'scoutProject' => 'Project',
	    'scoutArea' => 'Area' ,
	    ));
    SS_Log::add_writer($writer, SS_Log::NOTICE, '<=');

/dev/build, then test by doing a /dev/tasks/BugzScoutLogWriterTestTask. You might want to include the code only when `Director::isLive()`
