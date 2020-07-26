<?php if(!defined('ABSPATH')) exit;
/**
 * Plugin Name: FooEvents for WooCommerce
 * Description: Adds event and ticketing features to WooCommerce
 * Version: 1.11.35
 * Author: FooEvents
 * Plugin URI: https://www.fooevents.com/
 * Author URI: https://www.fooevents.com/
 * Developer: FooEvents
 * Developer URI: https://www.fooevents.com/
 * Text Domain: woocommerce-events
 * WC requires at least: 3.0.0
 * WC tested up to: 4.0.0
 *
 * Copyright: © 2009-2020 FooEvents.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

//include config
require(WP_PLUGIN_DIR.'/fooevents/config.php');

class FooEvents {
    
    private $WooHelper;
    private $ICSHelper;
    private $Config;
    private $XMLRPCHelper;
    private $CommsHelper;
    private $CheckoutHelper;
    private $TicketHelper;
    private $EventReportHelper;
    private $UpdateHelper;
    private $RESTAPIHelper;
    private $ZoomAPIHelper;
    private $Salt;
    private $ThemeHelper;
    private $APIKey;
    private $pluginFile;
    private $slug;
    private $pluginData;
    
    public function __construct() {
        
        $plugin = plugin_basename(__FILE__); 
        
        $this->APIKey = get_option('globalWooCommerceEventsAPIKey', true);
        $this->pluginFile = __FILE__;

        add_action('init', array($this, 'plugin_init'));
        add_action('admin_init', array($this, 'register_scripts'));
        add_action('admin_notices', array($this, 'check_woocommerce_events'));
        add_action('admin_notices', array($this, 'check_fooevents_errors'));
        add_action('wp_enqueue_scripts', array($this, 'register_scripts_frontend'));
        add_action('admin_init', array($this, 'register_styles'));
        add_action('admin_menu', array($this, 'add_woocommerce_submenu'));
        add_action('admin_menu', array($this, 'add_admin_menu'), 10);
        add_action('wp_ajax_fooevents_ics', array($this, 'fooevents_ics'));
        add_action('wp_ajax_nopriv_fooevents_ics', array($this, 'fooevents_ics'));
        add_action('plugins_loaded', array($this, 'load_text_domain'));
        
        add_action('activated_plugin', array($this, 'activate_plugin'));
        add_action('wpml_loaded', array($this, 'fooevents_wpml_loaded'));
        
        add_action('wp_ajax_woocommerce_events_cancel', array($this, 'woocommerce_events_cancel'));
        add_action('wp_ajax_nopriv_woocommerce_events_cancel', array($this, 'woocommerce_events_cancel'));

        add_filter('plugin_action_links_'.$plugin, array($this, 'add_plugin_links'));
        add_filter('add_to_cart_text', array($this, 'woo_custom_cart_button_text'));
        add_filter('woocommerce_product_single_add_to_cart_text', array($this, 'woo_custom_cart_button_text'));
        add_filter('woocommerce_product_add_to_cart_text', array($this, 'woo_custom_cart_button_text'));

        add_filter('parse_query', array($this, 'fooevents_filter_ticket_results'));  

        add_action('admin_init', array(&$this, 'assign_admin_caps'));
        register_activation_hook( __FILE__, array($this, 'update_db'));
        register_deactivation_hook( __FILE__, array(&$this, 'remove_event_user_caps'));
        
        add_action('admin_init', array($this, 'register_settings_options'));
        add_filter('custom_menu_order', array($this, 'fooevents_menu_order'));
      
    }
    
    /**
     * Basic checks to see if FooEvents will run correctly. 
     * 
     */
    public function check_woocommerce_events() {

        if ( $this->is_plugin_active('woocommerce_events/woocommerce-events.php')) {

                $this->output_notices(array(__('WooCommerce Events has re-branded to FooEvents. Please disable and remove the older WooCommerce Events plugin.', 'woocommerce-events')));

        } 

        if(!is_writable($this->Config->uploadsPath)) {

            $this->output_notices(array(sprintf(__('Directory %s is not writeable', 'woocommerce-events'), $this->Config->uploadsPath)));
            
            if(!is_writable($this->Config->barcodePath)) {
                
                $this->output_notices(array(sprintf(__('Directory %s is not writeable', 'woocommerce-events'), $this->Config->barcodePath)));

            }
            
            if(!is_writable($this->Config->themePacksPath)) {
                
                $this->output_notices(array(sprintf(__('Directory %s is not writeable', 'woocommerce-events'), $this->Config->themePacksPath)));

            }
            
            if(!is_writable($this->Config->themePacksPath.'default')) {
                
                $this->output_notices(array(sprintf(__('Directory %s is not writeable', 'woocommerce-events'), $this->Config->themePacksPath.'default')));

            }

            if(!is_writable($this->Config->icsPath)) {
                
                $this->output_notices(array(sprintf(__('Directory %s is not writeable', 'woocommerce-events'), $this->Config->icsPath)));

            }
            
        }
        
        if(file_exists($this->Config->emailTemplatePathTheme.'email/header.php') || file_exists($this->Config->emailTemplatePathTheme.'email/footer.php') || file_exists($this->Config->emailTemplatePathTheme.'email/ticket.php') || file_exists($this->Config->emailTemplatePathTheme.'email/tickets.php')) {

            $this->output_notices(array(sprintf(__('We have detected that you have overridden FooEvents ticket template files in your Wordpress theme. Please move these to an overridden ticket theme directory. Please consult the FooEvents documentation on how to do this.', 'woocommerce-events'), $this->Config->themePacksPath.'default')));

        } 
        
        $globalWooCommerceEventsEnableQRCode = get_option('globalWooCommerceEventsEnableQRCode');
        
        if($globalWooCommerceEventsEnableQRCode && !extension_loaded('gd')){
            
            $this->output_notices(array(sprintf(__( 'PHP GD library is a requirement for FooEvents to generate QR codes. Please contact your web host to enable PHP GD libraries.', 'woocommerce-events' ), $this->Config->themePacksPath.'default')));

        }

    }

    /**
     * Checks for and displays FooEvents errors. 
     * 
     */
    public function check_fooevents_errors() {
        
        $errorCodes = array(
            '1' => __('Purchaser username already used. Ticket was not created.', 'woocommerce-events'),
            '2' => __('An error occured. Ticket was not created.', 'woocommerce-events'),
            '3' => __('Purchaser email address already used. Ticket was not created.', 'woocommerce-events'),
        );
        
        if(!empty($_GET['fooevents_error'])) {
            
            $this->output_notices(array($errorCodes[$_GET['fooevents_error']]));
            
        }
        
    }

    /**
     *  Initialize events plugin and helpers.
     * 
     */
    public function plugin_init() {
        
        $fooeventsDB = get_option('fooevents_db', false);
        
        if(!$fooeventsDB) {
            
            $this->update_db();
            
        }
        
        //Main config
        $this->Config = new FooEvents_Config();

        //WooHelper
        require_once($this->Config->classPath.'woohelper.php');
        $this->WooHelper = new FooEvents_Woo_Helper($this->Config);
        
        //ICSHelper
        require_once($this->Config->classPath.'icshelper.php');
        $this->ICSHelper = new FooEvents_ICS_helper($this->Config);
        
        //CommsHelper
        require_once($this->Config->classPath.'commshelper.php');
        $this->CommsHelper = new FooEvents_Comms_Helper($this->Config);
        
        //CheckoutHelper
        require_once($this->Config->classPath.'checkouthelper.php');
        $this->CheckoutHelper = new FooEvents_Checkout_Helper($this->Config);

        //ThemeHelper
        require_once($this->Config->classPath.'themehelper.php');
        $this->ThemeHelper = new FooEvents_Theme_Helper($this->Config);
        
        //ThemeHelper
        require_once($this->Config->classPath.'eventreporthelper.php');
        $this->EventReportHelper = new FooEvents_Event_Report_Helper($this->Config);
        
        //BarcodeHelper
        require_once($this->Config->classPath.'barcodehelper.php');
        $this->BarcodeHelper = new FooEvents_Barcode_Helper($this->Config);

        //UpdateHelper
        require_once($this->Config->classPath.'updatehelper.php');
        $this->UpdateHelper = new FooEvents_Update_Helper($this->Config);

        //API helper methods
        require_once($this->Config->classPath.'apihelper.php');

        //XMLRPCHelper
        require_once($this->Config->classPath.'xmlrpchelper.php');
        $this->XMLRPCHelper = new FooEvents_XMLRPC_Helper($this->Config);

        //RESTAPIHelper
        require_once($this->Config->classPath.'restapihelper.php');
        $this->RESTAPIHelper = new FooEvents_REST_API_Helper();

        //ZoomAPIHelper
        require_once($this->Config->classPath.'zoomapihelper.php');
        $this->ZoomAPIHelper = new FooEvents_Zoom_API_Helper($this->Config);

        $this->Salt = $this->Config->salt;
        
        if(empty($this->Salt)) {
            
            $salt = rand(111111,999999); 
            update_option('woocommerce_events_do_salt', $salt);
            $this->Salt = $salt;
            $this->Config->salt = $salt;
            
        }

        if (!file_exists($this->Config->uploadsPath) && is_writable($this->Config->uploadsDirPath)) {

            if (!mkdir($this->Config->uploadsPath, 0755, true)) {

                $this->output_notices(array(sprintf(__('FooEvents failed to create the directory %s please manually create this directory on your server.', 'woocommerce-events'), $this->Config->uploadsPath)));

            }
            
            if (!file_exists($this->Config->barcodePath)) {

                if(!mkdir($this->Config->barcodePath, 0755, true)) {
                    
                    $this->output_notices(array(sprintf(__('FooEvents failed to create the directory %s please manually create this directory on your server.', 'woocommerce-events'), $this->Config->barcodePath)));
                    
                }

            }

            if (!file_exists($this->Config->themePacksPath)) {

                if(!mkdir($this->Config->themePacksPath, 0755, true)) {
                    
                    $this->output_notices(array(sprintf(__('FooEvents failed to create the directory %s please manually create this directory on your server.', 'woocommerce-events'), $this->Config->themePacksPath)));
                    
                }

            }
            
            if (!file_exists($this->Config->pdfTicketPath)) {

                if(!mkdir($this->Config->pdfTicketPath, 0755, true)) {
                    
                    $this->output_notices(array(sprintf(__('FooEvents failed to create the directory %s please manually create this directory on your server.', 'woocommerce-events'), $this->Config->pdfTicketPath)));

                }

            }

            if(!file_exists($this->Config->uploadsPath.'themes/default') && is_writable($this->Config->uploadsDirPath)) {

               $this->xcopy($this->Config->emailTemplatePath, $this->Config->themePacksPath.'default');

            }

            //generate barcode
            if (!file_exists($this->Config->barcodePath.'/111111111.png')) {

                $this->BarcodeHelper->generate_barcode('111111111');

            }
 
        }

        if (!file_exists($this->Config->icsPath)) {

            if(!mkdir($this->Config->icsPath, 0755, true)) {
                
                $this->output_notices(array(sprintf(__('FooEvents failed to create the directory %s please manually create this directory on your server.', 'woocommerce-events'), $this->Config->icsPath)));
                
            }

        }

        // Add ability to change an event's owner
        add_post_type_support( 'product', 'author' );
    }
    
    /**
     * When WPML is loaded
     * 
     */
    public function fooevents_wpml_loaded() {
        
        add_action('pre_get_posts', array($this, 'fooevents_wpml_compatibility'));
        
    }
    
    /**
     * WPML compatibility for events within app
     * 
     */
    public function fooevents_wpml_compatibility($wp_query) {
        
        $q = $wp_query->query_vars;
        
        if(!empty($_GET['action']) && $_GET['action'] == "woocommerce_events_csv") {
            
            return;
            
        }
        
        if (isset($q['meta_query']) && isset($q['post_type']) && in_array('event_magic_tickets', (array) $q['post_type'])) {

            foreach ((array) $q['meta_query'] as $i => $meta_query) {

                if ( $meta_query['key'] === 'WooCommerceEventsProductID' && is_numeric( $meta_query['value'] ) ) {

                        $trid = apply_filters( 'wpml_element_trid', null, $meta_query['value'], 'post_event_magic_tickets' );
                        $values = apply_filters( 'wpml_get_element_translations', null, $trid, 'post_event_magic_tickets' );
                        $q['meta_query'][ $i ]['value'] = wp_list_pluck( $values, 'element_id' );

                        $wp_query->query_vars = $q;

                }

            }

        }

    }
    
    /**
     * Create and update FooEvents tables
     * 
     */
    public function update_db() {
        
        global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'fooevents_check_in';
        
        $version = get_option('fooevents_version', '1.0');
        
        $sql = "CREATE TABLE $table_name (
		id bigint(20) NOT NULL AUTO_INCREMENT,
                tid BIGINT(20) UNSIGNED NOT NULL,
                eid BIGINT(20) UNSIGNED NOT NULL,
                day int(3),
                checkin int(10),
		updated datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
		UNIQUE KEY id (id),
                FOREIGN KEY (`tid`) REFERENCES ".$wpdb->prefix."posts (`ID`)
	) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
        
        update_option('fooevents_db', true);
        
    }
    
    /**
     * Register plugin scripts.
     * 
     */
    public function register_scripts() {
        
        global $wp_locale;
        global $woocommerce;
        
        $woocommerce_currency_symbol = '';
        if (class_exists('WooCommerce')) {
            
            $woocommerce_currency_symbol = get_woocommerce_currency_symbol();
            
        }
        
        $localArgs = array(
            'closeText'         => __( 'Done', 'woocommerce-events' ),
            'currentText'       => __( 'Today', 'woocommerce-events' ),
            'monthNames'        => $this->_strip_array_indices( $wp_locale->month ),
            'monthNamesShort'   => $this->_strip_array_indices( $wp_locale->month_abbrev ),
            'monthStatus'       => __( 'Show a different month', 'woocommerce-events' ),
            'dayNames'          => $this->_strip_array_indices( $wp_locale->weekday ),
            'dayNamesShort'     => $this->_strip_array_indices( $wp_locale->weekday_abbrev ),
            'dayNamesMin'       => $this->_strip_array_indices( $wp_locale->weekday_initial ),
            'dateFormat'        => $this->_date_format_php_to_js( get_option( 'date_format' ) ),
            'firstDay'          => get_option( 'start_of_week' ),
            'isRTL'             => $wp_locale->is_rtl(),
            'currencySymbol'    => $woocommerce_currency_symbol,
        );
 
        $localRemindersArgs = array(
            'minutesValue'      => __('minutes', 'woocommerce-events'),
            'hoursValue'        => __('hours', 'woocommerce-events'),
            'daysValue'         => __('days', 'woocommerce-events'),
            'weeksValue'        => __('weeks', 'woocommerce-events')
        );

        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('jquery-ui-tooltip');
        wp_enqueue_script('jquery-ui-draggable');
        wp_enqueue_script('jquery-ui-droppable');
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_script('woocommerce-events-admin-script', $this->Config->scriptsPath . 'events-admin.js', array('jquery', 'jquery-ui-datepicker', 'wp-color-picker'), '1.11.30', true );
        wp_localize_script('woocommerce-events-admin-script', 'localObj', $localArgs);
        wp_localize_script('woocommerce-events-admin-script', 'localRemindersObj', $localRemindersArgs);

        $localArgsPrint = array(
            'ajaxurl'           => admin_url('admin-ajax.php'),
            'ajaxSaveSuccess'   => __( 'Your stationery settings have been saved.', 'woocommerce-events' ),
            'ajaxSaveError'   => __( 'An error occurred while saving your stationery settings.', 'woocommerce-events' ),
        );

        wp_enqueue_script('woocommerce-events-printing-admin-script', $this->Config->scriptsPath . 'events-printing-admin.js', array('jquery'), '1.0.0', true );
        wp_localize_script('woocommerce-events-printing-admin-script', 'localObjPrint', $localArgsPrint);

        if(isset($_GET['page']) && $_GET['page'] == 'fooevents-settings') {
            
            wp_enqueue_media();
            
        }
        
        if(isset($_GET['page']) && $_GET['page'] == 'fooevents-event-report') {
            
          wp_enqueue_script('woocommerce-events-chartist', $this->Config->scriptsPath . 'chartist.min.js', array('jquery'), '0.11.3', true);  
          wp_enqueue_script('woocommerce-events-chartist-tooltip', $this->Config->scriptsPath . 'chartist-plugin-tooltip.min.js', array('jquery', 'woocommerce-events-chartist'), '0.0.18', true);  
          wp_enqueue_script('woocommerce-events-report', $this->Config->scriptsPath . 'events-reports.js', array('jquery', 'woocommerce-events-chartist'), '1.0.0', true);  
          wp_localize_script('woocommerce-events-report', 'localObj', $localArgs);
            
        }
        
        if(isset($_GET['post_type']) && $_GET['post_type'] == 'event_magic_tickets') {

            wp_enqueue_script('woocommerce-events-select', $this->Config->scriptsPath . 'select2.min.js', array('jquery'), '4.0.12', true);  
            wp_enqueue_script('woocommerce-events-admin-select', $this->Config->scriptsPath . 'event-admin-select.js', array('jquery', 'woocommerce-events-select'), '1.0.0', true);  

        }

        wp_enqueue_script('woocommerce-events-select', $this->Config->scriptsPath . 'select2.min.js', array('jquery'), '4.0.12', true);  
        wp_enqueue_script('woocommerce-events-admin-select', $this->Config->scriptsPath . 'event-admin-select.js', array('jquery', 'woocommerce-events-select'), '1.0.0', true);  
  
    }
    
    /**
     * Registers scripts on the Wordpress frontend.
     * 
     */
    public function register_scripts_frontend() {

        wp_enqueue_script('woocommerce-events-front-script',  $this->Config->scriptsPath . 'events-frontend.js', array('jquery'), '1.0.0', true);
        
    }

    /**
     * Register plugin styles.
     * 
     */
    public function register_styles() {

        wp_enqueue_style('woocommerce-events-admin-script',  $this->Config->stylesPath . 'events-admin.css', array(), '1.1.2');
        wp_enqueue_style('jquery-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
        
        wp_enqueue_style('wp-color-picker');
        
        if(isset($_GET['page']) && $_GET['page'] == 'fooevents-event-report') {
            
            wp_enqueue_style('woocommerce-events-chartist',  $this->Config->stylesPath . 'chartist.min.css', array(), '0.11.3');
            wp_enqueue_style('woocommerce-events-chartist-tooltip',  $this->Config->stylesPath . 'chartist-plugin-tooltip.css', array(), '0.0.18');
            
        }
        
        if(isset($_GET['post_type']) && $_GET['post_type'] == 'event_magic_tickets') {
            
            wp_enqueue_style('woocommerce-events-select',  $this->Config->stylesPath . 'select2.min.css', array(), '4.0.12');
            
        }

        wp_enqueue_style('woocommerce-events-select',  $this->Config->stylesPath . 'select2.min.css', array(), '4.0.12');
        
    }

    /**
     * Assign FooEvents user permissions to the admin user
     * 
     */    
    public function assign_admin_caps() {
        
        $role = get_role( 'administrator' );
        
        $role->add_cap('publish_event_magic_tickets'); 
        $role->add_cap('edit_event_magic_tickets'); 
        $role->add_cap('edit_published_event_magic_tickets'); 
        $role->add_cap('edit_others_event_magic_tickets'); 
        $role->add_cap('delete_event_magic_tickets'); 
        $role->add_cap('delete_others_event_magic_tickets'); 
        $role->add_cap('read_private_event_magic_tickets'); 
        $role->add_cap('edit_event_magic_ticket'); 
        $role->add_cap('delete_event_magic_ticket'); 
        $role->add_cap('read_event_magic_ticket'); 
        $role->add_cap('edit_published_event_magic_ticket'); 
        $role->add_cap('publish_event_magic_ticket'); 
        $role->add_cap('delete_others_event_magic_ticket'); 
        $role->add_cap('delete_published_event_magic_ticket'); 
        $role->add_cap('delete_published_event_magic_tickets'); 
        
    }
    
    /**
     * Removes FooEvents user permissions when plugin is disabled 
     * 
     */
    public function remove_event_user_caps() {
            
        $delete_caps = array(
            'publish_event_magic_tickets', 
            'edit_event_magic_tickets', 
            'edit_published_event_magic_tickets', 
            'edit_others_event_magic_tickets', 
            'delete_event_magic_tickets', 
            'delete_others_event_magic_tickets', 
            'read_private_event_magic_tickets', 
            'edit_event_magic_ticket', 
            'delete_event_magic_ticket', 
            'read_event_magic_ticket', 
            'edit_published_event_magic_ticket', 
            'publish_event_magic_ticket', 
            'delete_others_event_magic_ticket', 
            'delete_published_event_magic_ticket', 
            'delete_published_event_magic_tickets', 
            );
        
	global $wp_roles;
	foreach ($delete_caps as $cap) {
                
		foreach (array_keys($wp_roles->roles) as $role) {
                    
                    echo $role.' - '.$cap.'<br />';
                    
                    $wp_roles->remove_cap($role, $cap);
                        
		}
                
	}

    }
    
    /**
     * Outputs notices to screen.
     * 
     * @param array $notices
     */
    private function output_notices($notices) {

        foreach ($notices as $notice) {

                echo "<div class='notice notice-error'><p>$notice</p></div>";

        }

    }
    
    /**
     * xcopy function to move templates to new location in uploads directory
     * 
     */
    private function xcopy($source, $dest, $permissions = 0755)
    {
  
        if (is_link($source)) {
            
            return symlink(readlink($source), $dest);
            
        }

        if (is_file($source)) {
            
            return copy($source, $dest);
            
        }

        if (!is_dir($dest)) {
            
            mkdir($dest, $permissions);
            
        }

        $dir = dir($source);
        while (false !== $entry = $dir->read()) {

            if ($entry == '.' || $entry == '..') {
                continue;
            }

            $this->xcopy("$source/$entry", "$dest/$entry", $permissions);
        }

        $dir->close();
        return true;
        
    }
    
    /**
     * Adds option to for redirect.
     * 
     */
    static function activate_plugin($plugin) {

        $salt = rand(111111,999999); 
        update_option('woocommerce_events_do_salt', $salt);
 
        if( $plugin == plugin_basename( __FILE__ ) ) {

            wp_redirect('admin.php?page=woocommerce-events-help'); exit;
            
        }    

    }
    
    /**
     * Adds the FooEvents menu item
     * 
     */
    public function add_admin_menu() {
        
        add_menu_page( 
            null, 
            __('FooEvents', 'woocommerce-events'), 
            'edit_posts', 
            'fooevents', 
            array($this, 'redirect_to_tickets'), 
            'dashicons-tickets-alt',
            '55.9'    
        );

        add_submenu_page('fooevents', __('Settings', 'woocommerce-events'), __('Settings', 'woocommerce-events'), 'edit_posts', 'fooevents-settings', array( $this, 'display_settings_page'));
        add_submenu_page('fooevents', __('Getting Started', 'woocommerce-events'), __('Getting Started', 'woocommerce-events'), 'edit_posts', 'fooevents-introduction', array( $this, 'add_woocommerce_submenu_page'));

    }
    
    /**
     * Reorder the FooEvents menu items
     * @param array $menu_order
     * @return array $menu_order
     */
    public function fooevents_menu_order($menu_ord) {
        
        global $submenu;

        $menu = array();
        $menu[] = $submenu['fooevents'][0];
        $menu[] = $submenu['fooevents'][4];
        $menu[] = $submenu['fooevents'][1];
        $menu[] = $submenu['fooevents'][3];
        $menu[] = $submenu['fooevents'][2];
        
        if ( $this->is_plugin_active('fooevents_express_check_in/fooevents-express-check_in.php') || is_plugin_active_for_network('fooevents_express_check_in/fooevents-express-check_in.php')) {
            
            if(isset($submenu['fooevents'][5])) {
                
                $menu[] = $submenu['fooevents'][5];
                
            }
            
        }            
        
        $submenu['fooevents'] = $menu;

        return $menu_ord;
        
    }  
    
    /**
     * Redirects to tickets listing 
     * 
     */
    public function redirect_to_tickets() {
        
        wp_redirect('edit.php?post_type=event_magic_tickets');

        exit;
        
    }
    
    /**
     * Redirects to FooEvents settings
     * 
     */
    public function redirect_to_settings() {
        
        wp_redirect('admin.php?page=fooevents-settings&tab=api');

        exit;
    
    }    
    
    /**
     * Register FooEvents options
     * 
     */
    public function register_settings_options() {
        
        register_setting('fooevents-settings-api', 'globalWooCommerceEventsAPIKey');
        register_setting('fooevents-settings-api', 'globalWooCommerceEnvatoAPIKey');
        
        register_setting('fooevents-settings-general', 'globalWooCommerceEventsChangeAddToCart');
        register_setting('fooevents-settings-general', 'globalWooCommerceEventSorting');
        register_setting('fooevents-settings-general', 'globalWooCommerceDisplayEventDate');
        register_setting('fooevents-settings-general', 'globalWooCommerceHideEventDetailsTab');
        register_setting('fooevents-settings-general', 'globalWooCommerceUsePlaceHolders');
        register_setting('fooevents-settings-general', 'globalWooCommerceEventsHideUnpaidTickets');
        register_setting('fooevents-settings-general', 'globalWooCommerceEventsEmailTicketAdmin');
        
        register_setting('fooevents-settings-terminology', 'globalWooCommerceEventsEventOverride');
        register_setting('fooevents-settings-terminology', 'globalWooCommerceEventsEventOverridePlural');
        register_setting('fooevents-settings-terminology', 'globalWooCommerceEventsAttendeeOverride');
        register_setting('fooevents-settings-terminology', 'globalWooCommerceEventsAttendeeOverridePlural');
        register_setting('fooevents-settings-terminology', 'globalWooCommerceEventsTicketOverride');
        register_setting('fooevents-settings-terminology', 'globalWooCommerceEventsTicketOverridePlural');
        register_setting('fooevents-settings-terminology', 'WooCommerceEventsDayOverride');
        register_setting('fooevents-settings-terminology', 'WooCommerceEventsDayOverridePlural');
        
        register_setting('fooevents-settings-ticket-design', 'globalWooCommerceEventsTicketBackgroundColor');
        register_setting('fooevents-settings-ticket-design', 'globalWooCommerceEventsTicketButtonColor');
        register_setting('fooevents-settings-ticket-design', 'globalWooCommerceEventsTicketTextColor');
        register_setting('fooevents-settings-ticket-design', 'globalWooCommerceEventsTicketLogo');
        register_setting('fooevents-settings-ticket-design', 'globalWooCommerceEventsTicketHeaderImage');
        register_setting('fooevents-settings-ticket-design', 'globalWooCommerceEventsEnableQRCode');
        
        register_setting('fooevents-settings-checkins-app', 'globalWooCommerceHideUnpaidTicketsApp');
        register_setting('fooevents-settings-checkins-app', 'globalWooCommerceEventsAppTitle');
        register_setting('fooevents-settings-checkins-app', 'globalWooCommerceEventsAppLogo');
        register_setting('fooevents-settings-checkins-app', 'globalWooCommerceEventsAppColor');
        register_setting('fooevents-settings-checkins-app', 'globalWooCommerceEventsAppTextColor');
        register_setting('fooevents-settings-checkins-app', 'globalWooCommerceEventsAppBackgroundColor');
        register_setting('fooevents-settings-checkins-app', 'globalWooCommerceEventsAppSignInTextColor');
        register_setting('fooevents-settings-checkins-app', 'globalWooCommerceEventsAppEvents');
        register_setting('fooevents-settings-checkins-app', 'globalWooCommerceEventsAppEventIDs');
        register_setting('fooevents-settings-checkins-app', 'globalWooCommerceEventsAppHidePersonalInfo');

        register_setting('fooevents-settings-integration', 'globalWooCommerceEventsGoogleMapsAPIKey');
        register_setting('fooevents-settings-integration', 'globalWooCommerceEventsZoomAPIKey');
        register_setting('fooevents-settings-integration', 'globalWooCommerceEventsZoomAPISecret');
        register_setting('fooevents-settings-integration', 'globalWooCommerceEventsZoomUsers');
        register_setting('fooevents-settings-integration', 'globalWooCommerceEventsZoomSelectedUserOption');
        register_setting('fooevents-settings-integration', 'globalWooCommerceEventsZoomSelectedUsers');

    }
    
    /**
     * Display and processes the FooEvents Settings page 
     * 
     */
    public function display_settings_page() {
        
        if (!current_user_can('publish_event_magic_tickets'))  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
        
        if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
            require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
        }
        
        $active_tab = '';
        if( isset( $_GET[ 'tab' ] ) ) {
            
            $active_tab = $_GET[ 'tab' ];
            
        } else {
            
            $active_tab = 'api';
            
        }
        
        $pdfEnabled = false;
        if ($this->is_plugin_active('fooevents_pdf_tickets/fooevents-pdf-tickets.php') || is_plugin_active_for_network('fooevents_pdf_tickets/fooevents-pdf-tickets.php')) {
        
            $pdfEnabled = true;
            
        } 
        
        $calendarEnabled = false;
        if ($this->is_plugin_active('fooevents-calendar/fooevents-calendar.php') || is_plugin_active_for_network('fooevents-calendar/fooevents-calendar.php')) {
            
            $calendarEnabled = true;
            
        }
        
        $seatingEnabled = false;
        if ($this->is_plugin_active('fooevents_seating/fooevents-seating.php') || is_plugin_active_for_network('fooevents_seating/fooevents-seating.php')) {
            
            $seatingEnabled = true;
            
        }
        
        $globalWooCommerceEventsAPIKey = get_option('globalWooCommerceEventsAPIKey');
        $globalWooCommerceEnvatoAPIKey = get_option('globalWooCommerceEnvatoAPIKey');
        $globalWooCommerceEventsGoogleMapsAPIKey = get_option('globalWooCommerceEventsGoogleMapsAPIKey');
        $globalWooCommerceEventsTicketBackgroundColor = get_option('globalWooCommerceEventsTicketBackgroundColor');
        $globalWooCommerceEventsTicketButtonColor = get_option('globalWooCommerceEventsTicketButtonColor');
        $globalWooCommerceEventsTicketTextColor = get_option('globalWooCommerceEventsTicketTextColor');
        $globalWooCommerceEventsTicketLogo = get_option('globalWooCommerceEventsTicketLogo');
        $globalWooCommerceEventsTicketHeaderImage = get_option('globalWooCommerceEventsTicketHeaderImage');
        $globalWooCommerceEventsEnableQRCode = get_option('globalWooCommerceEventsEnableQRCode');
        $globalWooCommerceEventsChangeAddToCart = get_option('globalWooCommerceEventsChangeAddToCart');
        $globalWooCommerceEventSorting = get_option('globalWooCommerceEventSorting');
        $globalWooCommerceDisplayEventDate = get_option('globalWooCommerceDisplayEventDate');
        $globalWooCommerceHideEventDetailsTab = get_option('globalWooCommerceHideEventDetailsTab');
        $globalWooCommerceUsePlaceHolders = get_option('globalWooCommerceUsePlaceHolders');
        $globalWooCommerceHideUnpaidTicketsApp = get_option('globalWooCommerceHideUnpaidTicketsApp');
        $globalWooCommerceEventsHideUnpaidTickets = get_option('globalWooCommerceEventsHideUnpaidTickets');
        $globalWooCommerceEventsEmailTicketAdmin = get_option('globalWooCommerceEventsEmailTicketAdmin');
        $globalWooCommerceEventsAppTitle = get_option('globalWooCommerceEventsAppTitle');
        $globalWooCommerceEventsAppLogo = get_option('globalWooCommerceEventsAppLogo');
        $globalWooCommerceEventsAppColor = get_option('globalWooCommerceEventsAppColor');
        $globalWooCommerceEventsAppTextColor = get_option('globalWooCommerceEventsAppTextColor');
        $globalWooCommerceEventsAppBackgroundColor = get_option('globalWooCommerceEventsAppBackgroundColor');
        $globalWooCommerceEventsAppSignInTextColor = get_option('globalWooCommerceEventsAppSignInTextColor');
        $globalWooCommerceEventsAppEvents = get_option('globalWooCommerceEventsAppEvents');
        $globalWooCommerceEventsAppEventIDs = get_option('globalWooCommerceEventsAppEventIDs');
        $globalWooCommerceEventsAppHidePersonalInfo = get_option('globalWooCommerceEventsAppHidePersonalInfo');

        $WooCommerceEventsAppEventsArgs = array(
            'post_type' => 'product',
            'order' => 'ASC',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => 'WooCommerceEventsEvent',
                    'value' => 'Event',
                    'compare' => '=',
                ),
            ),
        );
    
        $WooCommerceEventsAppEventsQuery = new WP_Query($WooCommerceEventsAppEventsArgs);
        $WooCommerceEventsAppEvents = $WooCommerceEventsAppEventsQuery->get_posts();

        $globalWooCommerceEventsEventOverride = get_option('globalWooCommerceEventsEventOverride');
        $globalWooCommerceEventsEventOverridePlural = get_option('globalWooCommerceEventsEventOverridePlural');
        $globalWooCommerceEventsAttendeeOverride = get_option('globalWooCommerceEventsAttendeeOverride');
        $globalWooCommerceEventsAttendeeOverridePlural = get_option('globalWooCommerceEventsAttendeeOverridePlural');
        $globalWooCommerceEventsTicketOverride = get_option('globalWooCommerceEventsTicketOverride');
        $globalWooCommerceEventsTicketOverridePlural = get_option('globalWooCommerceEventsTicketOverridePlural');
        $WooCommerceEventsDayOverride = get_option('WooCommerceEventsDayOverride');
        $WooCommerceEventsDayOverridePlural = get_option('WooCommerceEventsDayOverridePlural');
        $globalWooCommerceEventsZoomAPIKey = get_option('globalWooCommerceEventsZoomAPIKey');
        $globalWooCommerceEventsZoomAPISecret = get_option('globalWooCommerceEventsZoomAPISecret');
        $globalWooCommerceEventsZoomUsers = json_decode(get_option('globalWooCommerceEventsZoomUsers', json_encode(array())), true);
        $globalWooCommerceEventsZoomSelectedUserOption = get_option('globalWooCommerceEventsZoomSelectedUserOption');
        $globalWooCommerceEventsZoomSelectedUsers = get_option('globalWooCommerceEventsZoomSelectedUsers');
        
        $pdfOptions = '';
        if ( $this->is_plugin_active('fooevents_pdf_tickets/fooevents-pdf-tickets.php') || is_plugin_active_for_network('fooevents_pdf_tickets/fooevents-pdf-tickets.php')) {
            
            $FooEvents_PDF_Tickets = new FooEvents_PDF_Tickets();
            $pdfOptions = $FooEvents_PDF_Tickets->get_pdf_options();
            
        }
        
        $calendarOptions = '';
        $eventbriteOptions = '';
        if ( $this->is_plugin_active('fooevents-calendar/fooevents-calendar.php') || is_plugin_active_for_network('fooevents-calendar/fooevents-calendar.php')) {
            
            $FooEvents_Calendar = new FooEvents_Calendar();
            $calendarOptions = $FooEvents_Calendar->get_calendar_options();
            $eventbriteOptions = $FooEvents_Calendar->get_eventbrite_options();
            
        }
        
        $seatingOptions = '';
        if ( $this->is_plugin_active('fooevents_seating/fooevents-seating.php') || is_plugin_active_for_network('fooevents_seating/fooevents-seating.php')) {
            
            $FooEvents_Seating = new FooEvents_Seating();
            $seatingOptions = $FooEvents_Seating->get_seating_options();
            
        }
        
        require($this->Config->templatePath.'settingsglobal.php');
        
    }
    
    /**
     * Adds the WooCommerce sub menu
     * 
     */
    public function add_woocommerce_submenu() {

        add_submenu_page( 'null',__( 'FooEvents Introduction', 'woocommerce-events' ), __( 'FooEvents Introduction', 'woocommerce-events' ), 'manage_options', 'woocommerce-events-help', array($this, 'add_woocommerce_submenu_page') ); 

    }
    
    /**
     * Adds the WooCommerce sub menu page
     * 
     */
    public function add_woocommerce_submenu_page() {
        
        require($this->Config->templatePath.'pluginintroduction.php');

    }
    
    /**
     * Adds plugin links to the plugins page
     * 
     * @param array $links
     * @return array $links
     */
    public function add_plugin_links($links) {
        
        $linkSettings = '<a href="admin.php?page=fooevents-settings&tab=api">'.__( 'Settings', 'woocommerce-events' ).'</a>'; 
        array_unshift($links, $linkSettings); 
        
        $linkIntroduction = '<a href="admin.php?page=woocommerce-events-help">'.__( 'Getting Started', 'woocommerce-events' ).'</a>'; 
        array_unshift($links, $linkIntroduction); 
        
        return $links;
        
    }
    
    /**
     * Builds the calendar ICS file
     * 
     */
    public function fooevents_ics() {

        $event = sanitize_text_field($_GET['event']);
        $registrant_email = isset($_GET['email']) ? sanitize_text_field($_GET['email']) : '';
                
        $this->ICSHelper->generate_ICS($event, $registrant_email);

        $this->ICSHelper->show();
        
        exit();
    }

    /**
     * Changes the WooCommerce 'Add to cart' text
     * 
     */
    public function woo_custom_cart_button_text($text) {
        
        global $post;
        global $product;

        $WooCommerceEventsEvent                         = get_post_meta($post->ID, 'WooCommerceEventsEvent', true);
        $globalWooCommerceEventsChangeAddToCart         = get_option('globalWooCommerceEventsChangeAddToCart', true);
        $ticketTerm                                     = get_post_meta($post->ID, 'WooCommerceEventsTicketOverride', true);
        
        if(empty($ticketTerm)) {

            $ticketTerm = get_option('globalWooCommerceEventsTicketOverride', true);

        }
        
        if(empty($ticketTerm) || $ticketTerm == 1) {

            $ticketTerm = __( 'Book ticket', 'woocommerce-events' );

        }

        if($WooCommerceEventsEvent == 'Event' && $globalWooCommerceEventsChangeAddToCart === 'yes') {
        
            return $ticketTerm;
        
        } else {
            
            return $text;
            
        }
        
    }
    
    /**
     * External access to ticket data
     * 
     * @param int $ticketID
     * @return array
     */
    public function get_ticket_data($ticketID) {
        
        //Main config
        $this->Config = new FooEvents_Config();
        
        //TicketHelper
        require_once($this->Config->classPath.'tickethelper.php');
        $this->TicketHelper = new FooEvents_Ticket_Helper($this->Config);
        
        $ticket_data = $this->TicketHelper->get_ticket_data($ticketID);
        
        return $ticket_data;
        
    }
    
    /**
     * Returns the plugin path
     * 
     * @return string
     */
    public function get_plugin_path() {
        
        return $this->Config->path;
        
    }
    
    /**
     * Returns the plugin URL
     * 
     * @return string
     */
    public function get_plugin_url() {
        
        return $this->Config->eventPluginURL;
        
    }
    
    /**
     * Returns the barcode path
     * 
     * @return string
     */
    public function get_barcode_path() {
        
        return $this->Config->barcodePath;
        
    }
    
    /**
     * Loads text-domain for localization
     * 
     */
    public function load_text_domain() {
        
        $path = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
        $loaded = load_plugin_textdomain( 'woocommerce-events', false, $path);

    }
 
    /**
    * Format array for the datepicker
    *
    * WordPress stores the locale information in an array with a alphanumeric index, and
    * the datepicker wants a numerical index. This function replaces the index with a number
    */
    private function _strip_array_indices($ArrayToStrip) {
        
        foreach($ArrayToStrip as $objArrayItem) {
            
            $NewArray[] =  $objArrayItem;
            
        }

        return($NewArray);
        
    }
    
    /**
    * Convert the php date format string to a js date format
    * 
    */
   private function _date_format_php_to_js($sFormat) {

        switch($sFormat) {
            //Predefined WP date formats
            case 'D d-m-y':
            return( 'D dd-mm-yy' );
            break;
            
            case 'D d-m-Y':
            return( 'D dd-mm-yy' );
            break;
            
            case 'l d-m-Y':
            return( 'DD dd-mm-yy' );
            break;
        
            case 'jS F Y':
            return( 'd MM, yy' );
            break;
        
            case 'F j, Y':
            return( 'MM dd, yy' );
            break;
        
            case 'j F Y':
            return( 'd MM yy' );
            break;
        
            case 'Y/m/d':
            return( 'yy/mm/dd' );
            break;
        
            case 'm/d/Y':
            return( 'mm/dd/yy' );
            break;
        
            case 'd/m/Y':
            return( 'dd/mm/yy' );
            break;
        
            case 'Y-m-d':
            return( 'yy-mm-dd' );
            break;
        
            case 'm-d-Y':
            return( 'mm-dd-yy' );
            break;
        
            case 'd-m-Y':
            return( 'dd-mm-yy' );
            break;
        
            case 'j. FY':
            return( 'd. MMyy' );
            break;
        
            default:
            return( 'yy-mm-dd' );
        }
        
    }
    
    /**
     * Returns if a plugin is active or not
     * 
     * @param string $plugin
     * @return bool
     */
    private function is_plugin_active($plugin) {

        return in_array( $plugin, (array) get_option( 'active_plugins', array() ) );

    }

    /**
    * Filter tickets listing based on event filter selection
    * 
    */     
    public function fooevents_filter_ticket_results($query) {
        
        global $pagenow;
        $fooevents_filter ='';
        
        if ( is_admin() && 'edit.php' == $pagenow && isset($_GET['event_id']) && '' != $_GET['event_id'] ) {
            
            $fooevents_filter = sanitize_text_field($_GET['event_id']);
            $query->query_vars['meta_key']   = 'WooCommerceEventsProductID';
            $query->query_vars['meta_value'] = $fooevents_filter;  
            
        }
        
    }

}

$FooEvents = new FooEvents();

//TODO: move this function into WooHelper
function fooevents_displayEventTab() {
    
    global $post;
    $Config = new FooEvents_Config();
    
    $WooCommerceEventsEventDetailsText  = get_post_meta($post->ID, 'WooCommerceEventsEventDetailsText', true);
    $WooCommerceEventsBackgroundColor   = get_post_meta($post->ID, 'WooCommerceEventsBackgroundColor', true);
    $WooCommerceEventsTextColor         = get_post_meta($post->ID, 'WooCommerceEventsTextColor', true);
    $WooCommerceEventsEvent             = get_post_meta($post->ID, 'WooCommerceEventsEvent', true);
    $WooCommerceEventsDate              = get_post_meta($post->ID, 'WooCommerceEventsDate', true);
    $WooCommerceEventsEndDate           = get_post_meta($post->ID, 'WooCommerceEventsEndDate', true);
    $WooCommerceEventsHour              = get_post_meta($post->ID, 'WooCommerceEventsHour', true);
    $WooCommerceEventsMinutes           = get_post_meta($post->ID, 'WooCommerceEventsMinutes', true);
    $WooCommerceEventsPeriod            = get_post_meta($post->ID, 'WooCommerceEventsPeriod', true);
    $WooCommerceEventsHourEnd           = get_post_meta($post->ID, 'WooCommerceEventsHourEnd', true);
    $WooCommerceEventsMinutesEnd        = get_post_meta($post->ID, 'WooCommerceEventsMinutesEnd', true);
    $WooCommerceEventsEndPeriod         = get_post_meta($post->ID, 'WooCommerceEventsEndPeriod', true);
    $WooCommerceEventsTimeZone          = get_post_meta($post->ID, 'WooCommerceEventsTimeZone', true);
    $WooCommerceEventsLocation          = get_post_meta($post->ID, 'WooCommerceEventsLocation', true);
    $WooCommerceEventsTicketLogo        = get_post_meta($post->ID, 'WooCommerceEventsTicketLogo', true);
    $WooCommerceEventsSupportContact    = get_post_meta($post->ID, 'WooCommerceEventsSupportContact', true);
    $WooCommerceEventsGPS               = get_post_meta($post->ID, 'WooCommerceEventsGPS', true);
    $WooCommerceEventsDirections        = get_post_meta($post->ID, 'WooCommerceEventsDirections', true);
    $WooCommerceEventsEmail             = get_post_meta($post->ID, 'WooCommerceEventsEmail', true);
    $WooCommerceEventsMultiDayType      = get_post_meta($post->ID, 'WooCommerceEventsMultiDayType', true);
    $WooCommerceEventsSelectDate        = get_post_meta($post->ID, 'WooCommerceEventsSelectDate', true);
    
    $dayTerm = get_post_meta($post->ID, 'WooCommerceEventsDayOverride', true);

    if(empty($dayTerm)) {

        $dayTerm = get_option('WooCommerceEventsDayOverride', true);

    }

    if(empty($dayTerm) || $dayTerm == 1) {

        $dayTerm = __('Day', 'woocommerce-events');

    }
    
    $multiDayEvent = false;
    
    if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
        require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
    }

    if (fooevents_check_plugin_active('fooevents_multi_day/fooevents-multi-day.php') || is_plugin_active_for_network('fooevents_multi_day/fooevents-multi-day.php')) {
        
        $multiDayEvent = true;
        
    }


    if ($WooCommerceEventsTimeZone != "") {

        $timeZoneDate = new DateTime();
        $timeZoneDate->setTimeZone(new DateTimeZone($WooCommerceEventsTimeZone));
        $timeZone = $timeZoneDate->format('T');
        if((int)$timeZone > 0) {
            $timeZone = "UTC" . $timeZone;
        }
    } else {
        $timeZone = "";
    }

    
    if(file_exists($Config->emailTemplatePathTheme.'eventtab.php') ) {
        
        require($Config->emailTemplatePathTheme.'eventtab.php');
    
    } else {
        
        require($Config->templatePath.'eventtab.php');
        
    }
    
}

function fooevents_displayEventTabMap() {
    
    global $post;
    $Config = new FooEvents_Config();
    
    $WooCommerceEventsGoogleMaps = get_post_meta($post->ID, 'WooCommerceEventsGoogleMaps', true);
    $globalWooCommerceEventsGoogleMapsAPIKey = get_option('globalWooCommerceEventsGoogleMapsAPIKey', true);
    
    if($globalWooCommerceEventsGoogleMapsAPIKey == 1) {
        
        $globalWooCommerceEventsGoogleMapsAPIKey = '';
        
    }

    $eventContent = $post->post_content;
    
    $eventContent = apply_filters( 'the_content', $eventContent );
    
    if(!empty($WooCommerceEventsGoogleMaps) && !empty($globalWooCommerceEventsGoogleMapsAPIKey)) {
        
        if(file_exists($Config->emailTemplatePathTheme.'eventtabmap.php') ) {

            require($Config->emailTemplatePathTheme.'eventtabmap.php');

        } else {

            require($Config->templatePath.'eventtabmap.php');

        }
        
    }
    
}

function fooevents_ics() {
    
    $Config = new FooEvents_Config();

}

add_action( 'wp_dashboard_setup', 'fooevents_dashboard_widget' );

function fooevents_dashboard_widget() {
    
    wp_add_dashboard_widget(
        'fooevents_widget',
        'FooEvents',
        'fooevents_widget_display'
    ); 

}

function fooevents_widget_display() {
    
    /*$events_query = new WP_Query( array('post_type' => array('event_magic_tickets'), 'posts_per_page' => -1, 'meta_query' => array( array( 'key' => 'WooCommerceEventsStatus', 'value' => 'Unpaid', 'compare' => '!=' ) )) );
    $events = $events_query->get_posts();
    $ticket_count = $events_query->found_posts;
    
    $events_query = new WP_Query( array('post_type' => array('event_magic_tickets'), 'posts_per_page' => -1, 'meta_query' => array( array( 'key' => 'WooCommerceEventsStatus', 'value' => 'Not Checked In', 'compare' => '=' ) )) );
    $events = $events_query->get_posts();
    $not_checked_in_count = $events_query->found_posts;
    
    $events_query = new WP_Query( array('post_type' => array('event_magic_tickets'), 'posts_per_page' => -1, 'meta_query' => array( array( 'key' => 'WooCommerceEventsStatus', 'value' => 'Checked In', 'compare' => '=' ) )) );
    $events = $events_query->get_posts();
    $checked_in_count = $events_query->found_posts;
    
    $events_query = new WP_Query( array('post_type' => array('event_magic_tickets'), 'posts_per_page' => -1, 'meta_query' => array( array( 'key' => 'WooCommerceEventsStatus', 'value' => 'Checked In', 'compare' => '=' ) )) );
    $events = $events_query->get_posts();
    $checked_in_count = $events_query->found_posts;
    
    $events_query = new WP_Query( array('post_type' => array('product'), 'posts_per_page' => -1, 'meta_query' => array( array( 'key' => 'WooCommerceEventsEvent', 'value' => 'Event', 'compare' => '=' ) )) );
    $events = $events_query->get_posts();
    $event_count = $events_query->found_posts;*/
    
    $fooevents = get_plugin_data(WP_PLUGIN_DIR.'/fooevents/fooevents.php');
    $woocommerce = get_plugin_data(WP_PLUGIN_DIR.'/woocommerce/woocommerce.php');

    /*echo "<div class='fooevents_widget_item'>"."Total tickets: ".$ticket_count."</div>";
    
    echo "<div class='fooevents_widget_item'>"."Total events: ".$event_count."</div>";

    echo "<div class='fooevents_widget_item'>"."Tickets 'Not Checked In': ".$not_checked_in_count."</div>";
    
    echo "<div class='fooevents_widget_item'>"."Tickets 'Checked In': ".$checked_in_count."</div>";*/

    echo "<p><a href='".$fooevents['PluginURI']."' target='_BLANK'>FooEvents</a> ".$fooevents['Version']. " running on <a href='".$woocommerce['PluginURI']."' target='_BLANK'>WooCommerce</a> ".$woocommerce['Version']."</p>";

    if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
        require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
    }
    
    $fooevents_pdf_tickets_active = 'No';
    $fooevents_pdf_tickets = array('Version' => '');
    if ( fooevents_check_plugin_active('fooevents_pdf_tickets/fooevents-pdf-tickets.php') || is_plugin_active_for_network('fooevents_pdf_tickets/fooevents-pdf-tickets.php')) {
        
        $fooevents_pdf_tickets_active = 'Yes';
        $fooevents_pdf_tickets = get_plugin_data(WP_PLUGIN_DIR.'/fooevents_pdf_tickets/fooevents-pdf-tickets.php');
        
    }
    
    echo "<div class='fooevents_widget_item'><a href='https://www.fooevents.com/fooevents-pdf-tickets/' target='_BLANK'>FooEvents PDF tickets</a>: "."<b>".$fooevents_pdf_tickets_active."</b> ".$fooevents_pdf_tickets['Version']."</div>";
    
    $fooevents_express_check_in_active = 'No';
    $fooevents_express_check_in = array('Version' => '');
    if ( fooevents_check_plugin_active('fooevents_express_check_in/fooevents-express-check_in.php') || is_plugin_active_for_network('fooevents_express_check_in/fooevents-express-check_in.php')) {
        
        $fooevents_express_check_in_active = 'Yes';
        $fooevents_express_check_in = get_plugin_data(WP_PLUGIN_DIR.'/fooevents_express_check_in/fooevents-express-check_in.php');
        
    }
    
    echo "<div class='fooevents_widget_item'><a href='https://www.fooevents.com/fooevents-express-check-in/' target='_BLANK'>FooEvents Express Check-in</a>: "."<b>".$fooevents_express_check_in_active."</b> ".$fooevents_express_check_in['Version']."</div>";
   
    $fooevents_calendar_active = 'No';
    $fooevents_calendar = array('Version' => '');
    if ( fooevents_check_plugin_active('fooevents-calendar/fooevents-calendar.php') || is_plugin_active_for_network('fooevents-calendar/fooevents-calendar.php')) {
        
        $fooevents_calendar_active = 'Yes';
        $fooevents_calendar = get_plugin_data(WP_PLUGIN_DIR.'/fooevents-calendar/fooevents-calendar.php');
        
    }
    
    echo "<div class='fooevents_widget_item'><a href='https://www.fooevents.com/fooevents-calendar/' target='_BLANK'>FooEvents Calendar</a>: "."<b>".$fooevents_calendar_active."</b> ".$fooevents_calendar['Version']."</div>";
    
    $fooevents_custom_attendee_fields_active = 'No';
    $fooevents_custom_attendee_fields = array('Version' => '');
    if ( fooevents_check_plugin_active('fooevents_custom_attendee_fields/fooevents-custom-attendee-fields.php') || is_plugin_active_for_network('fooevents_custom_attendee_fields/fooevents-custom-attendee-fields.php')) {
        
        $fooevents_custom_attendee_fields_active = 'Yes';
        $fooevents_custom_attendee_fields = get_plugin_data(WP_PLUGIN_DIR.'/fooevents_custom_attendee_fields/fooevents-custom-attendee-fields.php');
        
    }
    
    echo "<div class='fooevents_widget_item'><a href='https://www.fooevents.com/fooevents-custom-attendee-fields/' target='_BLANK'>FooEvents Custom Attendee Fields</a>: "."<b>".$fooevents_custom_attendee_fields_active."</b> ".$fooevents_custom_attendee_fields['Version']."</div>";
    
    $fooevents_multi_day_active = 'No';
    $fooevents_multi_day = array('Version' => '');
    if ( fooevents_check_plugin_active('fooevents_multi_day/fooevents-multi-day.php') || is_plugin_active_for_network('fooevents_multi_day/fooevents-multi-day.php')) {
        
        $fooevents_multi_day_active = 'Yes';
        $fooevents_multi_day = get_plugin_data(WP_PLUGIN_DIR.'/fooevents_multi_day/fooevents-multi-day.php');
        
    }
    
    echo "<div class='fooevents_widget_item'><a href='https://www.fooevents.com/fooevents-multi-day/' target='_BLANK'>FooEvents Multi-Day</a>: "."<b>".$fooevents_multi_day_active."</b> ".$fooevents_multi_day['Version']."</div>";
    
    $fooevents_seating_active = 'No';
    $fooevents_seating = array('Version' => '');
    if ( fooevents_check_plugin_active('fooevents_seating/fooevents-seating.php') || is_plugin_active_for_network('fooevents_seating/fooevents-seating.php')) {
        
        $fooevents_seating_active = 'Yes';
        $fooevents_seating = get_plugin_data(WP_PLUGIN_DIR.'/fooevents_seating/fooevents-seating.php');
        
    }
    
    echo "<div class='fooevents_widget_item'><a href='https://www.fooevents.com/fooevents-seating/' target='_BLANK'>FooEvents Seating</a>: "."<b>".$fooevents_seating_active."</b> ".$fooevents_seating['Version']."</div>";
    
}

function fooevents_check_plugin_active( $plugin ) {

    return in_array( $plugin, (array) get_option( 'active_plugins', array() ) );

}

function uninstallFooEvents() {
    
    delete_option('globalWooCommerceEventsAPIKey');
    delete_option('globalWooCommerceEnvatoAPIKey');
    delete_option('globalWooCommerceEventsGoogleMapsAPIKey');
    delete_option('globalWooCommerceEventsTicketBackgroundColor');
    delete_option('globalWooCommerceEventsTicketButtonColor');
    delete_option('globalWooCommerceEventsTicketTextColor');
    delete_option('globalWooCommerceEventsTicketLogo');
    delete_option('globalWooCommerceEventsTicketHeaderImage');
    delete_option('globalWooCommerceEventsChangeAddToCart');
    delete_option('globalWooCommerceEventSorting');
    delete_option('globalWooCommerceDisplayEventDate');
    delete_option('globalWooCommerceHideEventDetailsTab');
    delete_option('globalWooCommerceUsePlaceHolders');
    delete_option('globalWooCommerceHideUnpaidTicketsApp');
    delete_option('globalWooCommerceEventsHideUnpaidTickets');
    delete_option('globalWooCommerceEventsEmailTicketAdmin');
    delete_option('globalWooCommerceEventsAppTitle');
    delete_option('globalWooCommerceEventsAppLogo');
    delete_option('globalWooCommerceEventsAppColor');
    delete_option('globalWooCommerceEventsAppTextColor');
    delete_option('globalWooCommerceEventsAppBackgroundColor');
    delete_option('globalWooCommerceEventsAppSignInTextColor');
    delete_option('globalWooCommerceEventsAppEvents');
    delete_option('globalWooCommerceEventsAppEventIDs');
    delete_option('globalWooCommerceEventsAppHidePersonalInfo');
    delete_option('globalWooCommerceEventsEventOverride');
    delete_option('globalWooCommerceEventsEventOverridePlural');
    delete_option('globalWooCommerceEventsAttendeeOverride');
    delete_option('globalWooCommerceEventsAttendeeOverridePlural');
    delete_option('globalWooCommerceEventsTicketOverride');
    delete_option('globalWooCommerceEventsTicketOverridePlural');
    delete_option('WooCommerceEventsDayOverride');
    delete_option('WooCommerceEventsDayOverridePlural');
    delete_option('globalWooCommerceEventsZoomAPIKey');
    delete_option('globalWooCommerceEventsZoomAPISecret');
    delete_option('globalWooCommerceEventsZoomUsers');
    delete_option('globalWooCommerceEventsZoomSelectedUserOption');
    delete_option('globalWooCommerceEventsZoomSelectedUsers');
    
}
register_uninstall_hook(__FILE__, 'uninstallFooEvents');