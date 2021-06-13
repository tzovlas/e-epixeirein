<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_Async_Request', false ) ) {
	include_once( dirname( WC_PLUGIN_FILE ) . '/includes/libraries/wp-async-request.php' );
}

if ( ! class_exists( 'WP_Background_Process', false ) ) {
	include_once( dirname( WC_PLUGIN_FILE ) . '/includes/libraries/wp-background-process.php' );
}

/**
 * WCCS_Background_Updater Class.
 */
class WCCS_Background_Updater extends WP_Background_Process {

	/**
	 * Initiate new background process.
	 */
	public function __construct() {
		// Uses unique prefix per blog so each blog has separate queue.
		$this->prefix = 'wp_' . get_current_blog_id();
		$this->action = 'wccs_updater';

		parent::__construct();
	}

	/**
	 * Dispatch updater.
	 *
	 * Updater will still run via cron job if this fails for any reason.
	 */
	public function dispatch() {
		$dispatched = parent::dispatch();
		$logger     = WCCS()->WCCS_Helpers->wc_get_logger();

		if ( is_wp_error( $dispatched ) ) {
			if ( WCCS()->WCCS_Helpers->wc_version_check() ) {
				$logger->error(
					sprintf( 'Unable to dispatch WooCommerce Conditions updater: %s', $dispatched->get_error_message() ),
					array( 'source' => 'wccs_db_updates' )
				);
			} else {
				$logger->add( 'wccs_db_updates', sprintf( 'Unable to dispatch WooCommerce Conditions updater: %s', $dispatched->get_error_message() ) );
			}
		}
	}

	/**
	 * Handle cron healthcheck
	 *
	 * Restart the background process if not already running
	 * and data exists in the queue.
	 */
	public function handle_cron_healthcheck() {
		if ( $this->is_process_running() ) {
			// Background process already running.
			return;
		}

		if ( $this->is_queue_empty() ) {
			// No data to process.
			$this->clear_scheduled_event();
			return;
		}

		$this->handle();
	}

	/**
	 * Schedule fallback event.
	 */
	protected function schedule_event() {
		if ( ! wp_next_scheduled( $this->cron_hook_identifier ) ) {
			wp_schedule_event( time() + 10, $this->cron_interval_identifier, $this->cron_hook_identifier );
		}
	}

	/**
	 * Is the updater running?
	 * @return boolean
	 */
	public function is_updating() {
		return false === $this->is_queue_empty();
	}

	/**
	 * Task
	 *
	 * Override this method to perform any actions required on each
	 * queue item. Return the modified item for further processing
	 * in the next pass through. Or, return false to remove the
	 * item from the queue.
	 *
	 * @param string $callback Update callback function
	 * @return mixed
	 */
	protected function task( $callback ) {
		if ( ! defined( 'WCCS_UPDATING' ) ) {
			define( 'WCCS_UPDATING', true );
		}

		$logger = WCCS()->WCCS_Helpers->wc_get_logger();

		if ( ! class_exists( 'WCCS_Updates' ) ) {
			include_once( dirname( __FILE__ ) . '/class-wccs-updates.php' );
		}

		if ( is_callable( $callback ) ) {
			if ( WCCS()->WCCS_Helpers->wc_version_check() ) {
				$logger->info( sprintf( 'Running %s callback', $callback ), array( 'source' => 'wccs_db_updates' ) );
			} else {
				$logger->add( 'wccs_db_updates', sprintf( 'Running %s callback', $callback ) );
			}
			call_user_func( $callback );
			if ( WCCS()->WCCS_Helpers->wc_version_check() ) {
				$logger->info( sprintf( 'Finished %s callback', $callback ), array( 'source' => 'wccs_db_updates' ) );
			} else {
				$logger->add( 'wccs_db_updates', sprintf( 'Finished %s callback', $callback ) );
			}
		} else {
			if ( WCCS()->WCCS_Helpers->wc_version_check() ) {
				$logger->notice( sprintf( 'Could not find %s callback', $callback ), array( 'source' => 'wccs_db_updates' ) );
			} else {
				$logger->add( 'wccs_db_updates', sprintf( 'Could not find %s callback', $callback ) );
			}
		}

		return false;
	}

	/**
	 * Complete
	 *
	 * Override if applicable, but ensure that the below actions are
	 * performed, or, call parent::complete().
	 */
	protected function complete() {
		$logger = WCCS()->WCCS_Helpers->wc_get_logger();
		if ( WCCS()->WCCS_Helpers->wc_version_check() ) {
			$logger->info( 'Data update complete', array( 'source' => 'wccs_db_updates' ) );
		} else {
			$logger->add( 'wccs_db_updates', 'Data update complete' );
		}
		WCCS_Activator::update_db_version();
		parent::complete();
	}

}
