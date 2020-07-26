<?php if(!defined('ABSPATH')) exit;

class FooEvents_XMLRPC_Helper {

    public $Config;

    public function __construct($Config) {

        $this->Config = $Config;
        $this->check_xmlrpc_enabled();

    }

    public function check_xmlrpc_enabled() {

        if(!$this->is_xmlrpc_enabled()) {

            $this->output_notices(array("XMLRPC is not enabled."));

        }

    }

    public function is_xmlrpc_enabled() {

        $returnBool = false; 
        $enabled = get_option('enable_xmlrpc');

        if($enabled) {

            $returnBool = true;

        } else {

            global $wp_version;
            if (version_compare($wp_version, '3.5', '>=')) {

                $returnBool = true; 

            }

            else {

                $returnBool = false;

            }  

        }

        return $returnBool;

    }

    private function output_notices($notices) {

        foreach ($notices as $notice) {

            echo "<div class='updated'><p>$notice</p></div>";

        }

    }

}

/**
 * Tests whether or not XMLRPC is accessible
 * 
 * @global object $wp_xmlrpc_server
 * @param type $args
 */
function fooevents_test_access($args)
{
    echo 'FooEvents success';
    
    exit();
}

/**
 * Checks a users login details
 * 
 * @global object $wp_xmlrpc_server
 * @param type $args
 */
function fooevents_login_status($args) {

    error_reporting(0);
    ini_set('display_errors', 0);

    global $wp_xmlrpc_server;
    $wp_xmlrpc_server->escape( $args );

    $username           = $args[0];
    $password           = $args[1];
    $user               = '';

    if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) ) {

        $output['message'] = false;
        $output = json_encode($output);
        echo $output;
        exit();

    } else {

        $output['message'] = true;
        $output['data']    = json_decode(json_encode($user->data), true);

    }

    if(!fooevents_checkroles($user)) {
        $output['message'] = false;
        $output['invalid_user'] = "1";

        echo json_encode($output);

        exit();
    }
    
    $output = fooevents_append_output_data($output);

    $output = json_encode($output);
    echo $output;

    exit();

}


/**
 * Gets all data for all events for offline mode
 * 
 * @global object $wp_xmlrpc_server
 * @param array $args
 */

function fooevents_get_all_data($args) {

    error_reporting(0);
    ini_set('display_errors', 0);

    set_time_limit(0);
    $memory_limit = ini_get('memory_limit');
    ini_set('memory_limit', '-1');

    global $wp_xmlrpc_server;
    
    $wp_xmlrpc_server->escape( $args );

    $username = $args[0];
    $password = $args[1];

    if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) ) {

        return $wp_xmlrpc_server->error;
        exit();

    }

    if(!fooevents_checkroles($user)) {
        $output['message'] = false;
        $output['invalid_user'] = "1";

        echo json_encode($output);

        exit();
    }

    $dataOutput = getAllEvents($user);

    foreach ( $dataOutput as &$event ) {

        $event['eventTickets'] = getEventTickets($event['WooCommerceEventsProductID']);

    }

    echo json_encode($dataOutput);

    ini_set('memory_limit', $memory_limit);

    exit();

}

/**
 * Gets all events
 * 
 * @global object $wp_xmlrpc_server
 * @param array $args
 */

function fooevents_get_list_of_events($args) {

    error_reporting(0);
    ini_set('display_errors', 0);

    set_time_limit(0);
    $memory_limit = ini_get('memory_limit');
    ini_set('memory_limit', '-1');

    global $wp_xmlrpc_server;

    $wp_xmlrpc_server->escape( $args );

    $username = $args[0];
    $password = $args[1];

    if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) ) {

        return $wp_xmlrpc_server->error;
        exit();

    }

    if(!fooevents_checkroles($user)) {
        $output['message'] = false;
        $output['invalid_user'] = "1";

        echo json_encode($output);

        exit();
    }

    echo json_encode(getAllEvents($user));

    ini_set('memory_limit', $memory_limit);

    exit();

}

/**
 * Gets a list of tickets belonging to an event
 * 
 * @global object $wp_xmlrpc_server
 * @param array $args
 */
function fooevents_get_tickets_in_event($args) {
    
    error_reporting(0);
    ini_set('display_errors', 0);

    set_time_limit(0);
    $memory_limit = ini_get('memory_limit');
    ini_set('memory_limit', '-1');

    global $woocommerce;
    global $wp_xmlrpc_server;
    
    $wp_xmlrpc_server->escape( $args );

    $username   = $args[0];
    $password   = $args[1];
    $eventID    = $args[2];

    if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) ) {

        return $wp_xmlrpc_server->error;
        exit();

    }

    if(!fooevents_checkroles($user)) {
        $output['message'] = false;
        $output['invalid_user'] = "1";

        echo json_encode($output);

        exit();
    }

    echo json_encode(getEventTickets($eventID));

    ini_set('memory_limit', $memory_limit);
    exit();

}

/**
 * Gets a list of updated tickets belonging to an event
 * 
 * @global object $wp_xmlrpc_server
 * @param array $args
 */
function fooevents_get_updated_tickets_in_event($args) {
    
    error_reporting(0);
    ini_set('display_errors', 0);

    set_time_limit(0);
    $memory_limit = ini_get('memory_limit');
    ini_set('memory_limit', '-1');

    global $woocommerce;
    global $wp_xmlrpc_server;
    
    $wp_xmlrpc_server->escape( $args );

    $username   = $args[0];
    $password   = $args[1];
    $eventID    = $args[2];
    $since      = $args[3];

    if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) ) {

        return $wp_xmlrpc_server->error;
        exit();

    }

    if(!fooevents_checkroles($user)) {
        $output['message'] = false;
        $output['invalid_user'] = "1";

        echo json_encode($output);

        exit();
    }

    echo json_encode(getEventUpdatedTickets($eventID, $since));

    ini_set('memory_limit', $memory_limit);
    exit();

}

/**
 * Get a single ticket if it exists
 * 
 * @global object $wp_xmlrpc_server
 * @param array $args
 */
function fooevents_get_single_ticket($args) {
    
    error_reporting(0);
    ini_set('display_errors', 0);

    global $wp_xmlrpc_server;
    
    $wp_xmlrpc_server->escape( $args );

    $username   = $args[0];
    $password   = $args[1];
    $ticketID    = $args[2];

    if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) ) {

        return $wp_xmlrpc_server->error;
        exit();

    }

    if(!fooevents_checkroles($user)) {
        $output['message'] = false;
        $output['invalid_user'] = "1";

        echo json_encode($output);

        exit();
    }

    echo json_encode(getSingleTicket($ticketID));
    
    exit();

}

/**
 * Updates a tickets status
 * 
 */
function fooevents_update_ticket_status($args) {

    error_reporting(0);
    ini_set('display_errors', 0);

    global $wp_xmlrpc_server;
    $wp_xmlrpc_server->escape( $args );
    
    $username           = $args[0];
    $password           = $args[1];
    $ticketID           = $args[2];
    $status             = $args[3];

    if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) ) {

        return $wp_xmlrpc_server->error;
        exit();

    }

    $output = array();

    if(!fooevents_checkroles($user)) {
        $output['message'] = false;
        $output['invalid_user'] = "1";

        echo json_encode($output);

        exit();
    }

    $output['message'] = updateTicketStatus($ticketID, $status);

    echo json_encode($output);

    exit();
    
}

function fooevents_update_ticket_status_m($args) {

    error_reporting(0);
    ini_set('display_errors', 0);

    set_time_limit(0);
    $memory_limit = ini_get('memory_limit');
    ini_set('memory_limit', '-1');

    global $wp_xmlrpc_server;
    $wp_xmlrpc_server->escape( $args );

    $username           = $args[0];
    $password           = $args[1];
    $ticketsStatus      = stripslashes(($args[2]));
    
    $output = array();
    
    if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) ) {

        return $wp_xmlrpc_server->error;
        exit();

    }

    if(!fooevents_checkroles($user)) {
        $output['message'] = false;
        $output['invalid_user'] = "1";

        echo json_encode($output);

        exit();
    }

    $output = updateTicketMultipleStatus($ticketsStatus);

    echo json_encode($output);
    ini_set('memory_limit', $memory_limit);

    exit();

}

function fooevents_update_ticket_status_multiday($args) {

    global $wp_xmlrpc_server;
    $wp_xmlrpc_server->escape( $args );

    $username           = $args[0];
    $password           = $args[1];
    $ticketID           = $args[2];
    $status             = $args[3];
    $day                = $args[4];
    
    $output = array();
    if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) ) {

        return $wp_xmlrpc_server->error;
        exit();

    }

    if(!empty($ticketID) && !empty($status) && !empty($day)) {

        $output['message'] = updateTicketMultidayStatus($ticketID, $status, $day);

    } else {
        
        $output['message'] = 'All fields are required.';
        
    }
    
    echo json_encode($output);
    exit();
    
}

function fooevents_new_xmlrpc_methods( $methods ) {

    error_reporting(0);
    ini_set('display_errors', 0);

    $methods['fooevents.test_access'] = 'fooevents_test_access';
    $methods['fooevents.login_status'] = 'fooevents_login_status';

    $methods['fooevents.get_all_data'] = 'fooevents_get_all_data';
    $methods['fooevents.get_list_of_events'] = 'fooevents_get_list_of_events';
    $methods['fooevents.get_tickets_in_event'] = 'fooevents_get_tickets_in_event';
    $methods['fooevents.get_updated_tickets_in_event'] = 'fooevents_get_updated_tickets_in_event';
    $methods['fooevents.get_single_ticket'] = 'fooevents_get_single_ticket';

    $methods['fooevents.update_ticket_status'] = 'fooevents_update_ticket_status';
    $methods['fooevents.update_ticket_status_m'] = 'fooevents_update_ticket_status_m';
    $methods['fooevents.update_ticket_status_multiday'] = 'fooevents_update_ticket_status_multiday';

    return $methods;   

}

add_filter( 'xmlrpc_methods', 'fooevents_new_xmlrpc_methods');

function fooevents_checkroles($user) {

    if(user_can($user, 'publish_event_magic_tickets')) {

        return true;

    } else {
        
        return false;
        
    }

}
