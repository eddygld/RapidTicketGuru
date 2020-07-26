<?php if (!defined('ABSPATH')) exit;
class FooEvents_Zoom_API_Helper {

    public  $Config;
    private $scriptVersion = "1.0.3";

    public function __construct($config) {

        $this->Config = $config;

        add_action('admin_init', array($this, 'register_scripts'));
        add_action('admin_init', array($this, 'register_styles'));

        add_action('wp_enqueue_scripts', array($this, 'register_styles_frontend'));

        add_action('wp_ajax_fooevents_zoom_test_access', array($this, 'fooevents_zoom_test_access'));
        add_action('wp_ajax_fooevents_zoom_fetch_users', array($this, 'fooevents_zoom_fetch_users'));
        add_action('wp_ajax_fooevents_fetch_zoom_meeting', array($this, 'fooevents_fetch_zoom_meeting'));
        add_action('wp_ajax_fooevents_update_zoom_registration', array($this, 'fooevents_update_zoom_registration'));

    }

    /**
     * Register Zoom API helper scripts
     * 
     */
    public function register_scripts() {
        
        $zoomArgs = array(
            'testAccess'                            => __('Test Access', 'woocommerce-events'),
            'testingAccess'                         => __('Testing Access...', 'woocommerce-events'),
            'successFullyConnectedZoomAccount'      => __('Successfully connected to your Zoom account', 'woocommerce-events' ),
            'fetchUsers'                            => __('Fetch Users', 'woocommerce-events'),
            'fetchingUsers'                         => __('Fetching Users...', 'woocommerce-events'),
            'userOptionMe'                          => __('Show only meetings/webinars for the user that generated the API Key and Secret', 'woocommerce-events'),
            'userOptionSelect'                      => __('Show all meetings/webinars created by the following users:', 'woocommerce-events'),
            'userLoadTimes'                         => __('Please note that meeting/webinar load times will increase as more users are selected.', 'woocommerce-events'),
            'adminURL'                              => get_admin_url(),
            'pluginsURL'                            => plugins_url(),
            'notSet'                                => __('(Not set)', 'woocommerce-events'),
            'description'                           => __('Description', 'woocommerce-events'),
            'date'                                  => __('Date', 'woocommerce-events'),
            'startDate'                             => __('Start date', 'woocommerce-events'),
            'startTime'                             => __('Start time', 'woocommerce-events'),
            'endTime'                               => __('End time', 'woocommerce-events'),
            'duration'                              => __('Duration', 'woocommerce-events'),
            'recurrence'                            => __('Recurrence', 'woocommerce-events'),
            'upcomingOccurrences'                   => __('Upcoming occurrences', 'woocommerce-events'),
            'unableToFetchMeeting'                  => __('Unable to fetch meeting details', 'woocommerce-events'),
            'unableToFetchWebinar'                  => __('Unable to fetch webinar details', 'woocommerce-events'),
            'registrationRequired'                  => __('Note: Automatic attendee registration is required.','woocommerce-events'),
            'registrationRequiredForAllOccurrences' => __('Note: Automatic attendee registration is required for all occurrences.','woocommerce-events'),
            'meetingRegistrationCurrentlyEnabled'   => __('Automatic attendee registration is currently enabled for this meeting','woocommerce-events'),
            'webinarRegistrationCurrentlyEnabled'   => __('Automatic attendee registration is currently enabled for this webinar','woocommerce-events'),
            'meetingRegistrationCurrentlyDisabled'  => __('Automatic attendee registration is currently disabled for this meeting','woocommerce-events'),
            'webinarRegistrationCurrentlyDisabled'  => __('Automatic attendee registration is currently disabled for this webinar','woocommerce-events'),
            'enableMeetingRegistration'             => __('Enable automatic attendee registration for this meeting','woocommerce-events'),
            'enableWebinarRegistration'             => __('Enable automatic attendee registration for this webinar','woocommerce-events'),
            'registrationAllOccurrencesEnabled'     => __('Automatic attendee registration is currently enabled for all occurrences','woocommerce-events'),
            'registrationAllOccurrencesDisabled'    => __('Automatic attendee registration is not currently enabled for all occurrences','woocommerce-events'),
            'checkRegistrationForAllOccurrences'    => __('Check whether automatic attendee registration is enabled for all occurrences','woocommerce-events'),
            'registrations'                         => __('Registrations','woocommerce-events'),
            'linkMultiMeetingsWebinars'             => __('Link the event to these meetings/webinars:', 'woocommerce-events'),
            'showDetails'                           => __('Show details', 'woocommerce-events'),
            'hideDetails'                           => __('Hide details', 'woocommerce-events'),
            'selectMeetingWebinarTooltip'           => __('Select a meeting/webinar which attendees will automatically be registered for when purchasing an event ticket (must be created through your Zoom account).', 'woocommerce-events'),
            'notRecurringMeeting'                   => __('This is not a recurring meeting', 'woocommerce-events'),
            'notRecurringWebinar'                   => __('This is not a recurring webinar', 'woocommerce-events'),
            'noFixedTimeMeeting'                    => __("This meeting's recurrence is currently set to 'No Fixed Time' which does not allow attendees to pre-register in advance. Please change the setting for this meeting to have a fixed recurrence (daily/weekly/monthly) in your Zoom account before proceeding.", 'woocommerce-events'),
            'noFixedTimeWebinar'                    => __("This webinar's recurrence is currently set to 'No Fixed Time' which does not allow attendees to pre-register in advance. Please change the setting for this webinar to have a fixed recurrence (daily/weekly/monthly) in your Zoom account before proceeding.", 'woocommerce-events'),
            'editMeeting'                           => __('Edit meeting', 'woocommerce-events'),
            'editWebinar'                           => __('Edit webinar', 'woocommerce-events'),
        );

        wp_enqueue_script('woocommerce-events-zoom-admin-script', $this->Config->scriptsPath . 'events-zoom-admin.js', array('jquery'), $this->scriptVersion, true );
        wp_localize_script('woocommerce-events-zoom-admin-script', 'zoomObj', $zoomArgs);
  
    }

    /**
     * Register Zoom API helper styles
     * 
     */
    public function register_styles() {

        wp_enqueue_style('woocommerce-events-zoom-admin-style',  $this->Config->stylesPath . 'events-zoom-admin.css', array(), $this->scriptVersion);
        
    }

    /**
     * Register Zoom API helper styles for the front-end
     * 
     */
    public function register_styles_frontend() {

        wp_enqueue_style('woocommerce-events-zoom-frontend-style',  $this->Config->stylesPath . 'events-zoom-frontend.css', array(), $this->scriptVersion);
        
    }

    /**
     * Generate a Zoom API JWT token based using the provided API key and secret
     * 
     * @param string $key
     * @param string $secret
     * @param int $expiry
     * 
     * @return string
     */
    private function fooevents_zoom_generate_jwt($key, $secret, $expiry = 300) {

        if ( trim($key) == '' || trim($secret) == '' ) {

            return '';

        }

        require_once($this->Config->vendorPath.'/php-jwt/BeforeValidException.php');
        require_once($this->Config->vendorPath.'/php-jwt/ExpiredException.php');
        require_once($this->Config->vendorPath.'/php-jwt/SignatureInvalidException.php');
        require_once($this->Config->vendorPath.'/php-jwt/JWT.php');

        $token = array(
            "iss" => $key,
            "exp" => time() + $expiry
        );

        $jwt = \Firebase\JWT\JWT::encode($token, $secret);

        return $jwt;

    }

    /**
     * Generate a Zoom API JWT token from the saved key and secret
     * 
     * @return string
     */
    private function fooevents_zoom_jwt() {

        $key = (string)get_option('globalWooCommerceEventsZoomAPIKey', '');
        $secret = (string)get_option('globalWooCommerceEventsZoomAPISecret', '');

        return $this->fooevents_zoom_generate_jwt($key, $secret);

    }

    /**
     * Perform a Zoom API request and return the result
     * 
     * @param string $method
     * @param array $args
     * @param string $jwt
     * @param string $request_type
     * 
     * @return array
     */
    private function fooevents_zoom_request($method = '', $args = array(), $jwt = '', $request_type = 'GET') {

        $result = array('status' => 'error');

        if ( $jwt == '' ) {

            $jwt = $this->fooevents_zoom_jwt();

        }

        if ( empty($jwt) ) {

            $result['message'] = __('Error generating JSON Web Token (JWT) using the provided Zoom API Key and API Secret.', 'woocommerce-events');

            return $result;

        }

        $params = http_build_query($args);

        $curl = curl_init();

        $curl_options = array(
            CURLOPT_URL => "https://api.zoom.us/v2/" . $method . ($request_type == 'GET' ? "?" . $params : ''),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $request_type,
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . $jwt,
                "content-type: application/json"
            ),
        );

        if ( $request_type == 'POST' || $request_type == 'PUT' || $request_type == 'PATCH' ) {

            $curl_options[CURLOPT_POSTFIELDS] = json_encode($args);

        }

        curl_setopt_array($curl, $curl_options);

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        if ($error) {

            $result['message'] = __('Unable to connect to your Zoom account', 'woocommerce-events');

        } else {

            $response_array = json_decode($response, true);

            if ( !empty($response_array['code']) && !empty($response_array['message']) ) {

                $result = array(
                    'status' => 'error',
                    'message' => $response_array['message']
                );

            } else {

                $result = array(
                    'status' => 'success',
                    'data' => $response_array
                );

            }

        }

        return $result;

    }

    /**
     * Tests whether or not the Zoom API key and secret have been set up correctly
     * 
     */
    public function fooevents_zoom_test_access() {
        
        $key = trim($_POST['key']);
        $secret = trim($_POST['secret']);

        if ( trim($key) == '' || trim($secret) == '' ) {

            $result = array(
                'status' => 'error',
                'message' => __('Please enter your Zoom API Key and API Secret', 'woocommerce-events')
            );

            echo json_encode($result);

            exit();

        }

        $jwt = $this->fooevents_zoom_generate_jwt($key, $secret);
        
        $result = $this->fooevents_zoom_request('accounts/me/settings', array(), $jwt);

        echo json_encode($result);

        exit();

    }

    /**
     * Fetches all users on the account to allow selecting of specific users' meetings to display
     */
    public function fooevents_zoom_fetch_users() {

        $key = trim($_POST['key']);
        $secret = trim($_POST['secret']);

        if ( trim($key) == '' || trim($secret) == '' ) {

            $result = array(
                'status' => 'error',
                'message' => __('Please enter your Zoom API Key and API Secret', 'woocommerce-events')
            );

            echo json_encode($result);

            exit();

        }

        $jwt = $this->fooevents_zoom_generate_jwt($key, $secret);

        $zoomUsers = array();
        $loadedAllUsers = false;
        $page = 1;
        $userCount = 0;

        while ( !$loadedAllUsers ) {

            $response = $this->fooevents_zoom_request('users', array(
                'page_size' => 300,
                'page_number' => $page,
                'status' => 'active'
            ), $jwt);

            if ( $response['status'] == 'success' ) {

                $users = array();

                foreach ( $response['data']['users'] as $user ) {

                    $userCount++;

                    if ( $user['type'] == 1 ) {
                        continue;
                    }

                    $users[$user['id']] = array(
                        'id' => $user['id'],
                        'first_name' => ucwords($user['first_name']),
                        'last_name' => ucwords($user['last_name']),
                        'email' => $user['email']
                    );

                }
                
                if ( empty($zoomUsers) ) {

                    $zoomUsers = $response;

                    $zoomUsers['data']['users'] = $users;

                } else {

                    $zoomUsers['data']['users'] = array_merge($zoomUsers['data']['users'], $users);

                }

                if ( $zoomUsers['data']['total_records'] > $userCount ) {

                    $page++;

                } else {

                    $loadedAllUsers = true;

                }

            } else {

                echo json_encode($response);

                exit();

            }

        }

        uasort($zoomUsers['data']['users'], function($a, $b) {
            return $a['first_name'] <=> $b['first_name'];
        });

        echo json_encode($zoomUsers);

        exit();

    }

    /**
     * Fetch available Zoom meetings
     * 
     * @return array
     */
    public function fooevents_fetch_zoom_meetings() {

        return $this->fooevents_fetch_zoom('meetings');

    }

    /**
     * Fetch available Zoom webinars
     * 
     * @return array
     */
    public function fooevents_fetch_zoom_webinars() {

        return $this->fooevents_fetch_zoom('webinars');

    }
    
    /**
     * Fetch available Zoom meetings/webinars
     * 
     * @return array
     */
    public function fooevents_fetch_zoom($endpoint = 'webinars') {

        $zoomMeetings = array();

        $date_format = get_option('date_format');
        $time_format = get_option('time_format');
        $globalWooCommerceEventsZoomUsers = json_decode(get_option('globalWooCommerceEventsZoomUsers', json_encode(array())), true);
        $globalWooCommerceEventsZoomSelectedUserOption = get_option('globalWooCommerceEventsZoomSelectedUserOption');
        $globalWooCommerceEventsZoomSelectedUsers = get_option('globalWooCommerceEventsZoomSelectedUsers');

        if ( empty($globalWooCommerceEventsZoomSelectedUserOption) || (!empty($globalWooCommerceEventsZoomSelectedUserOption) && $globalWooCommerceEventsZoomSelectedUserOption == 'me') || (!empty($globalWooCommerceEventsZoomSelectedUserOption) && $globalWooCommerceEventsZoomSelectedUserOption == 'select' && empty($globalWooCommerceEventsZoomSelectedUsers)) ) {

            $globalWooCommerceEventsZoomSelectedUsers = array('me');

        }

        foreach ( $globalWooCommerceEventsZoomSelectedUsers as $userID ) {

            $user = array(
                'id' => 'me',
                'first_name' => '',
                'last_name' => '',
                'email' => 'me'
            );

            if ( $userID != 'me' ) {

                $user = $globalWooCommerceEventsZoomUsers[$userID];

            }

            $loadedAllMeetings = false;
            $page = 1;

            while ( !$loadedAllMeetings ) {
                $response = $this->fooevents_zoom_request('users/' . $user['email'] . '/' . $endpoint, array(
                    'page_size' => 300,
                    'page_number' => $page
                ));
    
                if ( $response['status'] == 'success' ) {

                    $meetings = array();

                    foreach ( $response['data'][$endpoint] as &$zoomMeeting ) {

                        if ( $zoomMeeting['type'] == 1 ) {
                            continue;
                        }
                        
                        $zoomMeeting['id'] = $zoomMeeting['id'] . '_' . $endpoint;
    
                        if ( $zoomMeeting['type'] != 3 && $zoomMeeting['type'] != 6 ) {

                            $start_timestamp = strtotime($zoomMeeting['start_time']);
        
                            $start_date = new DateTime("@".$start_timestamp);
                            $start_date_timezone = new DateTimeZone($zoomMeeting['timezone']);
                            $start_date->setTimezone($start_date_timezone);
            
                            $zoomMeeting['start_date_display'] = $start_date->format($date_format);
                            $zoomMeeting['start_time_display'] = $start_date->format($time_format . ' T');

                        }

                        $zoomMeeting['host'] = $user;

                        $meetings[] = $zoomMeeting;
    
                    }
    
                    if ( empty($zoomMeetings) ) {
                        $zoomMeetings = $response;

                        $zoomMeetings['data'][$endpoint] = $meetings;
                    } else {
                        $zoomMeetings['data'][$endpoint] = array_merge($zoomMeetings['data'][$endpoint], $meetings);
                    }
    
                    if ( $zoomMeetings['data']['total_records'] > count($zoomMeetings['data'][$endpoint]) ) {
                        $page++;
                    } else {
                        $loadedAllMeetings = true;
                    }
                } else {
                    return $response;
                }
            }

        }

        $zoomMeetings['user_count'] = count($globalWooCommerceEventsZoomSelectedUsers);

        return $zoomMeetings;

    }

    /**
     * Fetch individual Zoom meeting AJAX call
     * 
     */
    public function fooevents_fetch_zoom_meeting() {

        $zoomMeetingID = $_POST['zoomMeetingID'];

        $result = $this->do_fooevents_fetch_zoom_meeting($zoomMeetingID);

        echo json_encode($result);

        exit();

    }

    /**
     * Fetch individual Zoom meeting
     * 
     */
    public function do_fooevents_fetch_zoom_meeting($zoomMeetingID) {

        $zoomIDParts = explode("_", $zoomMeetingID);
        $zoomID = $zoomIDParts[0];
        $endpoint = !empty($zoomIDParts[1]) ? $zoomIDParts[1] : 'webinars';

        $result = $this->fooevents_zoom_request($endpoint . '/' . $zoomID);
        
        if ( $result['status'] == 'success' && !empty($result['data']) ) {

            $zoomMeeting = &$result['data'];

            $start_timestamp = 0;

            if ( $zoomMeeting['type'] != 5 && $zoomMeeting['type'] != 2 ) {

                // Recurrence type
                switch ( $zoomMeeting['recurrence']['type'] ) {
                    case 1:
                        
                        $zoomMeeting['recurrence']['type_display'] = $zoomMeeting['recurrence']['repeat_interval'] == 1 ? __('Daily', 'woocommerce-events') : __('Every', 'woocommerce-events') . ' ' . $zoomMeeting['recurrence']['repeat_interval'] . ' ' . __('days', 'woocommerce-events');
                        break;

                    case 2:
                        
                        $zoomMeeting['recurrence']['type_display'] = $zoomMeeting['recurrence']['repeat_interval'] == 1 ? __('Weekly', 'woocommerce-events') : __('Every', 'woocommerce-events') . ' ' . $zoomMeeting['recurrence']['repeat_interval'] . ' ' . __('weeks', 'woocommerce-events');
                        break;

                    case 3:

                        $zoomMeeting['recurrence']['type_display'] = $zoomMeeting['recurrence']['repeat_interval'] == 1 ? __('Monthly', 'woocommerce-events') : __('Every', 'woocommerce-events') . ' ' . $zoomMeeting['recurrence']['repeat_interval'] . ' ' . __('months', 'woocommerce-events');
                        break;

                }

                // Weekly days
                if ( isset($zoomMeeting['recurrence']['weekly_days']) ) {

                    $weekly_days = explode(',', $zoomMeeting['recurrence']['weekly_days']);

                    $zoomMeeting['recurrence']['type_display'] .= __(' on ', 'woocommerce-events');

                    $last_weekly_day = end($weekly_days);

                    foreach ( $weekly_days as $weekly_day ) {
                        
                        switch ( $weekly_day ) {
                            case 1:

                                $zoomMeeting['recurrence']['type_display'] .= __('Sunday', 'woocommerce-events');
                                break;

                            case 2:
                                
                                $zoomMeeting['recurrence']['type_display'] .= __('Monday', 'woocommerce-events');
                                break;

                            case 3:
                                
                                $zoomMeeting['recurrence']['type_display'] .= __('Tuesday', 'woocommerce-events');
                                break;

                            case 4:
                                
                                    $zoomMeeting['recurrence']['type_display'] .= __('Wednesday', 'woocommerce-events');
                                    break;

                            case 5:
                                    
                                    $zoomMeeting['recurrence']['type_display'] .= __('Thursday', 'woocommerce-events');
                                    break;

                            case 6:

                                $zoomMeeting['recurrence']['type_display'] .= __('Friday', 'woocommerce-events');
                                break;

                            case 7:
                                
                                $zoomMeeting['recurrence']['type_display'] .= __('Saturday', 'woocommerce-events');
                                break;

                        }

                        if ( $weekly_day != $last_weekly_day ) {

                            $zoomMeeting['recurrence']['type_display'] .= ', ';

                        }

                    }

                }

                // Monthly day
                if ( isset($zoomMeeting['recurrence']['monthly_day']) ) {

                    $zoomMeeting['recurrence']['type_display'] .= __(' on the ', 'woocommerce-events');
                    $zoomMeeting['recurrence']['type_display'] .= $zoomMeeting['recurrence']['monthly_day'];
                    $zoomMeeting['recurrence']['type_display'] .= __(' of the month', 'woocommerce-events');

                }

                // Monthly week
                if ( isset($zoomMeeting['recurrence']['monthly_week']) ) {

                    switch ( $zoomMeeting['recurrence']['monthly_week'] ) {
                        case -1:
                            
                            $zoomMeeting['recurrence']['type_display'] .= ' ' . __('on the last', 'woocommerce-events');
                            break;

                        case 1:
                            
                            $zoomMeeting['recurrence']['type_display'] .= ' ' . __('on the first', 'woocommerce-events');
                            break;

                        case 2:
                            
                            $zoomMeeting['recurrence']['type_display'] .= ' ' . __('on the second', 'woocommerce-events');
                            break;

                        case 3:
                            
                            $zoomMeeting['recurrence']['type_display'] .= ' ' . __('on the third', 'woocommerce-events');
                            break;

                        case 4:
                            
                            $zoomMeeting['recurrence']['type_display'] .= ' ' . __('on the fourth', 'woocommerce-events');
                            break;

                    }

                    // Monthly week day
                    if ( isset($zoomMeeting['recurrence']['monthly_week_day']) ) {

                        switch ( $zoomMeeting['recurrence']['monthly_week_day'] ) {
                            case 1:
                                
                                $zoomMeeting['recurrence']['type_display'] .= ' ' . __('Sunday', 'woocommerce-events');
                                break;

                            case 2:
                                
                                $zoomMeeting['recurrence']['type_display'] .= ' ' . __('Monday', 'woocommerce-events');
                                break;

                            case 3:
                                
                                $zoomMeeting['recurrence']['type_display'] .= ' ' . __('Tuesday', 'woocommerce-events');
                                break;

                            case 4:
                                
                                $zoomMeeting['recurrence']['type_display'] .= ' ' . __('Wednesday', 'woocommerce-events');
                                break;

                            case 5:
                                
                                $zoomMeeting['recurrence']['type_display'] .= ' ' . __('Thursday', 'woocommerce-events');
                                break;

                            case 6:
                                
                                $zoomMeeting['recurrence']['type_display'] .= ' ' . __('Friday', 'woocommerce-events');
                                break;

                            case 7:
                                
                                $zoomMeeting['recurrence']['type_display'] .= ' ' . __('Saturday', 'woocommerce-events');
                                break;

                        }

                    }

                }

                if ( !empty($zoomMeeting['recurrence']['end_date_time']) ) {

                    $end_timestamp = strtotime($zoomMeeting['recurrence']['end_date_time']);

                    $end_date = new DateTime("@".$end_timestamp);
                    $end_date_timezone = new DateTimeZone($zoomMeeting['timezone']);
                    $end_date->setTimezone($end_date_timezone);

                    $zoomMeeting['recurrence']['type_display'] .= ' ' . __('until', 'woocommerce-events') . ' ' . $end_date->format(get_option('date_format'));

                } elseif ( !empty($zoomMeeting['recurrence']['end_times']) ) {

                    $occurrences = (int)$zoomMeeting['recurrence']['end_times'];

                    $zoomMeeting['recurrence']['type_display'] .= ', ' . $occurrences . ' ' . ($occurrences == 1 ? __('occurrence', 'woocommerce-events') : __('occurrences', 'woocommerce-events'));

                }

                if ( !empty($zoomMeeting['occurrences']) ) {

                    foreach ( $zoomMeeting['occurrences'] as &$occurrence ) {

                        $start_timestamp = strtotime($occurrence['start_time']);
                        $start_date = new DateTime("@".$start_timestamp);
                        $start_date_timezone = new DateTimeZone($zoomMeeting['timezone']);
                        $start_date->setTimezone($start_date_timezone);

                        $occurrence['start_date_display'] = $start_date->format(get_option('date_format'));
                        $occurrence['start_time_display'] = $start_date->format(get_option('time_format') . ' T');

                        $end_timestamp = $start_timestamp + ((int)$occurrence['duration'] * 60);
                        $end_date = new DateTime("@".$end_timestamp);
                        $end_date_timezone = new DateTimeZone($zoomMeeting['timezone']);
                        $end_date->setTimezone($end_date_timezone);

                        $occurrence['end_time_display'] = $end_date->format(get_option('time_format') . ' T');

                        $occurrence['duration_display'] = $this->fooevents_format_minutes((int)$occurrence['duration']);

                    }

                    $zoomMeeting['start_date_display'] = $zoomMeeting['occurrences'][0]['start_date_display'];
                    $zoomMeeting['start_time_display'] = $zoomMeeting['occurrences'][0]['start_time_display'];
                    $zoomMeeting['end_time_display'] = $zoomMeeting['occurrences'][0]['end_time_display'];
                    $zoomMeeting['duration_display'] = $zoomMeeting['occurrences'][0]['duration_display'];

                }

            } else {

                if ( $zoomMeeting['type'] != 3 && $zoomMeeting['type'] != 6 ) {

                    $start_timestamp = strtotime($zoomMeeting['start_time']);
                    $start_date = new DateTime("@".$start_timestamp);
                    $start_date_timezone = new DateTimeZone($zoomMeeting['timezone']);
                    $start_date->setTimezone($start_date_timezone);

                    $zoomMeeting['start_date_display'] = $start_date->format(get_option('date_format'));
                    $zoomMeeting['start_time_display'] = $start_date->format(get_option('time_format') . ' T');

                    $end_timestamp = $start_timestamp + ((int)$zoomMeeting['duration'] * 60);
                    $end_date = new DateTime("@".$end_timestamp);
                    $end_date_timezone = new DateTimeZone($zoomMeeting['timezone']);
                    $end_date->setTimezone($end_date_timezone);

                    $zoomMeeting['end_time_display'] = $end_date->format(get_option('time_format') . ' T');

                    $zoomMeeting['duration_display'] = $this->fooevents_format_minutes((int)$zoomMeeting['duration']);

                }

            }

            $zoomMeeting['meeting_capacity'] = (!empty($zoomMeeting['settings']['registrants_restrict_number']) && (int)$zoomMeeting['settings']['registrants_restrict_number'] > 0) ? $zoomMeeting['settings']['registrants_restrict_number'] : $this->fooevents_zoom_user_meeting_capacity($zoomMeeting['host_id'], in_array((int)$zoomMeeting['type'], array(2, 3, 8)));
            
            $registrants = $this->fooevents_get_zoom_meeting_registrants($zoomMeetingID);

            $zoomMeeting['registrants'] = array(
                'total_records' => 0,
                'registrants' => array()
            );

            if ( $registrants['status'] == 'success' ) {

                $zoomMeeting['registrants'] = $registrants['data'];

            }
            
        }

        return $result;

    }

    /**
     * Format number of minutes into a presentable string of hours and minutes
     * 
     * @param int $minutes
     * 
     * @return string
     */
    private function fooevents_format_minutes($minutes) {

        $formatted_minutes = '';

        if ( $minutes >= 60 ) {

            $hours = floor($minutes / 60);
            $remaining_minutes = $minutes % 60;

            $formatted_minutes = $hours . ' ' . ($hours == 1 ? __('hour', 'woocommerce-events') : __('hours', 'woocommerce-events')) . ($remaining_minutes > 0 ? ' ' . $remaining_minutes . ' ' . __('minutes', 'woocommerce-events') : '');

        } else {

            $formatted_minutes = $minutes . ' ' . __('minutes', 'woocommerce-events');

        }

        return $formatted_minutes;

    }

    /**
     * Register attendee for a Zoom meeting
     * 
     * @param int $zoomMeetingID
     * @param array $args
     * 
     * @return array
     */
    public function fooevents_register_zoom_attendee($zoomMeetingID, $args) {

        $zoomIDParts = explode("_", $zoomMeetingID);
        $zoomID = $zoomIDParts[0];
        $endpoint = !empty($zoomIDParts[1]) ? $zoomIDParts[1] : 'webinars';

        $result = $this->fooevents_zoom_request($endpoint . '/' . $zoomID . '/registrants', $args, '', 'POST');

        return $result;
    }

    /**
     * Updates attendee registration statuses for a Zoom meeting
     * 
     * @param int $zoomMeetingID
     * @param array $args
     * 
     * @return array
     */
    public function fooevents_update_zoom_registration_statuses($zoomMeetingID, $args) {

        $zoomIDParts = explode("_", $zoomMeetingID);
        $zoomID = $zoomIDParts[0];
        $endpoint = !empty($zoomIDParts[1]) ? $zoomIDParts[1] : 'webinars';

        $result = $this->fooevents_zoom_request($endpoint . '/' . $zoomID . '/registrants/status', $args, '', 'PUT');

        return $result;

    }

    /**
     * Update Zoom registration and approval type
     * 
     */
    public function fooevents_update_zoom_registration() {

        $zoomMeetingID = $_POST['zoomMeetingID'];
        $recurringMeeting = (bool)$_POST['recurringMeeting'];

        $args = array(
            'settings' => array(
                'approval_type' => 0
            )
        );

        if ( $recurringMeeting ) {

            $args['settings']['registration_type'] = 1;

        }

        $zoomIDParts = explode("_", $zoomMeetingID);
        $zoomID = $zoomIDParts[0];
        $endpoint = !empty($zoomIDParts[1]) ? $zoomIDParts[1] : 'webinars';

        $result = $this->fooevents_zoom_request($endpoint . '/' . $zoomID, $args, '', 'PATCH');

        echo json_encode($result);

        exit();

    }

    /**
     * Fetch the Zoom user's maximum meeting capacity
     * 
     * @return int
     */
    public function fooevents_zoom_user_meeting_capacity($host_id = 'me', $is_meeting = true) {

        $capacity = 0;
        $result = $this->fooevents_zoom_request('users/' . $host_id . '/settings');

        if ( $result['status'] === 'success' ) {

            $capacity_key = $is_meeting ? 'meeting_capacity' : 'webinar_capacity';
            $capacity = $result['data']['feature'][$capacity_key];

        }

        return $capacity;

    }

    /**
     * Get the Zoom meeting registrants
     * 
     * @param int $zoomMeetingID
     * 
     * @return array
     */
    private function fooevents_get_zoom_meeting_registrants($zoomMeetingID) {

        $zoomIDParts = explode("_", $zoomMeetingID);
        $zoomID = $zoomIDParts[0];
        $endpoint = !empty($zoomIDParts[1]) ? $zoomIDParts[1] : 'webinars';

        $loadedAllRegistrants = false;
        $page = 1;

        $meetingRegistrants = array();

        while ( !$loadedAllRegistrants ) {
            
            $response = $this->fooevents_zoom_request($endpoint . '/' . $zoomID . '/registrants', array(
                'page_size' => 300,
                'page_number' => $page
            ));

            if ( $response['status'] == 'success' ) {

                if ( empty($meetingRegistrants) ) {

                    $meetingRegistrants = $response;

                } else {

                    $meetingRegistrants['data']['registrants'] = array_merge($meetingRegistrants['data']['registrants'], $response['data']['registrants']);

                }

                if ( $meetingRegistrants['data']['total_records'] > count($meetingRegistrants['data']['registrants']) ) {

                    $page++;

                } else {

                    $loadedAllRegistrants = true;

                }

            } else {

                return $response;

            }
        }

        return $meetingRegistrants;

    }

    /**
     * Adds attendees as registrants or updates registrant statuses for the Zoom meeting
     * 
     * @param int $order_id
     */
    public function add_update_zoom_registrants($order_id) {
        
        $tickets_query = new WP_Query( array('post_type' => array('event_magic_tickets'), 'posts_per_page' => -1, 'meta_query' => array( array( 'key' => 'WooCommerceEventsOrderID', 'value' => $order_id ) )) );
        $orderTickets = $tickets_query->get_posts();

        foreach ( $orderTickets as $ticket ) {

            $ticket_meta = get_post_meta($ticket->ID);

            $args = array(
                'email' => $ticket_meta['WooCommerceEventsAttendeeEmail'][0],
                'first_name' => $ticket_meta['WooCommerceEventsAttendeeName'][0],
                'last_name' => $ticket_meta['WooCommerceEventsAttendeeLastName'][0],
                'phone' => $ticket_meta['WooCommerceEventsAttendeeTelephone'][0],
                'org' => $ticket_meta['WooCommerceEventsAttendeeCompany'][0],
                'job_title' => $ticket_meta['WooCommerceEventsAttendeeDesignation'][0]
            );

            $WooCommerceEventsProductID = $ticket_meta['WooCommerceEventsProductID'][0];
            $WooCommerceEventsZoomMultiOption = get_post_meta($WooCommerceEventsProductID, 'WooCommerceEventsZoomMultiOption', true);
            
            if ( empty($WooCommerceEventsZoomMultiOption) || $WooCommerceEventsZoomMultiOption == 'single' ) {

                // Single meeting
                $zoomMeetingID = get_post_meta($WooCommerceEventsProductID, 'WooCommerceEventsZoomWebinar', true);

                $this->add_update_single_zoom_registrant($zoomMeetingID, $args);

            } else {

                // Multiple meetings
                $WooCommerceEventsZoomWebinarMulti = get_post_meta($WooCommerceEventsProductID, 'WooCommerceEventsZoomWebinarMulti', true);

                foreach ( $WooCommerceEventsZoomWebinarMulti as $zoomMeetingID ) {

                    $this->add_update_single_zoom_registrant($zoomMeetingID, $args);

                }

            }

        }

    }

    /**
     * Adds a single attendee as registrant or updates registrant status for the provided Zoom meeting
     * 
     * @param int $zoomMeetingID
     * @param array $args
     */
    private function add_update_single_zoom_registrant($zoomMeetingID, $args) {

        if ( $zoomMeetingID != '' ) {

            if ( $args['email'] != '' && $args['first_name'] != '' && $args['last_name'] != '' ) {

                $result = $this->fooevents_register_zoom_attendee($zoomMeetingID, $args);

                if ( $result['status'] == 'error' ) {

                    // Possibly already exists, try updating to approved
                    $update_args = array(
                        'action' => 'approve',
                        'registrants' => array(
                            array('email' => $args['email'])
                        )
                    );

                    $result = $this->fooevents_update_zoom_registration_statuses($zoomMeetingID, $update_args);

                }

            }

        }

    }

    /**
     * Cancel registrations for all provided tickets
     * 
     * @param array $tickets
     */
    public function cancel_zoom_registrations($tickets) {

        $zoomRegistrants = array();

        foreach ( $tickets as $ticket ) {

            $WooCommerceEventsProductID = get_post_meta($ticket->ID, 'WooCommerceEventsProductID', true);
            $WooCommerceEventsZoomMultiOption = get_post_meta($WooCommerceEventsProductID, 'WooCommerceEventsZoomMultiOption', true);
            $WooCommerceEventsStatus = get_post_meta($ticket->ID, 'WooCommerceEventsStatus', true);

            if ( empty($WooCommerceEventsZoomMultiOption) || $WooCommerceEventsZoomMultiOption == 'single' ) {

                $zoomMeetingID = get_post_meta($WooCommerceEventsProductID, 'WooCommerceEventsZoomWebinar', true);
                
                if ( $zoomMeetingID != '' && !empty($WooCommerceEventsStatus) && $WooCommerceEventsStatus == 'Canceled' ) {

                    if ( empty($zoomRegistrants[(string)$zoomMeetingID]) ) {

                        $zoomRegistrants[(string)$zoomMeetingID] = array();

                    }
    
                    $email = get_post_meta($ticket->ID, 'WooCommerceEventsAttendeeEmail', true);
    
                    if ( $email == '' ) {

                        $email = get_post_meta($ticket->ID, 'WooCommerceEventsPurchaserEmail', true);

                    }
    
                    $zoomRegistrants[(string)$zoomMeetingID][] = array('email' => $email);

                }

            } else {

                $WooCommerceEventsZoomWebinarMulti = get_post_meta($WooCommerceEventsProductID, 'WooCommerceEventsZoomWebinarMulti', true);
                $WooCommerceEventsMultidayStatus = json_decode(get_post_meta($ticket->ID, "WooCommerceEventsMultidayStatus", true), true);

                for ( $i = 1; $i <= count($WooCommerceEventsZoomWebinarMulti); $i++ ) {

                    $zoomMeetingID = $WooCommerceEventsZoomWebinarMulti[$i - 1];

                    if ( $zoomMeetingID != '' && ($WooCommerceEventsStatus == 'Canceled' || empty($WooCommerceEventsMultidayStatus) || (!empty($WooCommerceEventsMultidayStatus) && $WooCommerceEventsMultidayStatus[(string)$i] == 'Canceled')) ) {

                        if ( empty($zoomRegistrants[(string)$zoomMeetingID]) ) {

                            $zoomRegistrants[(string)$zoomMeetingID] = array();

                        }
        
                        $email = get_post_meta($ticket->ID, 'WooCommerceEventsAttendeeEmail', true);
        
                        if ( $email == '' ) {

                            $email = get_post_meta($ticket->ID, 'WooCommerceEventsPurchaserEmail', true);

                        }
        
                        $zoomRegistrants[(string)$zoomMeetingID][] = array('email' => $email);

                    }

                }

            }

        }

        if ( !empty($zoomRegistrants) ) {

            foreach ( $zoomRegistrants as $zoomMeetingID => $registrants ) {

                $args = array(
                    'action' => 'cancel',
                    'registrants' => $registrants
                );
    
                $result = $this->fooevents_update_zoom_registration_statuses($zoomMeetingID, $args);

            }

        }

    }

    /**
     * Register an attendee for a meeting when manually saving a ticket
     * 
     * @param int $ticket_ID
     */
    public function register_ticket_attendee($ticket_ID) {

        $ticket_meta = get_post_meta($ticket_ID);

        $args = array(
            'email' => !empty($ticket_meta['WooCommerceEventsAttendeeEmail'][0]) ? $ticket_meta['WooCommerceEventsAttendeeEmail'][0] : $ticket_meta['WooCommerceEventsPurchaserEmail'][0],
            'first_name' => !empty($ticket_meta['WooCommerceEventsAttendeeName'][0]) ? $ticket_meta['WooCommerceEventsAttendeeName'][0] : $ticket_meta['WooCommerceEventsPurchaserFirstName'][0],
            'last_name' => !empty($ticket_meta['WooCommerceEventsAttendeeLastName'][0]) ? $ticket_meta['WooCommerceEventsAttendeeLastName'][0] : $ticket_meta['WooCommerceEventsPurchaserLastName'][0]
        );

        $WooCommerceEventsZoomMultiOption = get_post_meta($ticket_meta['WooCommerceEventsProductID'][0], 'WooCommerceEventsZoomMultiOption', true);

        if ( empty($WooCommerceEventsZoomMultiOption) || $WooCommerceEventsZoomMultiOption == 'single' ) {

            if ( $ticket_meta['WooCommerceEventsStatus'][0] != 'Canceled' ) {

                $zoomMeetingID = get_post_meta($ticket_meta['WooCommerceEventsProductID'][0], 'WooCommerceEventsZoomWebinar', true);

                if ( $zoomMeetingID != '' ) {

                    $this->add_update_single_zoom_registrant($zoomMeetingID, $args);

                }

            }

        } else {

            $WooCommerceEventsZoomWebinarMulti = get_post_meta($ticket_meta['WooCommerceEventsProductID'][0], 'WooCommerceEventsZoomWebinarMulti', true);
            $WooCommerceEventsMultidayStatus = json_decode($ticket_meta['WooCommerceEventsMultidayStatus'][0], true);

            for ( $i = 1; $i <= count($WooCommerceEventsZoomWebinarMulti); $i++ ) {

                $zoomMeetingID = $WooCommerceEventsZoomWebinarMulti[$i - 1];

                if ( $zoomMeetingID != '' ) {

                    if ( empty($WooCommerceEventsMultidayStatus) || (!empty($WooCommerceEventsMultidayStatus) && $WooCommerceEventsMultidayStatus[(string)$i] != 'Canceled') ) {

                        $this->add_update_single_zoom_registrant($zoomMeetingID, $args);
                        
                    }

                }

            }

        }

    }

    /**
     * Generate text to display on the attendee's ticket
     * 
     * @param int $WooCommerceEventsProductID
     * 
     * @return string
     */
    public function get_ticket_text($WooCommerceEventsProductID, $display = '', $registrant_email = '') {

        $zoomTicketText = '';

        $WooCommerceEventsZoomMultiOption = get_post_meta($WooCommerceEventsProductID, 'WooCommerceEventsZoomMultiOption', true);

        if ( empty($WooCommerceEventsZoomMultiOption) || $WooCommerceEventsZoomMultiOption == 'single' ) {

            $zoomMeetingID = get_post_meta($WooCommerceEventsProductID, 'WooCommerceEventsZoomWebinar', true);

            if ( $zoomMeetingID != '' ) {

                $result = $this->do_fooevents_fetch_zoom_meeting($zoomMeetingID);

                if ( !empty($result['status']) && $result['status'] == 'success' ) {

                    $zoomMeeting = $result['data'];
                    $isMeeting = in_array((int)$zoomMeeting['type'], array(2, 3, 8));

                    if ( $display != 'admin' ) {
                        $zoomTicketText .= '<br/><br/>';

                        if ( $isMeeting ) {
                            $zoomTicketText .= '<strong>' . __('Zoom Meeting', 'woocommerce-events') . '</strong><br/>';
                        } else {
                            $zoomTicketText .= '<strong>' . __('Zoom Webinar', 'woocommerce-events') . '</strong><br/>';
                        }
                    }
                    
                    $zoomTicketText .= __('Topic', 'woocommerce-events') . ': ' . $zoomMeeting['topic'] . '<br/>';
                    $zoomTicketText .= (($zoomMeeting['type'] == 5 || $zoomMeeting['type'] == 2) ? __('Date', 'woocommerce-events') : __('Start date', 'woocommerce-events')) . ': ' . $zoomMeeting['start_date_display'] . '<br/>';
                    $zoomTicketText .= __('Start time', 'woocommerce-events') . ': ' . $zoomMeeting['start_time_display'] . '<br/>';
                    $zoomTicketText .= __('End time', 'woocommerce-events') . ': ' . $zoomMeeting['end_time_display'] . '<br/>';
                    $zoomTicketText .= __('Duration', 'woocommerce-events') . ': ' . $zoomMeeting['duration_display'] . '<br/>';

                    if ( $zoomMeeting['type'] != 5 && $zoomMeeting['type'] != 2 ) {

                        $zoomTicketText .= __('Recurrence', 'woocommerce-events') . ': ' . $zoomMeeting['recurrence']['type_display'] . '<br/>';

                    }

                    if ( $isMeeting ) {
                        $zoomTicketText .= __('Meeting ID', 'woocommerce-events') . ': ' . $this->format_zoom_id($zoomMeeting['id']) . '<br/>';
                        
                        if ( !empty($zoomMeeting['password']) ) {
                            $zoomTicketText .= __('Meeting password', 'woocommerce-events') . ': ' . $zoomMeeting['password'] . '<br/>';
                        }
                    } else {
                        $zoomTicketText .= __('Webinar ID', 'woocommerce-events') . ': ' . $this->format_zoom_id($zoomMeeting['id']) . '<br/>';

                        if ( !empty($zoomMeeting['password']) ) {
                            $zoomTicketText .= __('Webinar password', 'woocommerce-events') . ': ' . $zoomMeeting['password'] . '<br/>';
                        }
                    }
                    
                    $join_url = $zoomMeeting['join_url'];

                    if ( $registrant_email != '' ) {
                        
                        foreach ( $zoomMeeting['registrants']['registrants'] as $registrant ) {

                            if ( $registrant['email'] == $registrant_email ) {

                                $join_url = $registrant['join_url'];

                                break;

                            }

                        }

                    }

                    if ( $display == 'calendar' ) {

                        $zoomTicketText .= __('Join link', 'woocommerce-events') . ':<br/><a href="' . $join_url . '">' . $join_url . '</a><br/>';
                    
                    } elseif ( $display != 'admin' ) {

                        $zoomTicketText .= '<a href="' . $join_url . '">' . ($isMeeting ? __('Join this meeting', 'woocommerce-events') : __('Join this webinar', 'woocommerce-events')) . '</a><br/>';

                    }

                }

            }

        } else {

            $dayTerm = get_post_meta($WooCommerceEventsProductID, 'WooCommerceEventsDayOverride', true);

            if(empty($dayTerm)) {

                $dayTerm = get_option('WooCommerceEventsDayOverride', true);

            }

            if(empty($dayTerm) || $dayTerm == 1) {

                $dayTerm = __('Day', 'woocommerce-events');

            }

            $WooCommerceEventsZoomWebinarMulti = get_post_meta($WooCommerceEventsProductID, 'WooCommerceEventsZoomWebinarMulti', true);

            if ( !empty($WooCommerceEventsZoomWebinarMulti) ) {

                if ( $display != 'admin' ) {
                    $zoomTicketText .= '<br/><br/>';
                    $zoomTicketText .= '<strong>' . __('Zoom Meetings and Webinars', 'woocommerce-events') . '</strong>';
                    $zoomTicketText .= '<br/>';
                }

                for ( $i = 1; $i <= count($WooCommerceEventsZoomWebinarMulti); $i++ ) {

                    $zoomMeetingID = $WooCommerceEventsZoomWebinarMulti[$i - 1];

                    if ( $zoomMeetingID != '' ) {

                        $result = $this->do_fooevents_fetch_zoom_meeting($zoomMeetingID);

                        if ( !empty($result['status']) && $result['status'] == 'success' ) {

                            $zoomMeeting = $result['data'];

                            $isMeeting = in_array((int)$zoomMeeting['type'], array(2, 3, 8));
        
                            if ( $i > 1 ) {
                                $zoomTicketText .= '<br/>';
                            }

                            $zoomTicketText .= '<strong>' . $dayTerm . ' ' . $i . ':</strong><br/>';
                            $zoomTicketText .= __('Topic', 'woocommerce-events') . ': ' . $zoomMeeting['topic'] . '<br/>';
                            
                            $zoomTicketText .= !empty($zoomMeeting['start_date_display']) ? (($zoomMeeting['type'] == 5 || $zoomMeeting['type'] == 2) ? __('Date', 'woocommerce-events') : __('Start date', 'woocommerce-events')) . ': ' . $zoomMeeting['start_date_display'] . '<br/>' : '';

                            $zoomTicketText .= !empty($zoomMeeting['start_time_display']) ? __('Start time', 'woocommerce-events') . ': ' . $zoomMeeting['start_time_display'] . '<br/>' : '';
                            $zoomTicketText .= !empty($zoomMeeting['end_time_display']) ? __('End time', 'woocommerce-events') . ': ' . $zoomMeeting['end_time_display'] . '<br/>' : '';
                            $zoomTicketText .= !empty($zoomMeeting['duration_display']) ? __('Duration', 'woocommerce-events') . ': ' . $zoomMeeting['duration_display'] . '<br/>' : '';

                            if ( $zoomMeeting['type'] != 5 && $zoomMeeting['type'] != 2 ) {

                                $zoomTicketText .= __('Recurrence', 'woocommerce-events') . ': ' . $zoomMeeting['recurrence']['type_display'] . '<br/>';

                            }
                            
                            if ( $isMeeting ) {
                                $zoomTicketText .= __('Meeting ID', 'woocommerce-events') . ': ' . $this->format_zoom_id($zoomMeeting['id']) . '<br/>';
                                
                                if ( !empty($zoomMeeting['password']) ) {
                                    $zoomTicketText .= __('Meeting password', 'woocommerce-events') . ': ' . $zoomMeeting['password'] . '<br/>';
                                }
                            } else {
                                $zoomTicketText .= __('Webinar ID', 'woocommerce-events') . ': ' . $this->format_zoom_id($zoomMeeting['id']) . '<br/>';
        
                                if ( !empty($zoomMeeting['password']) ) {
                                    $zoomTicketText .= __('Webinar password', 'woocommerce-events') . ': ' . $zoomMeeting['password'] . '<br/>';
                                }
                            }
                            
                            $join_url = $zoomMeeting['join_url'];

                            if ( $registrant_email != '' ) {
                                
                                foreach ( $zoomMeeting['registrants']['registrants'] as $registrant ) {

                                    if ( $registrant['email'] == $registrant_email ) {

                                        $join_url = $registrant['join_url'];

                                        break;

                                    }

                                }

                            }

                            if ( $display == 'calendar' ) {

                                $zoomTicketText .= __('Join link', 'woocommerce-events') . ':<br/><a href="' . $join_url . '">' . $join_url . '</a><br/>';
                            
                            } elseif ( $display != 'admin' ) {

                                $zoomTicketText .= '<a href="' . $join_url . '">' . ($isMeeting ? __('Join this meeting', 'woocommerce-events') : __('Join this webinar', 'woocommerce-events')) . '</a><br/>';

                            }

                        }
        
                    }

                }

            }

        }

        return $zoomTicketText;

    }

    /**
     * Generate text to display in the calendar event's description
     * 
     * @param int $WooCommerceEventsProductID
     * 
     * @return string
     */
    public function get_calendar_text($WooCommerceEventsProductID, $registrant_email) {

        $ticketText = $this->get_ticket_text($WooCommerceEventsProductID, 'calendar', $registrant_email);

        $ticketText = strip_tags(str_replace('<br/>', '\n', $ticketText));

        return $ticketText;

    }

    /**
     * Format Zoom meeting/webinar ID
     * 
     * @param int $zoomID
     * 
     * @return string
     */
    private function format_zoom_id($zoomID) {

        switch ( strlen($zoomID) ) {

            case 9:
            case 10:
            return substr($zoomID, 0, 3) . '-' . substr($zoomID, 3, 3) . '-' . substr($zoomID, 6);
            break;

            case 11:
            return substr($zoomID, 0, 3) . '-' . substr($zoomID, 3, 4) . '-' . substr($zoomID, 7);
            break;

        }

    }

}
