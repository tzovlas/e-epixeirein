<?php

ob_start();
get_search_form();
$widget = ob_get_clean();
$heading = false;
onestore_popup( 'off-canvas-search', $heading, $widget, 'top' );
