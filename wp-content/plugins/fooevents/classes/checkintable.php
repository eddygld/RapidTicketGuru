<?php if(!defined('ABSPATH')) exit;

// WP_List_Table is not loaded automatically so we need to load it in our application
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Create a new table class that will extend the WP_List_Table
 */
class Check_in_List_Table extends WP_List_Table
{
 
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items()
    {
        
        global $wpdb;

        $paged = $this->get_pagenum();;
        $perPage = 20;
        
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $data = $this->table_data($perPage, $paged);
        
        $totalItems = $this->table_data('', '', true);
        
        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
        
    }
    
    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name )
    {
     
        $WooCommerceEventsMultidayCheckInTimes = '';
        
        if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
            require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
        }
        
        if (($this->is_plugin_active('fooevents_multi_day/fooevents-multi-day.php') || is_plugin_active_for_network('fooevents_multi_day/fooevents-multi-day.php')) && $WooCommerceEventsNumDays > 1) {
            
            $Fooevents_Multiday_Events = new Fooevents_Multiday_Events();
            $WooCommerceEventsMultidayCheckInTimes = $Fooevents_Multiday_Events->display_multiday_check_in_times($post->ID);
            
        }
        
        switch($column_name) {
            
            case 'ticketid':
                return '<a href="'.get_admin_url().'post.php?post='.$item['ID'].'&action=edit">#'.$item['ticketid']."</a>";
            case 'time':
                echo empty($WooCommerceEventsMultidayCheckInTimes) ? $item['time'] : $WooCommerceEventsMultidayCheckInTimes;
            case 'attendee':
                return $item['attendee'];
            case 'day':
                return $item['day'];
            case 'purchaser':
                return '<a href="'.get_admin_url().'user-edit.php?user_id='.$item['customerid'].'">'.$item['purchaser']."</a>";    

            default:
                return print_r( $item, true ) ;
            
        }
        
    }  
    
    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        
        $columns = array(
            'ticketid'      => 'Ticket ID',
            'purchaser'     => 'Purchaser Name',
            'attendee'      => 'Attendee Name',
            'day'           => 'Day',
            'time'          => 'Check-in Time',
        );
        
        return $columns;
        
    }
    
    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        
        $sortable_columns = array(
            //'id' => array('id', true),
            //'event_name' => array('event_name', true),
        );
        
        return $sortable_columns;
        
    }
    
    /**
     * Get the table data
     *
     * @param int $perPage
     * @param int $paged
     * @param bool $returnPostCount
     * @return Array
     */
    private function table_data($perPage, $paged, $returnPostCount = false)
    {
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'fooevents_check_in';
        
        wp_reset_postdata();
        
        $event = sanitize_text_field($_GET['event']);
        $dateTo = strtotime("tomorrow") - 1;

        $dateFrom = strtotime('-10 days');
        
        if(isset($_POST['WooCommerceEventsDateFrom'])) {
            
            $dateFrom = sanitize_text_field($_POST['WooCommerceEventsDateFrom']);
            $dateFrom = strtotime($dateFrom);
            
        }
        
        if(isset($_POST['WooCommerceEventsDateTo'])) {
            
            $dateTo = sanitize_text_field($_POST['WooCommerceEventsDateTo']);
            $dateTo = strtotime($dateTo);
            
        }

        if($returnPostCount) {
            
            $perPage = -1;
            
        }
      
        if($returnPostCount) {
            
            $tickets = $wpdb->get_results("
                SELECT * FROM ".$table_name."
                WHERE eid = ".$event."
            "); 
            
            return $wpdb->num_rows;
            
        }
        
        if($paged == 1) {
            
            $paged = 0;
            
        }
        
        $rows = $perPage * $paged;
        
        $tickets = $wpdb->get_results("
                    SELECT * FROM ".$table_name."
                    WHERE eid = ".$event."
                    ORDER BY checkin desc    
                    LIMIT ".$rows.", ".$perPage." 
                "); 
        
        $attendees = array();
        
        $x = 0;
        foreach($tickets as $ticket) {

            $WooCommerceEventsCheckInTimeFormatted = date_i18n(get_option('date_format') . ' ' . get_option('time_format') . ' (P)', $ticket->checkin);
            $WooCommerceEventsTicketID = get_post_meta($ticket->tid, 'WooCommerceEventsTicketID', true);
            $WooCommerceEventsAttendeeName = get_post_meta($ticket->tid, 'WooCommerceEventsAttendeeName', true);
            $WooCommerceEventsAttendeeLastName = get_post_meta($ticket->tid, 'WooCommerceEventsAttendeeLastName', true);
            $WooCommerceEventsPurchaserFirstName = get_post_meta($ticket->tid, 'WooCommerceEventsPurchaserFirstName', true);
            $WooCommerceEventsPurchaserLastName = get_post_meta($ticket->tid, 'WooCommerceEventsPurchaserLastName', true);
            $WooCommerceEventsPurchaserEmail = get_post_meta($ticket->tid, 'WooCommerceEventsPurchaserEmail', true);
            $customer_id = get_post_meta($ticket->tid, 'WooCommerceEventsCustomerID', true);
            
            $attendees[$x]['ID'] = $ticket->tid;
            $attendees[$x]['ticketid'] = $WooCommerceEventsTicketID;
            $attendees[$x]['time'] = $WooCommerceEventsCheckInTimeFormatted;
            $attendees[$x]['timestamp'] = $ticket->checkin;
            $attendees[$x]['attendee'] = $WooCommerceEventsAttendeeName." ".$WooCommerceEventsAttendeeLastName;
            $attendees[$x]['purchaser'] = $WooCommerceEventsPurchaserFirstName." ".$WooCommerceEventsPurchaserLastName." -(".$WooCommerceEventsPurchaserEmail.")";
            $attendees[$x]['customerid'] = $customer_id;
            $attendees[$x]['day'] = $ticket->day;
            
            $x++;
        }
        
        return $attendees;
        
    }
    
}