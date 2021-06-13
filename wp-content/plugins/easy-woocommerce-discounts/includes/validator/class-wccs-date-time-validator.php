<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WCCS_Date_Time_Validator {

	/**
	 * Checking is valid given date times.
	 *
	 * @param  array  $date_times
	 * @param  string $match_mode 'one' or 'all'
	 *
	 * @return boolean
	 */
	public function is_valid_date_times( array $date_times, $match_mode = 'one' ) {
		if ( empty( $date_times ) ) {
			return true;
		}

		foreach ( $date_times as $date_time ) {
			if ( 'one' === $match_mode && $this->is_valid( $date_time ) ) {
				return true;
			} elseif ( 'all' === $match_mode && ! $this->is_valid( $date_time ) ) {
				return false;
			}
		}

		return 'all' === $match_mode;
	}

	/**
	 * Is valid given date time.
	 *
	 * @param  array $date_time
	 *
	 * @return boolean
	 */
	public function is_valid( array $date_time ) {
		if ( empty( $date_time ) || empty( $date_time['type'] ) ) {
			return false;
		}

		if ( in_array( $date_time['type'], array( 'date', 'date_time' ) ) ) {
			if ( empty( $date_time['start']['time'] ) && empty( $date_time['end']['time'] ) ) {
				return false;
			}

			$format = 'date_time' === $date_time['type'] ? 'Y-m-d H:i' : 'Y-m-d';
			$now    = strtotime( @date( $format, current_time( 'timestamp' ) ) );

			if ( ! empty( $date_time['start']['time'] ) ) {
				$start_date = strtotime( @date( $format, strtotime( $date_time['start']['time'] ) ) );
				if ( false === $start_date || $now < $start_date ) {
					return false;
				}
			}

			if ( ! empty( $date_time['end']['time'] ) ) {
				$end_date = strtotime( @date( $format, strtotime( $date_time['end']['time'] ) ) );
				if ( false === $end_date || $now > $end_date ) {
					return false;
				}
			}

			return true;
		} elseif ( 'specific_date' === $date_time['type'] ) {
			if ( ! empty( $date_time['date']['time'] ) ) {
				$dates = array_map( 'trim', explode( ',', trim( $date_time['date']['time'], '[]' ) ) );
				if ( ! empty( $dates ) ) {
					$now = strtotime( @date( 'Y-m-d', current_time( 'timestamp' ) ) );
					foreach ( $dates as $date ) {
						$date = strtotime( trim( $date, '"' ) );
						if ( $now == $date ) {
							return true;
						}
					}
				}
			}
		} elseif ( 'time' === $date_time['type'] ) {
			if ( empty( $date_time['start_time'] ) || empty( $date_time['end_time'] ) ) {
				return false;
			}

			$now = strtotime( @date( 'H:i', current_time( 'timestamp' ) ) );

			if ( ! empty( $date_time['start_time'] ) ) {
				$start_date = strtotime( $date_time['start_time'] );
				if ( false === $start_date || $now < $start_date ) {
					return false;
				}
			}

			if ( ! empty( $date_time['end_time'] ) ) {
				$end_date = strtotime( $date_time['end_time'] );
				if ( false === $end_date || $now > $end_date ) {
					return false;
				}
			}

			return true;
		} elseif ( 'days' === $date_time['type'] ) {
			if ( ! empty( $date_time['days'] ) ) {
				$today = date( 'l', current_time( 'timestamp' ) );
				foreach ( $date_time['days'] as $day ) {
					if ( $today == $day ) {
						return true;
					}
				}
			}
		}

		return false;
	}

}
