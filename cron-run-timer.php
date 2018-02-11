<?php

set_time_limit(0);
ini_set('memory_limit','1028M');

######################################################
#
# cron script to run other scripts on timer via curl
# - run this php script on a 5 minute cronjob
#
######################################################

// include functions
require('cron_functions.php');
// init class
$cron = new execute_cronjobs();



# Run 1-hour crons
	// sync orders, customers, invoices, payments, and inventory with QB
	$cron->exec("tasks-to-run/QuickBooks_sync.php","0");

# Run 30-minute crons
	// ship orders on marketplaces
	$cron->exec("tasks-to-run/amazon-api/ship_orders.php","30");
	$cron->exec("tasks-to-run/walmart-api/ship_orders.php","30");

# Run 20-minutes crons
	//update inventory on marketplaces
	$cron->exec("tasks-to-run/amazon-api/inventory_update.php","20");
	$cron->exec("tasks-to-run/walmart-api/inventory_update.php","20");

# Run 10-minute crons
	// import orders from marketplaces
	$cron->exec("tasks-to-run/walmart-api/order_import.php","10");
	$cron->exec("tasks-to-run/amazon-api/order_import.php","10");

# Run 5-minute crons
	// calendar tasks
	$cron->exec("tasks-to-run/do_calendar_events.php","5");
	// import PO shipments from fedex
	$cron->exec("tasks-to-run/fedex-api/import_shipments.php","5");
	// run auto ship
	$cron->exec("tasks-to-run/ship_all_orders.php","5");


?>