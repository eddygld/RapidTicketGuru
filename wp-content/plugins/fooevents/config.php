<?php if(!defined('ABSPATH')) exit;

class FooEvents_Config {
	
    public $pluginVersion;
    public $pluginDirectory;
    public $path;
    public $classPath;
    public $templatePath; 
    public $vendorPath; 
    public $barcodePath;
    public $pdfTicketPath;
    public $pdfTicketURL;
    public $icsPath;
    public $icsURL;
    public $scriptsPath;
    public $stylesPath;
    public $emailTemplatePath;
    public $pluginURL;
    public $eventPluginURL;
    public $clientMode;
    public $salt;
	
        /**
         * Initialize configuration variables to be used as object.
         * 
         */
	public function __construct() {

            $upload_dir = wp_upload_dir();
            
            $this->pluginVersion = '1.11.35';
            $this->pluginDirectory = 'fooevents';
            $this->path = plugin_dir_path( __FILE__ );
            $this->pluginFile = $this->path.'fooevents.php';
            $this->pluginURL = plugin_dir_url(__FILE__);
            $this->classPath = plugin_dir_path( __FILE__ ).'classes/';
            $this->templatePath = plugin_dir_path( __FILE__ ).'templates/';
            $this->vendorPath = plugin_dir_path( __FILE__ ).'vendor/';
            $this->uploadsDirPath = $upload_dir['basedir'];
            $this->uploadsPath = $upload_dir['basedir'].'/fooevents/';
            $this->barcodePath = $upload_dir['basedir'].'/fooevents/barcodes/';
            $this->barcodeURL = $upload_dir['baseurl'].'/fooevents/barcodes/';
            $this->themePacksPath = $upload_dir['basedir'].'/fooevents/themes/';
            $this->themePacksURL = $upload_dir['baseurl'].'/fooevents/themes/';
            $this->pdfTicketPath = $upload_dir['basedir'].'/fooevents/pdftickets/'; 
            $this->pdfTicketURL = $upload_dir['baseurl'].'/fooevents/pdftickets/'; 
            $this->icsPath = $upload_dir['basedir'].'/fooevents/ics/'; 
            $this->icsURL = $upload_dir['baseurl'].'/fooevents/ics/'; 
            $this->emailTemplatePath = plugin_dir_path( __FILE__ ).'templates/email/';
            $this->emailTemplatePathThemeEmail = get_stylesheet_directory().'/'.$this->pluginDirectory.'/themes/';
            $this->emailTemplatePathTheme = get_stylesheet_directory().'/'.$this->pluginDirectory.'/templates/';
            $this->scriptsPath = plugin_dir_url(__FILE__) .'js/';
            $this->stylesPath = plugin_dir_url(__FILE__) .'css/';
            $this->eventPluginURL = plugins_url().'/'.$this->pluginDirectory.'/';
            $this->clientMode = false;
            $this->salt = get_option('woocommerce_events_do_salt');
            
	}

}