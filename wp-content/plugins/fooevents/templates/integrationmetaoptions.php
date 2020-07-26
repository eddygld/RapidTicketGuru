<div id="fooevents_integration" class="panel woocommerce_options_panel fooevents-settings">
    <div class="options_group">
        <h2><b><?php _e('Zoom Meetings and Webinars', 'woocommerce-events'); ?></b></h2>
        <p class="form-field">
            <label><?php _e('Attendee details', 'woocommerce-events'); ?></label>
            <?php _e('Note: Meeting and webinar registration requires attendee details to be captured at checkout.','woocommerce-events'); ?>
            <br/>
            <span id="fooevents_enable_attendee_details_note">
                <span id="fooevents_capture_attendee_details_enabled" <?php if ( empty($WooCommerceEventsCaptureAttendeeDetails) || $WooCommerceEventsCaptureAttendeeDetails == 'off' ) : ?>style="display:none;"<?php endif; ?>>
                    <mark class="yes fooevents-zoom-test-access-result" style="padding:0;"><span class="dashicons dashicons-yes"></span> <?php _e('Capture attendee details is currently enabled', 'woocommerce-events'); ?></mark>
                </span>
                <span id="fooevents_capture_attendee_details_disabled" <?php if ( !empty($WooCommerceEventsCaptureAttendeeDetails) && $WooCommerceEventsCaptureAttendeeDetails == 'on' ) : ?>style="display:none;"<?php endif; ?>>
                    <mark class="error fooevents-zoom-test-access-result" style="padding:0;"><span class="dashicons dashicons-warning"></span> <?php _e('Capture attendee details is currently disabled', 'woocommerce-events'); ?></mark>
                    <br/>
                    <a href="javascript:enableCaptureAttendeeDetails();"><?php _e('Enable attendee detail capture option', 'woocommerce-events'); ?></a>
                </span>
            </span>
        </p>
        <?php if ( $this->is_plugin_active( 'fooevents_multi_day/fooevents-multi-day.php' ) || is_plugin_active_for_network('fooevents_multi_day/fooevents-multi-day.php') ) : ?>
        <p class="form-field">
            <label><?php _e('Multi-day options', 'woocommerce-events'); ?></label>
            <label class="fooevents-options-inner-label"><input type="radio" name="WooCommerceEventsZoomMultiOption" value="single" <?php echo (empty($WooCommerceEventsZoomMultiOption) || $WooCommerceEventsZoomMultiOption == 'single')? 'CHECKED' : ''; ?>> <?php _e('Single meeting/webinar', 'woocommerce-events'); ?></label>
            <img class="help_tip" data-tip="<?php _e("Choose whether to link this event to a single Zoom meeting/webinar OR select recurring or separate Zoom meetings/webinars for each day of the event." , 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
            <br/>
            <label class="fooevents-options-inner-label"><input type="radio" name="WooCommerceEventsZoomMultiOption" value="multi" <?php echo (!empty($WooCommerceEventsZoomMultiOption) && $WooCommerceEventsZoomMultiOption == 'multi')? 'CHECKED' : ''; ?>> <?php _e('Multiple meetings/webinars', 'woocommerce-events'); ?></label>
        </p>
        <?php endif; ?>
        <div id ="fooevents_zoom_meeting_single" class="options_group">
            <p class="form-field">
                <label><?php _e('Link the event to this meeting/webinar:', 'woocommerce-events'); ?></label>
                <select name="WooCommerceEventsZoomWebinar" id="WooCommerceEventsZoomWebinar" class="WooCommerceEventsZoomSelect fooevents-search-list">
                    <option value=""><?php _e('(Not set)', 'woocommerce-events'); ?></option>
                    <?php if($zoomWebinars['status'] == 'success' && !empty($zoomWebinars['data']['webinars'])) : ?>
                        <optgroup label="<?php _e('Webinars', 'woocommerce-events'); ?>">
                        <?php foreach($zoomWebinars['data']['webinars'] as $zoomWebinar) :?>
                            <option value="<?php echo $zoomWebinar['id']; ?>" <?php echo (str_replace('_webinars', '', $WooCommerceEventsZoomWebinar) == str_replace('_webinars', '', $zoomWebinar['id']))? 'SELECTED' : '' ?>><?php echo $zoomWebinars['user_count'] > 1 ? $zoomWebinar['host']['first_name'] . ' ' . $zoomWebinar['host']['last_name'] . ' - ' : ''; ?><?php echo $zoomWebinar['topic']; ?> - <?php echo (!empty($zoomWebinar['start_date_display']) && !empty($zoomWebinar['start_time_display'])) ? $zoomWebinar['start_date_display'] . ' ' . $zoomWebinar['start_time_display'] : __('No fixed time', 'woocommerce-events'); ?></option>
                        <?php endforeach; ?>
                        </optgroup>
                    <?php endif; ?>
                    <?php if($zoomMeetings['status'] == 'success' && !empty($zoomMeetings['data']['meetings'])) : ?>
                        <optgroup label="<?php _e('Meetings', 'woocommerce-events'); ?>">
                        <?php foreach($zoomMeetings['data']['meetings'] as $zoomMeeting) :?>
                            <option value="<?php echo $zoomMeeting['id']; ?>" <?php echo (str_replace('_meetings', '', $WooCommerceEventsZoomWebinar) == str_replace('_meetings', '', $zoomMeeting['id']))? 'SELECTED' : '' ?>><?php echo $zoomMeetings['user_count'] > 1 ? $zoomMeeting['host']['first_name'] . ' ' . $zoomMeeting['host']['last_name'] . ' - ' : ''; ?><?php echo $zoomMeeting['topic']; ?> - <?php echo (!empty($zoomMeeting['start_date_display']) && !empty($zoomMeeting['start_time_display'])) ? $zoomMeeting['start_date_display'] . ' ' . $zoomMeeting['start_time_display'] : __('No fixed time', 'woocommerce-events'); ?></option>
                        <?php endforeach; ?>
                        </optgroup>
                    <?php endif; ?>
                </select>
                <img class="help_tip" data-tip="<?php _e('Select a meeting/webinar which attendees will automatically be registered for when purchasing an event ticket (must be created through your Zoom account).', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                <?php if($zoomMeetings['status'] == 'success' && $zoomWebinars['status'] == 'success' && empty($zoomMeetings['data']['meetings']) && empty($zoomWebinars['data']['webinars'])) : ?>
                <br /><br />
                <?php _e('No Zoom meetings/webinars found.','woocommerce-events'); ?>
                <br/>
                <br/>
                <a href="https://zoom.us/meeting" target="_blank"><?php _e('Create a Zoom meeting', 'woocommerce-events'); ?></a>
                <br/>
                <a href="https://zoom.us/webinar/list" target="_blank"><?php _e('Create a Zoom webinar', 'woocommerce-events'); ?></a>
                <?php endif; ?>
                <?php if(empty($globalWooCommerceEventsZoomAPIKey) || empty($globalWooCommerceEventsZoomAPISecret)) :?>
                <br /><br />
                <?php _e('The Zoom API Key and API Secret are not set.','woocommerce-events'); ?> <a href="admin.php?page=fooevents-settings&tab=integration"><?php _e('Please check the Event Integration settings.', 'woocommerce-events'); ?></a>
                <?php endif; ?>
            </p> 
            <p class="form-field">
                <label><?php _e('Details:', 'woocommerce-events'); ?></label>
                <span id="WooCommerceEventsZoomWebinarDetails"><?php _e('(Not set)', 'woocommerce-events'); ?></span>
            </p>
        </div>
        <?php if ( $this->is_plugin_active( 'fooevents_multi_day/fooevents-multi-day.php' ) || is_plugin_active_for_network('fooevents_multi_day/fooevents-multi-day.php') ) : ?>
            <div id ="fooevents_zoom_meeting_multi" class="options_group" data-day-term="<?php echo $dayTerm; ?>">
                <?php
                    for ( $x = 1; $x <= $numDaysValue; $x++ ) {

                        $zoomWebinarMulti = 0;

                        if ( !empty($WooCommerceEventsZoomWebinarMulti) ) {
                            $zoomWebinarMulti = $WooCommerceEventsZoomWebinarMulti[$x - 1];
                        }
                ?>
                    <p class="form-field">
                        <?php if ( $x == 1 ) : ?>
                            <label><?php _e('Link the event to these meetings/webinars:', 'woocommerce-events'); ?></label>
                        <?php endif; ?>
                        <span class="fooevents-zoom-day-override-title"><?php echo $dayTerm; ?> <?php echo $x; ?></span>
                        <select name="WooCommerceEventsZoomWebinarMulti[]" id="WooCommerceEventsZoomWebinarMulti<?php echo $x; ?>" class="WooCommerceEventsZoomSelect fooevents-search-list">
                            <option value=""><?php _e('(Not set)', 'woocommerce-events'); ?></option>
                            <?php if($zoomWebinars['status'] == 'success' && !empty($zoomWebinars['data']['webinars'])) : ?>
                                <optgroup label="<?php _e('Webinars', 'woocommerce-events'); ?>">
                                <?php foreach($zoomWebinars['data']['webinars'] as $zoomWebinar) :?>
                                    <option value="<?php echo $zoomWebinar['id']; ?>" <?php echo (str_replace('_webinars', '', $zoomWebinarMulti) == str_replace('_webinars', '', $zoomWebinar['id']))? 'SELECTED' : '' ?>><?php echo $zoomWebinars['user_count'] > 1 ? $zoomWebinar['host']['first_name'] . ' ' . $zoomWebinar['host']['last_name'] . ' - ' : ''; ?><?php echo $zoomWebinar['topic']; ?> - <?php echo (!empty($zoomWebinar['start_date_display']) && !empty($zoomWebinar['start_time_display'])) ? $zoomWebinar['start_date_display'] . ' ' . $zoomWebinar['start_time_display'] : __('No fixed time', 'woocommerce-events'); ?></option>
                                <?php endforeach; ?>
                                </optgroup>
                            <?php endif; ?>
                            <?php if($zoomMeetings['status'] == 'success' && !empty($zoomMeetings['data']['meetings'])) : ?>
                                <optgroup label="<?php _e('Meetings', 'woocommerce-events'); ?>">
                                <?php foreach($zoomMeetings['data']['meetings'] as $zoomMeeting) :?>
                                    <option value="<?php echo $zoomMeeting['id']; ?>" <?php echo (str_replace('_meetings', '', $zoomWebinarMulti) == str_replace('_meetings', '', $zoomMeeting['id']))? 'SELECTED' : '' ?>><?php echo $zoomMeetings['user_count'] > 1 ? $zoomMeeting['host']['first_name'] . ' ' . $zoomMeeting['host']['last_name'] . ' - ' : ''; ?><?php echo $zoomMeeting['topic']; ?> - <?php echo (!empty($zoomMeeting['start_date_display']) && !empty($zoomMeeting['start_time_display'])) ? $zoomMeeting['start_date_display'] . ' ' . $zoomMeeting['start_time_display'] : __('No fixed time', 'woocommerce-events'); ?></option>
                                <?php endforeach; ?>
                                </optgroup>
                            <?php endif; ?>
                        </select>
                        <a href="#" class="fooevents-zoom-show-hide-meeting-details-link" data-meeting="WooCommerceEventsZoomWebinarMulti<?php echo $x; ?>">
                            <span class="toggle-indicator fooevents-zoom-show-hide-meeting-details" aria-hidden="true"></span>
                            <span class="fooevents-zoom-show-hide-meeting-details-link-text"><?php _e('Show details', 'woocommerce-events'); ?></span>
                        </a>
                        <img class="help_tip" data-tip="<?php _e('Select a meeting/webinar which attendees will automatically be registered for when purchasing an event ticket (must be created through your Zoom account).', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                    </p>
                    <p class="form-field fooevents-zoom-multi-meeting-details">
                        <span class="fooevents-zoom-multi-meeting-details-container" id="WooCommerceEventsZoomWebinarMulti<?php echo $x; ?>Details"><?php _e('(Not set)', 'woocommerce-events'); ?></span>
                    </p>
                <?php } ?>
            </div>
        <?php endif; ?>
    </div>
</div>

