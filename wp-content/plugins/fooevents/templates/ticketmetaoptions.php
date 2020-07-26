<div id="fooevents_tickets" class="panel woocommerce_options_panel">
    <p><h2><b><?php _e('Ticket Settings', 'woocommerce-events'); ?></b></h2></p>
    <div class="options_group">
            <p class="form-field">
            <label><?php _e('HTML ticket theme:', 'woocommerce-events'); ?></label>
            <select name="WooCommerceEventsTicketTheme" id="WooCommerceEventsTicketTheme">
            <?php foreach($themes as $theme => $theme_details) :?>
                <option value="<?php echo $theme_details['path']; ?>" <?php echo ($WooCommerceEventsTicketTheme == $theme_details['path'])? 'SELECTED' : '' ?>><?php echo $theme_details['name']; ?></option>
            <?php endforeach; ?>
            </select>
            <img class="help_tip" data-tip="<?php _e('Select the ticket theme that will be used to style the embedded HTML tickets within ticket emails.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
            </p> 
    </div>
    <?php echo $pdfTicketThemes; ?>
    <div class="options_group">
                <?php $WooCommerceEventsTicketLogo = (empty($WooCommerceEventsTicketLogo))? $globalWooCommerceEventsTicketLogo : $WooCommerceEventsTicketLogo; ?>
                <p class="form-field">
                        <label><?php _e('Ticket logo:', 'woocommerce-events'); ?></label>
                        <input id="WooCommerceEventsTicketLogo" class="text uploadfield" type="text" size="40" name="WooCommerceEventsTicketLogo" value="<?php echo esc_attr($WooCommerceEventsTicketLogo); ?>" />				
                        <span class="uploadbox">
                                <input class="upload_image_button_woocommerce_events  button  " type="button" value="Upload file" />
                                <a href="#" class="upload_reset"><?php _e('Clear', 'woocommerce-events'); ?></a>
                        </span>
                        <img class="help_tip" data-tip="<?php _e('Full URL that links to the logo that will be used in the ticket (JPG or PNG format).', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
	<div class="options_group">
                <?php $WooCommerceEventsTicketHeaderImage = (empty($WooCommerceEventsTicketHeaderImage))? $globalWooCommerceEventsTicketHeaderImage : $WooCommerceEventsTicketHeaderImage; ?>
                <p class="form-field">
                        <label><?php _e('Ticket header image:', 'woocommerce-events'); ?></label>
                        <input id="WooCommerceEventsTicketHeaderImage" class="text uploadfield" type="text" size="40" name="WooCommerceEventsTicketHeaderImage" value="<?php echo esc_attr($WooCommerceEventsTicketHeaderImage); ?>" />				
                        <span class="uploadbox">
                                <input class="upload_image_button_woocommerce_events  button  " type="button" value="Upload file" />
                                <a href="#" class="upload_reset"><?php _e('Clear', 'woocommerce-events'); ?></a>
                        </span>
                        <img class="help_tip" data-tip="<?php _e('Full URL that links to the image that will be used as the ticket header (JPG or PNG format).', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Ticket email subject:', 'woocommerce-events'); ?></label>
                       <input type="text" id="WooCommerceEventsEmailSubjectSingle" name="WooCommerceEventsEmailSubjectSingle" value="<?php echo esc_attr($WooCommerceEventsEmailSubjectSingle); ?>"/>
                       <img class="help_tip" data-tip="<?php _e("The subject line used in ticket emails. Use {OrderNumber} to display the proper order number.", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
            <div style="padding-left: 30px; padding-right: 30px;">
                <p class="form-field">
                    <label><?php _e('Ticket email body:', 'woocommerce-events'); ?></label>
                    <?php wp_editor( $WooCommerceEventsTicketText, 'WooCommerceEventsTicketText' ); ?>
                    <img class="help_tip" data-tip="<?php _e("The copy that will be displayed in the main body of the ticket email.", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
            </div>
        </div>
        <div class="options_group">
                <?php $globalWooCommerceEventsTicketBackgroundColor = (empty($globalWooCommerceEventsTicketBackgroundColor))? '' : $globalWooCommerceEventsTicketBackgroundColor; ?>
                <?php $WooCommerceEventsTicketBackgroundColor = (empty($WooCommerceEventsTicketBackgroundColor))? $globalWooCommerceEventsTicketBackgroundColor : $WooCommerceEventsTicketBackgroundColor; ?>
                <p class="form-field">
                       <label><?php _e('Ticket border:', 'woocommerce-events'); ?></label>
                       <input class="woocommerce-events-color-field" type="text" name="WooCommerceEventsTicketBackgroundColor" value="<?php echo ''.esc_attr($WooCommerceEventsTicketBackgroundColor); ?>"/>
                       <img class="help_tip" data-tip="<?php _e('Color of the ticket border.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <?php $globalWooCommerceEventsTicketButtonColor = (empty($globalWooCommerceEventsTicketButtonColor))? '' : $globalWooCommerceEventsTicketButtonColor; ?>
                <?php $WooCommerceEventsTicketButtonColor = (empty($WooCommerceEventsTicketButtonColor))? $globalWooCommerceEventsTicketButtonColor : $WooCommerceEventsTicketButtonColor; ?>
                <p class="form-field">
                       <label><?php _e('Ticket button:', 'woocommerce-events'); ?></label>
                       <input class="woocommerce-events-color-field" type="text" name="WooCommerceEventsTicketButtonColor" value="<?php echo ''.esc_attr($WooCommerceEventsTicketButtonColor); ?>"/>
                       <img class="help_tip" data-tip="<?php _e('Color of the ticket button.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <?php $globalWooCommerceEventsTicketTextColor = (empty($globalWooCommerceEventsTicketTextColor))? '' : $globalWooCommerceEventsTicketTextColor; ?>
                <?php $WooCommerceEventsTicketTextColor = (empty($WooCommerceEventsTicketTextColor))? $globalWooCommerceEventsTicketTextColor : $WooCommerceEventsTicketTextColor; ?>
                <p class="form-field">
                       <label><?php _e('Ticket button text:', 'woocommerce-events'); ?></label>
                       <input class="woocommerce-events-color-field" type="text" name="WooCommerceEventsTicketTextColor" value="<?php echo ''.esc_attr($WooCommerceEventsTicketTextColor); ?>"/>
                       <img class="help_tip" data-tip="<?php _e('Color of the ticket button text.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Display purchaser or attendee details on ticket?', 'woocommerce-events'); ?></label>
                       <input type="checkbox" name="WooCommerceEventsTicketPurchaserDetails" value="on" <?php echo (empty($WooCommerceEventsTicketPurchaserDetails) || $WooCommerceEventsTicketPurchaserDetails == 'on')? 'CHECKED' : ''; ?>>
                       <img class="help_tip" data-tip="<?php _e("Display the purchaser/attendee's name and details on the ticket.", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
         <?php echo $eventsIncludeCustomAttendeeFields; ?>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Display "Add to calendar" option on ticket?', 'woocommerce-events'); ?></label>
                       <input type="checkbox" id="WooCommerceEventsTicketAddCalendarMeta" name="WooCommerceEventsTicketAddCalendar" value="on" <?php echo (empty($WooCommerceEventsTicketAddCalendar) || $WooCommerceEventsTicketAddCalendar == 'on')? 'CHECKED' : ''; ?>>
                       <img class="help_tip" data-tip="<?php _e("Display an 'Add to calendar' button on the ticket which will generate an ICS file containing the event details when clicked.", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                    <label><?php _e('"Add to calendar" reminder alerts:', 'woocommerce-events'); ?></label>
                    <span id="fooevents_add_to_calendar_reminders_container">
                        <?php
                            for ( $i = 0; $i < count($WooCommerceEventsTicketAddCalendarReminders); $i++ ) {
                                $reminder = $WooCommerceEventsTicketAddCalendarReminders[$i];
                        ?>
                            <span class="fooevents-add-to-calendar-reminder-row">
                                <input type="number" min="0" step="1" name="WooCommerceEventsTicketAddCalendarReminderAmounts[]" value="<?php echo $reminder['amount']; ?>" />
                                <select name="WooCommerceEventsTicketAddCalendarReminderUnits[]">
                                    <?php
                                        $units = array('minutes', 'hours', 'days', 'weeks');

                                        foreach ( $units as $unit ) {
                                    ?>
                                        <option value="<?php echo $unit; ?>" <?php echo $reminder['unit'] == $unit ? 'SELECTED' : ''; ?>><?php _e($unit, 'woocommerce-events'); ?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                                <a href="#" class="fooevents_add_to_calendar_reminders_remove">[X]</a>
                            </span>
                        <?php
                            }
                        ?>
                    </span>
                    <a href="#" id="fooevents_add_to_calendar_reminders_new_field" class="button button-primary"><?php _e('+ New reminder', 'woocommerce-events'); ?></a>
                    <img class="help_tip" data-tip="<?php _e("Add calendar alerts at specified intervals to remind attendees about the event. These alerts will automatically appear in the attendee's calendar client after clicking the 'Add to calendar' button on the ticket.", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Attach calendar ICS file to the ticket email?', 'woocommerce-events'); ?></label>
                       <input type="checkbox" id="WooCommerceEventsTicketAttachICS" name="WooCommerceEventsTicketAttachICS" value="on" <?php echo (empty($WooCommerceEventsTicketAttachICS) || $WooCommerceEventsTicketAttachICS == 'on')? 'CHECKED' : ''; ?>>
                       <img class="help_tip" data-tip="<?php _e("Attach an ICS file to the ticket email so that the event details automatically appear in certain calendar clients.", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Display date and time on ticket?', 'woocommerce-events'); ?></label>
                       <input type="checkbox" name="WooCommerceEventsTicketDisplayDateTime" value="on" <?php echo (empty($WooCommerceEventsTicketDisplayDateTime) || $WooCommerceEventsTicketDisplayDateTime == 'on')? 'CHECKED' : ''; ?>>
                       <img class="help_tip" data-tip="<?php _e('Display the time and date of the event on the ticket.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Display barcode on ticket?', 'woocommerce-events'); ?></label>
                       <input type="checkbox" name="WooCommerceEventsTicketDisplayBarcode" value="on" <?php echo (empty($WooCommerceEventsTicketDisplayBarcode) || $WooCommerceEventsTicketDisplayBarcode == 'on')? 'CHECKED' : ''; ?>>
                       <img class="help_tip" data-tip="<?php _e('Display a barcode on the ticket which is used for check-ins.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Display price on ticket?', 'woocommerce-events'); ?></label>
                       <input type="checkbox" name="WooCommerceEventsTicketDisplayPrice" value="on" <?php echo ($WooCommerceEventsTicketDisplayPrice == 'on')? 'CHECKED' : ''; ?>>
                       <img class="help_tip" data-tip="<?php _e('Display the ticket price on the ticket.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Display Zoom meeting/webinar details on ticket?', 'woocommerce-events'); ?></label>
                       <input type="checkbox" name="WooCommerceEventsTicketDisplayZoom" value="on" <?php echo ($WooCommerceEventsTicketDisplayZoom == 'on')? 'CHECKED' : ''; ?>>
                       <img class="help_tip" data-tip="<?php _e('Display all the Zoom meeting/webinar details such as the Meeting ID and Join link on the ticket.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <?php echo $pdfTicketOptions; ?>
</div>

