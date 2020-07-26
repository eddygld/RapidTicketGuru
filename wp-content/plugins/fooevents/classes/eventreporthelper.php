<?php if(!defined('ABSPATH')) exit;

// WP_List_Table is not loaded automatically so we need to load it in our application
if(!class_exists('WP_List_Table')) {
    
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
    
}

class FooEvents_Event_Report_Helper extends WP_List_Table {
    
    public $Config;
    public $example_data;

    public function __construct($Config) {
        
        $this->Config = $Config;

        require_once($this->Config->classPath.'eventreporttable.php');
        require_once($this->Config->classPath.'checkintable.php');
        
        add_action('admin_menu',  array( $this, 'add_menu_item'));
        add_action('wp_ajax_fetch_tickets_sold', array($this, 'fetch_tickets_sold'));
        add_action('wp_ajax_fetch_tickets_revenue', array($this, 'fetch_tickets_revenue'));
        add_action('wp_ajax_fetch_revenue_formatted', array($this, 'fetch_revenue_formatted'));
        add_action('wp_ajax_fetch_check_ins', array($this, 'fetch_check_ins'));
        add_action('wp_ajax_fetch_check_ins_today', array($this, 'fetch_check_ins_today'));
        
    }
    
    /**
     * Add admin reports menu item
     * 
     */
    public function add_menu_item() {
        
        add_submenu_page('fooevents', __('Reports', 'woocommerce-events'), __('Reports', 'woocommerce-events'), 'edit_posts', 'fooevents-reports', array( $this, 'display_report_table_page'));
        add_submenu_page(NULL, __('Report', 'woocommerce-events'), 'Test', 'edit_posts', 'fooevents-event-report', array($this, 'display_report_page')); 
        
    }
    
    /**
     * Display ticket themes page
     * 
     */
    public function display_report_table_page() {

        $eventsListTable = new Events_List_Table();
        $eventsListTable->prepare_items();

        include($this->Config->templatePath.'eventreportslisting.php'); 
        
    }
    
    /**
     * Builds and displays the event report page
     * 
     */
    public function display_report_page() {
        
        $checkInListTable = new Check_in_List_Table();
        $checkInListTable->prepare_items();
        
        $id = sanitize_text_field($_GET['event']);
        $event = get_post($id);
        
        $dateFormat = get_option('date_format');
        
        if($dateFormat == 'd/m/Y') {
            
            $dateFormat = 'd-m-Y';
            
        }
        
        $todaysDate = date($dateFormat);
        $previousDate = date($dateFormat, strtotime('-10 days'));
        $previousMonth= date($dateFormat, strtotime('-30 days'));
        $previous90days= date($dateFormat, strtotime('-39 days'));
        $previousYear= date($dateFormat, strtotime('-365 days')); 
        $WooCommerceEventsDate = get_post_meta($id, 'WooCommerceEventsDate', true);
        $WooCommerceEventsLocation = get_post_meta($id, 'WooCommerceEventsLocation', true);
        
        if(isset($_POST['WooCommerceEventsDateFrom'])) {
            
            $previousDate = sanitize_text_field($_POST['WooCommerceEventsDateFrom']);
            
        }
        
        if(isset($_POST['WooCommerceEventsDateTo'])) {
            
            $todaysDate = sanitize_text_field($_POST['WooCommerceEventsDateTo']);
            
        }

        include($this->Config->templatePath.'eventreportpage.php'); 
        
    }
    
    /**
     * Fetches number check-in pre hour of today
     * 
     */
    public function fetch_check_ins_today() {
        
        $id = sanitize_text_field($_POST['eventID']);
        $dateFrom = sanitize_text_field($_POST['dateFrom']);
        $dateTo = sanitize_text_field($_POST['dateTo']);

        $requestedHours = $this->_fetch_all_hours_for_date($dateTo);
        $checkInsPerHour = array();
        
        foreach ($requestedHours as $hour) {
            
            $hourFormatted = date('H:00', $hour);
            $checkInsPerHour[$hourFormatted] = $this->_fetch_check_ins_on_hour($hour, $id);
            
        }
        
        echo json_encode($checkInsPerHour);
        
        exit();
        
    }
    
    /**
     * Fetches number check-ins per day between two dates
     * 
     */
    public function fetch_check_ins() {
        
        $id = sanitize_text_field($_POST['eventID']);
        $dateFrom = sanitize_text_field($_POST['dateFrom']);
        $dateTo = sanitize_text_field($_POST['dateTo']);
        
        $requestedDates = $this->_all_dates_between($dateFrom, $dateTo);
        $checkInsPerDay = array();
        
        foreach($requestedDates as $day) {
            
            $checkInsPerDay[$day] = $this->_fetch_check_ins_on_day($day, $id);
            
        }
        
        echo json_encode($checkInsPerDay);
        
        exit;
        
    }
    
    /**
     * Fetches number tickets sold per day between two dates
     * 
     */
    public function fetch_tickets_sold() {
        
        $id = sanitize_text_field($_POST['eventID']);
        $dateFrom = sanitize_text_field($_POST['dateFrom']);
        $dateTo = sanitize_text_field($_POST['dateTo']);
        
        $requestedDates = $this->_all_dates_between($dateFrom, $dateTo);
        $ticketsSoldPerDay = array();
        
        foreach($requestedDates as $day) {
            
            $ticketsSoldPerDay[$day] = $this->_fetch_tickets_sold_on_day($day, $id);
            
        }
        
        echo json_encode($ticketsSoldPerDay);
        
        exit;
    }
    
    /**
     * Fetches tickets revenue per day between two dates
     * 
     */
    public function fetch_tickets_revenue() {
        
        $id = sanitize_text_field($_POST['eventID']);
        $dateFrom = sanitize_text_field($_POST['dateFrom']);
        $dateTo = sanitize_text_field($_POST['dateTo']);
        
        $requestedDates = $this->_all_dates_between($dateFrom, $dateTo);
        $ticketsRevenuePerDay = array();
        
        foreach($requestedDates as $day) {
            
            $ticketsRevenuePerDay[$day] = $this->_fetch_tickets_revenue_on_day($day, $id);
            
        }
        
        echo json_encode($ticketsRevenuePerDay);
        
        exit();
    }
    
    /**
     * Formats revenue with store currency
     * 
     */
    public function fetch_revenue_formatted() {
        
        $total_revenue = sanitize_text_field($_POST['total_revenue']);
        echo wc_price($total_revenue);
        
        exit();
        
    }
    
    /**
     * Fetches the dates between two given days
     * 
     * @param string $previousDate
     * @param string $todaysDate
     * @param string $step
     * @param string $output_format
     * @return array
     */
    private function _all_dates_between($previousDate, $todaysDate, $step = '+1 day', $output_format = 'Y-m-d') {
        
        $dates = array();
        $current = strtotime($previousDate);
        $last = strtotime($todaysDate);

        while($current <= $last) {

            $dates[] = date($output_format, $current);
            $current = strtotime($step, $current);
            
        }

        return $dates;

    }
    
    /**
     * Fetches tickets sold on a particular day
     * 
     * @global object $wpdb
     * @param string $day
     * @param int $id
     * @return int
     */
    private function _fetch_tickets_sold_on_day($day, $ID) {

        /*$sql = "
        SELECT COUNT(*) AS sale_count
        FROM {$wpdb->prefix}woocommerce_order_items AS order_items
        INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS order_meta ON order_items.order_item_id = order_meta.order_item_id
        INNER JOIN {$wpdb->posts} AS posts ON order_meta.meta_value = posts.ID
        WHERE order_items.order_item_type = 'line_item'
        AND order_meta.meta_key = '_product_id'
        AND order_meta.meta_value = %d
        AND order_items.order_id IN (
            SELECT posts.ID AS post_id
            FROM {$wpdb->posts} AS posts
            WHERE posts.post_type = 'shop_order'
                AND posts.post_status IN ('wc-completed')
                AND DATE(posts.post_date) = %s
        )
        GROUP BY order_meta.meta_value";

        $num = $wpdb->get_var($wpdb->prepare($sql, $id, $day));

        if(empty($num)) {

            $num = 0;

        }

        return $num;*/
        
       $args = [
            'post_type' => ['event_magic_tickets'],
            'posts_per_page' => -1,
            /*'date_query' => [
                'column' => 'post_date',
                'after' => $day,
                'before' => $day,
                'inclusive' => true,
              ],*/
            'meta_query'     => [
                [
                    'key'      => 'WooCommerceEventsProductID',
                    'value'    => $ID,
                    'compare'  => '='
                ],
            ],
        ];

        $events_query = new WP_Query(array('post_type' => array('event_magic_tickets'), 'posts_per_page' => -1, 'date_query'=> array('column' => 'post_date', 'after' => $day, 'before' => $day, 'inclusive' => true), 'meta_query' => array(array('key' => 'WooCommerceEventsProductID', 'value' => $ID))));

        return $events_query->found_posts; 
        
        if(empty($num)) {

            $num = 0;

        }

        return $num;
        
    }
    
    /**
     * Fetches a product's revenue for a particular day
     * 
     * @global object $wpdb
     * @param string $day
     * @param int $id
     * @return int
     */
    private function _fetch_tickets_revenue_on_day($day, $id) {
        
        global $wpdb;
        $prefix = $wpdb->base_prefix;

        $sql = "SELECT SUM( order_item_meta__line_total.meta_value) 
                as order_item_amount 
                FROM ".$prefix."posts AS posts 
                INNER JOIN ".$prefix."woocommerce_order_items AS order_items ON posts.ID = order_items.order_id 
                INNER JOIN ".$prefix."woocommerce_order_itemmeta AS order_item_meta__line_total ON (order_items.order_item_id = order_item_meta__line_total.order_item_id)  AND (order_item_meta__line_total.meta_key = '_line_total') 
                INNER JOIN ".$prefix."woocommerce_order_itemmeta AS order_item_meta__product_id_array ON order_items.order_item_id = order_item_meta__product_id_array.order_item_id 
                WHERE posts.post_type IN ('shop_order','shop_order_refund') 
                    AND posts.post_status IN ('wc-completed') 
                    AND ((order_item_meta__product_id_array.meta_key IN ('_product_id','_variation_id')
                    AND DATE(posts.post_date) = '%s'
                    AND order_item_meta__product_id_array.meta_value IN ('%d')))";

        $num = $wpdb->get_var($wpdb->prepare($sql, $day, $id));
        
        if(empty($num)) {
           
           $num = 0;
           
       }
       
       return $num;

    }
    
    /**
     * Fetches check-ins on a particular day
     * 
     * @param string $day
     * @param int $event
     * @return int
     */
    private function _fetch_check_ins_on_day($day, $event) {
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'fooevents_check_in';
        
        $dayBegin = strtotime($day);
        $dayEnd = strtotime("tomorrow", $dayBegin) - 1;

        $wpdb->get_results("
                    SELECT * FROM ".$table_name."
                    WHERE checkin BETWEEN ".$dayBegin." AND ".$dayEnd." 
                        AND eid = ".$event."
                "); 

        return $wpdb->num_rows;
        
    }
    
    /**
     * Fetch checkings for a particular hour
     * 
     * @param string $hour
     * @param int $event
     * @return int
     */
    private function _fetch_check_ins_on_hour($hour, $event) {
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'fooevents_check_in';
        
        $hourBegin = $hour;
        $hourEnd = $hourBegin + 3599;
        
        $wpdb->get_results("
                    SELECT * FROM ".$table_name."
                    WHERE checkin BETWEEN ".$hourBegin." AND ".$hourEnd." 
                        AND eid = ".$event."
                "); 

        return $wpdb->num_rows;
        
    }
    
    /**
     * Fetches all hours for a particular day
     * 
     * @param string $date
     * @return array
     */
    private function _fetch_all_hours_for_date($date) {
        
        $dayBegin = strtotime($date);
        $hoursReturnArray = array(1 => $dayBegin);
        
        for ($x = 2; $x <= 24; $x++) {
            
            $hoursReturnArray[$x] = $hoursReturnArray[$x-1] + 3600;
            
        }
        
        return $hoursReturnArray;
        
    }
    
}