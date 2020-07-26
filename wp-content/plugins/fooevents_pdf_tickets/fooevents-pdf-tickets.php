<?php if ( ! defined( 'ABSPATH' ) ) exit; 
/**
 * Plugin Name: FooEvents PDF Tickets
 * Description: Attach tickets as .pdf files
 * Version: 1.7.8
 * Author: FooEvents
 * Plugin URI: https://www.fooevents.com/
 * Author URI: https://www.fooevents.com/
 * Developer: FooEvents
 * Developer URI: https://www.fooevents.com/
 * Text Domain: fooevents-pdf-tickets
 *
 * Copyright: © 2009-2020 FooEvents.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

//include config
require(WP_PLUGIN_DIR.'/fooevents_pdf_tickets/config.php');
require(WP_PLUGIN_DIR.'/fooevents_pdf_tickets/vendor/autoload.php');

// reference the Dompdf namespace
use Dompdf\Dompdf;

class FooEvents_PDF_Tickets {

    public $Config;
    public $PDFHelper;
    public $TicketHelper;
    private $UpdateHelper;
    
    public function __construct() {

        add_action('admin_notices', array($this, 'check_fooevents'));
        add_action('admin_notices', array($this, 'check_gd'));
        add_action('woocommerce_settings_tabs_settings_woocommerce_events', array($this, 'add_settings_tab_settings'));
        add_action('woocommerce_update_options_settings_woocommerce_events', array($this, 'update_settings_tab_settings'));
        add_action('woocommerce_process_product_meta', array($this, 'process_meta_box'));
        add_action('admin_init', array($this, 'register_scripts_and_styles'));
        add_action('plugins_loaded', array($this, 'load_text_domain'));
        
        add_action('init', array($this, 'fooevents_endpoints'));
        add_filter('woocommerce_account_menu_items', array($this, 'add_tickets_account_menu_item'));
        add_filter('query_vars', array($this, 'fooevents_query_vars'), 0);
        add_action('after_switch_theme', array($this, 'fooevents_flush_rewrite_rules'));
        add_action('woocommerce_account_fooevents-tickets_endpoint', array($this, 'fooevents_custom_endpoint_content'));
        
        add_action('admin_init', array($this, 'register_settings_options'));
        
        $this->plugin_init();
    }
    
    /**
     * Register JavaScript and CSS file in Wordpress admin
     * 
     */
    public function register_scripts_and_styles() {
   
        wp_enqueue_style( 'fooevents-pdf-tickets-admin-style',  $this->Config->stylesPath . 'pdf-tickets-admin.css', array(), '1.0.0' );

    }
  
    /**
     * Processes the meta box form once the plubish / update button is clicked.
     * 
     * @global object $woocommerce_errors
     * @param int $post_id
     * @param object $post
     */
    public function process_meta_box($post_id) {
        
        global $woocommerce_errors;

        if(isset($_POST['FooEventsPDFTicketsEmailText'])) {

            update_post_meta($post_id, 'FooEventsPDFTicketsEmailText', $_POST['FooEventsPDFTicketsEmailText']);

        }
        
        if(isset($_POST['FooEventsTicketFooterText'])) {

            update_post_meta($post_id, 'FooEventsTicketFooterText', wp_kses_post($_POST['FooEventsTicketFooterText']));

        }
        
    }
    
    /**
     * Generate the PDF theme options in a product's event options
     * 
     * @param object $post
     * @return string
     */
    public function generate_pdf_theme_options($post) {

        $WooCommerceEventsPDFTicketTheme = get_post_meta($post->ID, 'WooCommerceEventsPDFTicketTheme', true);
        
        if(empty($WooCommerceEventsPDFTicketTheme)) {
            
            $globalFooEventsPDFTicketsLayout = get_option('globalFooEventsPDFTicketsLayout');
            
            if($globalFooEventsPDFTicketsLayout == 'multiple') {
                
                $WooCommerceEventsPDFTicketTheme = $this->Config->uploadsPath.'themes/default_pdf_multiple';
                
            } else {
                
                $WooCommerceEventsPDFTicketTheme = $this->Config->uploadsPath.'themes/default_pdf_single';
                
            }
            
        }
        
        $themes = $this->get_pdf_ticket_themes();
        
        ob_start();

        require($this->Config->templatePath.'pdf-ticket-theme-options.php');

        $pdf_ticket_theme_options = ob_get_clean();
        
        return $pdf_ticket_theme_options;
        
    }
    
    /**
     * Returns an array of valid themes supporting PDF tickets
     * 
     * @return array
     */
    public function get_pdf_ticket_themes()  {
        
        $valid_themes = array();
        
        foreach (new DirectoryIterator($this->Config->themePacksPath) as $file) {
            
            if ($file->isDir() && !$file->isDot()) {
                
                $theme_name = $file->getFilename();
                
                $theme_path = $file->getPath();
                $theme_path = $theme_path.'/'.$theme_name;
                
                $theme_name_pretty = str_replace('_', " ", $theme_name);
                $theme_name_prep = ucwords($theme_name_pretty);
                
                if(file_exists($theme_path.'/header.php') && file_exists($theme_path.'/footer.php') && file_exists($theme_path.'/ticket.php') && file_exists($theme_path.'/config.json')) {
                    
                    $theme_config = file_get_contents($theme_path.'/config.json');
                    $theme_config = json_decode($theme_config, true);
                    
                    if($theme_config['supports-pdf'] == 'true') {
                    
                        $valid_themes[$theme_name_prep]['path'] = $theme_path;
                        $theme_url = $this->Config->themePacksURL.$theme_name;
                        $valid_themes[$theme_name_prep]['url'] = $theme_url;
                        $valid_themes[$theme_name_prep]['name'] = $theme_config['name'];

                        if(file_exists($theme_path.'/preview.jpg')) {   

                            $valid_themes[$theme_name_prep]['preview'] = $theme_url.'/preview.jpg';

                        } else {

                            $valid_themes[$theme_name_prep]['preview'] = $this->Config->eventPluginURL.'images/no-preview.jpg';

                        }

                        $valid_themes[$theme_name_prep]['file_name'] = $file->getFilename();
                        
                    }    
                    
                }

            }
            
        }

        return $valid_themes;
        
    }
    
    /**
     * Checks if FooEvents is installed
     * 
     */
    public function check_fooevents() {
        
        if (!is_plugin_active('fooevents/fooevents.php')) {

            $this->output_notices(array(__( 'The FooEvents PDF Tickets plugin requires FooEvents for WooCommerce to be installed.', 'fooevents-pdf-tickets' )));

        } 
        
    }
    
    /**
     * Checks if GD libraries is enabled
     * 
     */
    public function check_gd() {
        
        if(!extension_loaded('gd')){
            
            $this->output_notices(array(__( 'GD libraries is not enabled on your server. This is a requirement for FooEvents PDF tickets. Please contact your host to enable this.', 'fooevents-pdf-tickets' )));
            
        }
        
        if(!ini_get('allow_url_fopen')) {
            
            $this->output_notices(array(__( 'The setting allow_url_fopen is not enabled on your server. This is a requirement for FooEvents PDF tickets. Please contact your host to enable this.', 'fooevents-pdf-tickets' )));
            
        }
        
        if(!extension_loaded('mbstring')) {
            
            $this->output_notices(array(__( 'The PHP MBstring module is not enabled on your server. This is a requirement for FooEvents PDF tickets. Please contact your host to enable this.', 'fooevents-pdf-tickets' )));

        }
        
    }
    
    /**
     * Initializes plugin
     * 
     */
    public function plugin_init() {
        
        //Main config
        $this->Config = new FooEvents_PDF_Tickets_Config();
        
        //PDFHelper
        require_once($this->Config->classPath.'pdfhelper.php');
        $this->PDFHelper = new FooEvents_PDF_helper($this->Config);
        
        //UpdateHelper
        require_once($this->Config->classPath.'updatehelper.php');
        $this->UpdateHelper = new FooEvents_PDF_Tickets_Update_Helper($this->Config);
        
        //copy default PDF themes
        if(!file_exists($this->Config->uploadsPath.'themes/default_pdf_single') && is_writable($this->Config->uploadsDirPath)) {
            
            $this->xcopy($this->Config->pdfTemplateSingle, $this->Config->themePacksPath.'default_pdf_single');
            
        }
        
        if(!file_exists($this->Config->uploadsPath.'themes/default_pdf_multiple') && is_writable($this->Config->uploadsDirPath)) {
            
            $this->xcopy($this->Config->pdfTemplateMultiple, $this->Config->themePacksPath.'default_pdf_multiple');
            
        }
        
    }

    /**
     * Initializes the WooCommerce meta box
     * 
     */
    public function add_product_pdf_tickets_options_tab() {

        echo '<li class="custom_tab_pdf_tickets"><a href="#fooevents_pdf_ticket_settings">'.__( ' PDF Tickets', 'fooevents-pdf-tickets' ).'</a></li>';

    }
    
    public function add_product_pdf_tickets_options_tab_options($post) {

        $FooEventsPDFTicketsEmailText   = get_post_meta($post->ID, 'FooEventsPDFTicketsEmailText', true);
        $FooEventsTicketFooterText      = get_post_meta($post->ID, 'FooEventsTicketFooterText', true);

        ob_start();
        
        require($this->Config->templatePath.'pdf-ticket-options.php');
        
        $pdf_ticket_options = ob_get_clean();
        
        return $pdf_ticket_options;
        
    }
    
    /**
     * Adds the WooCommerce tab settings
     * 
     */
    public function add_settings_tab_settings() {
        
        woocommerce_admin_fields($this->get_tab_settings());
        
    }
    
    /**
     * Saves the WooCommerce tab settings
     * 
     */
    public function update_settings_tab_settings() {

        woocommerce_update_options($this->get_tab_settings());

    }
    
    /**
     * Builds a ticket per page pdf
     * 
     * @param array $ticket
     * @param string $eventPluginURL
     * @param string $eventPluginPath
     */
    public function generate_ticket($productID, $tickets, $eventPluginURL, $eventPluginPath) {
        
        /*error_reporting(E_ALL);
        ini_set('display_errors', 1);*/

        $WooCommerceEventsPDFTicketTheme = get_post_meta($productID, 'WooCommerceEventsPDFTicketTheme', true);

        if(empty($WooCommerceEventsPDFTicketTheme)) {
            
            $globalFooEventsPDFTicketsLayout = get_option('globalFooEventsPDFTicketsLayout');
            
            if(!empty($globalFooEventsPDFTicketsLayout)) {
                
                if($globalFooEventsPDFTicketsLayout == 'multiple') {
                    
                    $WooCommerceEventsPDFTicketTheme = $this->Config->uploadsPath.'themes/default_pdf_multiple';
                    
                } else {
                    
                    $WooCommerceEventsPDFTicketTheme = $this->Config->uploadsPath.'themes/default_pdf_single';
                    
                }
                
            } else {
            
                $WooCommerceEventsPDFTicketTheme = $this->Config->uploadsPath.'themes/default_pdf_single';
            
            }
            
        }

        $ticket_output = '';
        $fileName = '';
        $x = 1;
        $numTickets = count($tickets);
        
        $ticket_output .= $this->PDFHelper->parse_email_template($WooCommerceEventsPDFTicketTheme.'/header.php', array(), $tickets[0]);

        foreach($tickets as $ticket) {
            
            $ticket['ticketNumber'] = $x;
            $ticket['type'] = "PDF";
            $ticket['ticketTotal'] = $numTickets;
            $ticket_output .= $this->PDFHelper->parse_ticket_template($ticket, $WooCommerceEventsPDFTicketTheme.'/ticket.php');
 
            if($x == 1) {
                
                $fileName .= $ticket['barcodeFileName'];
                
            }
            
            if($x == $numTickets) {
                
                $fileName .= '-'.$ticket['barcodeFileName'];
                
            } else {

                $x++;

            }

        }
        $tickets[0]['type'] = "PDF";
        $tickets[0]['ticketNumber'] = $x;
        $ticket_output .= $this->PDFHelper->parse_email_template($WooCommerceEventsPDFTicketTheme.'/footer.php', array(), $tickets[0]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($ticket_output);
        $dompdf->setBasePath(ABSPATH);
        $dompdf->set_option('enable_remote', TRUE);
        $dompdf->setPaper('A4');

        $dompdf->getOptions()->setIsFontSubsettingEnabled(true);

        $dompdf->render();

        $output = $dompdf->output();
        $path = $this->Config->pdfTicketPath.''.$fileName.'.pdf';
        file_put_contents($path, $output);
        
        return $path;
        
        exit();
    }

    /**
     * Build multiple tickets per page pdf
     * 
     * @param array $tickets
     * @param string $eventPluginURL
     * @param string $eventPluginPath
     */
    public function generate_multiple_ticket($tickets, $eventPluginURL, $eventPluginPath) {
        
        $ticket_output = '';
        $fileName = '';
        $x = 1;
        $numTickets = count($tickets);
        $sortedTickets = array();
        
        foreach($tickets as $ticket) {
            
            $sortedTickets[$ticket['name']][] = $ticket;
            
        }

        foreach($tickets as $ticket) {
            
            if($x == 1) {
                
                $fileName .= $ticket['barcodeFileName'];
                
            }
            
            if($x == $numTickets) {
                
                $fileName .= '-'.$ticket['barcodeFileName'];
                
            }
            
            $x++;
            
        }
        
        foreach($sortedTickets as $tickets) {
        
            $ticket_output .= $this->PDFHelper->parse_multiple_ticket_template($tickets, 'pdf-ticket-template-multiple.php', $eventPluginURL, $eventPluginPath);
        
        }

        $dompdf = new Dompdf();
        $dompdf->loadHtml($ticket_output);
        $dompdf->set_option('enable_remote', TRUE);
        $dompdf->setPaper('A4');

        $dompdf->render();

        $output = $dompdf->output();
        $path = $this->Config->pdfTicketPath.''.$fileName.'.pdf';
        file_put_contents($path, $output);
        
        return $path;
        
        exit();
    }
    
    /**
     * Includes email template and parses PHP.
     * 
     * @param string $template
     * @return string
     */
    public function parse_email_template($template) {

        ob_start();
        
        //Check theme directory for template first
        if(file_exists($this->Config->templatePathTheme.$template) ) {

             include($this->Config->templatePathTheme.$template);

        }else {

            include($this->Config->templatePath.$template); 

        }

        return ob_get_clean();

    }
    
    /**
     * Display PDF options 
     * 
     */
    public function get_pdf_options() {
        
        ob_start();
        
        $globalFooEventsPDFTicketsEnable = get_option('globalFooEventsPDFTicketsEnable');
        $globalFooEventsPDFTicketsDownloads = get_option('globalFooEventsPDFTicketsDownloads');
        $globalFooEventsPDFTicketsAttachHTMLTicket = get_option('globalFooEventsPDFTicketsAttachHTMLTicket');
        $globalFooEventsPDFTicketsFont = get_option('globalFooEventsPDFTicketsFont');
        
        if(empty($globalFooEventsPDFTicketsFont)) {
            
            $globalFooEventsPDFTicketsFont = 'DejaVu Sans';
            
        }
        
        include($this->Config->templatePath.'/pdf-options.php'); 
        
        return ob_get_clean();
        
    }
    
    /**
     * Register FooEvents PDF Ticket options
     * 
     */
    public function register_settings_options() {
        
        register_setting('fooevents-settings-pdf', 'globalFooEventsPDFTicketsEnable');
        register_setting('fooevents-settings-pdf', 'globalFooEventsPDFTicketsDownloads');
        register_setting('fooevents-settings-pdf', 'globalFooEventsPDFTicketsAttachHTMLTicket');
        register_setting('fooevents-settings-pdf', 'globalFooEventsPDFTicketsFont');
        
    }
    
    public function load_text_domain() {

        $path = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
        $loaded = load_plugin_textdomain( 'fooevents-pdf-tickets', false, $path);
        
    }
    
    public function add_tickets_account_menu_item($items) {
        
        $globalFooEventsPDFTicketsDownloads = get_option('globalFooEventsPDFTicketsDownloads');
        
        if($globalFooEventsPDFTicketsDownloads == "yes") {

            $logout = $items['customer-logout'];
            unset( $items['customer-logout'] );

            $items['fooevents-tickets'] = __('Tickets', 'fooevents-pdf-tickets');
            $items['customer-logout'] = $logout;
        
        }

        return $items;
        
    }
    
    public function fooevents_endpoints() {
        
        add_rewrite_endpoint('fooevents-tickets', EP_ROOT | EP_PAGES);
        
    }
    
    public function fooevents_query_vars($vars) {
        
        $vars[] = 'fooevents-tickets';

        return $vars;
        
    }
    
    public function fooevents_flush_rewrite_rules() {
        
        flush_rewrite_rules();
        
    }
    
    public function fooevents_custom_endpoint_content() {
        
        $user = wp_get_current_user();
        
        $tickets = new WP_Query( array('post_type' => array('event_magic_tickets'), 'posts_per_page' => -1, 'meta_query' => array( array( 'key' => 'WooCommerceEventsCustomerID', 'value' => $user->ID ) )) );
        $tickets = $tickets->get_posts();
        
        //generate tickets if no exists
        foreach ($tickets as $ticket) {

            $WooCommerceEventsTicketID = get_post_meta($ticket->ID, 'WooCommerceEventsTicketID', true);
            $WooCommerceEventsTicketHash = get_post_meta($ticket->ID, 'WooCommerceEventsTicketHash', true);
            $WooCommerceEventsProductID = get_post_meta($ticket->ID, 'WooCommerceEventsProductID', true);
            
            $fileName = '';
            if(!empty($WooCommerceEventsTicketHash)) {
                
                $fileName = $WooCommerceEventsTicketHash.'-'.$WooCommerceEventsTicketID.'-'.$WooCommerceEventsTicketHash.'-'.$WooCommerceEventsTicketID;
                
            } else {
            
                $fileName = $WooCommerceEventsTicketID.'-'.$WooCommerceEventsTicketID;
            
            }
            
            $path = $this->Config->pdfTicketPath.''.$fileName.'.pdf';

            if(!file_exists($path)) {

                $ticket_gen = array();
                $FooEvents = new FooEvents();
                
                $ticket_data = $FooEvents->get_ticket_data($ticket->ID);
                $ticket_gen[] = $ticket_data;
                
                $eventPluginPath = $FooEvents->get_plugin_path();
                $eventPluginURL = $FooEvents->get_plugin_url();
                $eventPluginBarcodePath = $FooEvents->get_barcode_path();
                
                $this->generate_ticket($WooCommerceEventsProductID, $ticket_gen, $eventPluginBarcodePath, $eventPluginPath);
                
            } 
            
        }

        if(file_exists($this->Config->templatePathTheme.'ticket-list.php') ) {

             include($this->Config->templatePathTheme.'ticket-list.php');

        }else {

            include($this->Config->templatePath.'ticket-list.php'); 

        }
        
    }
    
    public function display_ticket_download($postID, $WooCommerceEventsTicketID, $eventbarcodePath, $eventPluginURL) {
        
        $WooCommerceEventsTicketID = get_post_meta($postID, 'WooCommerceEventsTicketID', true);
        $WooCommerceEventsTicketHash = get_post_meta($postID, 'WooCommerceEventsTicketHash', true);
        $WooCommerceEventsProductID = get_post_meta($postID, 'WooCommerceEventsProductID', true);

        $fileName = '';
        if(!empty($WooCommerceEventsTicketHash)) {

            $fileName = $WooCommerceEventsTicketHash.'-'.$WooCommerceEventsTicketID.'-'.$WooCommerceEventsTicketHash.'-'.$WooCommerceEventsTicketID;

        } else {

            $fileName = $WooCommerceEventsTicketID.'-'.$WooCommerceEventsTicketID;

        }
        
        $urlPath = $this->Config->pdfTicketURL.$fileName.'.pdf';
        $filePath = $this->Config->pdfTicketPath.$fileName.'.pdf';

        if(!file_exists($filePath)) {
            
            $ticket = array();
            $FooEvents = new FooEvents();
            $ticket_data = $FooEvents->get_ticket_data($postID);
            $ticket[] = $ticket_data;
            
            $this->generate_ticket($WooCommerceEventsProductID, $ticket, $eventbarcodePath, $eventPluginURL);
           
        }

        include $this->Config->path.'templates/download-ticket-admin.php'; 
        
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
     * Outputs notices to screen.
     * 
     * @param array $notices
     */
    private function output_notices($notices) {

        foreach ($notices as $notice) {

                echo "<div class='updated'><p>$notice</p></div>";

        }

    }
    
    
}

$FooEvents_PDF_Tickets = new FooEvents_PDF_Tickets();

function uninstallFooEventsPDFTickets() {

    delete_option('globalFooEventsPDFTicketsEnable');
    delete_option('globalFooEventsPDFTicketsDownloads');
    delete_option('globalFooEventsPDFTicketsAttachHTMLTicket');
    delete_option('globalFooEventsPDFTicketsFont');
    delete_option('globalFooEventsPDFTicketsLayout');
    
}

register_uninstall_hook(__FILE__, 'uninstallFooEventsPDFTickets');