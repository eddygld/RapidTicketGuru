<?php if(!defined('ABSPATH')) exit;
class FooEvents_ICS_helper {
    
    public $data;
    public $name;
    public $Config;
    private $ZoomAPIHelper;

    function __construct($Config) {
        
        $this->Config = $Config;

        //ZoomAPIHelper
        require_once($this->Config->classPath.'zoomapihelper.php');
        $this->ZoomAPIHelper = new FooEvents_Zoom_API_Helper($this->Config);
        
    }

    /**
     * Generate variables needed to build .ics file
     * 
     * @param int $event
     * @param string $registrant_email
     */
    function generate_ICS($event, $registrant_email = '') {

        $this->data = '';

        $post = get_post($event);
        
        $WooCommerceEventsEvent             = get_post_meta($event, 'WooCommerceEventsEvent', true);
        $WooCommerceEventsDate              = get_post_meta($event, 'WooCommerceEventsDate', true);
        $WooCommerceEventsEndDate           = get_post_meta($event, 'WooCommerceEventsEndDate', true);
        $WooCommerceEventsHour              = get_post_meta($event, 'WooCommerceEventsHour', true);
        $WooCommerceEventsMinutes           = get_post_meta($event, 'WooCommerceEventsMinutes', true);
        $WooCommerceEventsPeriod            = get_post_meta($event, 'WooCommerceEventsPeriod', true);
        $WooCommerceEventsHourEnd           = get_post_meta($event, 'WooCommerceEventsHourEnd', true);
        $WooCommerceEventsMinutesEnd        = get_post_meta($event, 'WooCommerceEventsMinutesEnd', true);
        $WooCommerceEventsLocation          = get_post_meta($event, 'WooCommerceEventsLocation', true);
        $WooCommerceEventsEndPeriod         = get_post_meta($event, 'WooCommerceEventsEndPeriod', true);
        $WooCommerceEventsTimeZone          = get_post_meta($event, 'WooCommerceEventsTimeZone', true);
        $WooCommerceEventsTicketText        = get_post_meta($event, 'WooCommerceEventsTicketText', true);
        $WooCommerceEventsTicketDisplayZoom = get_post_meta($event, 'WooCommerceEventsTicketDisplayZoom', true);
        $WooCommerceEventsTicketAddCalendarReminders = get_post_meta($event, 'WooCommerceEventsTicketAddCalendarReminders', true);

        $WooCommerceEventsTimeZone = str_replace('UTC', 'GMT', $WooCommerceEventsTimeZone);

        if ( $WooCommerceEventsEndDate == '' ) {

            $WooCommerceEventsEndDate = $WooCommerceEventsDate;

        }

        $WooCommerceEventsPeriod = $WooCommerceEventsHour > 12 ? '' : strtoupper(str_replace('.', '', $WooCommerceEventsPeriod));
        $WooCommerceEventsEndPeriod = $WooCommerceEventsHourEnd > 12 ? '' : strtoupper(str_replace('.', '', $WooCommerceEventsEndPeriod));
        
        if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
            require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
        }
        
        $multiDayType = '';
        
        if ($this->is_plugin_active('fooevents_multi_day/fooevents-multi-day.php') || is_plugin_active_for_network('fooevents_multi_day/fooevents-multi-day.php')) {
            
            $Fooevents_Multiday_Events = new Fooevents_Multiday_Events();
            $multiDayType = $Fooevents_Multiday_Events->get_multi_day_type($event);

        }
        
        $description = get_bloginfo('name');

        if ( !empty($WooCommerceEventsTicketText) ) {

            $description .= '\n\n' . strip_tags(str_replace('<br/>', '\n', $WooCommerceEventsTicketText));

        }

        if ( !empty($WooCommerceEventsTicketDisplayZoom) && $WooCommerceEventsTicketDisplayZoom != 'off' ) {

            $description .= $this->ZoomAPIHelper->get_calendar_text($event, $registrant_email);

        }

        $date_format = get_option( 'date_format' ) . ' H:i';

        if($multiDayType == 'select') {
            
            $multiDayDates = $Fooevents_Multiday_Events->get_multi_day_selected_dates($event);
            
            foreach($multiDayDates as $dayDate) {
                
                $startPeriodFormat = $WooCommerceEventsPeriod != '' ? ' A' : '';
                $startPeriod = $WooCommerceEventsPeriod != '' ? ' ' . $WooCommerceEventsPeriod : '';

                $endPeriodFormat = $WooCommerceEventsEndPeriod != '' ? ' A' : '';
                $endPeriod = $WooCommerceEventsEndPeriod != '' ? ' ' . $WooCommerceEventsEndPeriod : '';

                $dayDate = $this->convert_month_to_english($dayDate);

                $tempStartDate = DateTime::createFromFormat($date_format.$startPeriodFormat, $dayDate." ".$WooCommerceEventsHour.':'.$WooCommerceEventsMinutes.$startPeriod);
                $tempEndDate = DateTime::createFromFormat($date_format.$endPeriodFormat, $dayDate." ".$WooCommerceEventsHourEnd.':'.$WooCommerceEventsMinutesEnd.$endPeriod);

                if ( $tempStartDate ) {

                    $startDate = date("Y-m-d H:i:s", $tempStartDate->getTimestamp());

                } else {

                    $WooCommerceEventsDate = str_replace('/', '-', $dayDate);
                    $WooCommerceEventsDate = str_replace(',', '', $WooCommerceEventsDate);

                    $startDate = date("Y-m-d H:i:s", strtotime($WooCommerceEventsDate." ".$WooCommerceEventsHour.':'.$WooCommerceEventsMinutes.' '.$WooCommerceEventsPeriod));

                }

                if ( $tempEndDate ) {

                    $endDate = date("Y-m-d H:i:s", $tempEndDate->getTimestamp());

                } else {

                    $WooCommerceEventsDate = str_replace('/', '-', $dayDate);
                    $WooCommerceEventsDate = str_replace(',', '', $WooCommerceEventsDate);

                    $endDate = date("Y-m-d H:i:s", strtotime($WooCommerceEventsDate." ".$WooCommerceEventsHourEnd.':'.$WooCommerceEventsMinutesEnd.' '.$WooCommerceEventsEndPeriod));

                }
                
                $this->build_ICS($startDate, $endDate, $WooCommerceEventsTimeZone, $post->post_title, $description, $WooCommerceEventsLocation, $WooCommerceEventsTicketAddCalendarReminders); 
                
            }
            
        } else {
            
            $startPeriodFormat = $WooCommerceEventsPeriod != '' ? ' A' : '';
            $startPeriod = $WooCommerceEventsPeriod != '' ? ' ' . $WooCommerceEventsPeriod : '';

            $endPeriodFormat = $WooCommerceEventsEndPeriod != '' ? ' A' : '';
            $endPeriod = $WooCommerceEventsEndPeriod != '' ? ' ' . $WooCommerceEventsEndPeriod : '';
            
            $WooCommerceEventsDate = $this->convert_month_to_english($WooCommerceEventsDate);
            $WooCommerceEventsEndDate = $this->convert_month_to_english($WooCommerceEventsEndDate);

            $tempStartDate = DateTime::createFromFormat($date_format.$startPeriodFormat, $WooCommerceEventsDate." ".$WooCommerceEventsHour.':'.$WooCommerceEventsMinutes.$startPeriod);
            $tempEndDate = DateTime::createFromFormat($date_format.$endPeriodFormat, $WooCommerceEventsEndDate." ".$WooCommerceEventsHourEnd.':'.$WooCommerceEventsMinutesEnd.$endPeriodFormat);

            if ( $tempStartDate ) {

                $startDate = date("Y-m-d H:i:s", $tempStartDate->getTimestamp());

            } else {

                $WooCommerceEventsDate = str_replace('/', '-', $WooCommerceEventsDate);
                $WooCommerceEventsDate = str_replace(',', '', $WooCommerceEventsDate);

                $startDate = date("Y-m-d H:i:s", strtotime($WooCommerceEventsDate." ".$WooCommerceEventsHour.':'.$WooCommerceEventsMinutes.' '.$WooCommerceEventsPeriod));

            }
            
            if ( $tempEndDate ) {

                $endDate = date("Y-m-d H:i:s", $tempEndDate->getTimestamp());

            } else {

                $WooCommerceEventsEndDate = str_replace('/', '-', $WooCommerceEventsEndDate);
                $WooCommerceEventsEndDate = str_replace(',', '', $WooCommerceEventsEndDate);

                $endDate = date("Y-m-d H:i:s", strtotime($WooCommerceEventsEndDate." ".$WooCommerceEventsHourEnd.':'.$WooCommerceEventsMinutesEnd.' '.$WooCommerceEventsEndPeriod));

            }

            $this->build_ICS($startDate, $endDate, $WooCommerceEventsTimeZone, $post->post_title, $description, $WooCommerceEventsLocation, $WooCommerceEventsTicketAddCalendarReminders); 
            
        }

    }
    
    /**
     * Builds add to calendar .ics file
     * 
     * @param string $start
     * @param string $end
     * @param string $name
     * @param string $description
     * @param string $location
     */
    function build_ICS($start,$end,$timezone,$name,$description,$location = '', $reminders = '') {

        if ( $reminders == '' ) {

            $reminders = array(
                array('amount' => 1, 'unit' => 'weeks'),
                array('amount' => 1, 'unit' => 'days'),
                array('amount' => 1, 'unit' => 'hours'),
                array('amount' => 15, 'unit' => 'minutes')
            );

        }
        
        $this->name = $name;
        
        if(empty($this->name)) {
            
            $this->name = 'Event';
            
        }
        
        $start = (string) date("Ymd\THis",strtotime($start));
        $end = (string) date("Ymd\THis",strtotime($end));

        $domain = parse_url(get_site_url())['host'];
        
        $random = rand(111111,999999);

        $tzid_start = '';
        $tzid_end = '';

        if ( trim($timezone) != '' ) {
            $tzid_start = ";TZID=".$timezone;
            $tzid_end = ";TZID=".$timezone;
        }

        $this->data .= "BEGIN:VEVENT\r\nDTSTART".$tzid_start.":".$start."\r\nDTEND".$tzid_end.":".$end."\r\nLOCATION:".$location."\r\nTRANSP:OPAQUE\r\nSEQUENCE:0\r\nUID:".$start.$random."-fooevents@".$domain."\r\nDTSTAMP:".$start."\r\nSUMMARY:".$name."\r\nDESCRIPTION:".$description."\r\nPRIORITY:1\r\nCLASS:PUBLIC\r\n";
        
        foreach ( $reminders as $reminder ) {

            $minutes = 0;

            switch ( $reminder['unit'] ) {

                case 'minutes':
                $minutes = (int)$reminder['amount'];
                break;

                case 'hours':
                $minutes = (int)$reminder['amount'] * 60;
                break;

                case 'days':
                $minutes = (int)$reminder['amount'] * 1440;
                break;

                case 'weeks':
                $minutes = (int)$reminder['amount'] * 10080;
                break;

            }

            $this->data .= "BEGIN:VALARM\r\nTRIGGER:-PT" . $minutes . "M\r\nACTION:DISPLAY\r\nDESCRIPTION:Reminder\r\nEND:VALARM\r\n";

        }
        
        $this->data .= "END:VEVENT\r\n";
    
    }
    
    /**
     * Saves ICS file.
     * 
     * @param int $ticketID
     */
    function save($ticketID) {

        $data = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nMETHOD:PUBLISH\r\n".$this->data."END:VCALENDAR\r\n";

        file_put_contents($this->Config->icsPath.$ticketID.".ics", $data);
        
    }
    
    /**
     * Download the ICS file.
     * 
     */
    function show() {
        
        $data = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nMETHOD:PUBLISH\r\n".$this->data."END:VCALENDAR\r\n";
        
        header("Content-type:text/calendar");
        header('Content-Disposition: attachment; filename="'.$this->name.'.ics"');
        Header('Content-Length: '.strlen($data));
        Header('Connection: close');
        echo $data;
        
    }

    /**
     * Checks if a plugin is active.
     * 
     * @param string $plugin
     * @return boolean
     */
    private function is_plugin_active( $plugin ) {

        return in_array( $plugin, (array) get_option( 'active_plugins', array() ) );

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
    
}