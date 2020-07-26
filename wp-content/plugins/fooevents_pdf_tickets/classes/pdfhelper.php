<?php

class FooEvents_PDF_helper {
    
    private $Config;
    
    public function __construct($Config) {
    
        $this->Config = $Config;
        
    }
    
    /**
     * Includes email template and parses PHP
     * 
     * @param string $template
     * @param array $customerDetails
     * @param array $ticket
     * @return string
     */
    public function parse_email_template($template, $customerDetails = array(), $ticket) {
        
        ob_start();
	$themePacksURL = $this->Config->themePacksURL;
        $barcodeURL =  $this->Config->barcodeURL;
        
        $globalFooEventsPDFTicketsFont = get_option('globalFooEventsPDFTicketsFont');
        $font_family = $this->_get_font_family($globalFooEventsPDFTicketsFont);
        $font_face = $this->_get_font_face($globalFooEventsPDFTicketsFont);
        $font_face .= $this->_get_font_face_bold($globalFooEventsPDFTicketsFont);
        
        include($template); 
        
        return ob_get_clean();
        
    }
    
    /**
     * Includes the ticket template and parses PHP.
     * 
     * @param array $ticket
     * @param string $template_name
     */
    public function parse_ticket_template($ticket, $template) {
        
        ob_start();
        
        $plugins_url = plugins_url();
        $barcodeURL =  $this->Config->barcodeURL;
        
        $globalFooEventsPDFTicketsFont = get_option('globalFooEventsPDFTicketsFont');
        $font_family = $this->_get_font_family($globalFooEventsPDFTicketsFont);
        $font_face = $this->_get_font_face($globalFooEventsPDFTicketsFont);
        
        //Check theme directory for template first
        if(file_exists($this->Config->templatePathTheme.$template) ) {

             include($this->Config->templatePathTheme.$template);

        } else {

            include($template); 

        }

        return ob_get_clean();
        
    }
    
    /**
     * Includes the ticket template and parses PHP.
     * 
     * @param array $tickets
     * @param string $template_name
     */
    public function parse_multiple_ticket_template($tickets, $template, $eventPluginURL) {
        
        ob_start();
        
        $plugins_url = plugins_url();
        
        //Check theme directory for template first
        if(file_exists($this->Config->templatePathTheme.$template) ) {

             include($this->Config->templatePathTheme.$template);

        }else {

            include($this->Config->templatePath.$template); 

        }

        return ob_get_clean();
        
    }

    /**
     * Generates font family based on selected global font
     * 
     * @param string $globalFooEventsPDFTicketsFont
     * @return string 
     */
    private function _get_font_family($globalFooEventsPDFTicketsFont) {
        
        switch($globalFooEventsPDFTicketsFont) {
            
            case 'DejaVu Sans':
            return "'DejaVu Sans','Helvetica'";
            break;
        
            case 'Firefly Sung':
            return "'Firefly Sung', 'DejaVu Sans','Helvetica'";
            break;
        
            default:
            return "'DejaVu Sans','Helvetica'";
            
        }
        
    }
    
    /**
     * Generates font face based on selected global font
     * 
     * @param string $globalFooEventsPDFTicketsFont
     * @return string 
     */
    private function _get_font_face($globalFooEventsPDFTicketsFont) {
        
        switch($globalFooEventsPDFTicketsFont) {
            
            case 'DejaVu Sans':
            return "";
            break;
        
            case 'Firefly Sung':
            return "@font-face {
                    font-family: 'Firefly Sung';
                    font-style: normal;
                    font-weight: 400;
                    src: url(https://www.fooevents.com/fonts/fireflysung.ttf) format('truetype');
                  }";
            break;
        
            default:
            return "";
            
        }
        
    }
    
    /**
     * Generates font face based on selected global font
     * 
     * @param string $globalFooEventsPDFTicketsFont
     * @return string 
     */
    private function _get_font_face_bold($globalFooEventsPDFTicketsFont) {
        
        switch($globalFooEventsPDFTicketsFont) {
            
            case 'DejaVu Sans':
            return "";
            break;
        
            case 'Firefly Sung':
            return "@font-face {
                    font-family: 'Firefly Sung';
                    font-style: bold;
                    font-weight: bold;
                    src: url(https://www.fooevents.com/fonts/fireflysung.ttf) format('truetype');
                  }";
            break;
        
            default:
            return "";
            
        }
        
    }
    
}
