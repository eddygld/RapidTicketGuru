<?php if (!defined('ABSPATH')) exit;
class FooEvents_Woo_Helper {
	
    public  $Config;
    public  $TicketHelper;
    private $BarcodeHelper;
    public  $MailHelper;
    public  $ZoomAPIHelper;

    public function __construct($config) {

        $this->check_woocommerce_exists();
        $this->Config = $config;

        //TicketHelper
        require_once($this->Config->classPath.'tickethelper.php');
        $this->TicketHelper = new FooEvents_Ticket_Helper($this->Config);

        //BarcodeHelper
        require_once($this->Config->classPath.'barcodehelper.php');
        $this->BarcodeHelper = new FooEvents_Barcode_Helper($this->Config);
        
        //MailHelper
        require_once($this->Config->classPath.'mailhelper.php');
        $this->MailHelper = new FooEvents_Mail_Helper($this->Config);

        //ZoomAPIHelper
        require_once($this->Config->classPath.'zoomapihelper.php');
        $this->ZoomAPIHelper = new FooEvents_Zoom_API_Helper($this->Config);
        
        add_action('woocommerce_product_tabs', array(&$this, 'add_front_end_tab'), 10, 2);

        add_action('woocommerce_order_status_completed', array(&$this, 'process_order_tickets'), 20);
        add_action('woocommerce_product_write_panel_tabs', array($this, 'add_product_options_tab'), 21);
        add_action('woocommerce_product_write_panel_tabs', array($this, 'add_product_ticket_tab'), 22);
        add_action('woocommerce_product_write_panel_tabs', array($this, 'add_product_terminology_tab'), 23);
        add_action('woocommerce_product_write_panel_tabs', array($this, 'add_product_exports_tab'), 26);
        add_action('woocommerce_product_write_panel_tabs', array($this, 'add_product_printing_tab'), 27);
        add_action('woocommerce_product_write_panel_tabs', array($this, 'add_product_integration_tab'), 28);
        add_action('woocommerce_product_data_panels', array($this, 'add_product_options_tab_options'));
        add_action('woocommerce_process_product_meta', array($this, 'process_meta_box'));
        add_action('wp_ajax_woocommerce_events_csv', array($this, 'woocommerce_events_csv'));
        add_action('wp_ajax_woocommerce_events_attendee_badges', array($this, 'woocommerce_events_attendee_badges'));
        add_action('wp_ajax_fooevents_save_printing_options', array($this, 'save_printing_options'));

        add_action('woocommerce_thankyou_order_received_text', array($this, 'display_thank_you_text'));

        add_action('woocommerce_order_status_cancelled', array($this, 'order_status_cancelled'));
        add_action('woocommerce_order_status_completed', array(&$this, 'order_status_completed_cancelled'), 10, 1);
        
        add_filter('woocommerce_events_meta_format', 'wptexturize');
        add_filter('woocommerce_events_meta_format', 'convert_smilies');
        add_filter('woocommerce_events_meta_format', 'convert_chars');
        add_filter('woocommerce_events_meta_format', 'wpautop');
        add_filter('woocommerce_events_meta_format', 'shortcode_unautop');
        add_filter('woocommerce_events_meta_format', 'prepend_attachment');

        add_filter('parse_query', array($this, 'filter_product_results'));
        add_filter('restrict_manage_posts', array($this, 'filter_product_options'));

        add_filter('woocommerce_get_catalog_ordering_args', array($this, 'add_postmeta_ordering'));
        add_filter('woocommerce_default_catalog_orderby_options', array($this, 'add_postmeta_orderby'));
        add_filter('woocommerce_catalog_orderby', array($this, 'add_postmeta_orderby'));
        add_filter('woocommerce_before_shop_loop_item_title', array($this, 'display_product_date'), 35);  

    }

    /**
     * Checks if the WooCommerce plugin exists
     * 
     */
    public function check_woocommerce_exists() {

        if (!class_exists('WooCommerce')) {

            $this->output_notices(array(__( 'WooCommerce is required for FooEvents. Please install and activate the latest version of WooCommerce.', 'woocommerce-events')));

        } 

    }

    /**
     * Add options tab to WooCommerce meta box
     * 
     */
    public function add_product_options_tab() {

        echo '<li class="custom_tab_fooevents"><a href="#fooevents_options">'.__( 'Event Settings', 'woocommerce-events' ).'</a></li>';

    }

    /**
     * Displays the event form 
     * 
     * @param object $post
     */
    public function add_product_options_tab_options() {

        global $post;

        $WooCommerceEventsEvent                     = get_post_meta($post->ID, 'WooCommerceEventsEvent', true);
        $WooCommerceEventsDate                      = get_post_meta($post->ID, 'WooCommerceEventsDate', true);
        $WooCommerceEventsHour                      = get_post_meta($post->ID, 'WooCommerceEventsHour', true);
        $WooCommerceEventsPeriod                    = get_post_meta($post->ID, 'WooCommerceEventsPeriod', true);
        $WooCommerceEventsMinutes                   = get_post_meta($post->ID, 'WooCommerceEventsMinutes', true);
        $WooCommerceEventsHourEnd                   = get_post_meta($post->ID, 'WooCommerceEventsHourEnd', true);
        $WooCommerceEventsMinutesEnd                = get_post_meta($post->ID, 'WooCommerceEventsMinutesEnd', true);
        $WooCommerceEventsEndPeriod                 = get_post_meta($post->ID, 'WooCommerceEventsEndPeriod', true);
        $WooCommerceEventsTimeZone                  = get_post_meta($post->ID, 'WooCommerceEventsTimeZone', true);
        $WooCommerceEventsLocation                  = get_post_meta($post->ID, 'WooCommerceEventsLocation', true);
        
        $WooCommerceEventsPrintTicketLogo           = get_post_meta($post->ID, 'WooCommerceEventsPrintTicketLogo', true);
        $WooCommerceEventsPrintCustomText           = get_post_meta($post->ID, 'WooCommerceEventsPrintCustomText', true);
        $WooCommerceEventsTicketLogo                = get_post_meta($post->ID, 'WooCommerceEventsTicketLogo', true);
        $WooCommerceEventsTicketHeaderImage         = get_post_meta($post->ID, 'WooCommerceEventsTicketHeaderImage', true);
        $WooCommerceEventsSupportContact            = get_post_meta($post->ID, 'WooCommerceEventsSupportContact', true);
        $WooCommerceEventsGPS                       = get_post_meta($post->ID, 'WooCommerceEventsGPS', true);
        $WooCommerceEventsGoogleMaps                = get_post_meta($post->ID, 'WooCommerceEventsGoogleMaps', true);
        $WooCommerceEventsDirections                = get_post_meta($post->ID, 'WooCommerceEventsDirections', true);
        $WooCommerceEventsEmail                     = get_post_meta($post->ID, 'WooCommerceEventsEmail', true);
        $WooCommerceEventsTicketBackgroundColor     = get_post_meta($post->ID, 'WooCommerceEventsTicketBackgroundColor', true);
        $WooCommerceEventsTicketButtonColor         = get_post_meta($post->ID, 'WooCommerceEventsTicketButtonColor', true);
        $WooCommerceEventsTicketTextColor           = get_post_meta($post->ID, 'WooCommerceEventsTicketTextColor', true);
        $WooCommerceEventsTicketPurchaserDetails    = get_post_meta($post->ID, 'WooCommerceEventsTicketPurchaserDetails', true);
        $WooCommerceEventsTicketAddCalendar         = get_post_meta($post->ID, 'WooCommerceEventsTicketAddCalendar', true);
        $WooCommerceEventsTicketAddCalendarReminders = get_post_meta($post->ID, 'WooCommerceEventsTicketAddCalendarReminders', true);
        $WooCommerceEventsTicketAttachICS            = get_post_meta($post->ID, 'WooCommerceEventsTicketAttachICS', true);

        if ( !is_array($WooCommerceEventsTicketAddCalendarReminders) ) {

            $WooCommerceEventsTicketAddCalendarReminders = array(
                array('amount' => 1, 'unit' => 'weeks'),
                array('amount' => 1, 'unit' => 'days'),
                array('amount' => 1, 'unit' => 'hours'),
                array('amount' => 15, 'unit' => 'minutes')
            );

        }

        $WooCommerceEventsTicketDisplayDateTime     = get_post_meta($post->ID, 'WooCommerceEventsTicketDisplayDateTime', true);
        $WooCommerceEventsTicketDisplayBarcode      = get_post_meta($post->ID, 'WooCommerceEventsTicketDisplayBarcode', true);
        $WooCommerceEventsTicketDisplayPrice            = get_post_meta($post->ID, 'WooCommerceEventsTicketDisplayPrice', true);
        $WooCommerceEventsTicketDisplayZoom             = get_post_meta($post->ID, 'WooCommerceEventsTicketDisplayZoom', true);
        $WooCommerceEventsTicketText                    = get_post_meta($post->ID, 'WooCommerceEventsTicketText', true);
        $WooCommerceEventsThankYouText                  = get_post_meta($post->ID, 'WooCommerceEventsThankYouText', true);
        $WooCommerceEventsEventDetailsText              = get_post_meta($post->ID, 'WooCommerceEventsEventDetailsText', true);
        $WooCommerceEventsBackgroundColor               = get_post_meta($post->ID, 'WooCommerceEventsBackgroundColor', true);
        $WooCommerceEventsTextColor                     = get_post_meta($post->ID, 'WooCommerceEventsTextColor', true);
        $WooCommerceEventsCaptureAttendeeDetails        = get_post_meta($post->ID, 'WooCommerceEventsCaptureAttendeeDetails', true);
        $WooCommerceEventsEmailAttendee                 = get_post_meta($post->ID, 'WooCommerceEventsEmailAttendee', true);
        $WooCommerceEventsSendEmailTickets              = get_post_meta($post->ID, 'WooCommerceEventsSendEmailTickets', true);
        $WooCommerceEventsCaptureAttendeeTelephone      = get_post_meta($post->ID, 'WooCommerceEventsCaptureAttendeeTelephone', true);
        $WooCommerceEventsCaptureAttendeeCompany        = get_post_meta($post->ID, 'WooCommerceEventsCaptureAttendeeCompany', true);
        $WooCommerceEventsCaptureAttendeeDesignation    = get_post_meta($post->ID, 'WooCommerceEventsCaptureAttendeeDesignation', true);

        $WooCommerceEventsViewSeatingChart              = get_post_meta($post->ID, 'WooCommerceEventsViewSeatingChart', true);

        $WooCommerceEventsExportUnpaidTickets           = get_post_meta($post->ID, 'WooCommerceEventsExportUnpaidTickets', true);
        $WooCommerceEventsExportBillingDetails          = get_post_meta($post->ID, 'WooCommerceEventsExportBillingDetails', true);


        $WooCommercePrintTicketSize                    = get_post_meta($post->ID, 'WooCommercePrintTicketSize', true);
        $WooCommercePrintTicketNrColumns               = get_post_meta($post->ID, 'WooCommercePrintTicketNrColumns', true);
        $WooCommercePrintTicketNrRows                  = get_post_meta($post->ID, 'WooCommercePrintTicketNrRows', true);

        $WooCommerceBadgeFieldTopLeft                  = get_post_meta($post->ID, 'WooCommerceBadgeFieldTopLeft', true);
        $WooCommerceBadgeFieldTopMiddle                = get_post_meta($post->ID, 'WooCommerceBadgeFieldTopMiddle', true);
        $WooCommerceBadgeFieldTopRight                 = get_post_meta($post->ID, 'WooCommerceBadgeFieldTopRight', true);
        $WooCommerceBadgeFieldMiddleLeft               = get_post_meta($post->ID, 'WooCommerceBadgeFieldMiddleLeft', true);
        $WooCommerceBadgeFieldMiddleMiddle             = get_post_meta($post->ID, 'WooCommerceBadgeFieldMiddleMiddle', true);
        $WooCommerceBadgeFieldMiddleRight              = get_post_meta($post->ID, 'WooCommerceBadgeFieldMiddleRight', true);
        $WooCommerceBadgeFieldBottomLeft               = get_post_meta($post->ID, 'WooCommerceBadgeFieldBottomLeft', true);
        $WooCommerceBadgeFieldBottomMiddle             = get_post_meta($post->ID, 'WooCommerceBadgeFieldBottomMiddle', true);
        $WooCommerceBadgeFieldBottomRight              = get_post_meta($post->ID, 'WooCommerceBadgeFieldBottomRight', true);
                
        $WooCommerceBadgeFieldTopLeft_font             = get_post_meta($post->ID, 'WooCommerceBadgeFieldTopLeft_font', true);
        $WooCommerceBadgeFieldTopMiddle_font           = get_post_meta($post->ID, 'WooCommerceBadgeFieldTopMiddle_font', true);
        $WooCommerceBadgeFieldTopRight_font            = get_post_meta($post->ID, 'WooCommerceBadgeFieldTopRight_font', true);
        $WooCommerceBadgeFieldMiddleLeft_font          = get_post_meta($post->ID, 'WooCommerceBadgeFieldMiddleLeft_font', true);
        $WooCommerceBadgeFieldMiddleMiddle_font        = get_post_meta($post->ID, 'WooCommerceBadgeFieldMiddleMiddle_font', true);
        $WooCommerceBadgeFieldMiddleRight_font         = get_post_meta($post->ID, 'WooCommerceBadgeFieldMiddleRight_font', true);
        $WooCommerceBadgeFieldBottomLeft_font          = get_post_meta($post->ID, 'WooCommerceBadgeFieldBottomLeft_font', true);
        $WooCommerceBadgeFieldBottomMiddle_font        = get_post_meta($post->ID, 'WooCommerceBadgeFieldBottomMiddle_font', true);
        $WooCommerceBadgeFieldBottomRight_font         = get_post_meta($post->ID, 'WooCommerceBadgeFieldBottomRight_font', true);

        $WooCommerceBadgeFieldTopLeft_logo             = get_post_meta($post->ID, 'WooCommerceBadgeFieldTopLeft_logo', true);
        $WooCommerceBadgeFieldTopMiddle_logo           = get_post_meta($post->ID, 'WooCommerceBadgeFieldTopMiddle_logo', true);
        $WooCommerceBadgeFieldTopRight_logo            = get_post_meta($post->ID, 'WooCommerceBadgeFieldTopRight_logo', true);
        $WooCommerceBadgeFieldMiddleLeft_logo          = get_post_meta($post->ID, 'WooCommerceBadgeFieldMiddleLeft_logo', true);
        $WooCommerceBadgeFieldMiddleMiddle_logo        = get_post_meta($post->ID, 'WooCommerceBadgeFieldMiddleMiddle_logo', true);
        $WooCommerceBadgeFieldMiddleRight_logo         = get_post_meta($post->ID, 'WooCommerceBadgeFieldMiddleRight_logo', true);
        $WooCommerceBadgeFieldBottomLeft_logo          = get_post_meta($post->ID, 'WooCommerceBadgeFieldBottomLeft_logo', true);
        $WooCommerceBadgeFieldBottomMiddle_logo        = get_post_meta($post->ID, 'WooCommerceBadgeFieldBottomMiddle_logo', true);
        $WooCommerceBadgeFieldBottomRight_logo         = get_post_meta($post->ID, 'WooCommerceBadgeFieldBottomRight_logo', true);

        $WooCommerceBadgeFieldTopLeft_custom           = get_post_meta($post->ID, 'WooCommerceBadgeFieldTopLeft_custom', true);
        $WooCommerceBadgeFieldTopMiddle_custom         = get_post_meta($post->ID, 'WooCommerceBadgeFieldTopMiddle_custom', true);
        $WooCommerceBadgeFieldTopRight_custom          = get_post_meta($post->ID, 'WooCommerceBadgeFieldTopRight_custom', true);
        $WooCommerceBadgeFieldMiddleLeft_custom        = get_post_meta($post->ID, 'WooCommerceBadgeFieldMiddleLeft_custom', true);
        $WooCommerceBadgeFieldMiddleMiddle_custom      = get_post_meta($post->ID, 'WooCommerceBadgeFieldMiddleMiddle_custom', true);
        $WooCommerceBadgeFieldMiddleRight_custom       = get_post_meta($post->ID, 'WooCommerceBadgeFieldMiddleRight_custom', true);
        $WooCommerceBadgeFieldBottomLeft_custom        = get_post_meta($post->ID, 'WooCommerceBadgeFieldBottomLeft_custom', true);
        $WooCommerceBadgeFieldBottomMiddle_custom      = get_post_meta($post->ID, 'WooCommerceBadgeFieldBottomMiddle_custom', true);
        $WooCommerceBadgeFieldBottomRight_custom       = get_post_meta($post->ID, 'WooCommerceBadgeFieldBottomRight_custom', true);

        $WooCommercePrintTicketSort                    = get_post_meta($post->ID, 'WooCommercePrintTicketSort', true);
        $WooCommercePrintTicketNumbers                 = get_post_meta($post->ID, 'WooCommercePrintTicketNumbers', true);
        $WooCommercePrintTicketOrders                  = get_post_meta($post->ID, 'WooCommercePrintTicketOrders', true);
        
        $WooCommerceEventsCutLinesPrintTicket           = get_post_meta($post->ID, 'WooCommerceEventsCutLinesPrintTicket', true);


        $WooCommerceEventsEmailSubjectSingle            = get_post_meta($post->ID, 'WooCommerceEventsEmailSubjectSingle', true);
        $WooCommerceEventsTicketTheme                   = get_post_meta($post->ID, 'WooCommerceEventsTicketTheme', true);
        
        $WooCommerceEventsAttendeeOverride              = get_post_meta($post->ID, 'WooCommerceEventsAttendeeOverride', true);
        $WooCommerceEventsAttendeeOverridePlural        = get_post_meta($post->ID, 'WooCommerceEventsAttendeeOverridePlural', true);
        $WooCommerceEventsTicketOverride                = get_post_meta($post->ID, 'WooCommerceEventsTicketOverride', true);
        $WooCommerceEventsTicketOverridePlural          = get_post_meta($post->ID, 'WooCommerceEventsTicketOverridePlural', true);

        $WooCommerceEventsViewSeatingChart              = get_post_meta($post->ID, 'WooCommerceEventsViewSeatingChart', true);

        $WooCommerceEventsZoomMultiOption               = get_post_meta($post->ID, 'WooCommerceEventsZoomMultiOption', true);
        $WooCommerceEventsZoomWebinar                   = get_post_meta($post->ID, 'WooCommerceEventsZoomWebinar', true);
        $WooCommerceEventsZoomWebinarMulti              = get_post_meta($post->ID, 'WooCommerceEventsZoomWebinarMulti', true);

        $globalWooCommerceEventsGoogleMapsAPIKey = get_option('globalWooCommerceEventsGoogleMapsAPIKey', true);
    
        if($globalWooCommerceEventsGoogleMapsAPIKey == 1) {

            $globalWooCommerceEventsGoogleMapsAPIKey = '';

        }

        if(empty($WooCommerceEventsEmailSubjectSingle)) {

            $WooCommerceEventsEmailSubjectSingle = __('{OrderNumber} Ticket', 'woocommerce-events');

        }

        $globalWooCommerceEventsTicketBackgroundColor   = get_option('globalWooCommerceEventsTicketBackgroundColor', true);
        $globalWooCommerceEventsTicketButtonColor       = get_option('globalWooCommerceEventsTicketButtonColor', true);
        $globalWooCommerceEventsTicketTextColor         = get_option('globalWooCommerceEventsTicketTextColor', true);
        $globalWooCommerceEventsTicketLogo              = get_option('globalWooCommerceEventsTicketLogo', true);
        $globalWooCommerceEventsTicketHeaderImage       = get_option('globalWooCommerceEventsTicketHeaderImage', true);

        $endDate = '';
        $numDays = '';
        $multiDayType = '';
        $multidayTerm = '';

        $dayTerm = get_post_meta($post->ID, 'WooCommerceEventsDayOverride', true);

        if(empty($dayTerm)) {

            $dayTerm = get_option('WooCommerceEventsDayOverride', true);

        }

        if(empty($dayTerm) || $dayTerm == 1) {

            $dayTerm = __('Day', 'woocommerce-events');

        }

        $numDaysValue = 1;

        $eventBackgroundColour = '';
        $eventTextColour = '';
        $pdfTicketThemes = '';
        $pdfTicketOptions = '';
        
        if (!function_exists('is_plugin_active_for_network')) {
            
            require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
            
        }
        
        if ($this->is_plugin_active('fooevents_multi_day/fooevents-multi-day.php') || is_plugin_active_for_network('fooevents_multi_day/fooevents-multi-day.php')) {

            $Fooevents_Multiday_Events = new Fooevents_Multiday_Events();
            $endDate = $Fooevents_Multiday_Events->generate_end_date_option($post);
            $numDays = $Fooevents_Multiday_Events->generate_num_days_option($post);
            $multiDayType = $Fooevents_Multiday_Events->generate_multiday_type_option($post);
            $multidayTerm = $Fooevents_Multiday_Events->generate_multiday_term_option($post);
            $numDaysValue = (int)get_post_meta($post->ID, 'WooCommerceEventsNumDays', true);
            
        }

        $eventbrite_option = '';
        if ($this->is_plugin_active('fooevents-calendar/fooevents-calendar.php') || is_plugin_active_for_network('fooevents-calendar/fooevents-calendar.php')) {
            
            $FooEvents_Calendar = new FooEvents_Calendar();
            
            $globalFooEventsEventbriteToken = get_option('globalFooEventsEventbriteToken');
            
            if(!empty($globalFooEventsEventbriteToken)) {
                
                $eventbrite_option = $FooEvents_Calendar->generate_eventbrite_option($post);
            
            }
            
        }
        
        $eventsIncludeCustomAttendeeFields = '';
        $cf_array = array();
        
        if ($this->is_plugin_active('fooevents_custom_attendee_fields/fooevents-custom-attendee-fields.php') || is_plugin_active_for_network('fooevents_custom_attendee_fields/fooevents-custom-attendee-fields.php')) {
            
            $Fooevents_Custom_Attendee_Fields = new Fooevents_Custom_Attendee_Fields($post);
            $eventsIncludeCustomAttendeeFields = $Fooevents_Custom_Attendee_Fields->generate_include_custom_attendee_options($post);
            
            $fooevents_custom_attendee_fields_options = $Fooevents_Custom_Attendee_Fields->display_tickets_meta_custom_options_array($post->ID); 
            
            if (!empty($fooevents_custom_attendee_fields_options["fooevents_custom_attendee_fields_options_serialized"])) {
                
                $custom_fields = json_decode($fooevents_custom_attendee_fields_options["fooevents_custom_attendee_fields_options_serialized"], true);

                foreach( $custom_fields as $key => $value) {
                    foreach( $value as $key_cf => $value_cf) {
                        if (strpos($key_cf, '_label') !== false)
                        {
                            $cf_array["fooevents_custom_" . str_replace(" ", "_", strtolower($value_cf))] = $value_cf;
                        }   
                    }
                }
                
            }
        }
        
        if ($this->is_plugin_active('fooevents_pdf_tickets/fooevents-pdf-tickets.php') || is_plugin_active_for_network('fooevents_pdf_tickets/fooevents-pdf-tickets.php')) {
            
            $FooEvents_PDF_Tickets = new FooEvents_PDF_Tickets();
            $pdfTicketThemes = $FooEvents_PDF_Tickets->generate_pdf_theme_options($post);
            $pdfTicketOptions = $FooEvents_PDF_Tickets->add_product_pdf_tickets_options_tab_options($post);
            
        }
        
        $themes = $this->get_ticket_themes();
        
        require($this->Config->templatePath.'eventmetaoptions.php');
        require($this->Config->templatePath.'terminologymetaoptions.php');
        require($this->Config->templatePath.'ticketmetaoptions.php');
        require($this->Config->templatePath.'exportmetaoptions.php');
        $WooHelper = $this;
        require($this->Config->templatePath.'printmetaoptions.php');

        $globalWooCommerceEventsZoomAPIKey      = get_option('globalWooCommerceEventsZoomAPIKey', '');
        $globalWooCommerceEventsZoomAPISecret   = get_option('globalWooCommerceEventsZoomAPISecret', '');
        $zoomMeetings = $this->ZoomAPIHelper->fooevents_fetch_zoom_meetings();
        $zoomWebinars = $this->ZoomAPIHelper->fooevents_fetch_zoom_webinars();

        require($this->Config->templatePath.'integrationmetaoptions.php');
        
    }

    /**
     * Add integration tab to WooCommerce meta box
     * 
     */
    public function add_product_integration_tab() {

        echo '<li class="custom_tab_fooevents"><a href="#fooevents_integration">'.__( 'Event Integration', 'woocommerce-events' ).'</a></li>';

    }

    /**
     * Add options tab to WooCommerce meta box
     * 
     */
    public function add_product_exports_tab() {

        echo '<li class="custom_tab_fooevents_exports"><a href="#fooevents_exports">'.__( 'Event Export', 'woocommerce-events' ).'</a></li>';

    }
    
    /**
     * Add options tab to WooCommerce meta box
     * 
     */
    public function add_product_printing_tab() {

        echo '<li class="custom_tab_fooevents_printing"><a href="#fooevents_printing">'.__( 'Stationery Builder', 'woocommerce-events' ).'</a></li>';

    }
    
    /**
     * Add options tab to WooCommerce meta box
     * 
     */
    public function add_product_terminology_tab() {

        echo '<li class="custom_tab_fooevents_terminology"><a href="#fooevents_terminology">'.__( 'Event Terminology', 'woocommerce-events' ).'</a></li>';

    }
    
    
    /**
     * Add options tab to WooCommerce meta box
     * 
     */
    public function add_product_ticket_tab() {

        echo '<li class="custom_tab_fooevents_tickets"><a href="#fooevents_tickets">'.__( 'Ticket Settings', 'woocommerce-events' ).'</a></li>';

    }
    
    /**
     * Gets a list of FooEvents Ticket themes
     * 
     */
    public function get_ticket_themes()  {
        
        $valid_themes = array();
        
        foreach (new DirectoryIterator($this->Config->themePacksPath) as $file) {
            
            if ($file->isDir() && !$file->isDot()) {
                
                $theme_name = $file->getFilename();
                
                $theme_path = $file->getPath();
                $theme_path = $theme_path.'/'.$theme_name;
                
                $theme_name_pretty = str_replace('_', " ", $theme_name);
                $theme_name_prep = ucwords($theme_name_pretty);
                
                if(file_exists($theme_path.'/header.php') && file_exists($theme_path.'/footer.php') && file_exists($theme_path.'/ticket.php')) {
                    
                    $theme_config = array();
                    if(file_exists($theme_path.'/config.json')) {
                        
                        $theme_config = file_get_contents($theme_path.'/config.json');
                        $theme_config = json_decode($theme_config, true);
                        $valid_themes[$theme_name_prep]['name'] = $theme_config['name'];
                        
                    } else {
                        
                       $valid_themes[$theme_name_prep]['name'] = $theme_name_prep;
                        
                    }
                    
                    $valid_themes[$theme_name_prep]['path'] = $theme_path;
                    $theme_url = $this->Config->themePacksURL.$theme_name;
                    $valid_themes[$theme_name_prep]['url'] = $theme_url;
                    
                    if(file_exists($theme_path.'/preview.jpg')) {   
                        
                        $valid_themes[$theme_name_prep]['preview'] = $theme_url.'/preview.jpg';
                        
                    } else {
                        
                        $valid_themes[$theme_name_prep]['preview'] = $this->Config->eventPluginURL.'images/no-preview.jpg';
                        
                    }
                    
                    $valid_themes[$theme_name_prep]['file_name'] = $file->getFilename();
                    
                }

            }
            
        }

        return $valid_themes;
        
    }
    
    /**
     * Processes the meta box form once the publish / update button is clicked.
     * 
     * @global object $woocommerce_errors
     * @param int $post_id
     * @param object $post
     */
    public function process_meta_box($post_id) {

        global $woocommerce_errors;
        global $wp_locale;

        if(isset($_POST['WooCommerceEventsEvent'])) {
            
            $WooCommerceEventsEvent = sanitize_text_field($_POST['WooCommerceEventsEvent']);
            update_post_meta($post_id, 'WooCommerceEventsEvent', $WooCommerceEventsEvent);

        }

        $format = get_option('date_format');
        
        $min = 60 * get_option('gmt_offset');
        $sign = $min < 0 ? "-" : "+";
        $absmin = abs($min);

        try {
            
            $tz = new DateTimeZone(sprintf("%s%02d%02d", $sign, $absmin/60, $absmin%60));

        } catch(Exception $e) {
            
            $serverTimezone = date_default_timezone_get();
            $tz = new DateTimeZone($serverTimezone);

        }
        
        $event_date = sanitize_text_field($_POST['WooCommerceEventsDate']);
        
        if(isset($event_date)) {
            
            if(isset($_POST['WooCommerceEventsSelectDate'][0]) && isset($_POST['WooCommerceEventsMultiDayType']) && $_POST['WooCommerceEventsMultiDayType'] == 'select') {
                
                $event_date = sanitize_text_field($_POST['WooCommerceEventsSelectDate'][0]);
                
            }

            $event_date = str_replace('/', '-', $event_date);
            $event_date = str_replace(',', '', $event_date);
            
            $WooCommerceEventsDate = sanitize_text_field($_POST['WooCommerceEventsDate']);
            
            update_post_meta($post_id, 'WooCommerceEventsDate', $WooCommerceEventsDate);
            $event_date = $this->convert_month_to_english($event_date);
            $dtime = DateTime::createFromFormat($format, $event_date, $tz);

            $timestamp = strtotime($event_date);
            
            if(empty($timestamp)) {
                
                $timestamp = 0;
                
            }
            
            update_post_meta($post_id, 'WooCommerceEventsDate', $_POST['WooCommerceEventsDate']);
            update_post_meta($post_id, 'WooCommerceEventsDateTimestamp', $timestamp);

        }
        
        if(isset($_POST['WooCommerceEventsEndDate'])) {
            
            $WooCommerceEventsEndDate = sanitize_text_field($_POST['WooCommerceEventsEndDate']);
            update_post_meta($post_id, 'WooCommerceEventsEndDate', $WooCommerceEventsEndDate);
            
            $dtime = DateTime::createFromFormat($format, $_POST['WooCommerceEventsEndDate'], $tz);

            $timestamp = '';
            if ($dtime instanceof DateTime) {
                
                if(isset($_POST['WooCommerceEventsHourEnd']) && isset($_POST['WooCommerceEventsMinutesEnd'])) {
                
                    $dtime->setTime((int)$_POST['WooCommerceEventsHourEnd'], (int)$_POST['WooCommerceEventsMinutesEnd']);

                }

                $timestamp = $dtime->getTimestamp();

            } else {

                $timestamp = 0;

            }

            update_post_meta($post_id, 'WooCommerceEventsEndDateTimestamp', $timestamp);

        }
        
        if(isset($_POST['WooCommerceEventsMultiDayType'])) {
            
            $WooCommerceEventsMultiDayType = sanitize_text_field($_POST['WooCommerceEventsMultiDayType']);
            update_post_meta($post_id, 'WooCommerceEventsMultiDayType', $WooCommerceEventsMultiDayType);
            
        }
        
        if(isset($_POST['WooCommerceEventsSelectDate'])) {
            
            $WooCommerceEventsSelectDate = $_POST['WooCommerceEventsSelectDate'];
            update_post_meta($post_id, 'WooCommerceEventsSelectDate', $WooCommerceEventsSelectDate);
            
        }
        
        if(isset($_POST['WooCommerceEventsNumDays'])) {
            
            $WooCommerceEventsNumDays = sanitize_text_field($_POST['WooCommerceEventsNumDays']);
            update_post_meta($post_id, 'WooCommerceEventsNumDays', $WooCommerceEventsNumDays);
            
        }
        
        if(isset($_POST['WooCommerceEventsHour'])) {
            
            $WooCommerceEventsHour = sanitize_text_field($_POST['WooCommerceEventsHour']);
            update_post_meta($post_id, 'WooCommerceEventsHour', $WooCommerceEventsHour);

        }

        if(isset($_POST['WooCommerceEventsMinutes'])) {
            
            $WooCommerceEventsMinutes = sanitize_text_field($_POST['WooCommerceEventsMinutes']);
            update_post_meta($post_id, 'WooCommerceEventsMinutes', $WooCommerceEventsMinutes);

        }

        if(isset($_POST['WooCommerceEventsPeriod'])) {
            
            $WooCommerceEventsPeriod = sanitize_text_field($_POST['WooCommerceEventsPeriod']);
            update_post_meta($post_id, 'WooCommerceEventsPeriod', $WooCommerceEventsPeriod);

        } else {

            update_post_meta($post_id, 'WooCommerceEventsPeriod', '');

        }

        if(isset($_POST['WooCommerceEventsTimeZone'])) {
            
            $WooCommerceEventsTimeZone = sanitize_text_field($_POST['WooCommerceEventsTimeZone']);
            update_post_meta($post_id, 'WooCommerceEventsTimeZone', $WooCommerceEventsTimeZone);

        }

        if(isset($_POST['WooCommerceEventsLocation'])) {

            $WooCommerceEventsLocation = htmlentities(stripslashes($_POST['WooCommerceEventsLocation']));
            update_post_meta($post_id, 'WooCommerceEventsLocation', $WooCommerceEventsLocation);

        }

        if(isset($_POST['WooCommerceEventsTicketLogo'])) {
            
            $WooCommerceEventsTicketLogo = sanitize_text_field($_POST['WooCommerceEventsTicketLogo']);
            update_post_meta($post_id, 'WooCommerceEventsTicketLogo', $WooCommerceEventsTicketLogo);

        }

        if(isset($_POST['WooCommerceEventsPrintTicketLogo'])) {
            
            $WooCommerceEventsPrintTicketLogo = sanitize_text_field($_POST['WooCommerceEventsPrintTicketLogo']);
            update_post_meta($post_id, 'WooCommerceEventsPrintTicketLogo', $WooCommerceEventsPrintTicketLogo);
 
        }

        if(isset($_POST['WooCommerceEventsPrintCustomText'])) {
            
            $WooCommerceEventsPrintCustomText = $_POST['WooCommerceEventsPrintCustomText'];
            update_post_meta($post_id, 'WooCommerceEventsPrintCustomText', $WooCommerceEventsPrintCustomText);
 
        }

	    if(isset($_POST['WooCommerceEventsTicketHeaderImage'])) {
            
            $WooCommerceEventsTicketHeaderImage = sanitize_text_field($_POST['WooCommerceEventsTicketHeaderImage']);
            update_post_meta($post_id, 'WooCommerceEventsTicketHeaderImage', $WooCommerceEventsTicketHeaderImage);

        }

        if(isset($_POST['WooCommerceEventsTicketText'])) {
            
            $WooCommerceEventsTicketText = wp_kses_post($_POST['WooCommerceEventsTicketText']);
            update_post_meta($post_id, 'WooCommerceEventsTicketText', $WooCommerceEventsTicketText);

        }

        if(isset($_POST['WooCommerceEventsThankYouText'])) {
            
            $WooCommerceEventsThankYouText = wp_kses_post($_POST['WooCommerceEventsThankYouText']);
            update_post_meta($post_id, 'WooCommerceEventsThankYouText', $WooCommerceEventsThankYouText);

        }
        
        if(isset($_POST['WooCommerceEventsEventDetailsText'])) {
            
            $WooCommerceEventsEventDetailsText = wp_kses_post($_POST['WooCommerceEventsEventDetailsText']);
            update_post_meta($post_id, 'WooCommerceEventsEventDetailsText', $WooCommerceEventsEventDetailsText);

        }
        
        if(isset($_POST['WooCommerceEventsSupportContact'])) {

            $WooCommerceEventsSupportContact = htmlentities(stripslashes($_POST['WooCommerceEventsSupportContact']));
            update_post_meta($post_id, 'WooCommerceEventsSupportContact', $WooCommerceEventsSupportContact);

        }

        if(isset($_POST['WooCommerceEventsHourEnd'])) {
            
            $WooCommerceEventsHourEnd = sanitize_text_field($_POST['WooCommerceEventsHourEnd']);
            update_post_meta($post_id, 'WooCommerceEventsHourEnd', $WooCommerceEventsHourEnd);

        }

        if(isset($_POST['WooCommerceEventsMinutesEnd'])) {
            
            $WooCommerceEventsMinutesEnd = sanitize_text_field($_POST['WooCommerceEventsMinutesEnd']);
            update_post_meta($post_id, 'WooCommerceEventsMinutesEnd', $WooCommerceEventsMinutesEnd);

        }

        if(isset($_POST['WooCommerceEventsEndPeriod'])) {
            
            $WooCommerceEventsEndPeriod = sanitize_text_field($_POST['WooCommerceEventsEndPeriod']);
            update_post_meta($post_id, 'WooCommerceEventsEndPeriod', $WooCommerceEventsEndPeriod);

        } else {

            update_post_meta($post_id, 'WooCommerceEventsEndPeriod', '');

        }

        if(isset($_POST['WooCommerceEventsAddEventbrite'])) {

            if (!function_exists( 'is_plugin_active_for_network')) {
                
                require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
                
            }

            if ($this->is_plugin_active('fooevents-calendar/fooevents-calendar.php') || is_plugin_active_for_network('fooevents-calendar/fooevents-calendar.php')) {
                
                $FooEvents_Calendar = new FooEvents_Calendar();
                $FooEvents_Calendar->process_eventbrite($post_id);

            }

            $WooCommerceEventsAddEventbrite= sanitize_text_field($_POST['WooCommerceEventsAddEventbrite']);
            update_post_meta($post_id, 'WooCommerceEventsAddEventbrite', $WooCommerceEventsAddEventbrite);
  
        } else {

            update_post_meta($post_id, 'WooCommerceEventsAddEventbrite', '');

        }
        
        if(isset($_POST['WooCommerceEventsGPS'])) {

            $WooCommerceEventsGPS = htmlentities(stripslashes($_POST['WooCommerceEventsGPS']));
            update_post_meta($post_id, 'WooCommerceEventsGPS', $WooCommerceEventsGPS);

        }

        if(isset($_POST['WooCommerceEventsDirections'])) {

            $WooCommerceEventsDirections = htmlentities(stripslashes($_POST['WooCommerceEventsDirections']));
            update_post_meta($post_id, 'WooCommerceEventsDirections', $WooCommerceEventsDirections);

        }

        if(isset($_POST['WooCommerceEventsEmail'])) {

            $WooCommerceEventsEmail = esc_textarea($_POST['WooCommerceEventsEmail']);
            update_post_meta($post_id, 'WooCommerceEventsEmail', $WooCommerceEventsEmail);

        }

        if(isset($_POST['WooCommerceEventsTicketBackgroundColor'])) {
            
            $WooCommerceEventsTicketBackgroundColor = sanitize_text_field($_POST['WooCommerceEventsTicketBackgroundColor']);
            update_post_meta($post_id, 'WooCommerceEventsTicketBackgroundColor', $WooCommerceEventsTicketBackgroundColor);

        }

        if(isset($_POST['WooCommerceEventsTicketButtonColor'])) {
            
            $WooCommerceEventsTicketButtonColor = sanitize_text_field($_POST['WooCommerceEventsTicketButtonColor']);
            update_post_meta($post_id, 'WooCommerceEventsTicketButtonColor', $WooCommerceEventsTicketButtonColor);

        }

        if(isset($_POST['WooCommerceEventsTicketTextColor'])) {
            
            $WooCommerceEventsTicketTextColor = sanitize_text_field($_POST['WooCommerceEventsTicketTextColor']);
            update_post_meta($post_id, 'WooCommerceEventsTicketTextColor', $WooCommerceEventsTicketTextColor);

        }
        
        if(isset($_POST['WooCommerceEventsBackgroundColor'])) {
            
            $WooCommerceEventsBackgroundColor = sanitize_text_field($_POST['WooCommerceEventsBackgroundColor']);
            update_post_meta($post_id, 'WooCommerceEventsBackgroundColor', $WooCommerceEventsBackgroundColor);

        }
        
        if(isset($_POST['WooCommerceEventsTextColor'])) {
            
            $WooCommerceEventsTextColor = sanitize_text_field($_POST['WooCommerceEventsTextColor']);
            update_post_meta($post_id, 'WooCommerceEventsTextColor', $WooCommerceEventsTextColor);

        }

        if(isset($_POST['WooCommerceEventsGoogleMaps'])) {
            
            $WooCommerceEventsGoogleMaps = sanitize_text_field($_POST['WooCommerceEventsGoogleMaps']);
            update_post_meta($post_id, 'WooCommerceEventsGoogleMaps', $WooCommerceEventsGoogleMaps);

        }

        if(isset($_POST['WooCommerceEventsTicketPurchaserDetails'])) {
            
            $WooCommerceEventsTicketPurchaserDetails = sanitize_text_field($_POST['WooCommerceEventsTicketPurchaserDetails']);
            update_post_meta($post_id, 'WooCommerceEventsTicketPurchaserDetails', $WooCommerceEventsTicketPurchaserDetails);

        } else {

            update_post_meta($post_id, 'WooCommerceEventsTicketPurchaserDetails', 'off');

        }

        if(isset($_POST['WooCommerceEventsTicketAddCalendar'])) {
            
            $WooCommerceEventsTicketAddCalendar = sanitize_text_field($_POST['WooCommerceEventsTicketAddCalendar']);
            update_post_meta($post_id, 'WooCommerceEventsTicketAddCalendar', $WooCommerceEventsTicketAddCalendar);

        } else {

            update_post_meta($post_id, 'WooCommerceEventsTicketAddCalendar', 'off');

        }

        $WooCommerceEventsTicketAddCalendarReminders = array();

        if( isset($_POST['WooCommerceEventsTicketAddCalendarReminderAmounts']) && isset($_POST['WooCommerceEventsTicketAddCalendarReminderUnits'])) {

            $WooCommerceEventsTicketAddCalendarReminderAmounts = array_map( 'sanitize_text_field', $_POST['WooCommerceEventsTicketAddCalendarReminderAmounts']);
            $WooCommerceEventsTicketAddCalendarReminderUnits = array_map( 'sanitize_text_field', $_POST['WooCommerceEventsTicketAddCalendarReminderUnits']);

            for ( $i = 0; $i < count($WooCommerceEventsTicketAddCalendarReminderAmounts); $i++ ) {

                $WooCommerceEventsTicketAddCalendarReminders[] = array(
                    'amount' => $WooCommerceEventsTicketAddCalendarReminderAmounts[$i],
                    'unit' => $WooCommerceEventsTicketAddCalendarReminderUnits[$i]
                );

            }

        }

        update_post_meta($post_id, 'WooCommerceEventsTicketAddCalendarReminders', $WooCommerceEventsTicketAddCalendarReminders);

        if(isset($_POST['WooCommerceEventsTicketAttachICS'])) {
            
            $WooCommerceEventsTicketAttachICS = sanitize_text_field($_POST['WooCommerceEventsTicketAttachICS']);
            update_post_meta($post_id, 'WooCommerceEventsTicketAttachICS', $WooCommerceEventsTicketAttachICS);

        } else {

            update_post_meta($post_id, 'WooCommerceEventsTicketAttachICS', 'off');

        }

        if(isset($_POST['WooCommerceEventsTicketDisplayDateTime'])) {
            
            $WooCommerceEventsTicketDisplayDateTime = sanitize_text_field($_POST['WooCommerceEventsTicketDisplayDateTime']);
            update_post_meta($post_id, 'WooCommerceEventsTicketDisplayDateTime', $WooCommerceEventsTicketDisplayDateTime);

        } else {

            update_post_meta($post_id, 'WooCommerceEventsTicketDisplayDateTime', 'off');

        }

        if(isset($_POST['WooCommerceEventsTicketDisplayBarcode'])) {
            
            $WooCommerceEventsTicketDisplayBarcode = sanitize_text_field($_POST['WooCommerceEventsTicketDisplayBarcode']);
            update_post_meta($post_id, 'WooCommerceEventsTicketDisplayBarcode', $WooCommerceEventsTicketDisplayBarcode);

        } else {

            update_post_meta($post_id, 'WooCommerceEventsTicketDisplayBarcode', 'off');

        }

        if(isset($_POST['WooCommerceEventsTicketDisplayPrice'])) {
            
            $WooCommerceEventsTicketDisplayPrice = sanitize_text_field($_POST['WooCommerceEventsTicketDisplayPrice']);
            update_post_meta($post_id, 'WooCommerceEventsTicketDisplayPrice', $WooCommerceEventsTicketDisplayPrice);

        } else {

            update_post_meta($post_id, 'WooCommerceEventsTicketDisplayPrice', 'off');

        }

        if(isset($_POST['WooCommerceEventsTicketDisplayZoom'])) {
            
            $WooCommerceEventsTicketDisplayZoom = sanitize_text_field($_POST['WooCommerceEventsTicketDisplayZoom']);
            update_post_meta($post_id, 'WooCommerceEventsTicketDisplayZoom', $WooCommerceEventsTicketDisplayZoom);

        } else {

            update_post_meta($post_id, 'WooCommerceEventsTicketDisplayZoom', 'off');

        }
        
        if(isset($_POST['WooCommerceEventsIncludeCustomAttendeeDetails'])) {
            
            $WooCommerceEventsIncludeCustomAttendeeDetails = sanitize_text_field($_POST['WooCommerceEventsIncludeCustomAttendeeDetails']);
            update_post_meta($post_id, 'WooCommerceEventsIncludeCustomAttendeeDetails', $WooCommerceEventsIncludeCustomAttendeeDetails);

        } else {

            update_post_meta($post_id, 'WooCommerceEventsIncludeCustomAttendeeDetails', 'off');

        }

        if(isset($_POST['WooCommerceEventsCaptureAttendeeDetails'])) {
            
            $WooCommerceEventsCaptureAttendeeDetails = sanitize_text_field($_POST['WooCommerceEventsCaptureAttendeeDetails']);
            update_post_meta($post_id, 'WooCommerceEventsCaptureAttendeeDetails', $WooCommerceEventsCaptureAttendeeDetails);

        } else {

            update_post_meta($post_id, 'WooCommerceEventsCaptureAttendeeDetails', 'off');

        }
        
        if(isset($_POST['WooCommerceEventsEmailAttendee'])) {
            
            $WooCommerceEventsEmailAttendee = sanitize_text_field($_POST['WooCommerceEventsEmailAttendee']);
            update_post_meta($post_id, 'WooCommerceEventsEmailAttendee', $WooCommerceEventsEmailAttendee);

        } else {

            update_post_meta($post_id, 'WooCommerceEventsEmailAttendee', 'off');

        }

        if(isset($_POST['WooCommerceEventsCaptureAttendeeTelephone'])) {
            
            $WooCommerceEventsCaptureAttendeeTelephone = sanitize_text_field($_POST['WooCommerceEventsCaptureAttendeeTelephone']);
            update_post_meta($post_id, 'WooCommerceEventsCaptureAttendeeTelephone', $WooCommerceEventsCaptureAttendeeTelephone);

        } else {

            update_post_meta($post_id, 'WooCommerceEventsCaptureAttendeeTelephone', 'off');

        }

        if(isset($_POST['WooCommerceEventsCaptureAttendeeCompany'])) {
            
            $WooCommerceEventsCaptureAttendeeCompany = sanitize_text_field($_POST['WooCommerceEventsCaptureAttendeeCompany']);
            update_post_meta($post_id, 'WooCommerceEventsCaptureAttendeeCompany', $WooCommerceEventsCaptureAttendeeCompany);

        } else {

            update_post_meta($post_id, 'WooCommerceEventsCaptureAttendeeCompany', 'off');

        }

        if(isset($_POST['WooCommerceEventsCaptureAttendeeDesignation'])) {
            
            $WooCommerceEventsCaptureAttendeeDesignation = sanitize_text_field($_POST['WooCommerceEventsCaptureAttendeeDesignation']);
            update_post_meta($post_id, 'WooCommerceEventsCaptureAttendeeDesignation', $WooCommerceEventsCaptureAttendeeDesignation);

        } else {

            update_post_meta($post_id, 'WooCommerceEventsCaptureAttendeeDesignation', 'off');

        }

        if(isset($_POST['WooCommerceEventsSendEmailTickets'])) {
            
            $WooCommerceEventsSendEmailTickets = sanitize_text_field($_POST['WooCommerceEventsSendEmailTickets']);
            update_post_meta($post_id, 'WooCommerceEventsSendEmailTickets', $WooCommerceEventsSendEmailTickets);

        } else {

            update_post_meta($post_id, 'WooCommerceEventsSendEmailTickets', 'off');

        }

        if(isset($_POST['WooCommerceEventsEmailSubjectSingle'])) {
            
            $WooCommerceEventsEmailSubjectSingle = htmlentities($_POST['WooCommerceEventsEmailSubjectSingle']);
            update_post_meta($post_id, 'WooCommerceEventsEmailSubjectSingle', $WooCommerceEventsEmailSubjectSingle);

        } else {

            update_post_meta($post_id, 'WooCommerceEventsEmailSubjectSingle', '{OrderNumber} Ticket');

        }

        if(isset($_POST['WooCommerceEventsExportUnpaidTickets'])) {
            
            $WooCommerceEventsExportUnpaidTickets = sanitize_text_field($_POST['WooCommerceEventsExportUnpaidTickets']);
            update_post_meta($post_id, 'WooCommerceEventsExportUnpaidTickets', $WooCommerceEventsExportUnpaidTickets);

        } else {

            update_post_meta($post_id, 'WooCommerceEventsExportUnpaidTickets', 'off');

        }

        if(isset($_POST['WooCommerceEventsExportBillingDetails'])) {
            
            $WooCommerceEventsExportBillingDetails = sanitize_text_field($_POST['WooCommerceEventsExportBillingDetails']);
            update_post_meta($post_id, 'WooCommerceEventsExportBillingDetails', $WooCommerceEventsExportBillingDetails);

        } else {

            update_post_meta($post_id, 'WooCommerceEventsExportBillingDetails', 'off');

        }
        
        if(isset($_POST['WooCommerceEventsTicketTheme'])) {
            
            $WooCommerceEventsTicketTheme = sanitize_text_field($_POST['WooCommerceEventsTicketTheme']);
            update_post_meta($post_id, 'WooCommerceEventsTicketTheme', $WooCommerceEventsTicketTheme);

        } 
        
        if(isset($_POST['WooCommerceEventsPDFTicketTheme'])) {

            update_post_meta($post_id, 'WooCommerceEventsPDFTicketTheme', sanitize_text_field($_POST['WooCommerceEventsPDFTicketTheme']));

        } 
        
        if(isset($_POST['WooCommerceEventsAttendeeOverride'])) {
            
            $WooCommerceEventsAttendeeOverride = sanitize_text_field($_POST['WooCommerceEventsAttendeeOverride']);
            update_post_meta($post_id, 'WooCommerceEventsAttendeeOverride', $WooCommerceEventsAttendeeOverride);

        }

        if(isset($_POST['WooCommerceEventsAttendeeOverridePlural'])) {
            
            $WooCommerceEventsAttendeeOverridePlural = sanitize_text_field($_POST['WooCommerceEventsAttendeeOverridePlural']);
            update_post_meta($post_id, 'WooCommerceEventsAttendeeOverridePlural', $WooCommerceEventsAttendeeOverridePlural);

        }
        
        if(isset($_POST['WooCommerceEventsTicketOverride'])) {
            
            $WooCommerceEventsTicketOverride = sanitize_text_field($_POST['WooCommerceEventsTicketOverride']);
            update_post_meta($post_id, 'WooCommerceEventsTicketOverride', $WooCommerceEventsTicketOverride);

        }

        if(isset($_POST['WooCommerceEventsTicketOverridePlural'])) {
            
            $WooCommerceEventsTicketOverridePlural = sanitize_text_field($_POST['WooCommerceEventsTicketOverridePlural']);
            update_post_meta($post_id, 'WooCommerceEventsTicketOverridePlural', $WooCommerceEventsTicketOverridePlural);

        }
        
        if(isset($_POST['WooCommerceEventsDayOverride'])) {
            
            $WooCommerceEventsDayOverride = sanitize_text_field($_POST['WooCommerceEventsDayOverride']);
            update_post_meta($post_id, 'WooCommerceEventsDayOverride', $WooCommerceEventsDayOverride);

        }

        if(isset($_POST['WooCommerceEventsDayOverridePlural'])) {
            
            $WooCommerceEventsDayOverridePlural = sanitize_text_field($_POST['WooCommerceEventsDayOverridePlural']);
            update_post_meta($post_id, 'WooCommerceEventsDayOverridePlural', $WooCommerceEventsDayOverridePlural);

        }

        if(isset($_POST['WooCommerceEventsViewSeatingChart'])) {
            
            $WooCommerceEventsViewSeatingChart = sanitize_text_field($_POST['WooCommerceEventsViewSeatingChart']);
            update_post_meta($post_id, 'WooCommerceEventsViewSeatingChart', $WooCommerceEventsViewSeatingChart);

        } else {

            update_post_meta($post_id, 'WooCommerceEventsViewSeatingChart', 'off');

        }

        $this->save_printing_options($post_id, false);

        if(isset($_POST['WooCommerceEventsZoomMultiOption'])) {
            
            $WooCommerceEventsZoomMultiOption = sanitize_text_field($_POST['WooCommerceEventsZoomMultiOption']);
            update_post_meta($post_id, 'WooCommerceEventsZoomMultiOption', $WooCommerceEventsZoomMultiOption);
        } 

        if(isset($_POST['WooCommerceEventsZoomWebinar'])) {
            
            $WooCommerceEventsZoomWebinar = sanitize_text_field($_POST['WooCommerceEventsZoomWebinar']);
            update_post_meta($post_id, 'WooCommerceEventsZoomWebinar', $WooCommerceEventsZoomWebinar);

        } 

        if(isset($_POST['WooCommerceEventsZoomWebinarMulti'])) {
            
            $WooCommerceEventsZoomWebinarMulti = $_POST['WooCommerceEventsZoomWebinarMulti'];
            update_post_meta($post_id, 'WooCommerceEventsZoomWebinarMulti', $WooCommerceEventsZoomWebinarMulti);
        }

    }

    /**
     * Displays the event details on the front end template. Before WooCommerce Displays content.
     * 
     * @param array $tabs
     * @global object $post
     * @return array $tabs
     */
    public function add_front_end_tab($tabs) {

        global $post;

        $WooCommerceEventsEvent = get_post_meta($post->ID, 'WooCommerceEventsEvent', true);
        $WooCommerceEventsGoogleMaps = get_post_meta($post->ID, 'WooCommerceEventsGoogleMaps', true);
        $globalWooCommerceHideEventDetailsTab = get_option('globalWooCommerceHideEventDetailsTab', true);

        if($WooCommerceEventsEvent == 'Event') {

            if($globalWooCommerceHideEventDetailsTab !== 'yes') {

                $tabs['woocommerce_events'] = array(
                    'title'     => __('Event Details', 'woocommerce-events'),
                    'priority'  => 30,
                    'callback'  => 'fooevents_displayEventTab'
                );

            }

            if(!empty($WooCommerceEventsGoogleMaps)) {

                $tabs['description'] = array(
                    'title'     => __('Description', 'woocommerce-events'),
                    'priority' => 1,
                    'callback'  => 'fooevents_displayEventTabMap'
                );

            }

        }
        
        return $tabs;

    }

    /**
     * Creates an orders tickets
     * 
     * @param int $order_id
     */
    public function create_tickets($order_id) {

        $WooCommerceEventsOrderTickets = get_post_meta($order_id, 'WooCommerceEventsOrderTickets', true);
        $WooCommerceEventsSentTicket =  get_post_meta($order_id, 'WooCommerceEventsTicketsGenerated', true);

        if($WooCommerceEventsSentTicket != 'yes' && !empty($WooCommerceEventsOrderTickets)) {

            $x = 1;
            foreach($WooCommerceEventsOrderTickets as $event => $tickets) {

                $y = 1;
                foreach($tickets as $ticket) {
                    
                    if(!empty($ticket['WooCommerceEventsOrderID'])) {

                        $rand = rand(111111,999999);

                        $post = array(

                                'post_author' => $ticket['WooCommerceEventsCustomerID'],
                                'post_content' => "Ticket",
                                'post_status' => "publish",
                                'post_title' => 'Assigned Ticket',
                                'post_type' => "event_magic_tickets"

                        );

                        $post['ID'] = wp_insert_post( $post );
                        $ticketID = $post['ID'].$rand;
                        $post['post_title'] = '#'.$ticketID;
                        $postID = wp_update_post( $post );

                        $ticketHash = $this->generate_random_string(8);

                        update_post_meta($postID, 'WooCommerceEventsTicketID', $ticketID);
                        update_post_meta($postID, 'WooCommerceEventsTicketHash', $ticketHash);
                        update_post_meta($postID, 'WooCommerceEventsProductID', $ticket['WooCommerceEventsProductID']);
                        update_post_meta($postID, 'WooCommerceEventsOrderID', $ticket['WooCommerceEventsOrderID']);
                        update_post_meta($postID, 'WooCommerceEventsTicketType', $ticket['WooCommerceEventsTicketType']);
                        update_post_meta($postID, 'WooCommerceEventsStatus', 'Unpaid');
                        update_post_meta($postID, 'WooCommerceEventsCustomerID', $ticket['WooCommerceEventsCustomerID']);
                        update_post_meta($postID, 'WooCommerceEventsAttendeeName', $ticket['WooCommerceEventsAttendeeName']);
                        update_post_meta($postID, 'WooCommerceEventsAttendeeLastName', $ticket['WooCommerceEventsAttendeeLastName']);
                        update_post_meta($postID, 'WooCommerceEventsAttendeeEmail', $ticket['WooCommerceEventsAttendeeEmail']);
                        update_post_meta($postID, 'WooCommerceEventsAttendeeTelephone', $ticket['WooCommerceEventsAttendeeTelephone']);
                        update_post_meta($postID, 'WooCommerceEventsAttendeeCompany', $ticket['WooCommerceEventsAttendeeCompany']);
                        update_post_meta($postID, 'WooCommerceEventsAttendeeDesignation', $ticket['WooCommerceEventsAttendeeDesignation']);
                        update_post_meta($postID, 'WooCommerceEventsVariations', $ticket['WooCommerceEventsVariations']);
                        update_post_meta($postID, 'WooCommerceEventsVariationID', $ticket['WooCommerceEventsVariationID']);

                        update_post_meta($postID, 'WooCommerceEventsPurchaserFirstName', $ticket['WooCommerceEventsPurchaserFirstName']);
                        update_post_meta($postID, 'WooCommerceEventsPurchaserLastName', $ticket['WooCommerceEventsPurchaserLastName']);
                        update_post_meta($postID, 'WooCommerceEventsPurchaserEmail', $ticket['WooCommerceEventsPurchaserEmail']);
                        update_post_meta($postID, 'WooCommerceEventsPurchaserPhone', $ticket['WooCommerceEventsPurchaserPhone']);

                        update_post_meta($postID, 'WooCommerceEventsPrice', $ticket['WooCommerceEventsPrice']);

                        if (!function_exists('is_plugin_active_for_network')) {
                            
                            require_once(ABSPATH.'/wp-admin/includes/plugin.php' );
                                
                        }

                        if ($this->is_plugin_active( 'fooevents_custom_attendee_fields/fooevents-custom-attendee-fields.php') || is_plugin_active_for_network('fooevents_custom_attendee_fields/fooevents-custom-attendee-fields.php')) {

                            $Fooevents_Custom_Attendee_Fields = new Fooevents_Custom_Attendee_Fields();
                            $WooCommerceEventsCustomAttendeeFields = $Fooevents_Custom_Attendee_Fields->process_capture_custom_attendee_options($postID, $ticket['WooCommerceEventsCustomAttendeeFields']);

                        }

                        if ($this->is_plugin_active( 'fooevents_seating/fooevents-seating.php' ) || is_plugin_active_for_network('fooevents_seating/fooevents-seating.php')) {

                            $Fooevents_Seating = new Fooevents_Seating();
                            $WooCommerceEventsSeatingFields = $Fooevents_Seating->process_capture_seating_options($postID, $ticket['WooCommerceEventsSeatingFields']);

                        }

                        $product = get_post($ticket['WooCommerceEventsProductID']);

                        update_post_meta($postID, 'WooCommerceEventsProductName', $product->post_title);

                        $y++;
                    }
                        
                }

                $x++;

            }
            
            update_post_meta($order_id, 'WooCommerceEventsTicketsGenerated', 'yes');
            
        }

    }    
    
    /**
     * Sends a ticket email once an order is completed.
     * 
     * @param int $order_id
     * @global $woocommerce, $evotx;
     */
     public function send_ticket_email($order_id) {

        $this->create_tickets($order_id);
     
        set_time_limit(0);

        global $woocommerce;

        $order = new WC_Order( $order_id );
        $tickets = $order->get_items();

        $WooCommerceEventsTicketsPurchased = get_post_meta($order_id, 'WooCommerceEventsTicketsPurchased', true);
        
        $customer = get_post_meta($order_id, '_customer_user', true);
        $usermeta = get_user_meta($customer);

        $WooCommerceEventsSentTicket =  get_post_meta($order_id, 'WooCommerceEventsSentTicket', true);

        if ($this->is_plugin_active( 'fooevents_custom_attendee_fields/fooevents-custom-attendee-fields.php') || is_plugin_active_for_network('fooevents_custom_attendee_fields/fooevents-custom-attendee-fields.php')) {

            $Fooevents_Custom_Attendee_Fields = new Fooevents_Custom_Attendee_Fields();
            $WooCommerceEventsCustomAttendeeFields = $Fooevents_Custom_Attendee_Fields->process_capture_custom_attendee_options($postID, $ticket['WooCommerceEventsCustomAttendeeFields']);

        }
        
        if ($this->is_plugin_active( 'fooevents_seating/fooevents-seating.php' ) || is_plugin_active_for_network('fooevents_seating/fooevents-seating.php')) {

            $Fooevents_Seating = new Fooevents_Seating();
            $WooCommerceEventsSeatingFields = $Fooevents_Seating->process_capture_seating_options($postID, $ticket['WooCommerceEventsSeatingFields']);

        }

        $product = get_post($ticket['WooCommerceEventsProductID']);

        update_post_meta($postID, 'WooCommerceEventsProductName', $product->post_title);

        $x++;

    }    

    /**
    * Sends a ticket email once an order is completed.
    * 
    * @param int $order_id
    * @global $woocommerce, $evotx;
    */
    public function process_order_tickets($order_id) {

        set_time_limit(0);
        
        $this->create_tickets($order_id);
        $this->build_send_tickets($order_id);
        $this->ZoomAPIHelper->add_update_zoom_registrants($order_id);

    }

    /**
     * Builds tickets to be emailed
     * 
     * @param int $order_id
     */
    public function build_send_tickets($order_id) {

        $order = array();
        try {
            
            $order = new WC_Order($order_id);
            
        } catch (Exception $e) {
            
        }  
        
        $tickets_query = new WP_Query( array('post_type' => array('event_magic_tickets'), 'posts_per_page' => -1, 'meta_query' => array( array( 'key' => 'WooCommerceEventsOrderID', 'value' => $order_id ) )) );
        $orderTickets = $tickets_query->get_posts();
        
        $emailHTML = '';
        
        $sortedOrderTickets = array();
        
        //Sort tickets into events
        foreach($orderTickets as $orderTicket) {
            
            $ticket = $this->TicketHelper->get_ticket_data($orderTicket->ID);
            $sortedOrderTickets[$ticket['WooCommerceEventsProductID']][] = $ticket;
            
        }
        
        foreach($sortedOrderTickets as $productID => $tickets) {

            $WooCommerceEventsEmailAttendee = get_post_meta($productID, 'WooCommerceEventsEmailAttendee', true);
            $WooCommerceEventsEmailSubjectSingle = get_post_meta($productID, 'WooCommerceEventsEmailSubjectSingle', true);
            
            if(empty($WooCommerceEventsEmailSubjectSingle)) {

                $WooCommerceEventsEmailSubjectSingle  = __('{OrderNumber} Ticket', 'woocommerce-events');

            }
            
            $subject = str_replace('{OrderNumber}', '[#'.$order_id.']', $WooCommerceEventsEmailSubjectSingle);
            
            $WooCommerceEventsTicketTheme = get_post_meta($productID, 'WooCommerceEventsTicketTheme', true);
            if(empty($WooCommerceEventsTicketTheme)) {
                
                $WooCommerceEventsTicketTheme = $this->Config->emailTemplatePath;
                
            }
            
            $header = $this->MailHelper->parse_email_template($WooCommerceEventsTicketTheme.'/header.php', array(), $tickets[0]); 
            $footer = $this->MailHelper->parse_email_template($WooCommerceEventsTicketTheme.'/footer.php', array(), $tickets[0]);
            
            $ticketBody = '';
            
            $emailAttendee = false;
            $ticketCount = 1;
            
            foreach($tickets as $ticket) {

                if ($WooCommerceEventsEmailAttendee == 'on') {
                    
                    $ticket['ticketNumber'] = 1;
                    
                } else {
                    
                    $ticket['ticketNumber'] = $ticketCount;
                    
                }    
                $ticket['ticketTotal'] = count($orderTickets);
                $body = $this->MailHelper->parse_ticket_template($WooCommerceEventsTicketTheme.'/ticket.php', $ticket);
                $ticketBody .= $body;

                //Send to attendee
                if ($WooCommerceEventsEmailAttendee == 'on' && isset($ticket['WooCommerceEventsAttendeeEmail'])) {
                    
                    $attachments = array();
                    if (!function_exists('is_plugin_active_for_network')) {
                        
                        require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
                        
                    }
                    if ( $this->is_plugin_active('fooevents_pdf_tickets/fooevents-pdf-tickets.php') || is_plugin_active_for_network('fooevents_pdf_tickets/fooevents-pdf-tickets.php')) {
                        
                        $globalFooEventsPDFTicketsEnable = get_option( 'globalFooEventsPDFTicketsEnable' );
                        $globalFooEventsPDFTicketsAttachHTMLTicket = get_option( 'globalFooEventsPDFTicketsAttachHTMLTicket' );

                        if($globalFooEventsPDFTicketsEnable == 'yes') {

                            $FooEvents_PDF_Tickets = new FooEvents_PDF_Tickets();
                            
                            $attachments[] = $FooEvents_PDF_Tickets->generate_ticket($productID, array($ticket), $this->Config->barcodePath, $this->Config->path);
                            $FooEventsPDFTicketsEmailText = get_post_meta($productID, 'FooEventsPDFTicketsEmailText', true);
                            
                            if($globalFooEventsPDFTicketsAttachHTMLTicket !== 'yes') {
                                
                                $header = $FooEvents_PDF_Tickets->parse_email_template('email-header.php');
                                $footer = $FooEvents_PDF_Tickets->parse_email_template('email-footer.php');

                                $body = $header.$FooEventsPDFTicketsEmailText.$footer;
                            
                            }
                            
                            if(empty($body)) {

                                $body = __('Your tickets are attached. Please print them and bring them to the event.', 'fooevents-pdf-tickets');

                            }
                            
                        }
                        
                    }

                    // attach ics
                    $WooCommerceEventsTicketAttachICS = get_post_meta($productID, 'WooCommerceEventsTicketAttachICS', true);

                    if (!empty($WooCommerceEventsTicketAttachICS) && $WooCommerceEventsTicketAttachICS == 'on' && file_exists($this->Config->icsPath.$ticket['WooCommerceEventsTicketID'].'.ics')) {

                        $attachments[] = $this->Config->icsPath.''.$ticket['WooCommerceEventsTicketID'].'.ics';

                    }
                    
                    if($ticket['WooCommerceEventsSendEmailTickets'] === 'on') {
                    
                        $mailStatus = $this->MailHelper->send_ticket($ticket['WooCommerceEventsAttendeeEmail'], $subject, $header.$body.$footer, $attachments);
                    
                    }
                    
                    $emailAttendee = true;

                }

                $ticketCount++;
                
            }
            
            //Send to purchaser
            if ($WooCommerceEventsEmailAttendee != 'on' && $emailAttendee === false) {
                
                $attachments = array();
                
                if (!function_exists('is_plugin_active_for_network')) {
                    
                    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
                    
                }
                if ($this->is_plugin_active('fooevents_pdf_tickets/fooevents-pdf-tickets.php') || is_plugin_active_for_network('fooevents_pdf_tickets/fooevents-pdf-tickets.php')) {
                    
                    $globalFooEventsPDFTicketsEnable = get_option('globalFooEventsPDFTicketsEnable');
                    $globalFooEventsPDFTicketsLayout = get_option('globalFooEventsPDFTicketsLayout');
                    $globalFooEventsPDFTicketsAttachHTMLTicket = get_option('globalFooEventsPDFTicketsAttachHTMLTicket');
                    
                    if(empty($globalFooEventsPDFTicketsLayout)) {

                        $globalFooEventsPDFTicketsLayout = 'single';

                    }
                    
                    if($globalFooEventsPDFTicketsEnable == 'yes') {

                        $FooEvents_PDF_Tickets = new FooEvents_PDF_Tickets();

                        $attachments[] = $FooEvents_PDF_Tickets->generate_ticket($productID, $tickets, $this->Config->barcodePath, $this->Config->path);

                        if($globalFooEventsPDFTicketsAttachHTMLTicket === 'yes') {

                            $attachedText = get_post_meta($productID, 'FooEventsPDFTicketsEmailText', true);

                            if (empty($attachedText)) {
                                
                                $attachedText = __('Your tickets are attached. Please print them and bring them to the event.', 'fooevents-pdf-tickets');
                                
                            }

                            $header = $header.$attachedText;

                        } else {
      
                            $ticketBody = get_post_meta($productID, 'FooEventsPDFTicketsEmailText', true);
							
                            if(empty($ticketBody)||$ticketBody == '') {
                                
                                $ticketBody = __('Your tickets are attached. Please print them and bring them to the event.', 'fooevents-pdf-tickets');
                                
                            }    
                               
                            $header = $FooEvents_PDF_Tickets->parse_email_template('email-header.php');
                            $footer = $FooEvents_PDF_Tickets->parse_email_template('email-footer.php');
                            
                        } 

                    }
                    
                }
                
                // attach ics
                $WooCommerceEventsTicketAttachICS = get_post_meta($productID, 'WooCommerceEventsTicketAttachICS', true);

                if (!empty($WooCommerceEventsTicketAttachICS) && $WooCommerceEventsTicketAttachICS == 'on' && file_exists($this->Config->icsPath.$ticket['WooCommerceEventsTicketID'].'.ics')) {

                    $attachments[] = $this->Config->icsPath.''.$ticket['WooCommerceEventsTicketID'].'.ics';

                }

                $orderEmailAddress = $order->get_billing_email();

                if($ticket['WooCommerceEventsSendEmailTickets'] === 'on') {

                    $mailStatus = $this->MailHelper->send_ticket($orderEmailAddress, $subject, $header.$ticketBody.$footer, $attachments);
                    
                }
            }
            
        }

    }

    /**
     * Displays thank you text on order completion page.
     * 
     * @param type $thankYouText
     * @return type
     */
    public function display_thank_you_text($thankYouText) {

        global $woocommerce;
        global $post;

        $actualLink = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $segments = array_reverse(explode('/', $actualLink));

        $orderID = $segments[1];
        $order = new WC_Order($orderID);
        $items = $order->get_items();

        $products = array();

        foreach($items as $item) {

            $products[$item['product_id']] = $item['product_id'];

        }

        foreach($products as $key => $productID) {

            $WooCommerceEventsThankYouText = get_post_meta($productID, 'WooCommerceEventsThankYouText', true);

            if(!empty($WooCommerceEventsThankYouText)) {

                echo $WooCommerceEventsThankYouText."<br/><br/>";

            }

        }

        return $thankYouText;

    }

    /**
     * Cancels ticket when order is canceled.
     * 
     * @param int $order_id
     */
    public function order_status_cancelled($order_id) {

        $tickets = new WP_Query(array('post_type' => array('event_magic_tickets'), 'posts_per_page' => -1, 'meta_query' => array(array('key' => 'WooCommerceEventsOrderID', 'value' => $order_id))));
        $tickets = $tickets->get_posts();

        foreach ($tickets as $ticket) {

            update_post_meta($ticket->ID, 'WooCommerceEventsStatus', 'Canceled');

        }

        $this->ZoomAPIHelper->cancel_zoom_registrations($tickets);
        
        return $order_id;
        
    }

    /**
     * Filter WooCommerce products based on event filter selection
     * 
     * @param array $query 
     */
    public function filter_product_results($query) {

        global $pagenow;
        global $typenow;
        $fooevents_filter ='';
        if (is_admin() && $typenow == 'product' && isset($_GET['fooevents_filter']) && '' != $_GET['fooevents_filter']) {
            $fooevents_filter = sanitize_text_field($_GET['fooevents_filter']);

            switch ($fooevents_filter) {
                case 'events':
                    // All events
                    $query->query_vars['meta_key']   = 'WooCommerceEventsEvent';
                    $query->query_vars['meta_value'] = 'Event';
                break;
                case 'non-events':
                    // All non-events
                    $query->query_vars['meta_key']   = 'WooCommerceEventsEvent';
                    $query->query_vars['meta_value'] = 'NotEvent';
                break;
            }     
        }
    }  

    /**
     * Adds FooEvents drop down filter selection to the WooCommerce product listing
     * 
     */
    public function filter_product_options() {
        global $typenow;
        if ($typenow == 'product') {
            $fooevents_filter ='';
            if (isset($_GET['fooevents_filter']) && '' != $_GET['fooevents_filter']) {
                $fooevents_filter = sanitize_text_field($_GET['fooevents_filter']);
            }
            global $wpdb;

            $foo ='meta_key';
            $fields = $wpdb->get_results($wpdb->prepare('SELECT DISTINCT %s FROM ' . $wpdb->postmeta . ' ORDER BY 1', $foo));
            
            require($this->Config->templatePath.'productfilteroptions.php');
        }
    }      

    /**
     * Add postmeta ordering arguments
     * 
     * @param array $sort_args
     * @return array $sort_args         
     */
    function add_postmeta_ordering($sort_args) {
            
        $orderby_value = isset($_GET['orderby']) ? wc_clean($_GET['orderby']) : apply_filters('woocommerce_default_catalog_orderby', get_option('woocommerce_default_catalog_orderby'));
        switch($orderby_value) {
        
            case 'eventdate-asc':
                $sort_args['orderby']= 'meta_value';
                $sort_args['order']= 'asc';
                $sort_args['meta_key'] = 'WooCommerceEventsDateTimestamp'; 
            break;
                    
            case 'eventdate-desc':
                $sort_args['orderby'] = 'meta_value';
                $sort_args['order'] = 'desc';
                $sort_args['meta_key'] = 'WooCommerceEventsDateTimestamp'; 
            break;
            
        }
        
        return $sort_args;
    }

    /**
     * Add postmeta order by options to WooCommerce order options
     * 
     * @param array $sortby
     * @return array $sortby     
     */
    function add_postmeta_orderby($sortby) {
        
        global $post;      
        $globalWooCommerceEventSorting = get_option('globalWooCommerceEventSorting', true);
        if($globalWooCommerceEventSorting === 'yes') {

            $sortby['eventdate-asc'] = __('Sort by event date: Old to New', 'woocommerce-events');
            $sortby['eventdate-desc'] = __('Sort by event date: New to Old', 'woocommerce-events');

        }
        return $sortby;
    }
 
    /**
     * Add event date to product template
     * 
     */
    function display_product_date() {
        
        global $post;      
        
        if (!function_exists('is_plugin_active_for_network')) {
            
            require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
            
        }
        
        $globalWooCommerceDisplayEventDate = get_option('globalWooCommerceDisplayEventDate', true);
        
        if($globalWooCommerceDisplayEventDate === 'yes') {
            
            $event_date = get_post_meta($post->ID, 'WooCommerceEventsDate', true); 
            
            if ($this->is_plugin_active('fooevents_multi_day/fooevents-multi-day.php') || is_plugin_active_for_network('fooevents_multi_day/fooevents-multi-day.php')) {

                $Fooevents_Multiday_Events = new Fooevents_Multiday_Events();
                $event_date = $Fooevents_Multiday_Events->get_multi_day_date_range($post->ID);

            }

            if (is_home() || is_shop() || is_product_category() || is_product_tag()) {

               echo '<p class="event-date">';
               echo $event_date; 
               echo '</p>';

            }

        }
        
    }      

    public function order_status_completed_cancelled($order_id) {

        $tickets = new WP_Query(array('post_type' => array('event_magic_tickets'), 'posts_per_page' => -1, 'meta_query' => array(array('key' => 'WooCommerceEventsOrderID', 'value' => $order_id))));
        $tickets = $tickets->get_posts();

        foreach ($tickets as $ticket) {

            $ticketStatus = get_post_meta($ticket->ID, 'WooCommerceEventsStatus', true);

            if($ticketStatus == 'Canceled') {

                update_post_meta($ticket->ID, 'WooCommerceEventsStatus', 'Not Checked In');

            }

        }

    }

    /**
     * Generates attendee CSV export.
     * 
     */
    public function woocommerce_events_csv() {
        
        if(!current_user_can('publish_event_magic_tickets'))
        {
            
            echo "User role does not have permission to export attendee details. Please contact site admin.";
            exit();
            
        }

        global $woocommerce;

        $event = sanitize_text_field($_GET['event']);
        $includeUnpaidTickets = sanitize_text_field($_GET['exportunpaidtickets']);
        $exportbillingdetails = sanitize_text_field($_GET['exportbillingdetails']);
        
        $csv_blueprint = array("TicketID", "OrderID", "Attendee First Name", "Attendee Last Name", "Attendee Email", "Ticket Status", "Ticket Type", "Variation", "Attendee Telephone", "Attendee Company", "Attendee Designation", "Purchaser First Name", "Purchaser Last Name", "Purchaser Email", "Purchaser Phone", "Purchaser Company");
        $sorted_rows = array();
        
        $events_query = new WP_Query( array('post_type' => array('event_magic_tickets'), 'posts_per_page' => -1, 'meta_query' => array( array( 'key' => 'WooCommerceEventsProductID', 'value' => $event ) )) );
        $events = $events_query->get_posts();
        
        $x = 0;
        foreach($events as $eventItem) {
            
            $id = $eventItem->ID;
            $ticket = get_post($id);
            $ticketID = $ticket->post_title;
            
            $order_id = get_post_meta($id, 'WooCommerceEventsOrderID', true);
            $product_id = get_post_meta($id, 'WooCommerceEventsProductID', true);
            $customer_id = get_post_meta($id, 'WooCommerceEventsCustomerID', true);
            $WooCommerceEventsStatus = get_post_meta($id, 'WooCommerceEventsStatus', true);
            $ticketType = get_post_meta($ticket->ID, 'WooCommerceEventsTicketType', true);
            
            if($includeUnpaidTickets != 'true' && $WooCommerceEventsStatus == 'Unpaid') {
                
                continue;
                
            }
            
            $WooCommerceEventsVariations = get_post_meta($id, 'WooCommerceEventsVariations', true);
            if(!empty($WooCommerceEventsVariations) && !is_array($WooCommerceEventsVariations)) {
                
                $WooCommerceEventsVariations = json_decode($WooCommerceEventsVariations);
                
            }
            $variationOutput = '';
            $i = 0;
            if(!empty($WooCommerceEventsVariations)) {
                foreach($WooCommerceEventsVariations as $variationName => $variationValue) {

                    if($i > 0) {

                        $variationOutput .= ' | ';

                    }

                    $variationNameOutput = str_replace('attribute_', '', $variationName);
                    $variationNameOutput = str_replace('pa_', '', $variationNameOutput);
                    $variationNameOutput = str_replace('_', ' ', $variationNameOutput);
                    $variationNameOutput = str_replace('-', ' ', $variationNameOutput);
                    $variationNameOutput = str_replace('', ' ', $variationNameOutput);
                    $variationNameOutput = str_replace('Pa_', '', $variationNameOutput);
                    $variationNameOutput = ucwords($variationNameOutput);

                    $variationValueOutput = str_replace('_', ' ', $variationValue);
                    $variationValueOutput = str_replace('-', ' ', $variationValueOutput);

                    $variationValueOutput = str_replace(',', '', $variationValueOutput);

                    $variationValueOutput = ucwords($variationValueOutput);

                    $variationOutput .= $variationNameOutput.': '.$variationValueOutput;

                    $i++;
                }
            }
            
            $order = '';
            
            try {
                
                $order = new WC_Order($order_id);
                
            } catch (Exception $e) {

            } 
            
            $WooCommerceEventsAttendeeName = get_post_meta($ticket->ID, 'WooCommerceEventsAttendeeName', true);
            if(empty($WooCommerceEventsAttendeeName)) {

                if(!empty($order)) {

                    $WooCommerceEventsAttendeeName = $order->get_billing_first_name();

                } else {

                    $WooCommerceEventsAttendeeName = '';

                }

            } 
            
            $WooCommerceEventsAttendeeLastName = get_post_meta($ticket->ID, 'WooCommerceEventsAttendeeLastName', true);
            if(empty($WooCommerceEventsAttendeeLastName)) {

                if(!empty($order)) {

                    $WooCommerceEventsAttendeeLastName = $order->get_billing_last_name();

                } else {

                    $WooCommerceEventsAttendeeLastName = '';

                }

            }
            
            $WooCommerceEventsAttendeeEmail = get_post_meta($ticket->ID, 'WooCommerceEventsAttendeeEmail', true);
            if(empty($WooCommerceEventsAttendeeEmail)) {

                if(!empty($order)) {

                    $WooCommerceEventsAttendeeEmail = $order->get_billing_email();

                } else {

                    $WooCommerceEventsAttendeeEmail = '';

                }

            }
            
            $WooCommerceEventsPurchaserPhone = get_post_meta($ticket->ID, 'WooCommerceEventsPurchaserPhone', true);
            if(empty($WooCommerceEventsPurchaserPhone)) {

                if(!empty($order)) {

                    $WooCommerceEventsPurchaserPhone = $order->get_billing_phone();

                } else {

                    $WooCommerceEventsPurchaserPhone = '';

                }

            }
            
            $WooCommerceEventsCaptureAttendeeTelephone = get_post_meta($ticket->ID, 'WooCommerceEventsAttendeeTelephone', true);
            $WooCommerceEventsCaptureAttendeeCompany = get_post_meta($ticket->ID, 'WooCommerceEventsAttendeeCompany', true);
            $WooCommerceEventsCaptureAttendeeDesignation = get_post_meta($ticket->ID, 'WooCommerceEventsAttendeeDesignation', true);
            $WooCommerceEventsPurchaserFirstName = get_post_meta($ticket->ID, 'WooCommerceEventsPurchaserFirstName', true);
            $WooCommerceEventsPurchaserLastName = get_post_meta($ticket->ID, 'WooCommerceEventsPurchaserLastName', true);
            $WooCommerceEventsPurchaserEmail = get_post_meta($ticket->ID, 'WooCommerceEventsPurchaserEmail', true);
            
            $sorted_rows[$x]["TicketID"] = $ticketID;
            $sorted_rows[$x]["OrderID"] = $order_id;
            $sorted_rows[$x]["Attendee First Name"] = $WooCommerceEventsAttendeeName;
            $sorted_rows[$x]["Attendee Last Name"] = $WooCommerceEventsAttendeeLastName;
            $sorted_rows[$x]["Attendee Email"] = $WooCommerceEventsAttendeeEmail;
            $sorted_rows[$x]["Ticket Status"] = $WooCommerceEventsStatus;
            $sorted_rows[$x]["Ticket Type"] = $ticketType;
            $sorted_rows[$x]["Variation"] = $variationOutput;
            $sorted_rows[$x]["Attendee Telephone"] = $WooCommerceEventsCaptureAttendeeTelephone;
            $sorted_rows[$x]["Attendee Company"] = $WooCommerceEventsCaptureAttendeeCompany;
            $sorted_rows[$x]["Attendee Designation"] = $WooCommerceEventsCaptureAttendeeDesignation;
            $sorted_rows[$x]["Purchaser First Name"] = $WooCommerceEventsPurchaserFirstName;
            $sorted_rows[$x]["Purchaser Last Name"] = $WooCommerceEventsPurchaserLastName;
            $sorted_rows[$x]["Purchaser Email"] = $WooCommerceEventsPurchaserEmail;
            $sorted_rows[$x]["Purchaser Phone"] = $WooCommerceEventsPurchaserPhone;
            
            if(!empty($order)) {

                $sorted_rows[$x]["Purchaser Company"] = $order->get_billing_company();

            } else {

                $sorted_rows[$x]["Purchaser Company"] = '';

            }
            
            if(!empty($exportbillingdetails)) {
                
                if(!empty($order)) {
                
                    $billing_address_1 = $order->get_billing_address_1();

                    $billing_fields = array("Billing Address 1" => '', "Billing Address 2" => '', "Billing City" => '', "Billing Postal Code" => '', "Billing Country" => '', "Billing State" => '', "Billing Phone Number" => '');
                    $billing_headings = array_keys($billing_fields);

                    foreach ($billing_headings as $value) {

                        if(!in_array($value, $csv_blueprint)) {

                            $csv_blueprint[] = $value;

                        }

                    }

                    $sorted_rows[$x]["Billing Address 1"] = $order->get_billing_address_1();
                    $sorted_rows[$x]["Billing Address 2"] = $order->get_billing_address_2();
                    $sorted_rows[$x]["Billing City"] = $order->get_billing_city();
                    $sorted_rows[$x]["Billing Postal Code"] = $order->get_billing_postcode();
                    $sorted_rows[$x]["Billing Country"] = $order->get_billing_country();
                    $sorted_rows[$x]["Billing State"] = $order->get_billing_state();
                    $sorted_rows[$x]["Billing Phone Number"] = $order->get_billing_phone();
                    
                }
            }
            
            if (!function_exists( 'is_plugin_active_for_network')) {
                
                require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
                
            }
            
            if ($this->is_plugin_active( 'fooevents_custom_attendee_fields/fooevents-custom-attendee-fields.php') || is_plugin_active_for_network('fooevents_custom_attendee_fields/fooevents-custom-attendee-fields.php')) {

                $Fooevents_Custom_Attendee_Fields = new Fooevents_Custom_Attendee_Fields();
                $fooevents_custom_attendee_fields_options = $Fooevents_Custom_Attendee_Fields->display_tickets_meta_custom_options_array($id);
                
                $fooevents_custom_attendee_fields_headings = array_keys($fooevents_custom_attendee_fields_options);

                foreach ($fooevents_custom_attendee_fields_headings as $value) {
                    
                    if(!in_array($value, $csv_blueprint)) {
                        
                        $csv_blueprint[] = $value;
                        
                    }
                    
                }
                
                foreach($fooevents_custom_attendee_fields_options as $key => $value) {
                    
                    $sorted_rows[$x][$key] = $value;
                    
                }
                
            }
            
            if ($this->is_plugin_active('fooevents_seating/fooevents-seating.php') || is_plugin_active_for_network('fooevents_seating/fooevents-seating.php')) {

                $Fooevents_Seating = new Fooevents_Seating();
                $fooevents_seating_options = $Fooevents_Seating->display_tickets_meta_seat_options_array($id);
                
                $fooevents_seating_headings = array_keys($fooevents_seating_options);

                foreach ($fooevents_seating_headings as $value) {
                    
                    if(!in_array($value, $csv_blueprint)) {
                        
                        $csv_blueprint[] = $value;
                        
                    }
                    
                }
                
                foreach($fooevents_seating_options as $key => $value) {
                    
                    $sorted_rows[$x][$key] = $value;
                    
                }
                
            }
            
            if ($this->is_plugin_active('fooevents_multi_day/fooevents-multi-day.php') || is_plugin_active_for_network('fooevents_multi_day/fooevents-multi-day.php')) {
                
                $WooCommerceEventsNumDays = get_post_meta($product_id, 'WooCommerceEventsNumDays', true);
                
                $Fooevents_Multiday_Events = new Fooevents_Multiday_Events();
                $fooevents_multiday_statuses = $Fooevents_Multiday_Events->get_array_of_check_ins($id, $WooCommerceEventsNumDays);

                $fooevents_multiday_statuses_headings = array_keys($fooevents_multiday_statuses);

                foreach ($fooevents_multiday_statuses_headings as $value) {
                    
                    if(!in_array($value, $csv_blueprint)) {
                        
                        $csv_blueprint[] = $value;
                        
                    }
                    
                }
                
                foreach($fooevents_multiday_statuses as $key => $value) {
                    
                    $sorted_rows[$x][$key] = $value;
                    
                }
                
            }

            $x++;
            
        }
       
        //unpaid tickets 
        if($includeUnpaidTickets) {
            
            $statuses = array('wc-processing', 'wc-on-hold', 'wc-pending');
            $order_ids = $this->get_orders_ids_by_product_id( $event, $statuses );
            $order_ids = array_unique($order_ids);

            $x = 0;
            $unpaidTickets = array();
            foreach($order_ids as $order_id) {
                
                $unpaid_order = '';
                try {
                    
                    $unpaid_order = new WC_Order($order_id);
                    
                } catch (Exception $e) {

                } 

                $WooCommerceEventsOrderTickets = get_post_meta($order_id, 'WooCommerceEventsOrderTickets', true);

                if(!empty($WooCommerceEventsOrderTickets)) {
                    foreach ($WooCommerceEventsOrderTickets as $order => $unpaidOrderTickets) {

                        foreach($unpaidOrderTickets as $unpaidOrderTicket) {
                            
                            if($unpaidOrderTicket['WooCommerceEventsProductID'] == $_GET['event']) {
                            
                                $UnpaidWooCommerceEventsAttendeeName = $unpaidOrderTicket['WooCommerceEventsAttendeeName'];
                                if(empty($UnpaidWooCommerceEventsAttendeeName)) {

                                    $UnpaidWooCommerceEventsAttendeeName = $unpaidOrderTicket['WooCommerceEventsPurchaserFirstName'];

                                } 

                                $UnpaidWooCommerceEventsAttendeeLastName = $unpaidOrderTicket['WooCommerceEventsAttendeeLastName'];
                                if(empty($UnpaidWooCommerceEventsAttendeeLastName)) {

                                    $UnpaidWooCommerceEventsAttendeeLastName = $unpaidOrderTicket['WooCommerceEventsPurchaserLastName'];

                                } 

                                $UnpaidWooCommerceEventsAttendeeEmail = $unpaidOrderTicket['WooCommerceEventsAttendeeEmail'];
                                if(empty($UnpaidWooCommerceEventsAttendeeEmail)) {

                                    $UnpaidWooCommerceEventsAttendeeEmail = $unpaidOrderTicket['WooCommerceEventsPurchaserEmail'];

                                }

                                $unpaidOrderWooCommerceEventsVariations = $unpaidOrderTicket['WooCommerceEventsVariations'];
                                if(!empty($unpaidOrderWooCommerceEventsVariations) && !is_array($unpaidOrderWooCommerceEventsVariations)) {

                                    $unpaidOrderWooCommerceEventsVariations = json_decode($unpaidOrderWooCommerceEventsVariations);

                                }

                                $unpaidVariationOutput = '';
                                $i = 0;
                                if(!empty($unpaidOrderWooCommerceEventsVariations)) {
                                    foreach($unpaidOrderWooCommerceEventsVariations as $variationName => $variationValue) {

                                        if($i > 0) {

                                            $variationOutput .= ' | ';

                                        }

                                        $variationNameOutput = str_replace('attribute_', '', $variationName);
                                        $variationNameOutput = str_replace('pa_', '', $variationNameOutput);
                                        $variationNameOutput = str_replace('_', ' ', $variationNameOutput);
                                        $variationNameOutput = str_replace('-', ' ', $variationNameOutput);
                                        $variationNameOutput = str_replace('Pa_', '', $variationNameOutput);
                                        $variationNameOutput = ucwords($variationNameOutput);

                                        $variationValueOutput = str_replace('_', ' ', $variationValue);
                                        $variationValueOutput = str_replace('-', ' ', $variationValueOutput);
                                        $variationValueOutput = ucwords($variationValueOutput);

                                        $unpaidVariationOutput .= $variationNameOutput.': '.$variationValueOutput;

                                        $i++;
                                    }
                                }

                                $unpaidTickets[$x]["TicketID"] = 'NA';
                                $unpaidTickets[$x]["OrderID"] = $unpaidOrderTicket['WooCommerceEventsOrderID'];
                                $unpaidTickets[$x]["Attendee First Name"] = $UnpaidWooCommerceEventsAttendeeName;
                                $unpaidTickets[$x]["Attendee Last Name"] = $UnpaidWooCommerceEventsAttendeeLastName;
                                $unpaidTickets[$x]["Attendee Email"] = $UnpaidWooCommerceEventsAttendeeEmail;
                                $unpaidTickets[$x]["Ticket Status"] = $unpaidOrderTicket['WooCommerceEventsStatus'];
                                $unpaidTickets[$x]["Ticket Type"] = $unpaidOrderTicket['WooCommerceEventsTicketType'];
                                $unpaidTickets[$x]["Variation"] = $unpaidVariationOutput;
                                $unpaidTickets[$x]["Attendee Telephone"] = $unpaidOrderTicket['WooCommerceEventsAttendeeTelephone'];
                                $unpaidTickets[$x]["Attendee Company"] = $unpaidOrderTicket['WooCommerceEventsAttendeeCompany'];
                                $unpaidTickets[$x]["Attendee Designation"] = $unpaidOrderTicket['WooCommerceEventsAttendeeDesignation'];
                                $unpaidTickets[$x]["Purchaser First Name"] = $unpaidOrderTicket['WooCommerceEventsPurchaserFirstName'];
                                $unpaidTickets[$x]["Purchaser Last Name"] = $unpaidOrderTicket['WooCommerceEventsPurchaserLastName'];
                                $unpaidTickets[$x]["Purchaser Email"] = $unpaidOrderTicket['WooCommerceEventsPurchaserEmail'];
                                $unpaidTickets[$x]["Purchaser Phone"] = $unpaid_order->billing_phone;
                                $unpaidTickets[$x]["Purchaser Company"] = $unpaid_order->get_billing_company();

                                if(!empty($exportbillingdetails)) {

                                    $unpaidTickets[$x]["Billing Address 1"] = $unpaid_order->get_billing_address_1();
                                    $unpaidTickets[$x]["Billing Address 2"] = $unpaid_order->get_billing_address_2();
                                    $unpaidTickets[$x]["Billing City"] = $unpaid_order->get_billing_city();
                                    $unpaidTickets[$x]["Billing Postal Code"] = $unpaid_order->get_billing_postcode();
                                    $unpaidTickets[$x]["Billing Country"] = $unpaid_order->get_billing_country();
                                    $unpaidTickets[$x]["Billing State"] = $unpaid_order->get_billing_state();
                                    $unpaidTickets[$x]["Billing Phone Number"] = $unpaid_order->get_billing_phone();

                                }

                                if ($this->is_plugin_active('fooevents_custom_attendee_fields/fooevents-custom-attendee-fields.php') || is_plugin_active_for_network('fooevents_custom_attendee_fields/fooevents-custom-attendee-fields.php')) {

                                    $y = 15;
                                    if(!empty($unpaidOrderTicket['WooCommerceEventsCustomAttendeeFields'])) {

                                        foreach($unpaidOrderTicket['WooCommerceEventsCustomAttendeeFields'] as $unpaidCustomField => $unpaidCustomValue) {

                                            $unpaidTickets[$x][$unpaidCustomField] = $unpaidCustomValue;

                                        }

                                    }

                                }

                                $x++;
                            
                            }

                        }

                    }
                }

            }
            
            $sorted_rows = array_merge($sorted_rows, $unpaidTickets);
            
        }

        $output = array();
        
        $y = 0;
        foreach($sorted_rows as $item) {
            
            foreach($item as $key => $valuetest) {
                
                foreach($csv_blueprint as $heading) {

                    if($key === $heading) {

                        $output[$y][$heading] = $valuetest;
  
                    } 

                }

                foreach($csv_blueprint as $heading) {
                    
                    if(empty($output[$y][$heading])) {
                        
                        $output[$y][$heading] = '';
                        
                    }
                    
                }
            }

            $y++;
            
        }

        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="'.date("Ymdhis").'.csv"');
        
        $fp = fopen('php://output', 'w');
        
        if(empty($output)) {

            $output[] = array(__('No tickets found.', 'woocommerce-events'));

        } else {

            fputcsv($fp, $csv_blueprint);

        }
        
        foreach ($output as $fields) {

            fputcsv($fp, $fields);

        }
        
        exit();

    }

 

    /**
     * Generates attendee badges.
     * 
     */
    public function woocommerce_events_attendee_badges() {
        
        if(!current_user_can('publish_event_magic_tickets')) {

            echo "User role does not have permission to export attendee details. Please contact site admin.";
            exit();
            
        }
        
        global $woocommerce;

        $event = sanitize_text_field($_GET['event']);
        $sort = get_post_meta($event, 'WooCommercePrintTicketSort', true);
        
        $ticketnrs = "";
        
        if (get_post_meta($event, 'WooCommercePrintTicketNumbers', true) != "") {

            $ticketnrs = explode(",", get_post_meta($event, 'WooCommercePrintTicketNumbers', true));

        }

        $ordernrs = "";

        if (get_post_meta($event, 'WooCommercePrintTicketOrders', true) != "") {

            $ordernrs = explode(",", get_post_meta($event, 'WooCommercePrintTicketOrders', true));

        }

        $page_size = "fooevents_letter_10";

        if (!empty(get_post_meta($event, 'WooCommercePrintTicketSize', true))) {

            $page_size = "fooevents_" . get_post_meta($event, 'WooCommercePrintTicketSize', true);

        }

        $cutlines = "on";

        if (get_post_meta($event, 'WooCommerceEventsCutLinesPrintTicket', true) != "") {

            $cutlines = get_post_meta($event, 'WooCommerceEventsCutLinesPrintTicket', true);

        }

        $nr_per_page = substr($page_size, strrpos($page_size, "_")+1);
        $nrcol = get_post_meta($event, 'WooCommercePrintTicketNrColumns', true);
        $nrrow = get_post_meta($event, 'WooCommercePrintTicketNrRows', true);
        
        $logo1 = get_post_meta($event, 'WooCommerceBadgeFieldTopLeft_logo', true);
        $logo2 = get_post_meta($event, 'WooCommerceBadgeFieldMiddleLeft_logo', true);
        $logo3 = get_post_meta($event, 'WooCommerceBadgeFieldBottomLeft_logo', true);
        $logo4 = get_post_meta($event, 'WooCommerceBadgeFieldTopMiddle_logo', true);
        $logo5 = get_post_meta($event, 'WooCommerceBadgeFieldMiddleMiddle_logo', true);
        $logo6 = get_post_meta($event, 'WooCommerceBadgeFieldBottomMiddle_logo', true);
        $logo7 = get_post_meta($event, 'WooCommerceBadgeFieldTopRight_logo', true);
        $logo8 = get_post_meta($event, 'WooCommerceBadgeFieldMiddleRight_logo', true);
        $logo9 = get_post_meta($event, 'WooCommerceBadgeFieldBottomRight_logo', true);

        $font1 = get_post_meta($event, 'WooCommerceBadgeFieldTopLeft_font', true);
        $font2 = get_post_meta($event, 'WooCommerceBadgeFieldMiddleLeft_font', true);
        $font3 = get_post_meta($event, 'WooCommerceBadgeFieldBottomLeft_font', true);
        $font4 = get_post_meta($event, 'WooCommerceBadgeFieldTopMiddle_font', true);
        $font5 = get_post_meta($event, 'WooCommerceBadgeFieldMiddleMiddle_font', true);
        $font6 = get_post_meta($event, 'WooCommerceBadgeFieldBottomMiddle_font', true);
        $font7 = get_post_meta($event, 'WooCommerceBadgeFieldTopRight_font', true);
        $font8 = get_post_meta($event, 'WooCommerceBadgeFieldMiddleRight_font', true);
        $font9 = get_post_meta($event, 'WooCommerceBadgeFieldBottomRight_font', true);

        $custom1 = get_post_meta($event, 'WooCommerceBadgeFieldTopLeft_custom', true);
        $custom2 = get_post_meta($event, 'WooCommerceBadgeFieldMiddleLeft_custom', true);
        $custom3 = get_post_meta($event, 'WooCommerceBadgeFieldBottomLeft_custom', true);
        $custom4 = get_post_meta($event, 'WooCommerceBadgeFieldTopMiddle_custom', true);
        $custom5 = get_post_meta($event, 'WooCommerceBadgeFieldMiddleMiddle_custom', true);
        $custom6 = get_post_meta($event, 'WooCommerceBadgeFieldBottomMiddle_custom', true);
        $custom7 = get_post_meta($event, 'WooCommerceBadgeFieldTopRight_custom', true);
        $custom8 = get_post_meta($event, 'WooCommerceBadgeFieldMiddleRight_custom', true);
        $custom9 = get_post_meta($event, 'WooCommerceBadgeFieldBottomRight_custom', true);

        $ticketfield1 = get_post_meta($event, 'WooCommerceBadgeFieldTopLeft', true);
        $ticketfield2 = get_post_meta($event, 'WooCommerceBadgeFieldMiddleLeft', true);
        $ticketfield3 = get_post_meta($event, 'WooCommerceBadgeFieldBottomLeft', true);
        $ticketfield4 = get_post_meta($event, 'WooCommerceBadgeFieldTopMiddle', true);
        $ticketfield5 = get_post_meta($event, 'WooCommerceBadgeFieldMiddleMiddle', true);
        $ticketfield6 = get_post_meta($event, 'WooCommerceBadgeFieldBottomMiddle', true);
        $ticketfield7 = get_post_meta($event, 'WooCommerceBadgeFieldTopRight', true);
        $ticketfield8 = get_post_meta($event, 'WooCommerceBadgeFieldMiddleRight', true);
        $ticketfield9 = get_post_meta($event, 'WooCommerceBadgeFieldBottomRight', true);

        $postids = array();

        /* Get tickets by ticket number */
        if (!empty($ticketnrs)) {

            $events_query = new WP_Query( array('post_type' => array('event_magic_tickets'),'posts_per_page' => -1, 'meta_key' => 'WooCommerceEventsTicketID', 'meta_value' => $ticketnrs ) );
            $ids = $events_query->get_posts();

            foreach($ids as $id) {

                array_push($postids, $id->ID);

            }

        }

        /* Get tickets by order number */
        if (!empty($ordernrs)) {

            $events_query_orders = new WP_Query( array('post_type' => array('event_magic_tickets'),'posts_per_page' => -1, 'meta_key' => 'WooCommerceEventsOrderID', 'meta_value' => $ordernrs ) );
            $ids_orders = $events_query_orders->get_posts();
            
            foreach($ids_orders as $id) {

                array_push($postids, $id->ID);

            }  
        }

        switch ($sort) {
            case "most_recent":
                $sort = "DESC";
            break;
            case "oldest":
                $sort = "ASC";
            break;
            case "a_z1":
                $sort = 'WooCommerceEventsAttendeeName';
            break;
            case "a_z2":
                $sort = 'WooCommerceEventsAttendeeLastName';
            break;
        }

        $sorted_rows = array();

        if (!empty($postids)) {

            $events_query = new WP_Query( array('post_type' => array('event_magic_tickets'), 'post__in' => $postids,'posts_per_page' => -1, 'meta_key' => 'WooCommerceEventsProductID', 'meta_value' => $event ) );

        }
        else {

            if ($sort == 'WooCommerceEventsAttendeeName' || $sort == 'WooCommerceEventsAttendeeLastName') {

                $events_query = new WP_Query( array('post_type' => array('event_magic_tickets'), 'posts_per_page' => -1, 'meta_key' => $sort, 'order' => 'ASC', 'orderby' => 'meta_value', 'meta_query' =>
                array(
                    'key' => 'WooCommerceEventsProductID',
                    'value' => $event
                ) ) );

            } else {

                $events_query = new WP_Query( array('post_type' => array('event_magic_tickets'), 'posts_per_page' => -1, 'meta_key' => 'WooCommerceEventsProductID', 'meta_value' => $event, 'order' => $sort ) );

            }
        }
        
        $events = $events_query->get_posts();

        if (empty($events)) {

            echo "There are no attendees/tickets for this event.";

        }

        $x = 0;
        
        $location_name = get_post_meta($event, 'WooCommerceEventsLocation', true);

        $current_logo_url = get_post_meta($event, 'WooCommerceEventsTicketLogo', true);
        $new_logo_url = get_post_meta($event, 'WooCommerceEventsPrintTicketLogo', true);
            
        foreach($events as $eventItem) {
            
            $id = $eventItem->ID;
            $ticket = get_post($id);
            $ticketID = $ticket->post_title;
            $WooCommerceEventsTicketHash = get_post_meta($id, 'WooCommerceEventsTicketHash', true);
            $order_id = get_post_meta($id, 'WooCommerceEventsOrderID', true);
            $product_id = get_post_meta($id, 'WooCommerceEventsProductID', true);
            $event_name = get_post_meta($id, 'WooCommerceEventsProductName', true);
            $customer_id = get_post_meta($id, 'WooCommerceEventsCustomerID', true);
            $WooCommerceEventsStatus = get_post_meta($id, 'WooCommerceEventsStatus', true);
            $ticketType = get_post_meta($ticket->ID, 'WooCommerceEventsTicketType', true);
            $WooCommerceEventsVariations = get_post_meta($id, 'WooCommerceEventsVariations', true);

            if(!empty($WooCommerceEventsVariations) && !is_array($WooCommerceEventsVariations)) {
                
                $WooCommerceEventsVariations = json_decode($WooCommerceEventsVariations);
                
            }

            $variationOutput = '';
            $i = 0;

            if(!empty($WooCommerceEventsVariations)) {

                foreach($WooCommerceEventsVariations as $variationName => $variationValue) {

                    if($i > 0) {

                        $variationOutput .= ' | ';

                    }

                    $variationNameOutput = str_replace('attribute_', '', $variationName);
                    $variationNameOutput = str_replace('pa_', '', $variationNameOutput);
                    $variationNameOutput = str_replace('_', ' ', $variationNameOutput);
                    $variationNameOutput = str_replace('-', ' ', $variationNameOutput);
                    $variationNameOutput = str_replace('Pa_', '', $variationNameOutput);
                    $variationNameOutput = ucwords($variationNameOutput);
                    $variationValueOutput = str_replace('_', ' ', $variationValue);
                    $variationValueOutput = str_replace('-', ' ', $variationValueOutput);
                    $variationValueOutput = ucwords($variationValueOutput);
                    $variationOutput .= $variationNameOutput.': '.$variationValueOutput;
                    $i++;

                }

            }
            
            $order = '';
            
            try {

                $order = new WC_Order($order_id);
                
            } catch (Exception $e) {

            } 
            
            $WooCommerceEventsAttendeeName = get_post_meta($ticket->ID, 'WooCommerceEventsAttendeeName', true);

            if(empty($WooCommerceEventsAttendeeName)) {

                $WooCommerceEventsAttendeeName = $order->billing_first_name;

            } 
            
            $WooCommerceEventsAttendeeLastName = get_post_meta($ticket->ID, 'WooCommerceEventsAttendeeLastName', true);

            if(empty($WooCommerceEventsAttendeeLastName)) {

                $WooCommerceEventsAttendeeLastName = $order->billing_last_name;

            }
            
            $WooCommerceEventsAttendeeEmail = get_post_meta($ticket->ID, 'WooCommerceEventsAttendeeEmail', true);

            if(empty($WooCommerceEventsAttendeeEmail)) {

                $WooCommerceEventsAttendeeEmail = $order->billing_email;

            }
            
            $WooCommerceEventsCaptureAttendeeTelephone = get_post_meta($ticket->ID, 'WooCommerceEventsAttendeeTelephone', true);
            $WooCommerceEventsCaptureAttendeeCompany = get_post_meta($ticket->ID, 'WooCommerceEventsAttendeeCompany', true);
            $WooCommerceEventsCaptureAttendeeDesignation = get_post_meta($ticket->ID, 'WooCommerceEventsAttendeeDesignation', true);
            $WooCommerceEventsPurchaserFirstName = get_post_meta($ticket->ID, 'WooCommerceEventsPurchaserFirstName', true);
            $WooCommerceEventsPurchaserLastName = get_post_meta($ticket->ID, 'WooCommerceEventsPurchaserLastName', true);
            $WooCommerceEventsPurchaserEmail = get_post_meta($ticket->ID, 'WooCommerceEventsPurchaserEmail', true);
            $WooCommerceEventsPurchaserPhone = get_post_meta($ticket->ID, 'WooCommerceEventsPurchaserPhone', true);

            $sorted_rows[$x]["TicketHash"] = $WooCommerceEventsTicketHash;
            $sorted_rows[$x]["TicketID"] = $ticketID;
            $sorted_rows[$x]["OrderID"] = $order_id;
            $sorted_rows[$x]["Event Name"] = $event_name;
            $sorted_rows[$x]["Location"] = $location_name;
            $sorted_rows[$x]["Event Name Variations"] = $event_name . " (" . $variationOutput . ")";
            $sorted_rows[$x]["Attendee First Name"] = $WooCommerceEventsAttendeeName;
            $sorted_rows[$x]["Attendee Last Name"] = $WooCommerceEventsAttendeeLastName;
            $sorted_rows[$x]["Attendee Email"] = $WooCommerceEventsAttendeeEmail;
            $sorted_rows[$x]["Ticket Status"] = $WooCommerceEventsStatus;
            $sorted_rows[$x]["Ticket Type"] = $ticketType;
            $sorted_rows[$x]["Variation"] = $variationOutput;
            $sorted_rows[$x]["Attendee Telephone"] = $WooCommerceEventsCaptureAttendeeTelephone;
            $sorted_rows[$x]["Attendee Company"] = $WooCommerceEventsCaptureAttendeeCompany;
            $sorted_rows[$x]["Attendee Designation"] = $WooCommerceEventsCaptureAttendeeDesignation;
            $sorted_rows[$x]["Purchaser First Name"] = $WooCommerceEventsPurchaserFirstName;
            $sorted_rows[$x]["Purchaser Last Name"] = $WooCommerceEventsPurchaserLastName;
            $sorted_rows[$x]["Purchaser Email"] = $WooCommerceEventsPurchaserEmail;
            $sorted_rows[$x]["Purchaser Phone"] = $WooCommerceEventsPurchaserPhone;
            $sorted_rows[$x]["Purchaser Company"] = $order->billing_company;

            if (!function_exists('is_plugin_active_for_network')) {
                
                require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
                
            }
            
            if ($this->is_plugin_active( 'fooevents_custom_attendee_fields/fooevents-custom-attendee-fields.php') || is_plugin_active_for_network('fooevents_custom_attendee_fields/fooevents-custom-attendee-fields.php')) {
                
                $Fooevents_Custom_Attendee_Fields = new Fooevents_Custom_Attendee_Fields();
                $fooevents_custom_attendee_fields_options = $Fooevents_Custom_Attendee_Fields->display_tickets_meta_custom_options_array($id);
            
                foreach($fooevents_custom_attendee_fields_options as $key => $value) {

                    $sorted_rows[$x][$key] = $value;
                }
                
            }

            if ($this->is_plugin_active( 'fooevents_seating/fooevents-seating.php') || is_plugin_active_for_network('fooevents_seating/fooevents-seating.php')) {
                
                $Fooevents_Seating = new Fooevents_Seating();
                $fooevents_seating_options = $Fooevents_Seating->display_tickets_meta_seat_options_output($id);
                $fooevents_seating_options = str_replace('Row Name: ', '', $fooevents_seating_options);
                $fooevents_seating_options = str_replace('Seat Number: ', '', $fooevents_seating_options);
                $fooevents_seating_options = preg_replace('/<br>/', ' - ', $fooevents_seating_options, 1);
                $sorted_rows[$x]["SeatInfo"] = $fooevents_seating_options;
                
            }
            
            $x++;  
            
        }
    
        $output = array();

        $y = 0;

        require($this->Config->templatePath.'attendeebadges.php');
        exit();
        
    }

    /**
     * Returns the widget label for the ticket print designer
     * 
     */
    public function widget_label($dataName, $cf_array) {

        switch($dataName) {
            case "barcode":
                return __('Barcode', 'woocommerce-events');
            case "logo":
                return __('Logo/Image', 'woocommerce-events');
            case "event":
                return __('Event Name Only', 'woocommerce-events');
            case "event_var":
                return __('Event Name/Variation', 'woocommerce-events');
            case "var_only":
                return __('Variation Only', 'woocommerce-events');
            case "ticketnr":
                return __('Ticket Number', 'woocommerce-events');
            case "name":
                return __('Attendee Name', 'woocommerce-events');
            case "email":
                return __('Attendee Email', 'woocommerce-events');
            case "phone":
                return __('Attendee Phone', 'woocommerce-events');
            case "company":
                return __('Attendee Company', 'woocommerce-events');
            case "designation":
                return __('Attendee Designation', 'woocommerce-events');
            case "seat":
                return __('Attendee Seat', 'woocommerce-events');
            case "location":
                return __('Event Location', 'woocommerce-events');
            case "custom":
                return __('Custom Text', 'woocommerce-events');
            case "spacer":
                return __('Empty Spacer', 'woocommerce-events');
            default:
                foreach( $cf_array as $key => $value) {

                    if ($dataName ==$key) {

                        return $value;

                    }

                }
        }
        
    }

    /**
     * Ajax function to save ticket and badge printing options 
     * 
     */
    public function save_printing_options($post_id = "", $ajaxCall = true) {

        $response = array("status" => "error");
        
        if ($post_id == "" && !empty($_POST['post_id'])) {
            $post_id = $_POST['post_id'];
        }
        
        if ($post_id != "") {
            if(isset($_POST['WooCommerceBadgeFieldTopLeft'])) {
                
                $WooCommerceBadgeFieldTopLeft = sanitize_text_field($_POST['WooCommerceBadgeFieldTopLeft']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldTopLeft', $WooCommerceBadgeFieldTopLeft);

            } 

            if(isset($_POST['WooCommerceBadgeFieldTopMiddle'])) {
                
                $WooCommerceBadgeFieldTopMiddle = sanitize_text_field($_POST['WooCommerceBadgeFieldTopMiddle']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldTopMiddle', $WooCommerceBadgeFieldTopMiddle);

            } 

            if(isset($_POST['WooCommerceBadgeFieldTopRight'])) {
                
                $WooCommerceBadgeFieldTopRight = sanitize_text_field($_POST['WooCommerceBadgeFieldTopRight']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldTopRight', $WooCommerceBadgeFieldTopRight);

            } 

            if(isset($_POST['WooCommerceBadgeFieldMiddleLeft'])) {
                
                $WooCommerceBadgeFieldMiddleLeft = sanitize_text_field($_POST['WooCommerceBadgeFieldMiddleLeft']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldMiddleLeft', $WooCommerceBadgeFieldMiddleLeft);

            } 

            if(isset($_POST['WooCommerceBadgeFieldMiddleMiddle'])) {
                
                $WooCommerceBadgeFieldMiddleMiddle = sanitize_text_field($_POST['WooCommerceBadgeFieldMiddleMiddle']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldMiddleMiddle', $WooCommerceBadgeFieldMiddleMiddle);

            } 

            if(isset($_POST['WooCommerceBadgeFieldMiddleRight'])) {
                
                $WooCommerceBadgeFieldMiddleRight = sanitize_text_field($_POST['WooCommerceBadgeFieldMiddleRight']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldMiddleRight', $WooCommerceBadgeFieldMiddleRight);

            } 

            if(isset($_POST['WooCommerceBadgeFieldBottomLeft'])) {
                
                $WooCommerceBadgeFieldBottomLeft = sanitize_text_field($_POST['WooCommerceBadgeFieldBottomLeft']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldBottomLeft', $WooCommerceBadgeFieldBottomLeft);

            } 

            if(isset($_POST['WooCommerceBadgeFieldBottomMiddle'])) {
                
                $WooCommerceBadgeFieldBottomMiddle = sanitize_text_field($_POST['WooCommerceBadgeFieldBottomMiddle']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldBottomMiddle', $WooCommerceBadgeFieldBottomMiddle);

            } 

            if(isset($_POST['WooCommerceBadgeFieldBottomRight'])) {
                
                $WooCommerceBadgeFieldBottomRight = sanitize_text_field($_POST['WooCommerceBadgeFieldBottomRight']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldBottomRight', $WooCommerceBadgeFieldBottomRight);

            } 

            if(isset($_POST['WooCommerceBadgeFieldTopLeft_font'])) {
                
                $WooCommerceBadgeFieldTopLeft_font = sanitize_text_field($_POST['WooCommerceBadgeFieldTopLeft_font']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldTopLeft_font', $WooCommerceBadgeFieldTopLeft_font);

            } 

            if(isset($_POST['WooCommerceBadgeFieldTopMiddle_font'])) {
                
                $WooCommerceBadgeFieldTopMiddle_font = sanitize_text_field($_POST['WooCommerceBadgeFieldTopMiddle_font']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldTopMiddle_font', $WooCommerceBadgeFieldTopMiddle_font);

            } 

            if(isset($_POST['WooCommerceBadgeFieldTopRight_font'])) {
                
                $WooCommerceBadgeFieldTopRight_font = sanitize_text_field($_POST['WooCommerceBadgeFieldTopRight_font']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldTopRight_font', $WooCommerceBadgeFieldTopRight_font);

            } 

            if(isset($_POST['WooCommerceBadgeFieldMiddleLeft_font'])) {
                
                $WooCommerceBadgeFieldMiddleLeft_font = sanitize_text_field($_POST['WooCommerceBadgeFieldMiddleLeft_font']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldMiddleLeft_font', $WooCommerceBadgeFieldMiddleLeft_font);

            } 

            if(isset($_POST['WooCommerceBadgeFieldMiddleMiddle_font'])) {
                
                $WooCommerceBadgeFieldMiddleMiddle_font = sanitize_text_field($_POST['WooCommerceBadgeFieldMiddleMiddle_font']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldMiddleMiddle_font', $WooCommerceBadgeFieldMiddleMiddle_font);

            } 

            if(isset($_POST['WooCommerceBadgeFieldMiddleRight_font'])) {
                
                $WooCommerceBadgeFieldMiddleRight_font = sanitize_text_field($_POST['WooCommerceBadgeFieldMiddleRight_font']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldMiddleRight_font', $WooCommerceBadgeFieldMiddleRight_font);

            } 

            if(isset($_POST['WooCommerceBadgeFieldBottomLeft_font'])) {
                
                $WooCommerceBadgeFieldBottomLeft_font = sanitize_text_field($_POST['WooCommerceBadgeFieldBottomLeft_font']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldBottomLeft_font', $WooCommerceBadgeFieldBottomLeft_font);

            } 

            if(isset($_POST['WooCommerceBadgeFieldBottomMiddle_font'])) {
                
                $WooCommerceBadgeFieldBottomMiddle_font = sanitize_text_field($_POST['WooCommerceBadgeFieldBottomMiddle_font']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldBottomMiddle_font', $WooCommerceBadgeFieldBottomMiddle_font);

            } 

            if(isset($_POST['WooCommerceBadgeFieldBottomRight_font'])) {
                
                $WooCommerceBadgeFieldBottomRight_font = sanitize_text_field($_POST['WooCommerceBadgeFieldBottomRight_font']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldBottomRight_font', $WooCommerceBadgeFieldBottomRight_font);

            } 

            if(isset($_POST['WooCommerceBadgeFieldTopLeft_logo'])) {
                
                $WooCommerceBadgeFieldTopLeft_logo = sanitize_text_field($_POST['WooCommerceBadgeFieldTopLeft_logo']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldTopLeft_logo', $WooCommerceBadgeFieldTopLeft_logo);

            } else {

                update_post_meta($post_id, 'WooCommerceBadgeFieldTopLeft_logo', '');

            }


            if(isset($_POST['WooCommerceBadgeFieldTopMiddle_logo'])) {
                
                $WooCommerceBadgeFieldTopMiddle_logo = sanitize_text_field($_POST['WooCommerceBadgeFieldTopMiddle_logo']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldTopMiddle_logo', $WooCommerceBadgeFieldTopMiddle_logo);

            } else {

                update_post_meta($post_id, 'WooCommerceBadgeFieldTopMiddle_logo', '');

            }


            if(isset($_POST['WooCommerceBadgeFieldTopRight_logo'])) {
                
                $WooCommerceBadgeFieldTopRight_logo = sanitize_text_field($_POST['WooCommerceBadgeFieldTopRight_logo']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldTopRight_logo', $WooCommerceBadgeFieldTopRight_logo);

            } else {

                update_post_meta($post_id, 'WooCommerceBadgeFieldTopRight_logo', '');

            } 

            if(isset($_POST['WooCommerceBadgeFieldMiddleLeft_logo'])) {
                
                $WooCommerceBadgeFieldMiddleLeft_logo = sanitize_text_field($_POST['WooCommerceBadgeFieldMiddleLeft_logo']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldMiddleLeft_logo', $WooCommerceBadgeFieldMiddleLeft_logo);

            } else {

                update_post_meta($post_id, 'WooCommerceBadgeFieldMiddleLeft_logo', '');

            }

            if(isset($_POST['WooCommerceBadgeFieldMiddleMiddle_logo'])) {
                
                $WooCommerceBadgeFieldMiddleMiddle_logo = sanitize_text_field($_POST['WooCommerceBadgeFieldMiddleMiddle_logo']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldMiddleMiddle_logo', $WooCommerceBadgeFieldMiddleMiddle_logo);

            } else {

                update_post_meta($post_id, 'WooCommerceBadgeFieldMiddleMiddle_logo', '');

            } 

            if(isset($_POST['WooCommerceBadgeFieldMiddleRight_logo'])) {
                
                $WooCommerceBadgeFieldMiddleRight_logo = sanitize_text_field($_POST['WooCommerceBadgeFieldMiddleRight_logo']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldMiddleRight_logo', $WooCommerceBadgeFieldMiddleRight_logo);

            } else {

                update_post_meta($post_id, 'WooCommerceBadgeFieldMiddleRight_logo', '');

            } 

            if(isset($_POST['WooCommerceBadgeFieldBottomLeft_logo'])) {
                
                $WooCommerceBadgeFieldBottomLeft_logo = sanitize_text_field($_POST['WooCommerceBadgeFieldBottomLeft_logo']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldBottomLeft_logo', $WooCommerceBadgeFieldBottomLeft_logo);

            } else {

                update_post_meta($post_id, 'WooCommerceBadgeFieldBottomLeft_logo', '');

            }

            if(isset($_POST['WooCommerceBadgeFieldBottomMiddle_logo'])) {
                
                $WooCommerceBadgeFieldBottomMiddle_logo = sanitize_text_field($_POST['WooCommerceBadgeFieldBottomMiddle_logo']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldBottomMiddle_logo', $WooCommerceBadgeFieldBottomMiddle_logo);

            } else {

                update_post_meta($post_id, 'WooCommerceBadgeFieldBottomMiddle_logo', '');

            }

            if(isset($_POST['WooCommerceBadgeFieldBottomRight_logo'])) {
                
                $WooCommerceBadgeFieldBottomRight_logo = sanitize_text_field($_POST['WooCommerceBadgeFieldBottomRight_logo']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldBottomRight_logo', $WooCommerceBadgeFieldBottomRight_logo);

            } else {

                update_post_meta($post_id, 'WooCommerceBadgeFieldBottomRight_logo', '');

            }

            if(isset($_POST['WooCommerceBadgeFieldTopLeft_custom'])) {
                
                $WooCommerceBadgeFieldTopLeft_custom = sanitize_text_field($_POST['WooCommerceBadgeFieldTopLeft_custom']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldTopLeft_custom', $WooCommerceBadgeFieldTopLeft_custom);

            } else {

                update_post_meta($post_id, 'WooCommerceBadgeFieldTopLeft_custom', '');

            }

            if(isset($_POST['WooCommerceBadgeFieldTopMiddle_custom'])) {
                
                $WooCommerceBadgeFieldTopMiddle_custom = sanitize_text_field($_POST['WooCommerceBadgeFieldTopMiddle_custom']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldTopMiddle_custom', $WooCommerceBadgeFieldTopMiddle_custom);

            } else {

                update_post_meta($post_id, 'WooCommerceBadgeFieldTopMiddle_custom', '');

            }

            if(isset($_POST['WooCommerceBadgeFieldTopRight_custom'])) {
                
                $WooCommerceBadgeFieldTopRight_custom = sanitize_text_field($_POST['WooCommerceBadgeFieldTopRight_custom']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldTopRight_custom', $WooCommerceBadgeFieldTopRight_custom);

            } else {

                update_post_meta($post_id, 'WooCommerceBadgeFieldTopRight_custom', '');

            } 

            if(isset($_POST['WooCommerceBadgeFieldMiddleLeft_custom'])) {
                
                $WooCommerceBadgeFieldMiddleLeft_custom = sanitize_text_field($_POST['WooCommerceBadgeFieldMiddleLeft_custom']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldMiddleLeft_custom', $WooCommerceBadgeFieldMiddleLeft_custom);

            } else {

                update_post_meta($post_id, 'WooCommerceBadgeFieldMiddleLeft_custom', '');

            }

            if(isset($_POST['WooCommerceBadgeFieldMiddleMiddle_custom'])) {
                
                $WooCommerceBadgeFieldMiddleMiddle_custom = sanitize_text_field($_POST['WooCommerceBadgeFieldMiddleMiddle_custom']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldMiddleMiddle_custom', $WooCommerceBadgeFieldMiddleMiddle_custom);

            } else {

                update_post_meta($post_id, 'WooCommerceBadgeFieldMiddleMiddle_custom', '');

            } 

            if(isset($_POST['WooCommerceBadgeFieldMiddleRight_custom'])) {
                
                $WooCommerceBadgeFieldMiddleRight_custom = sanitize_text_field($_POST['WooCommerceBadgeFieldMiddleRight_custom']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldMiddleRight_custom', $WooCommerceBadgeFieldMiddleRight_custom);

            } else {

                update_post_meta($post_id, 'WooCommerceBadgeFieldMiddleRight_custom', '');

            } 

            if(isset($_POST['WooCommerceBadgeFieldBottomLeft_custom'])) {
                
                $WooCommerceBadgeFieldBottomLeft_custom = sanitize_text_field($_POST['WooCommerceBadgeFieldBottomLeft_custom']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldBottomLeft_custom', $WooCommerceBadgeFieldBottomLeft_custom);

            } else {

                update_post_meta($post_id, 'WooCommerceBadgeFieldBottomLeft_custom', '');

            }

            if(isset($_POST['WooCommerceBadgeFieldBottomMiddle_custom'])) {
                
                $WooCommerceBadgeFieldBottomMiddle_custom = sanitize_text_field($_POST['WooCommerceBadgeFieldBottomMiddle_custom']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldBottomMiddle_custom', $WooCommerceBadgeFieldBottomMiddle_custom);

            } else {

                update_post_meta($post_id, 'WooCommerceBadgeFieldBottomMiddle_custom', '');

            }

            if(isset($_POST['WooCommerceBadgeFieldBottomRight_custom'])) {
                
                $WooCommerceBadgeFieldBottomRight_custom = sanitize_text_field($_POST['WooCommerceBadgeFieldBottomRight_custom']);
                update_post_meta($post_id, 'WooCommerceBadgeFieldBottomRight_custom', $WooCommerceBadgeFieldBottomRight_custom);

            } else {

                update_post_meta($post_id, 'WooCommerceBadgeFieldBottomRight_custom', '');

            }

            if(isset($_POST['WooCommercePrintTicketSort'])) {
                
                $WooCommercePrintTicketSort = sanitize_text_field($_POST['WooCommercePrintTicketSort']);
                update_post_meta($post_id, 'WooCommercePrintTicketSort', $WooCommercePrintTicketSort);

            } 

            if(isset($_POST['WooCommercePrintTicketNumbers'])) {
                
                $WooCommercePrintTicketNumbers = sanitize_text_field($_POST['WooCommercePrintTicketNumbers']);
                update_post_meta($post_id, 'WooCommercePrintTicketNumbers', $WooCommercePrintTicketNumbers);

            } else {

                update_post_meta($post_id, 'WooCommercePrintTicketNumbers', '');

            }

            if(isset($_POST['WooCommercePrintTicketOrders'])) {
                
                $WooCommercePrintTicketOrders = sanitize_text_field($_POST['WooCommercePrintTicketOrders']);
                update_post_meta($post_id, 'WooCommercePrintTicketOrders', $WooCommercePrintTicketOrders);

            } else {

                update_post_meta($post_id, 'WooCommercePrintTicketOrders', '');

            }

            if(isset($_POST['WooCommercePrintTicketSize'])) {
                
                $WooCommercePrintTicketSize = sanitize_text_field($_POST['WooCommercePrintTicketSize']);
                update_post_meta($post_id, 'WooCommercePrintTicketSize', $WooCommercePrintTicketSize);
    
            } 
    
            if(isset($_POST['WooCommercePrintTicketNrColumns'])) {
                
                $WooCommercePrintTicketNrColumns = sanitize_text_field($_POST['WooCommercePrintTicketNrColumns']);
                update_post_meta($post_id, 'WooCommercePrintTicketNrColumns', $WooCommercePrintTicketNrColumns);
    
            } 

            if(isset($_POST['WooCommercePrintTicketNrRows'])) {
                
                $WooCommercePrintTicketNrRows = sanitize_text_field($_POST['WooCommercePrintTicketNrRows']);
                update_post_meta($post_id, 'WooCommercePrintTicketNrRows', $WooCommercePrintTicketNrRows);
    
            } 
            
            if(isset($_POST['WooCommerceEventsCutLinesPrintTicket'])) {
                
                $WooCommerceEventsCutLinesPrintTicket = sanitize_text_field($_POST['WooCommerceEventsCutLinesPrintTicket']);
                update_post_meta($post_id, 'WooCommerceEventsCutLinesPrintTicket', $WooCommerceEventsCutLinesPrintTicket);
    
            } else {
    
                update_post_meta($post_id, 'WooCommerceEventsCutLinesPrintTicket', 'off');
    
            }

            $response["status"] = "success";
        }

        if ($ajaxCall) {

            echo json_encode($response);
            exit();

        }
        
    }
    
    /**
     * Get's orders that contain a particular order
     * 
     * @global object $wpdb
     * @param int $product_id
     * @param string $order_status
     * @return object
     */
    private function get_orders_ids_by_product_id( $product_id, $order_status = array( 'wc-completed' ) ){
        global $wpdb;

        $results = $wpdb->get_col("
            SELECT order_items.order_id
            FROM {$wpdb->prefix}woocommerce_order_items as order_items
            LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
            LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
            WHERE posts.post_type = 'shop_order'
            AND posts.post_status IN ( '" . implode( "','", $order_status ) . "' )
            AND order_items.order_item_type = 'line_item'
            AND order_item_meta.meta_key = '_product_id'
            AND order_item_meta.meta_value = '$product_id'
        ");

        return $results;
    }
    
    /**
     * Generates random string used for ticket hash
     * 
     * @param int $length
     * @return string
     */
    private function generate_random_string($length = 10) {
        
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);

    }
    
    /**
     * Outputs notices to screen.
     * 
     * @param array $notices
     */
    private function output_notices($notices) {

        foreach ($notices as $notice) {

                echo "<div class='updated'><p>$notice</p></div>";

        }

    }
    
    /**
     * Array of month names for translation to English
     * 
     * @param string $event_date
     * @return string
     */
    private function convert_month_to_english($event_date) {
        
        $months = array(
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
            'Januar' => 'January',
            'Februar' => 'February',
            'März' => 'March',
            'Mai' => 'May',
            'Juni' => 'June',
            'Juli' => 'July',
            'Oktober' => 'October',
            'Dezember' => 'December',
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
        );
        
        $pattern = array_keys($months);
        $replacement = array_values($months);

        foreach ($pattern as $key => $value) {
            
            $pattern[$key] = '/\b'.$value.'\b/i';
            
        }
        
        return preg_replace($pattern, $replacement, $event_date);
        
    }

    /**
    * Checks if a plugin is active.
    * 
    * @param string $plugin
    * @return boolean
    */
    private function is_plugin_active($plugin) {

        return in_array( $plugin, (array) get_option( 'active_plugins', array() ) );

    }

}