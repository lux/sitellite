<?php

// BEGIN KEEPOUT CHECKING
// Add these lines to the very top of any file you don't want people to
// be able to access directly.
if (! defined ('SAF_VERSION')) {
  header ('HTTP/1.1 404 Not Found');
  echo "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">\n"
		. "<html><head>\n<title>404 Not Found</title>\n</head><body>\n<h1>Not Found</h1>\n"
		. "The requested URL " . $PHP_SELF . " was not found on this server.<p>\n<hr>\n"
		. $_SERVER['SERVER_SIGNATURE'] . "</body></html>";
  exit;
}
// END KEEPOUT CHECKING

global $session, $site;

loader_import ('cms.Workflow.Lock');

lock_init ();

lock_clear ();

$user = session_username ();

$session->close ();

if (! empty ($parameters['goto'])) {
	if ($parameters['goto'] == 'cms-app') {
		loader_import ('cms.Workflow');
		Workflow::trigger (
			'logout',
			array (
				'message' => 'User: ' . $user,
				'username' => $user,
			)
		);
	}
	header ('Location: ' . $site->url . '/index/' . $parameters['goto']);
} else {
	header ('Location: ' . $site->url);
}

exit;

?>