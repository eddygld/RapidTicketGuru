<div id="fooevents_options" class="panel woocommerce_options_panel">
    <p><h2><b><?php _e('Event Settings', 'fooevents-custom-attendee-fields'); ?></b></h2></p>
    <div class="options_group">
            <p class="form-field">
                   <label><?php _e('Is this product an event?', 'woocommerce-events'); ?></label>
                   <select name="WooCommerceEventsEvent" id="WooCommerceEventsProductIsEvent">
                        <option value="NotEvent" <?php echo ($WooCommerceEventsEvent == 'NotEvent')? 'SELECTED' : '' ?>><?php _e('No', 'woocommerce-events'); ?></option>
                        <option value="Event" <?php echo ($WooCommerceEventsEvent == 'Event')? 'SELECTED' : '' ?>><?php _e('Yes', 'woocommerce-events'); ?></option>
                   </select>
                   <img class="help_tip" data-tip="<?php _e('This option enables event and ticketing functionality.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
            </p>
    </div>
    <div id="WooCommerceEventsForm">
        <?php echo $numDays; ?>
        <?php echo $multiDayType; ?>
        <div class="options_group" id="WooCommerceEventsDateContainer">
                <p class="form-field">
                       <label><?php _e('Start date:', 'woocommerce-events'); ?></label>
                       <input type="text" id="WooCommerceEventsDate" name="WooCommerceEventsDate" value="<?php echo esc_attr($WooCommerceEventsDate); ?>"/>
                       <img class="help_tip" data-tip="<?php _e("The date that the event is scheduled to take place. This is used as a label on your website and it's also used by the FooEvents Calendar to display the event.", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <?php echo $endDate; ?>
        <div class="options_group">
                <p class="form-field">
                        <label><?php _e('Start time:', 'woocommerce-events'); ?></label>
                        <select name="WooCommerceEventsHour" id="WooCommerceEventsHour">
                            <?php for($x=0; $x<=23; $x++) :?>
                            <?php $x = sprintf("%02d", $x); ?>
                            <option value="<?php echo $x; ?>" <?php echo ($WooCommerceEventsHour == $x) ? 'SELECTED' : ''; ?>><?php echo $x; ?></option>
                            <?php endfor; ?>
                        </select>
                        <select name="WooCommerceEventsMinutes" id="WooCommerceEventsMinutes">
                            <?php for($x=0; $x<=59; $x++) :?>
                            <?php $x = sprintf("%02d", $x); ?>
                            <option value="<?php echo $x; ?>" <?php echo ($WooCommerceEventsMinutes == $x) ? 'SELECTED' : ''; ?>><?php echo $x; ?></option>
                            <?php endfor; ?>
                        </select>
                        <select name="WooCommerceEventsPeriod" id="WooCommerceEventsPeriod" <?php echo ($WooCommerceEventsHour > 12 || $WooCommerceEventsHourEnd > 12) ? 'disabled' : ''; ?>>
                            <option value="">-</option>
                            <option value="a.m." <?php echo ($WooCommerceEventsHour <= 12 && $WooCommerceEventsHourEnd <= 12 && $WooCommerceEventsPeriod == 'a.m.') ? 'SELECTED' : ''; ?>>a.m.</option>
                            <option value="p.m." <?php echo ($WooCommerceEventsHour <= 12 && $WooCommerceEventsHourEnd <= 12 && $WooCommerceEventsPeriod == 'p.m.') ? 'SELECTED' : ''; ?>>p.m.</option>
                        </select>
                        <img class="help_tip" data-tip="<?php _e('The time that the event is scheduled to start.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                        <label><?php _e('End time:', 'woocommerce-events'); ?></label>
                        <select name="WooCommerceEventsHourEnd" id="WooCommerceEventsHourEnd">
                            <?php for($x=0; $x<=23; $x++) :?>
                            <?php $x = sprintf("%02d", $x); ?>
                            <option value="<?php echo $x; ?>" <?php echo ($WooCommerceEventsHourEnd == $x) ? 'SELECTED' : ''; ?>><?php echo $x; ?></option>
                            <?php endfor; ?>
                        </select>
                        <select name="WooCommerceEventsMinutesEnd" id="WooCommerceEventsMinutesEnd">
                            <?php for($x=0; $x<=59; $x++) :?>
                            <?php $x = sprintf("%02d", $x); ?>
                            <option value="<?php echo $x; ?>" <?php echo ($WooCommerceEventsMinutesEnd == $x) ? 'SELECTED' : ''; ?>><?php echo $x; ?></option>
                            <?php endfor; ?>
                        </select>
                        <select name="WooCommerceEventsEndPeriod" id="WooCommerceEventsEndPeriod" <?php echo ($WooCommerceEventsHour > 12 || $WooCommerceEventsHourEnd > 12) ? 'disabled' : ''; ?>>
                            <option value="">-</option>
                            <option value="a.m." <?php echo ($WooCommerceEventsHour <= 12 && $WooCommerceEventsHourEnd <= 12 && $WooCommerceEventsEndPeriod == 'a.m.') ? 'SELECTED' : ''; ?>>a.m.</option>
                            <option value="p.m." <?php echo ($WooCommerceEventsHour <= 12 && $WooCommerceEventsHourEnd <= 12 && $WooCommerceEventsEndPeriod == 'p.m.') ? 'SELECTED' : ''; ?>>p.m.</option>
                        </select>
                        <img class="help_tip" data-tip="<?php _e('The time that the event is scheduled to end.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                        <label><?php _e('Time zone:', 'woocommerce-events'); ?></label>
                        <select name="WooCommerceEventsTimeZone" id="WooCommerceEventsTimeZone">
                            <option value="" <?php if ( $WooCommerceEventsTimeZone == "" ) : ?>SELECTED<?php endif; ?>>(Not set)</option>
                        <?php
                            $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
                            foreach ( $tzlist as $tz ) {
                        ?>
                                <option value="<?php echo $tz; ?>" <?php if ( $WooCommerceEventsTimeZone == $tz ) : ?>SELECTED<?php endif; ?>><?php echo str_replace("_", " ", str_replace("/", " / ", $tz)); ?></option>
                        <?php
                            }
                        ?> 
                        </select>
                        <img class="help_tip" data-tip="<?php _e("The time zone where the event is taking place. If no time zone is set then the attendee's local time zone will be used for the 'Add to Calendar' functionality in the ticket email.", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <?php echo $eventbrite_option; ?>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Venue:', 'woocommerce-events'); ?></label>
                       <input type="text" id="WooCommerceEventsLocation" name="WooCommerceEventsLocation" value="<?php echo esc_attr($WooCommerceEventsLocation); ?>"/>
                       <img class="help_tip" data-tip="<?php _e('The venue where the event will be hosted.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('GPS coordinates:', 'woocommerce-events'); ?></label>
                       <input type="text" id="WooCommerceEventsGPS" name="WooCommerceEventsGPS" value="<?php echo esc_attr($WooCommerceEventsGPS); ?>"/>
                       <img class="help_tip" data-tip="<?php _e("GPS coordinates for the venue.", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Google Maps coordinates:', 'woocommerce-events'); ?></label>
                       <input type="text" id="WooCommerceEventsGoogleMaps" name="WooCommerceEventsGoogleMaps" value="<?php echo esc_attr($WooCommerceEventsGoogleMaps); ?>"/>
                       <img class="help_tip" data-tip="<?php _e('GPS coordinates that are used to calculate the pin position for Google Maps on the event page. A valid Google Maps API key must first be saved in FooEvents settings.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                       <?php if(empty($globalWooCommerceEventsGoogleMapsAPIKey)) :?>
                       <br /><br />
                       <?php _e('Google Maps API key not set.','woocommerce-events'); ?> <a href="admin.php?page=fooevents-settings&tab=integration"><?php _e('Please check the Event Integration settings.', 'woocommerce-events'); ?></a>
                       <?php endif; ?>
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Directions:', 'woocommerce-events'); ?></label>
                       <textarea name="WooCommerceEventsDirections" id="WooCommerceEventsDirections"><?php echo esc_attr($WooCommerceEventsDirections); ?></textarea>
                       <img class="help_tip" data-tip="<?php _e("Directions to the venue.", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Phone number:', 'woocommerce-events'); ?></label>
                       <input type="text" id="WooCommerceEventsSupportContact" name="WooCommerceEventsSupportContact" value="<?php echo esc_attr($WooCommerceEventsSupportContact); ?>"/>
                       <img class="help_tip" data-tip="<?php _e("Event organizer's contact number.", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Email address:', 'woocommerce-events'); ?></label>
                       <input type="text" id="WooCommerceEventsEmail" name="WooCommerceEventsEmail" value="<?php echo esc_attr($WooCommerceEventsEmail); ?>"/>
                       <img class="help_tip" data-tip="<?php _e("Event organizer's email address.", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        
        
        <div class="options_group">
            <div style="padding-left: 30px; padding-right: 30px;">
                <p class="form-field">
                    <label><?php _e('Thank-you page text:', 'woocommerce-events'); ?></label>
                    <?php wp_editor( $WooCommerceEventsThankYouText, 'WooCommerceEventsThankYouText' ); ?>
                    <img class="help_tip" data-tip="<?php _e("The copy that will be displayed on the thank-you page after ticket purchase.", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
            </div>
        </div>
        <div class="options_group">
            <div style="padding-left: 30px; padding-right: 30px;">
                <p class="form-field">
                    <label><?php _e('Event details tab text:', 'woocommerce-events'); ?></label>
                    <?php wp_editor( $WooCommerceEventsEventDetailsText, 'WooCommerceEventsEventDetailsText' ); ?>
                    <img class="help_tip" data-tip="<?php _e("The copy that will be displayed in the event details tab.", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
            </div>
        </div>
        <div class="options_group">
            <p class="form-field">
                <label><?php _e('Calendar background color:', 'fooevents-calendar'); ?></label>
                <input type="text" class="woocommerce-events-color-field" id="WooCommerceEventsBackgroundColor" name="WooCommerceEventsBackgroundColor" value="<?php echo esc_html($WooCommerceEventsBackgroundColor); ?>"/>
                <img class="help_tip" data-tip="<?php _e('Color of the calendar background for the event. Also changes the background color of the date icon in the FooEvents Check-ins app.', 'fooevents-calendar'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
            </p>
        </div>
        <div class="options_group">
            <p class="form-field">
                <label><?php _e('Calendar text color:', 'fooevents-calendar'); ?></label>
                <input type="text" class="woocommerce-events-color-field" id="WooCommerceEventsTextColor" name="WooCommerceEventsTextColor" value="<?php echo esc_html($WooCommerceEventsTextColor); ?>"/>
                <img class="help_tip" data-tip="<?php _e('Color of the calendar text for the event. Also changes the font color of the date icon in the FooEvents Check-ins app.', 'fooevents-calendar'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
            </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Capture attendee full name and email address? ', 'woocommerce-events'); ?></label>
                       <input type="checkbox" name="WooCommerceEventsCaptureAttendeeDetails" value="on" <?php echo ($WooCommerceEventsCaptureAttendeeDetails == 'on')? 'CHECKED' : ''; ?>>
                       <img class="help_tip" data-tip="<?php _e('This will add attendee capture fields on the checkout screen.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
                <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Capture attendee phone number?', 'woocommerce-events'); ?></label>
                       <input type="checkbox" name="WooCommerceEventsCaptureAttendeeTelephone" value="on" <?php echo ($WooCommerceEventsCaptureAttendeeTelephone == 'on')? 'CHECKED' : ''; ?>>
                       <img class="help_tip" data-tip="<?php _e('This will add a telephone number field to the attendee capture fields on the checkout screen.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Capture attendee company name?', 'woocommerce-events'); ?></label>
                       <input type="checkbox" name="WooCommerceEventsCaptureAttendeeCompany" value="on" <?php echo ($WooCommerceEventsCaptureAttendeeCompany == 'on')? 'CHECKED' : ''; ?>>
                       <img class="help_tip" data-tip="<?php _e('This will add a company field to the attendee capture fields on the checkout screen.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Capture attendee designation?', 'woocommerce-events'); ?></label>
                       <input type="checkbox" name="WooCommerceEventsCaptureAttendeeDesignation" value="on" <?php echo ($WooCommerceEventsCaptureAttendeeDesignation == 'on')? 'CHECKED' : ''; ?>>
                       <img class="help_tip" data-tip="<?php _e('This will add a designation field to the attendee capture fields on the checkout screen.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
       
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Email ticket to attendee rather than purchaser?', 'woocommerce-events'); ?></label>
                       <input type="checkbox" name="WooCommerceEventsEmailAttendee" value="on" <?php echo ($WooCommerceEventsEmailAttendee == 'on')? 'CHECKED' : ''; ?>>
                       <img class="help_tip" data-tip="<?php _e('This will email the ticket to the attendee instead of the ticket purchaser.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Email tickets?', 'woocommerce-events'); ?></label>
                       <input type="checkbox" name="WooCommerceEventsSendEmailTickets" value="on" <?php echo (empty($WooCommerceEventsSendEmailTickets) || $WooCommerceEventsSendEmailTickets == 'on')? 'CHECKED' : ''; ?>>
                       <img class="help_tip" data-tip="<?php _e('This will email the event tickets to the attendee or purchaser once the order has been completed.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <?php if ( $this->is_plugin_active( 'fooevents_seating/fooevents-seating.php' ) || is_plugin_active_for_network('fooevents_seating/fooevents-seating.php') ) : ?>
        <div class="options_group">
        <p class="form-field">
            <label><?php _e('Display "View seating chart" option on checkout page?', 'woocommerce-events'); ?></label>
                <input type="checkbox" name="WooCommerceEventsViewSeatingChart" value="on" <?php echo (empty($WooCommerceEventsViewSeatingChart) || $WooCommerceEventsViewSeatingChart == 'on')? 'CHECKED' : ''; ?>>
                <img class="help_tip" data-tip="<?php _e("This will display a 'View seating chart' link on the checkout page. Before enabling this option, please ensure that you have setup a seating chart on the Event Seating tab." , 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
            </p>
        </div>
        <?php endif; ?>
    </div>
</div>