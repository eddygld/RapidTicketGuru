<?php

class FooEvents_REST_API_Helper extends WP_REST_Controller {

    private $api_namespace;
    private $base;
    private $api_version;
    private $required_capability;
    
    public function __construct() {
        $this->api_namespace = 'fooevents/v';
        $this->api_version = '1';
        $this->required_capability = 'publish_event_magic_tickets';
        
        $this->init();
    }
    
    public function init() {
        add_action('rest_api_init', array($this, 'fooevents_register_rest_api_routes'));
    }
    
    /**
     * Register REST API endpoints with their corresponding callback functions
     */
    public function fooevents_register_rest_api_routes() {
        $namespace = $this->api_namespace . $this->api_version;
        
        $rest_api_endpoints = array(
            'login_status',

            'get_all_data',
            'get_list_of_events',
            'get_tickets_in_event',
            'get_updated_tickets_in_event',
            'get_single_ticket',
            
            'update_ticket_status',
            'update_ticket_status_m',
            'update_ticket_status_multiday'
        );

        foreach ( $rest_api_endpoints as $endpoint ) {
            register_rest_route($namespace, '/' . $endpoint, array(
                array('methods' => 'POST', 'callback' => array($this, 'fooevents_callback_' . $endpoint))
            ));
        }
    }

    /**
     * Test if is valid user with proper user role
     */
    public function fooevents_is_authorized_user($headers) {
        $creds = array();

        // Get username and password from the submitted headers.
        if ( array_key_exists('username', $headers) && array_key_exists('password', $headers) ) {
            $creds['user_login'] = $headers['username'][0];
            $creds['user_password'] =  $headers['password'][0];
            $creds['remember'] = false;
            
            $user = wp_signon($creds, false);
            
            if ( is_wp_error($user) ) {
                return array(
                    'message' => false
                );
            }
            
            wp_set_current_user($user->ID, $user->user_login);
            
            if ( !current_user_can($this->required_capability) ) {
                return array(
                    'message' => false,
                    'invalid_user' => "1"
                );
            }
            
            return $user;
        } else {
            return array(
                'message' => false
            );
        }
    }
    
    /**
     * Test login status
     */
    public function fooevents_callback_login_status(WP_REST_Request $request) {
        $authorize_result = $this->fooevents_is_authorized_user($request->get_headers());

        if ( $authorize_result && is_object($authorize_result) && is_a($authorize_result, 'WP_User') ) {
            $output = array(
                'message' => true,
                'data' => json_decode(json_encode($authorize_result->data), true)
            );

            $output = fooevents_append_output_data($output);

            $output = json_encode($output);

            echo $output;
        } else {
            echo json_encode($authorize_result);
        }

        exit();
    }
    
    /**
     * Fetch all data
     */
    public function fooevents_callback_get_all_data(WP_REST_Request $request) {
        $authorize_result = $this->fooevents_is_authorized_user($request->get_headers());

        if ( $authorize_result && is_object($authorize_result) && is_a($authorize_result, 'WP_User') ) {
            error_reporting(0);
            ini_set('display_errors', 0);

            set_time_limit(0);
            $memory_limit = ini_get('memory_limit');
            ini_set('memory_limit', '-1');

            $dataOutput = getAllEvents($authorize_result);

            foreach ( $dataOutput as &$event ) {

                $event['eventTickets'] = getEventTickets($event['WooCommerceEventsProductID']);

            }

            echo json_encode($dataOutput);

            ini_set('memory_limit', $memory_limit);
        } else {
            echo json_encode($authorize_result);
        }

        exit();
    }

    /**
     * Fetch list of all events
     */
    public function fooevents_callback_get_list_of_events(WP_REST_Request $request) {
        $authorize_result = $this->fooevents_is_authorized_user($request->get_headers());

        if ( $authorize_result && is_object($authorize_result) && is_a($authorize_result, 'WP_User') ) {
            error_reporting(0);
            ini_set('display_errors', 0);

            set_time_limit(0);
            $memory_limit = ini_get('memory_limit');
            ini_set('memory_limit', '-1');

            echo json_encode(getAllEvents($authorize_result));

            ini_set('memory_limit', $memory_limit);
        } else {
            echo json_encode($authorize_result);
        }

        exit();
    }
    
    /**
     * Fetch all tickets of selected event
     */
    public function fooevents_callback_get_tickets_in_event(WP_REST_Request $request) {
        $authorize_result = $this->fooevents_is_authorized_user($request->get_headers());

        if ( $authorize_result && is_object($authorize_result) && is_a($authorize_result, 'WP_User') ) {
            error_reporting(0);
            ini_set('display_errors', 0);

            set_time_limit(0);
            $memory_limit = ini_get('memory_limit');
            ini_set('memory_limit', '-1');

            $eventID = $request->get_param("param2");

            echo json_encode(getEventTickets($eventID));

            ini_set('memory_limit', $memory_limit);
        } else {
            echo json_encode($authorize_result);
        }

        exit();
    }

    /**
     * Fetch all updated tickets of selected event
     */
    public function fooevents_callback_get_updated_tickets_in_event(WP_REST_Request $request) {
        $authorize_result = $this->fooevents_is_authorized_user($request->get_headers());

        if ( $authorize_result && is_object($authorize_result) && is_a($authorize_result, 'WP_User') ) {
            error_reporting(0);
            ini_set('display_errors', 0);

            set_time_limit(0);
            $memory_limit = ini_get('memory_limit');
            ini_set('memory_limit', '-1');

            $eventID = $request->get_param("param2");
            $since = $request->get_param("param3");

            echo json_encode(getEventUpdatedTickets($eventID, $since));

            ini_set('memory_limit', $memory_limit);
        } else {
            echo json_encode($authorize_result);
        }

        exit();
    }

    /**
     * Fetch a single ticket if it exists
     */
    public function fooevents_callback_get_single_ticket(WP_REST_Request $request) {
        $authorize_result = $this->fooevents_is_authorized_user($request->get_headers());

        if ( $authorize_result && is_object($authorize_result) && is_a($authorize_result, 'WP_User') ) {
            error_reporting(0);
            ini_set('display_errors', 0);

            $ticketID = $request->get_param("param2");

            echo json_encode(getSingleTicket($ticketID));
        } else {
            echo json_encode($authorize_result);
        }

        exit();
    }
    
    /**
     * Update ticket status
     */
    public function fooevents_callback_update_ticket_status(WP_REST_Request $request) {
        $authorize_result = $this->fooevents_is_authorized_user($request->get_headers());

        if ( $authorize_result && is_object($authorize_result) && is_a($authorize_result, 'WP_User') ) {
            error_reporting(0);
            ini_set('display_errors', 0);

            $ticketID           = $request->get_param("param2");
            $status             = $request->get_param("param3");

            $output['message'] = updateTicketStatus($ticketID, $status);

            echo json_encode($output);
        } else {
            echo json_encode($authorize_result);
        }

        exit();
    }
    
    /**
     * Update multiple ticket statuses
     */
    public function fooevents_callback_update_ticket_status_m(WP_REST_Request $request) {
        $authorize_result = $this->fooevents_is_authorized_user($request->get_headers());

        if ( $authorize_result && is_object($authorize_result) && is_a($authorize_result, 'WP_User') ) {
            error_reporting(0);
            ini_set('display_errors', 0);

            set_time_limit(0);
            $memory_limit = ini_get('memory_limit');
            ini_set('memory_limit', '-1');

            $ticketsStatus = $request->get_param("param2");
            
            $output = updateTicketMultipleStatus($ticketsStatus);

            echo json_encode($output);

            ini_set('memory_limit', $memory_limit);
        } else {
            echo json_encode($authorize_result);
        }

        exit();
    }
    
    /**
     * Update multiday ticket status
     */
    public function fooevents_callback_update_ticket_status_multiday(WP_REST_Request $request) {
        $authorize_result = $this->fooevents_is_authorized_user($request->get_headers());

        if ( $authorize_result && is_object($authorize_result) && is_a($authorize_result, 'WP_User') ) {
            $ticketID = $request->get_param("param2");
            $status = $request->get_param("param3");
            $day = $request->get_param("param4");
            
            $output = array();
            
            if ( !empty($ticketID) && !empty($status) && !empty($day) ) {
                $output['message'] = updateTicketMultidayStatus($ticketID, $status, $day);
            } else {
                $output['message'] = 'All fields are required.';
            }
            
            echo json_encode($output);
        } else {
            echo json_encode($authorize_result);
        }

        exit();
    }
}
