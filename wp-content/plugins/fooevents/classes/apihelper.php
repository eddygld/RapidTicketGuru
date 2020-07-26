<?php

/**
 * Append additional data to output upon successful signin
 * @param type $output
 */
function fooevents_append_output_data($output) {
    //include config for plugin version
    require_once(WP_PLUGIN_DIR.'/fooevents/config.php');

    $tempConfig = new FooEvents_Config();

    $output['data']['plugin_version'] = (string)$tempConfig->pluginVersion; 

    // Get app settings
    $output['data']['app_title'] = (string)get_option('globalWooCommerceEventsAppTitle', '');
    $output['data']['app_logo'] = preg_replace_callback('/[^\x20-\x7f]/', function($match) {
        return urlencode($match[0]);
    }, (string)get_option('globalWooCommerceEventsAppLogo', ''));
    
    $output['data']['app_color'] = (string)get_option('globalWooCommerceEventsAppColor', '');
    $output['data']['app_text_color'] = (string)get_option('globalWooCommerceEventsAppTextColor', '');
    $output['data']['app_background_color'] = (string)get_option('globalWooCommerceEventsAppBackgroundColor', '');
    $output['data']['app_signin_text_color'] = (string)get_option('globalWooCommerceEventsAppSignInTextColor', '');
    $output['data']['event_override'] = (string)get_option('globalWooCommerceEventsEventOverride', '');
    $output['data']['event_override_plural'] = (string)get_option('globalWooCommerceEventsEventOverridePlural', '');
    $output['data']['attendee_override'] = (string)get_option('globalWooCommerceEventsAttendeeOverride', '');
    $output['data']['attendee_override_plural'] = (string)get_option('globalWooCommerceEventsAttendeeOverridePlural', '');
    $output['data']['day_override'] = (string)get_option('WooCommerceEventsDayOverride', '');
    $output['data']['day_override_plural'] = (string)get_option('WooCommerceEventsDayOverridePlural', '');
    $output['data']['hide_personal_info'] = (string)get_option('globalWooCommerceEventsAppHidePersonalInfo', '');
    $output['data']['gmt_offset'] = (string)get_option('gmt_offset');

    // Check if multiday event plugin is enabled
    if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
        require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
    }

    if (fooevents_check_plugin_active('fooevents_multi_day/fooevents-multi-day.php') || is_plugin_active_for_network('fooevents_multi_day/fooevents-multi-day.php')) {
        
        $output['data']['multiday_enabled'] = 'Yes';

    } else {
        
        $output['data']['multiday_enabled'] = 'No';
        
    }

    return $output;
}

/**
 * Get all events as an array
 * 
 * @return array eventsArray
 */

function getAllEvents($user = null) {

    $eventsArray = array();
    $args = array(
            'post_type' => 'product',
            'order' => 'ASC',
            'posts_per_page' => -1,
            'meta_query' => array(
                    array(
                            'key' => 'WooCommerceEventsEvent',
                            'value' => 'Event',
                            'compare' => '=',
                    ),
            ),
    );

    $appEvents = get_option('globalWooCommerceEventsAppEvents', 'all');

    if ( $appEvents != 'all' ) {
        if ( $appEvents === 'user' && $user != null ) {
            $args['author'] = $user->ID;
        } elseif ( $appEvents === 'id' ) {
            $appEventIDs = get_option('globalWooCommerceEventsAppEventIDs', array());
            
            if ( !empty($appEvents) ) {
                $args['post__in'] = $appEventIDs;
            }
        }
    }
    
    $query = new WP_Query($args);
    $events = $query->get_posts();

    foreach ( $events as &$event ) {
        
        $tempEvent = array();

        $event_meta = get_post_meta($event->ID);

        $tempEvent['WooCommerceEventsProductID'] = (string)$event->ID;
        $tempEvent['WooCommerceEventsName'] = (string)$event->post_title;
        $tempEvent['WooCommerceEventsDate'] = (string)$event_meta['WooCommerceEventsDate'][0];
        $tempEvent['WooCommerceEventsHour'] = (string)$event_meta['WooCommerceEventsHour'][0];
        $tempEvent['WooCommerceEventsMinutes'] = (string)$event_meta['WooCommerceEventsMinutes'][0];
        $tempEvent['WooCommerceEventsPeriod'] = (string)strtoupper(str_replace('.', '', $event_meta['WooCommerceEventsPeriod'][0]));
        $tempEvent['WooCommerceEventsHourEnd'] = (string)$event_meta['WooCommerceEventsHourEnd'][0];
        $tempEvent['WooCommerceEventsMinutesEnd'] = (string)$event_meta['WooCommerceEventsMinutesEnd'][0];
        $tempEvent['WooCommerceEventsEndPeriod'] = (string)strtoupper(str_replace('.', '', $event_meta['WooCommerceEventsEndPeriod'][0]));
        $tempEvent['WooCommerceEventsTimeZone'] = (string)$event_meta['WooCommerceEventsTimeZone'][0];

        // Start Date
        $tempEvent['WooCommerceEventsDateFull'] = $tempEvent['WooCommerceEventsHour'] . ':' . $tempEvent['WooCommerceEventsMinutes'] . ', ' . $tempEvent['WooCommerceEventsDate'];

        if ( $tempEvent['WooCommerceEventsTimeZone'] != '' ) {
            $tempEvent['WooCommerceEventsDateFull'] .= ', ' . $tempEvent['WooCommerceEventsTimeZone'];
        }

        $tempEvent['WooCommerceEventsDateTimestamp'] = (string)strtotime(convert_month_to_english($tempEvent['WooCommerceEventsDateFull']));

        $start_date = new DateTime("@".$tempEvent['WooCommerceEventsDateTimestamp']);

        if ( $tempEvent['WooCommerceEventsTimeZone'] != '' ) {
            $start_date_timezone = new DateTimeZone($tempEvent['WooCommerceEventsTimeZone']);
            $start_date->setTimezone($start_date_timezone);
        }

        $tempEvent['WooCommerceEventsDateDay'] = $start_date->format('d');
        $tempEvent['WooCommerceEventsDateMonth'] = date_i18n('M', $tempEvent['WooCommerceEventsDateTimestamp']);
        $tempEvent['WooCommerceEventsDateYear'] = $start_date->format('Y');
        
        $tempEvent['WooCommerceEventsNumDays'] = (string)$event_meta['WooCommerceEventsNumDays'][0];

        if ( (int)$tempEvent['WooCommerceEventsNumDays'] > 1 ) {
            $tempEvent['WooCommerceEventsEndDate'] = (string)$event_meta['WooCommerceEventsEndDate'][0];
            $tempEvent['WooCommerceEventsSelectDate'] = get_post_meta($event->ID, 'WooCommerceEventsSelectDate', true);

            if ( !empty($tempEvent['WooCommerceEventsSelectDate']) ) {
                
                if ( $tempEvent['WooCommerceEventsSelectDate'][0] != '' ) {
                    $tempEvent['WooCommerceEventsDate'] = $tempEvent['WooCommerceEventsSelectDate'][0];
                }

                if ( end($tempEvent['WooCommerceEventsSelectDate']) != '' ) {
                    $tempEvent['WooCommerceEventsEndDate'] = end($tempEvent['WooCommerceEventsSelectDate']);
                }
            }

            // End Date
            $tempEvent['WooCommerceEventsEndDateFull'] = $tempEvent['WooCommerceEventsHour'] . ':' . $tempEvent['WooCommerceEventsMinutes'] . ', ' . $tempEvent['WooCommerceEventsEndDate'];

            if ( $tempEvent['WooCommerceEventsTimeZone'] != '' ) {
                $tempEvent['WooCommerceEventsEndDateFull'] .= ', ' . $tempEvent['WooCommerceEventsTimeZone'];
            }

            $tempEvent['WooCommerceEventsEndDateTimestamp'] = strtotime(convert_month_to_english($tempEvent['WooCommerceEventsEndDateFull']));

            $end_date = new DateTime("@".$tempEvent['WooCommerceEventsEndDateTimestamp']);

            if ( $tempEvent['WooCommerceEventsTimeZone'] != '' ) {
                $end_date_timezone = new DateTimeZone($tempEvent['WooCommerceEventsTimeZone']);
                $end_date->setTimezone($end_date_timezone);
            }

            $tempEvent['WooCommerceEventsEndDateDay'] = $end_date->format('d');
            $tempEvent['WooCommerceEventsEndDateMonth'] = date_i18n('M', $tempEvent['WooCommerceEventsEndDateTimestamp']);
            $tempEvent['WooCommerceEventsEndDateYear'] = $end_date->format('Y');
        }

        $tempEvent['WooCommerceEventsTicketLogo'] = (string)$event_meta['WooCommerceEventsTicketLogo'][0];
        $tempEvent['WooCommerceEventsTicketHeaderImage'] = (string)$event_meta['WooCommerceEventsTicketHeaderImage'][0];
        $tempEvent['WooCommerceEventsLocation'] = (string)$event_meta['WooCommerceEventsLocation'][0];
        $tempEvent['WooCommerceEventsSupportContact'] = (string)$event_meta['WooCommerceEventsSupportContact'][0];
        $tempEvent['WooCommerceEventsEmail'] = (string)$event_meta['WooCommerceEventsEmail'][0];
        $tempEvent['WooCommerceEventsGPS'] = (string)$event_meta['WooCommerceEventsGPS'][0];
        $tempEvent['WooCommerceEventsGoogleMaps'] = (string)$event_meta['WooCommerceEventsGoogleMaps'][0];
        $tempEvent['WooCommerceEventsDirections'] = (string)$event_meta['WooCommerceEventsDirections'][0];
        $tempEvent['WooCommerceEventsBackgroundColor'] = (string)$event_meta['WooCommerceEventsBackgroundColor'][0];
        $tempEvent['WooCommerceEventsTextColor'] = (string)$event_meta['WooCommerceEventsTextColor'][0];

        $attendeeTerm = (string)$event_meta['WooCommerceEventsAttendeeOverride'][0];

        if(empty($attendeeTerm)) {

            $attendeeTerm = (string)get_option('globalWooCommerceEventsAttendeeOverride', true);

        }

        if(!empty($attendeeTerm) && $attendeeTerm != 1) {

            $tempEvent['WooCommerceEventsAttendeeOverride'] = $attendeeTerm;

        }

        $attendeeTermPlural = (string)$event_meta['WooCommerceEventsAttendeeOverridePlural'][0];

        if(empty($attendeeTermPlural)) {

            $attendeeTermPlural = (string)get_option('globalWooCommerceEventsAttendeeOverridePlural', true);

        }

        if(!empty($attendeeTermPlural) && $attendeeTermPlural != 1) {

            $tempEvent['WooCommerceEventsAttendeeOverridePlural'] = $attendeeTermPlural;

        }

        $dayTerm = (string)$event_meta['WooCommerceEventsDayOverride'][0];

        if(empty($dayTerm)) {

            $dayTerm = (string)get_option('WooCommerceEventsDayOverride', true);

        }

        if(!empty($dayTerm) && $dayTerm != 1) {

            $tempEvent['WooCommerceEventsDayOverride'] = $dayTerm;

        }

        $dayTermPlural = (string)$event_meta['WooCommerceEventsDayOverridePlural'][0];

        if(empty($dayTermPlural)) {

            $dayTermPlural = (string)get_option('WooCommerceEventsDayOverridePlural', true);

        }

        if(!empty($dayTermPlural) && $dayTermPlural != 1) {

            $tempEvent['WooCommerceEventsDayOverridePlural'] = $dayTermPlural;

        }

        $eventsArray[] = $tempEvent;

        unset($tempEvent);

    }

    return $eventsArray;

}

/**
 * Get a single ticket's data
 * 
 * @param string $ticket_id
 * @return array ticketArray
 */
function getTicketData($ticket_id) {

    $tempTicket = array();

    $order_id = get_post_meta($ticket_id, 'WooCommerceEventsOrderID', true);

    try {

        $order = new WC_Order( $order_id );

        $tempTicket['customerFirstName'] = (string)$order->get_billing_first_name();
        $tempTicket['customerLastName'] = (string)$order->get_billing_last_name();
        $tempTicket['customerEmail'] = (string)$order->get_billing_email();
        $tempTicket['customerPhone'] = (string)$order->get_billing_phone();

        if ( trim($tempTicket['customerFirstName']) == "" ) {
            $tempTicket['customerFirstName'] = get_post_meta($ticket_id, 'WooCommerceEventsPurchaserFirstName', true);
            $tempTicket['customerLastName'] = get_post_meta($ticket_id, 'WooCommerceEventsPurchaserLastName', true);
            $tempTicket['customerEmail'] = get_post_meta($ticket_id, 'WooCommerceEventsPurchaserEmail', true);
        }

        $tempTicket['WooCommerceEventsAttendeeName'] = (string)get_post_meta($ticket_id, 'WooCommerceEventsAttendeeName', true);
        $tempTicket['WooCommerceEventsAttendeeLastName'] = (string)get_post_meta($ticket_id, 'WooCommerceEventsAttendeeLastName', true);
        $tempTicket['WooCommerceEventsAttendeeEmail'] = (string)get_post_meta($ticket_id, 'WooCommerceEventsAttendeeEmail', true);           
        $tempTicket['WooCommerceEventsAttendeeTelephone'] = (string)get_post_meta($ticket_id, 'WooCommerceEventsAttendeeTelephone', true);           
        $tempTicket['WooCommerceEventsAttendeeCompany'] = (string)get_post_meta($ticket_id, 'WooCommerceEventsAttendeeCompany', true);            
        $tempTicket['WooCommerceEventsAttendeeDesignation'] = (string)get_post_meta($ticket_id, 'WooCommerceEventsAttendeeDesignation', true);
        $tempTicket['WooCommerceEventsTicketID'] = (string)get_post_meta($ticket_id, 'WooCommerceEventsTicketID', true);
        $tempTicket['WooCommerceEventsStatus'] = (string)get_post_meta($ticket_id, 'WooCommerceEventsStatus', true); 
        $tempTicket['WooCommerceEventsMultidayStatus'] = (string)get_post_meta($ticket_id, 'WooCommerceEventsMultidayStatus', true);
        $tempTicket['WooCommerceEventsTicketType'] = (string)get_post_meta($ticket_id, 'WooCommerceEventsTicketType', true);
        $tempTicket['WooCommerceEventsVariationID'] = (string)get_post_meta($ticket_id, 'WooCommerceEventsVariationID', true);
        $tempTicket['WooCommerceEventsProductID'] = (string)get_post_meta($ticket_id, 'WooCommerceEventsProductID', true);
        $tempTicket['WooCommerceEventsNumDays'] = (string)get_post_meta($tempTicket['WooCommerceEventsProductID'], "WooCommerceEventsNumDays", true);
        $tempTicket['WooCommerceEventsOrderID'] = (string)get_post_meta($ticket_id, 'WooCommerceEventsOrderID', true);
        $tempTicket['WooCommerceEventsTicketPrice'] = (string)get_post_meta($ticket_id, 'WooCommerceEventsPrice', true);
        $tempTicket['WooCommerceEventsTicketPriceText'] = (string)html_entity_decode(strip_tags(get_post_meta($ticket_id, 'WooCommerceEventsPrice', true)), ENT_HTML5, "UTF-8");

        $WooCommerceEventsVariations = get_post_meta($ticket_id, 'WooCommerceEventsVariations', true);

        $WooCommerceEventsVariationsOutput = array();

        if ( !empty($WooCommerceEventsVariations) ) {

            foreach ( $WooCommerceEventsVariations as $variationName => $variationValue ) {

                $variationNameOutput = str_replace('attribute_', '', $variationName);
                $variationNameOutput = str_replace('pa_', '', $variationNameOutput);
                $variationNameOutput = str_replace('_', ' ', $variationNameOutput);
                $variationNameOutput = str_replace('-', ' ', $variationNameOutput);
                $variationNameOutput = str_replace('Pa_', '', $variationNameOutput);
                $variationNameOutput = ucwords($variationNameOutput);                    

                $variationValueOutput = str_replace('_', ' ', $variationValue);
                $variationValueOutput = str_replace('-', ' ', $variationValueOutput);
                $variationValueOutput = ucwords($variationValueOutput);

                $WooCommerceEventsVariationsOutput[$variationNameOutput] = (string)$variationValueOutput;

            }

        }

        $tempTicket['WooCommerceEventsVariations'] = $WooCommerceEventsVariationsOutput;

        $post_meta = get_post_meta($ticket_id); 

        $custom_values = array();

        foreach($post_meta as $key => $meta) {

            if (strpos($key, 'fooevents_custom_') === 0) {

                $custom_values[$key] = $meta[0];

            }

        }

        $custom_values_output = array();
        foreach($custom_values as $key => $value) {

            $custom_values_output[fooevents_output_custom_field_name($key)] = $value;

        }

        $tempTicket['WooCommerceEventsCustomAttendeeFields'] = $custom_values_output;

        return $tempTicket;

    }

    catch ( Exception $e ) {

        return array();

    }

}

/**
 * Get all tickets for an event as an array
 * 
 * @param string $eventID
 * @return array ticketsArray
 */

function getEventTickets($eventID) {

    global $woocommerce;
    $ticketsArray = array();
    $ticketStatusOptions = array();
    
    $eventID = sanitize_text_field($eventID);
    
    $globalWooCommerceHideUnpaidTicketsApp = get_option('globalWooCommerceHideUnpaidTicketsApp', true);

    if ( $globalWooCommerceHideUnpaidTicketsApp == 'yes' ) {

        $ticketStatusOptions = array('key' => 'WooCommerceEventsStatus', 'compare' => '!=', 'value' => 'Unpaid');

    }

    $events_query = new WP_Query( array('post_type' => array('event_magic_tickets'), 'posts_per_page' => -1, 'fields' => 'ids', 'meta_query' => array( array( 'key' => 'WooCommerceEventsProductID', 'value' => $eventID ), $ticketStatusOptions )) );
    $ticket_ids = $events_query->get_posts();

    foreach ( $ticket_ids as $ticket_id ) {

        $tempTicket = getTicketData($ticket_id);

        if ( !empty($tempTicket) ) {

            $ticketsArray[] = $tempTicket;

        }

        unset($tempTicket);

    }

    return $ticketsArray;

}

/**
 * Get all updated tickets for an event as an array
 * 
 * @param string $eventID
 * @param int $since
 * @return array ticketsArray
 */

function getEventUpdatedTickets($eventID, $since) {

    global $woocommerce;
    global $wpdb;

    $table_name = $wpdb->prefix . 'fooevents_check_in';
    $postmeta_table_name = $wpdb->prefix . 'postmeta';
    
    $ticketsArray = array();
    $ticketStatusOptions = array();
    
    $eventID = sanitize_text_field($eventID);
    $since = sanitize_text_field($since);

    $tickets = $wpdb->get_results("
        SELECT * FROM ".$table_name."
        LEFT JOIN ".$postmeta_table_name." ON
            ".$table_name.".tid = ".$postmeta_table_name.".post_id AND
            ".$postmeta_table_name.".meta_key = 'WooCommerceEventsTicketID'
        WHERE
            eid = ".$eventID." AND
            checkin >= ".$since."
    ");

    foreach ( $tickets as $ticket ) {

        $ticketsArray[] = array(
            'WooCommerceEventsTicketID' => $ticket->meta_value,
            'Day' => (string)$ticket->day
        );

    }

    return $ticketsArray;

}

/**
 * Get a single ticket if it exists
 * 
 * @param string $ticketID
 * @return array ticket
 */

function getSingleTicket($ticketID) {

    $ticketID = sanitize_text_field($ticketID);

    $ticket_query = new WP_Query( array('post_type' => array('event_magic_tickets'), 'meta_query' => array( array( 'key' => 'WooCommerceEventsTicketID', 'value' => $ticketID ) )) );

    $ticket_posts = $ticket_query->get_posts();

    $output = array();

    if ( !empty($ticket_posts) ) {

        $ticket_post = $ticket_posts[0];

        $tempTicket = getTicketData($ticket_post->ID);

        if ( !empty($tempTicket) ) {

            $output['data'] = $tempTicket;

        } else {

            $output['status'] = 'error';

        }

    } else {

        $output['status'] = 'error';

    }

    return $output;
}

/**
 * Update ticket ID with the provided status
 * @param type $ticketID
 * @param type $status
 */
function updateTicketStatus($ticketID, $status) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'fooevents_check_in';
    
    $events_query = new WP_Query( array('post_type' => array('event_magic_tickets'), 'meta_query' => array( array( 'key' => 'WooCommerceEventsTicketID', 'value' => $ticketID ) )) );
    $ticket = $events_query->get_posts();
    $ticket = $ticket[0];

    $eventID = get_post_meta($ticket->ID, 'WooCommerceEventsProductID', true);
    
    $timestamp = current_time('timestamp');

    if(!empty($status)) {

        update_post_meta( $ticket->ID, 'WooCommerceEventsStatus', strip_tags( $status ));

        // Check if multiday event plugin is enabled
        if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
            require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
        }
        
        if (fooevents_check_plugin_active('fooevents_multi_day/fooevents-multi-day.php') || is_plugin_active_for_network('fooevents_multi_day/fooevents-multi-day.php')) {
            
            $WooCommerceEventsProductID = get_post_meta($ticket->ID, "WooCommerceEventsProductID", true);
            $WooCommerceEventsNumDays = (int)get_post_meta($WooCommerceEventsProductID, "WooCommerceEventsNumDays", true);
            
            if ( $WooCommerceEventsNumDays > 1 )
            {
                $WooCommerceEventsMultidayStatus = array();
                
                for ( $day = 1; $day <= $WooCommerceEventsNumDays; $day++ ) {
                    $WooCommerceEventsMultidayStatus[$day] = strip_tags( $status );

                    if($status == 'Checked In') {

                        $wpdb->insert($table_name, array(
                            'tid' => $ticket->ID,
                            'eid' => $eventID,
                            'day' => $day,
                            'checkin' => $timestamp
                        ));
        
                    }
                }
                
                $WooCommerceEventsMultidayStatus = json_encode($WooCommerceEventsMultidayStatus);
                
                update_post_meta($ticket->ID, 'WooCommerceEventsMultidayStatus', strip_tags($WooCommerceEventsMultidayStatus));
            }
            
        } else {
            
            if($status == 'Checked In') {

                $wpdb->insert($table_name, array(
                    'tid' => $ticket->ID,
                    'eid' => $eventID,
                    'day' => 1,
                    'checkin' => $timestamp
                ));

            }
            
        }
        
        return 'Status updated';

    } else {

        return 'Status is required';

    }
}

/**
 * Update multiple ticket IDs with the provided statuses
 * @param type $ticketsStatus
 */
function updateTicketMultipleStatus($ticketsStatus) {
    $output = array();

    $ticketsStatus = json_decode($ticketsStatus, true);

    if(!empty($ticketsStatus)) {

        foreach($ticketsStatus as $tempTicketID => $status) {

            if ( strpos($tempTicketID, "_") !== false )
            {
                $tempTicketArray = explode("_", $tempTicketID);
                
                $ticketID = $tempTicketArray[0];
                $day = $tempTicketArray[1];
                
                $output['message'][$ticketID] = updateTicketMultidayStatus($ticketID, $status, $day);
            }
            else
            {
                $output['message'][$tempTicketID] = updateTicketStatus($tempTicketID, strip_tags( $status ));
            }
        }

    } else {

        $output['message'] = 'Status is required';

    }

    return $output;
}

/**
 * Update ticket ID status for a specified day in a multiday event
 * @param type $ticketID
 * @param type $status
 * @param type $day
 */
function updateTicketMultidayStatus($ticketID, $status, $day)
{
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'fooevents_check_in';
    
    $ticketID = sanitize_text_field($ticketID);
    $status = sanitize_text_field($status);
    $day = sanitize_text_field($day);
    
    $events_query = new WP_Query( array('post_type' => array('event_magic_tickets'), 'meta_query' => array( array( 'key' => 'WooCommerceEventsTicketID', 'value' => $ticketID ) )) );
    $ticket = $events_query->get_posts();

    if(!empty($ticket)) {
        
        $ticket = $ticket[0];
        $eventID = get_post_meta($ticket->ID, 'WooCommerceEventsProductID', true);
        
        $timestamp = current_time('timestamp');
        if($status == 'Checked In') {

            $wpdb->insert($table_name, array(
                'tid' => $ticket->ID,
                'eid' => $eventID,
                'day' => $day,
                'checkin' => $timestamp
            ));

        }
        
        $WooCommerceEventsMultidayStatus = get_post_meta($ticket->ID, "WooCommerceEventsMultidayStatus", true);
        $WooCommerceEventsMultidayStatus = json_decode($WooCommerceEventsMultidayStatus, true);
        
        $WooCommerceEventsMultidayStatus[$day] = $status;

        $all_days_same_status = true;

        foreach ( $WooCommerceEventsMultidayStatus as $day => $multiday_status ) {

            if ( $multiday_status != $status ) {

                $all_days_same_status = false;

                break;

            }

        }

        $WooCommerceEventsMultidayStatus = json_encode($WooCommerceEventsMultidayStatus);
        
        update_post_meta($ticket->ID, 'WooCommerceEventsMultidayStatus', strip_tags($WooCommerceEventsMultidayStatus));

        if ( $all_days_same_status ) {

            update_post_meta($ticket->ID, 'WooCommerceEventsStatus', strip_tags($status));

            if($status == 'Checked In') {

                $wpdb->insert($table_name, array(
                    'tid' => $ticket->ID,
                    'eid' => $eventID,
                    'day' => 1,
                    'checkin' => $timestamp
                ));

            }
            
        } else {

            update_post_meta($ticket->ID, 'WooCommerceEventsStatus', 'Not Checked In');

        }
        
        return 'Status updated';
    }
    else
    {
        return 'Status not updated';
    }
}

/**
 * Output the name of a custom field
 * @param type $field_name
 */
function fooevents_output_custom_field_name($field_name) {

    $field_name = str_replace('fooevents_custom_', "", $field_name);
    $field_name = str_replace('_', " ", $field_name);
    $field_name = ucwords($field_name);

    return $field_name;

}

/**
 * Array of month names for translation to English
 * 
 * @param string $event_date
 * @return string
 */
function convert_month_to_english($event_date) {
    
    $months = array(
        // French
        'janvier' => 'January',
        'février' => 'February',
        'mars' => 'March',
        'avril' => 'April',
        'mai' => 'May',
        'juin' => 'June',
        'juillet' => 'July',
        'aout' => 'August',
        'septembre' => 'September',
        'octobre' => 'October',

        // German
        'Januar' => 'January',
        'Februar' => 'February',
        'März' => 'March',
        'Mai' => 'May',
        'Juni' => 'June',
        'Juli' => 'July',
        'Oktober' => 'October',
        'Dezember' => 'December',

        // Spanish
        'enero' => 'January',
        'febrero' => 'February',
        'marzo' => 'March',
        'abril' => 'April',
        'mayo' => 'May',
        'junio' => 'June',
        'julio' => 'July',
        'agosto' => 'August',
        'septiembre' => 'September',
        'setiembre' => 'September',
        'octubre' => 'October',
        'noviembre' => 'November',
        'diciembre' => 'December',
        'novembre' => 'November',
        'décembre' => 'December',

        // Dutch
        'januari' => 'January',
        'februari' => 'February',
        'maart' => 'March',
        'april' => 'April',
        'mei' => 'May',
        'juni' => 'June',
        'juli' => 'July',
        'augustus' => 'August',
        'september' => 'September',
        'oktober' => 'October',
        'november' => 'November',
        'december' => 'December',

        // Italian
        'Gennaio' => 'January',
        'Febbraio' => 'February',
        'Marzo' => 'March',
        'Aprile' => 'April',
        'Maggio' => 'May',
        'Giugno' => 'June',
        'Luglio' => 'July',
        'Agosto' => 'August',
        'Settembre' => 'September',
        'Ottobre' => 'October',
        'Novembre' => 'November',
        'Dicembre' => 'December',

        // Polish
        'Styczeń' => 'January',
        'Luty' => 'February',
        'Marzec' => 'March',
        'Kwiecień' => 'April',
        'Maj' => 'May',
        'Czerwiec' => 'June',
        'Lipiec' => 'July',
        'Sierpień' => 'August',
        'Wrzesień' => 'September',
        'Październik' => 'October',
        'Listopad' => 'November',
        'Grudzień' => 'December',

        // Afrikaans
        'Januarie' => 'January',
        'Februarie' => 'February',
        'Maart' => 'March',
        'Mei' => 'May',
        'Junie' => 'June',
        'Julie' => 'July',
        'Augustus' => 'August',
        'Oktober' => 'October',
        'Desember' => 'December',
    );
    
    $pattern = array_keys($months);
    $replacement = array_values($months);

    foreach ($pattern as $key => $value) {
        
        $pattern[$key] = '/\b'.$value.'\b/i';
        
    }
    
    return preg_replace($pattern, $replacement, $event_date);
    
}