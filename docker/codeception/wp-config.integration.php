<?php

global $wp_tests_options;

$wp_tests_options['installation_filters'] =  [
	'add' => [
		[ 'populate_options', '_mark_action_scheduler_migration_as_complete' ],
	]
];

/**
 * Marks Action Scheduler migration as complete.
 *
 * Helper function used to prevent database errors while WPLoader is installing WordPress to run the integration tests.
 */
function _mark_action_scheduler_migration_as_complete() {

	update_option( 'action_scheduler_migration_status', 'complete', true );
}
