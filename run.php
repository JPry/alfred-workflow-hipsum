<?php
/**
 * ${CARET}
 *
 * @package ${NAMESPACE}
 */

declare( strict_types=1 );

use JPry\AlfredWorkflow\Hipsum\Hipsum;

require_once __DIR__ . '/vendor/autoload.php';

function run( Hipsum $hipsum ) {
	echo $hipsum->get();
}

$hipsum = new Hipsum();
