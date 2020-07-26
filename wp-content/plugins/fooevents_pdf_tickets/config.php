<?php if ( ! defined( 'ABSPATH' ) ) exit; 

class FooEvents_PDF_Tickets_Config {
    
    public $classPath;
    public $pluginURL;
    public $pluginDirectory;
    public $templatePath; 
    public $barcodePath;
    public $pdfTicketPath;
    public $stylesPath;
    public $templatePathTheme;
    public $path;
    public $pluginFile;
    public $themePacksPath;
    public $themePacksURL;
    public $uploadsDirPath;
    public $uploadsPath;
    public $barcodeURL;

    /**
     * Initialize configuration variables to be used as object.
     * 
     */
    public function __construct() {
        
        $upload_dir = wp_upload_dir();
        
        $this->classPath = plugin_dir_path( __FILE__ ).'classes/';
        $this->pluginDirectory = 'fooevents_pdf_tickets';
        $this->eventPluginURL = plugins_url().'/'.$this->pluginDirectory.'/';
        $this->templatePath = plugin_dir_path( __FILE__ ).'templates/';
        $this->pdfTicketPath = $upload_dir['basedir'].'/fooevents/pdftickets/'; 
        $this->pdfTicketURL = $upload_dir['baseurl'].'/fooevents/pdftickets/'; 
        $this->stylesPath = plugin_dir_url(__FILE__) .'css/';
        $this->templatePathTheme = get_stylesheet_directory().'/'.$this->pluginDirectory.'/templates/';
        $this->path = plugin_dir_path( __FILE__ );
        $this->pluginFile = $this->path.'fooevents-pdf-tickets.php';
        $this->themePacksPath = $upload_dir['basedir'].'/fooevents/themes/';
        $this->themePacksURL = $upload_dir['baseurl'].'/fooevents/themes/';
        $this->uploadsDirPath = $upload_dir['basedir'];
        $this->uploadsPath = $upload_dir['basedir'].'/fooevents/';
        $this->pdfTemplateSingle = plugin_dir_path( __FILE__ ).'templates/default_pdf_single/';
        $this->pdfTemplateMultiple = plugin_dir_path( __FILE__ ).'templates/default_pdf_multiple/';
        $this->barcodeURL = $upload_dir['baseurl'].'/fooevents/barcodes/';

    }
    
}