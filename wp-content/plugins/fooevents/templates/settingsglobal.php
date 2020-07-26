<div class="wrap" id="fooevents-settings-page">
    <h1 class="wp-heading-inline"><?php _e('FooEvents Settings', 'woocommerce-events'); ?></h1>
    <h2 class="nav-tab-wrapper">
        <a href="?page=fooevents-settings&tab=api" class="nav-tab <?php echo $active_tab == 'api' ? 'nav-tab-active' : ''; ?>"><?php _e('License', 'woocommerce-events'); ?></a>
        <a href="?page=fooevents-settings&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>"><?php _e('General', 'woocommerce-events'); ?></a>
        <a href="?page=fooevents-settings&tab=terminology" class="nav-tab <?php echo $active_tab == 'terminology' ? 'nav-tab-active' : ''; ?>"><?php _e('Terminology', 'woocommerce-events'); ?></a>
        <a href="?page=fooevents-settings&tab=ticket_design" class="nav-tab <?php echo $active_tab == 'ticket_design' ? 'nav-tab-active' : ''; ?>"><?php _e('Ticket Design', 'woocommerce-events'); ?></a>
        <?php if($pdfEnabled) :?><a href="?page=fooevents-settings&tab=pdf" class="nav-tab <?php echo $active_tab == 'pdf' ? 'nav-tab-active' : ''; ?>"><?php _e('PDF Tickets', 'woocommerce-events'); ?></a><?php endif; ?>
        <?php if($seatingEnabled) :?><a href="?page=fooevents-settings&tab=seating" class="nav-tab <?php echo $active_tab == 'seating' ? 'nav-tab-active' : ''; ?>"><?php _e('Seating', 'woocommerce-events'); ?></a><?php endif; ?>
        <?php if($calendarEnabled) :?><a href="?page=fooevents-settings&tab=calendar" class="nav-tab <?php echo $active_tab == 'calendar' ? 'nav-tab-active' : ''; ?>"><?php _e('Calendar', 'woocommerce-events'); ?></a><?php endif; ?>
        <a href="?page=fooevents-settings&tab=checkins_app" class="nav-tab <?php echo $active_tab == 'checkins_app' ? 'nav-tab-active' : ''; ?>"><?php _e('Check-ins App', 'woocommerce-events'); ?></a>
        <a href="?page=fooevents-settings&tab=integration" class="nav-tab <?php echo $active_tab == 'integration' ? 'nav-tab-active' : ''; ?>"><?php _e('Integration', 'woocommerce-events'); ?></a>
    </h2>
    <form method="post" action="options.php">
        <table class="form-table fooevents-settings">
            <?php if( $active_tab == 'api' ) : ?>
            <?php settings_fields('fooevents-settings-api'); ?>
            <?php do_settings_sections('fooevents-settings-api'); ?>
            <tr valign="top">
                <th scope="row"><h2><?php _e('FooEvents License', 'woocommerce-events'); ?></h2></th>
                <td></td>
                <td></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('FooEvents license key', 'woocommerce-events'); ?></th>
                <td>
                    <input type="text" name="globalWooCommerceEventsAPIKey" id="globalWooCommerceEventsAPIKey" value="<?php echo esc_html($globalWooCommerceEventsAPIKey); ?>">
                    <img class="help_tip fooevents-tooltip" title="<?php _e('Required for automatic plugin updates. Leave empty if purchase was made on CodeCanyon.net AND no other plugin purchases were made on FooEvents.com. You must paste your license key here if any plugin purchases were made on FooEvents.com.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </td>
            </tr>  
            <tr valign="top">
                <th scope="row"><?php _e('Envato purchase code', 'woocommerce-events'); ?></th>
                <td>
                    <input type="text" name="globalWooCommerceEnvatoAPIKey" id="globalWooCommerceEnvatoAPIKey" value="<?php echo esc_html($globalWooCommerceEnvatoAPIKey); ?>">
                    <img class="help_tip fooevents-tooltip" title="<?php _e('Required for automatic plugin updates. Leave empty if purchase was made on FooEvents.com AND no purchases were made on CodeCanyon.net. You must paste your Envato purchase code here if the FooEvents for WooCommerce plugin was purchased on CodeCanyon.net.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </td>
            </tr> 
            <?php endif; ?>
            
            <?php if( $active_tab == 'general' ) : ?>
            <?php settings_fields('fooevents-settings-general'); ?>
            <?php do_settings_sections('fooevents-settings-general'); ?>
            <tr valign="top">
                <th scope="row"><h2><?php _e('General', 'woocommerce-events'); ?></h2></th>
                <td></td>
                <td></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Change add to cart text', 'woocommerce-events'); ?></th>
                <td>
                    <input type="checkbox" name="globalWooCommerceEventsChangeAddToCart" id="globalWooCommerceEventsChangeAddToCart" value="yes" <?php echo ($globalWooCommerceEventsChangeAddToCart == 'yes') ? 'CHECKED' : ''; ?>>
                    <img class="help_tip fooevents-tooltip" title="<?php _e("Changes 'Add to cart' text to 'Book ticket' for event products.", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </td>
            </tr> 
            <tr valign="top">
                <th scope="row"><?php _e('Enable event sorting options', 'woocommerce-events'); ?></th>
                <td>
                    <input type="checkbox" name="globalWooCommerceEventSorting" id="globalWooCommerceEventSorting" value="yes" <?php echo ($globalWooCommerceEventSorting == 'yes') ? 'CHECKED' : ''; ?>>
                    <img class="help_tip fooevents-tooltip" title="<?php _e("Adds sort by date options to the WooCommerce product sorting drop-down list. You can set the default sort option in the WordPress Customizer.", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </td>
            </tr> 
            <tr valign="top">
                <th scope="row"><?php _e('Display event date on product listings', 'woocommerce-events'); ?></th>
                <td>
                    <input type="checkbox" name="globalWooCommerceDisplayEventDate" id="globalWooCommerceDisplayEventDate" value="yes" <?php echo ($globalWooCommerceDisplayEventDate == 'yes') ? 'CHECKED' : ''; ?>>
                    <img class="help_tip fooevents-tooltip" title="<?php _e("Adds the event date above the product title on product listing pages.", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </td>
            </tr> 
            <tr valign="top">
                <th scope="row"><?php _e('Hide event details tab', 'woocommerce-events'); ?></th>
                <td>
                    <input type="checkbox" name="globalWooCommerceHideEventDetailsTab" id="globalWooCommerceHideEventDetailsTab" value="yes" <?php echo ($globalWooCommerceHideEventDetailsTab == 'yes') ? 'CHECKED' : ''; ?>>
                    <img class="help_tip fooevents-tooltip" title="<?php _e('Hides the event details tab on the product page.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </td>
            </tr> 
            <tr valign="top">
                <th scope="row"><?php _e('Use placeholders on checkout form', 'woocommerce-events'); ?></th>
                <td>
                    <input type="checkbox" name="globalWooCommerceUsePlaceHolders" id="globalWooCommerceUsePlaceHolders" value="yes" <?php echo ($globalWooCommerceUsePlaceHolders == 'yes') ? 'CHECKED' : ''; ?>>
                    <img class="help_tip fooevents-tooltip" title="<?php _e("Displays placeholders in the checkout form (useful for themes that don't support form labels).", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Hide unpaid tickets', 'woocommerce-events'); ?></th>
                <td>
                    <input type="checkbox" name="globalWooCommerceEventsHideUnpaidTickets" id="globalWooCommerceEventsHideUnpaidTickets" value="yes" <?php echo ($globalWooCommerceEventsHideUnpaidTickets == 'yes') ? 'CHECKED' : ''; ?>>
                    <img class="help_tip fooevents-tooltip" title="<?php _e("Hides unpaid tickets in ticket admin.", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </td>
            </tr> 
            <tr valign="top">
                <th scope="row"><?php _e('Email copy of ticket to admin', 'woocommerce-events'); ?></th>
                <td>
                    <input type="checkbox" name="globalWooCommerceEventsEmailTicketAdmin" id="globalWooCommerceEventsEmailTicketAdmin" value="yes" <?php echo ($globalWooCommerceEventsEmailTicketAdmin == 'yes') ? 'CHECKED' : ''; ?>>
                    <img class="help_tip fooevents-tooltip" title="<?php _e("Sends a copy of every emailed ticket to the website admin.", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </td>
            </tr>
            <?php endif; ?>
            
            <?php if( $active_tab == 'terminology' ) : ?>
            <?php settings_fields('fooevents-settings-terminology'); ?>
            <?php do_settings_sections('fooevents-settings-terminology'); ?>
            <tr valign="top">
                <th scope="row"><h2><?php _e('Terminology', 'woocommerce-events'); ?></h2></th>
                <td></td>
                <td width="100%"></td>
            </tr>
            <tr valign="top">
                <th scope="row"></th>
                <td><?php _e('Singular', 'woocommerce-events'); ?></td>
                <td><?php _e('Plural', 'woocommerce-events'); ?></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Event', 'woocommerce-events'); ?></th>
                <td>
                    <input type="text" name="globalWooCommerceEventsEventOverride" id="globalWooCommerceEventsEventOverride" value="<?php echo $globalWooCommerceEventsEventOverride; ?>">
                </td>
                <td>
                    <input type="text" name="globalWooCommerceEventsEventOverridePlural" id="globalWooCommerceEventsEventOverridePlural" value="<?php echo $globalWooCommerceEventsEventOverridePlural; ?>">
                    <img class="help_tip fooevents-tooltip" title="<?php _e("Change 'event' to your own custom text.", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Attendee', 'woocommerce-events'); ?></th>
                <td>
                    <input type="text" name="globalWooCommerceEventsAttendeeOverride" id="globalWooCommerceEventsAttendeeOverride" value="<?php echo $globalWooCommerceEventsAttendeeOverride; ?>">
                </td>
                <td>
                    <input type="text" name="globalWooCommerceEventsAttendeeOverridePlural" id="globalWooCommerceEventsAttendeeOverridePlural" value="<?php echo $globalWooCommerceEventsAttendeeOverridePlural; ?>">
                    <img class="help_tip fooevents-tooltip" title="<?php _e("Change 'attendee' to your own custom text.", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </td>
            </tr> 
            <tr valign="top">
                <th scope="row"><?php _e('Book ticket', 'woocommerce-events'); ?></th>
                <td>
                    <input type="text" name="globalWooCommerceEventsTicketOverride" id="globalWooCommerceEventsTicketOverride" value="<?php echo $globalWooCommerceEventsTicketOverride; ?>">
                </td>
                <td>
                    <input type="text" name="globalWooCommerceEventsTicketOverridePlural" id="globalWooCommerceEventsTicketOverridePlural" value="<?php echo $globalWooCommerceEventsTicketOverridePlural; ?>">
                    <img class="help_tip fooevents-tooltip" title="<?php _e("Change 'Book ticket' to your own custom text.", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </td>
            </tr> 
            <tr valign="top">
                <th scope="row"><?php _e('Day', 'woocommerce-events'); ?></th>
                <td>
                    <input type="text" name="WooCommerceEventsDayOverride" id="WooCommerceEventsDayOverride" value="<?php echo $WooCommerceEventsDayOverride; ?>">
                </td>
                <td>
                    <input type="text" name="WooCommerceEventsDayOverridePlural" id="WooCommerceEventsDayOverridePlural" value="<?php echo $WooCommerceEventsDayOverridePlural; ?>">
                    <img class="help_tip fooevents-tooltip" title="<?php _e("Change 'Day' to your own custom text.", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </td>
            </tr>
            <?php endif; ?>
            
            <?php if( $active_tab == 'ticket_design' ) : ?>
            <?php settings_fields('fooevents-settings-ticket-design'); ?>
            <?php do_settings_sections('fooevents-settings-ticket-design'); ?>
            <tr valign="top">
                <th scope="row"><h2><?php _e('Ticket Design', 'woocommerce-events'); ?></h2></th>
                <td></td>
                <td></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Global ticket border', 'woocommerce-events'); ?></th>
                <td>
                    <input type="text" name="globalWooCommerceEventsTicketBackgroundColor" id="globalWooCommerceEventsTicketBackgroundColor" class="woocommerce-events-color-field" value="<?php echo esc_html($globalWooCommerceEventsTicketBackgroundColor); ?>">
                    <img class="help_tip fooevents-tooltip" title="<?php _e('Color of the ticket border.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Global ticket button', 'woocommerce-events'); ?></th>
                <td>
                    <input type="text" name="globalWooCommerceEventsTicketButtonColor" id="globalWooCommerceEventsTicketButtonColor" class="woocommerce-events-color-field" value="<?php echo esc_html($globalWooCommerceEventsTicketButtonColor); ?>">
                    <img class="help_tip fooevents-tooltip" title="<?php _e('Color of the ticket button.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Global ticket button text', 'woocommerce-events'); ?></th>
                <td>
                    <input type="text" name="globalWooCommerceEventsTicketTextColor" id="globalWooCommerceEventsTicketTextColor" class="woocommerce-events-color-field" value="<?php echo esc_html($globalWooCommerceEventsTicketTextColor); ?>">
                    <img class="help_tip fooevents-tooltip" title="<?php _e('Color of the text in the ticket button.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </td>
            </tr>  
            <tr valign="top">
                <th scope="row"><?php _e('Global ticket logo', 'woocommerce-events'); ?></th>
                <td>
                    <input id="globalWooCommerceEventsTicketLogo" class="text uploadfield" type="text" size="40" name="globalWooCommerceEventsTicketLogo" value="<?php echo esc_attr($globalWooCommerceEventsTicketLogo); ?>" />                
                    <span class="uploadbox">
                        <input class="upload_image_button_woocommerce_events  button  " type="button" value="Upload file" />
                        <a href="#" class="upload_reset"><?php _e('Clear', 'woocommerce-events'); ?></a>
                        <img class="help_tip fooevents-tooltip" title="<?php _e('Full URL that links to the logo that will be used in the ticket.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                    </span>
                </td>
            </tr>  
            <tr valign="top">
                <th scope="row"><?php _e('Global ticket header image', 'woocommerce-events'); ?></th>
                <td>
                    <input id="globalWooCommerceEventsTicketHeaderImage" class="text uploadfield" type="text" size="40" name="globalWooCommerceEventsTicketHeaderImage" value="<?php echo esc_attr($globalWooCommerceEventsTicketHeaderImage); ?>" />               
                    <span class="uploadbox">
                        <input class="upload_image_button_woocommerce_events  button  " type="button" value="Upload file" />
                        <a href="#" class="upload_reset"><?php _e('Clear', 'woocommerce-events'); ?></a>
                        <img class="help_tip fooevents-tooltip" title="<?php _e('Full URL that links to the image that will be used as the ticket header.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                    </span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Enable QR codes', 'woocommerce-events'); ?></th>
                <td>
                    <input type="checkbox" name="globalWooCommerceEventsEnableQRCode" id="globalWooCommerceEventsEnableQRCode" value="yes" <?php echo ($globalWooCommerceEventsEnableQRCode == 'yes') ? 'CHECKED' : ''; ?>>
                    <img class="help_tip fooevents-tooltip" title="<?php _e("Use QR codes instead of 1D barcodes on tickets.", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </td>
            </tr> 
            <?php endif; ?>
            
            <?php if( $active_tab == 'pdf' ) : ?>
            <?php echo $pdfOptions; ?>
            <?php endif; ?>
            
            <?php if( $active_tab == 'calendar' ) : ?>
            <?php echo $calendarOptions; ?>
            <?php endif; ?>
            
            <?php if( $active_tab == 'seating' ) : ?>
            <?php echo $seatingOptions; ?>
            <?php endif; ?>
            
            <?php if( $active_tab == 'checkins_app' ) : ?>
            <?php settings_fields('fooevents-settings-checkins-app'); ?>
            <?php do_settings_sections('fooevents-settings-checkins-app'); ?>
            <tr valign="top">
                <th scope="row"><h2><?php _e('Check-ins App', 'woocommerce-events'); ?></h2></th>
                <td></td>
                <td></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Hide personal information', 'woocommerce-events'); ?></th>
                <td>
                    <input type="checkbox" name="globalWooCommerceEventsAppHidePersonalInfo" id="globalWooCommerceEventsAppHidePersonalInfo" value="yes" <?php echo ($globalWooCommerceEventsAppHidePersonalInfo == 'yes') ? 'CHECKED' : ''; ?>>
                    <img class="help_tip fooevents-tooltip" title="<?php _e("Hide all personal information for attendees and/or ticket purchasers in the app. Only attendee names will be visible for check-in purposes.", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </td>
            </tr> 
            <tr valign="top">
                <th scope="row"><?php _e('Hide unpaid tickets', 'woocommerce-events'); ?></th>
                <td>
                    <input type="checkbox" name="globalWooCommerceHideUnpaidTicketsApp" id="globalWooCommerceHideUnpaidTicketsApp" value="yes" <?php echo ($globalWooCommerceHideUnpaidTicketsApp == 'yes') ? 'CHECKED' : ''; ?>>
                    <img class="help_tip fooevents-tooltip" title="<?php _e("Hide all unpaid tickets in the app.", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </td>
            </tr> 
            <tr valign="top">
                <th scope="row"><?php _e('App title', 'woocommerce-events'); ?></th>
                <td>
                    <input type="text" name="globalWooCommerceEventsAppTitle" id="globalWooCommerceEventsAppTitle" placeholder="<?php _e('e.g. Attendee Check-ins', 'woocommerce-events'); ?>" class="text" size="40" value="<?php echo $globalWooCommerceEventsAppTitle; ?>">
                    <img class="help_tip fooevents-tooltip" title="<?php _e('The title that displays on the app sign-in screen beneath the app logo.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </td>
            </tr> 
            <tr valign="top">
                <th scope="row"><?php _e('App logo', 'woocommerce-events'); ?></th>
                <td>
                    <input id="globalWooCommerceEventsAppLogo" class="text uploadfield" type="text" size="40" name="globalWooCommerceEventsAppLogo" value="<?php echo esc_attr($globalWooCommerceEventsAppLogo); ?>" />             
                    <span class="uploadbox">
                        <input class="upload_image_button_woocommerce_events  button  " type="button" value="Upload file" />
                        <a href="#" class="upload_reset"><?php _e('Clear', 'woocommerce-events'); ?></a>
                        <img class="help_tip fooevents-tooltip" title="<?php _e('Full URL that links to the image that will be used as the logo on the sign-in screen (PNG format with transparency and a width of around 940px is recommended).', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                    </span>
                </td>
            </tr> 
            <tr valign="top">
                <th scope="row"><?php _e('Accent color', 'woocommerce-events'); ?></th>
                <td>
                    <input type="text" name="globalWooCommerceEventsAppColor" id="globalWooCommerceEventsAppColor" class="woocommerce-events-color-field" value="<?php echo $globalWooCommerceEventsAppColor; ?>">
                    <img class="help_tip fooevents-tooltip" title="<?php _e('Color of the top navigation bar and sign-in button.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </td>
            </tr> 
            <tr valign="top">
                <th scope="row"><?php _e('Accent text color', 'woocommerce-events'); ?></th>
                <td>
                    <input type="text" name="globalWooCommerceEventsAppTextColor" id="globalWooCommerceEventsAppTextColor" class="woocommerce-events-color-field" value="<?php echo $globalWooCommerceEventsAppTextColor; ?>">
                    <img class="help_tip fooevents-tooltip" title="<?php _e('Color of the text in the top navigation bar and sign-in button.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </td>
            </tr> 
            <tr valign="top">
                <th scope="row"><?php _e('Background color', 'woocommerce-events'); ?></th>
                <td>
                    <input type="text" name="globalWooCommerceEventsAppBackgroundColor" id="globalWooCommerceEventsAppBackgroundColor" class="woocommerce-events-color-field" value="<?php echo $globalWooCommerceEventsAppBackgroundColor; ?>">
                    <img class="help_tip fooevents-tooltip" title="<?php _e('Color of the background on the sign-in screen.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                <td>
            </tr> 
            <tr valign="top">
                <th scope="row"><?php _e('Title text color', 'woocommerce-events'); ?></th>
                <td>
                    <input type="text" name="globalWooCommerceEventsAppSignInTextColor" id="globalWooCommerceEventsAppSignInTextColor" class="woocommerce-events-color-field" value="<?php echo $globalWooCommerceEventsAppSignInTextColor; ?>">
                    <img class="help_tip fooevents-tooltip" title="<?php _e('Color of the title text beneath the logo on the sign-in screen.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </td>
            </tr> 
            <tr valign="top">
                <th scope="row"><?php _e('Event listing options', 'woocommerce-events'); ?></th>
                <td>
                    <label><input type="radio" name="globalWooCommerceEventsAppEvents" id="globalWooCommerceEventsAppEventsAll" value="all" <?php echo ($globalWooCommerceEventsAppEvents == 'all' || empty($globalWooCommerceEventsAppEvents)) ? 'CHECKED' : ''; ?>> <?php _e('Show all events', 'woocommerce-events'); ?></label>
                    <img class="help_tip fooevents-tooltip" title="<?php _e('Manage how events are listed in the app. Changes can be made in real-time without the user needing to sign-out.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                    <br/><br/>
                    <label><input type="radio" name="globalWooCommerceEventsAppEvents" id="globalWooCommerceEventsAppEventsUser" value="user" <?php echo ($globalWooCommerceEventsAppEvents == 'user') ? 'CHECKED' : ''; ?>> <?php _e("Only show events created by the signed-in user", 'woocommerce-events'); ?></label>
                    <br/><br/>
                    <label><input type="radio" name="globalWooCommerceEventsAppEvents" id="globalWooCommerceEventsAppEventsID" value="id" <?php echo ($globalWooCommerceEventsAppEvents == 'id') ? 'CHECKED' : ''; ?>> <?php _e("Only show the following events:", 'woocommerce-events'); ?></label>
                    <br/><br/>
                    <select name="globalWooCommerceEventsAppEventIDs[]" id="globalWooCommerceEventsAppEventIDs" multiple class="fooevents-multiselect" <?php echo ($globalWooCommerceEventsAppEvents !== 'id') ? 'disabled' : ''; ?>>
                        <?php
                            foreach ( $WooCommerceEventsAppEvents as $WooCommerceEventsAppEvent ) {
                        ?>
                                <option value="<?php echo $WooCommerceEventsAppEvent->ID; ?>" <?php echo !empty($globalWooCommerceEventsAppEventIDs) && in_array($WooCommerceEventsAppEvent->ID, $globalWooCommerceEventsAppEventIDs) ? 'SELECTED' : ''; ?>><?php echo $WooCommerceEventsAppEvent->post_title; ?></option>
                        <?php
                            }
                        ?>
                    </select>
                </td>
            </tr> 
            <?php endif; ?>

            <?php if( $active_tab == 'integration' ) : ?>
            <?php settings_fields('fooevents-settings-integration'); ?>
            <?php do_settings_sections('fooevents-settings-integration'); ?>
            
            <tr valign="top">
                <th scope="row" colspan="3"><h3 class="fooevents-settings-section-title"><?php _e('Google Maps', 'woocommerce-events'); ?></h3></th>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Google Maps API key', 'woocommerce-events'); ?></th>
                <td>
                    <input type="text" name="globalWooCommerceEventsGoogleMapsAPIKey" id="globalWooCommerceEventsGoogleMapsAPIKey" value="<?php echo esc_html($globalWooCommerceEventsGoogleMapsAPIKey); ?>">
                    <img class="help_tip fooevents-tooltip" title="<?php _e('Enable Google Maps to be displayed on the product page.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </td>
            </tr>
            
            <tr valign="top">
                <th scope="row" colspan="3"><h3 class="fooevents-settings-section-title"><?php _e('Zoom Meetings and Webinars', 'woocommerce-events'); ?></h3></th>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('API Key', 'woocommerce-events'); ?></th>
                <td>
                    <input type="text" name="globalWooCommerceEventsZoomAPIKey" id="globalWooCommerceEventsZoomAPIKey" value="<?php echo $globalWooCommerceEventsZoomAPIKey; ?>">
                    <img class="help_tip fooevents-tooltip" title="<?php _e('Required to securely connect to your Zoom account in order to register attendees for your meetings/webinars.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </td>
            </tr> 
            <tr valign="top">
                <th scope="row"><?php _e('API Secret', 'woocommerce-events'); ?></th>
                <td>
                    <input type="password" name="globalWooCommerceEventsZoomAPISecret" id="globalWooCommerceEventsZoomAPISecret" value="<?php echo $globalWooCommerceEventsZoomAPISecret; ?>">
                    <img class="help_tip fooevents-tooltip" title="<?php _e('Required to securely connect to your Zoom account in order to register attendees for your meetings/webinars.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </td>
            </tr> 
            <tr valign="top">
                <th scope="row"></th>
                <td>
                    <input id="fooevents_zoom_test_access" type="button" value="<?php _e('Test Access', 'woocommerce-events'); ?>" class="button button-secondary">
                    <br/>
                    <br/>
                    <a href="https://help.fooevents.com/docs/topics/events/zoom-meetings-and-webinars/#generating-a-zoom-api-key-and-secret" target="_blank"><?php _e('Get help generating Zoom API keys'); ?></a>
                </td>
            </tr> 
            <tr valign="top" id="globalWooCommerceEventsZoomUsers" <?php if ( empty($globalWooCommerceEventsZoomAPIKey) || empty($globalWooCommerceEventsZoomAPISecret) ) : ?>style="display:none;"<?php endif; ?>>
                <th scope="row"><?php _e('Users/Hosts', 'woocommerce-events'); ?></th>
                <td>
                    <input id="fooevents_zoom_fetch_users" type="button" value="<?php _e('Fetch Users', 'woocommerce-events'); ?>" class="button button-secondary">
                    <img class="help_tip fooevents-tooltip" title="<?php _e('Displays meetings/webinars on the Event Integration tab according to which users created them so that they can be linked to specific events. The default setting will only display meetings/webinars for the user that generated the API Key and Secret. The second option is useful if you have multiple hosts on your Zoom account and you would like to restrict which meetings/webinars are visible (Hint: Shift-Click or Ctrl/Cmd+Click to select multiple hosts).', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                    <br/>
                    <br/>
                    <div id="globalWooCommerceEventsZoomUsersContainer">
                        <?php if ( empty($globalWooCommerceEventsZoomUsers) ) : ?>
                            <input type="hidden" name="globalWooCommerceEventsZoomUsers" value="[]" />
                            <input type="hidden" name="globalWooCommerceEventsZoomSelectedUsers[]" value="me" />
                        <?php else : ?>
                            <input type="hidden" name="globalWooCommerceEventsZoomUsers" value="<?php echo esc_attr(json_encode($globalWooCommerceEventsZoomUsers)); ?>" />
                            <label><input type="radio" name="globalWooCommerceEventsZoomSelectedUserOption" id="globalWooCommerceEventsZoomSelectedUserOptionMe" value="me" <?php echo (empty($globalWooCommerceEventsZoomSelectedUserOption) || $globalWooCommerceEventsZoomSelectedUserOption == 'me') ? 'CHECKED' : ''; ?>> <?php _e("Show only meetings/webinars for the user that generated the API Key and Secret", 'woocommerce-events'); ?></label>
                            <br/><br/>
                            <label><input type="radio" name="globalWooCommerceEventsZoomSelectedUserOption" id="globalWooCommerceEventsZoomSelectedUserOptionSelect" value="select" <?php echo ($globalWooCommerceEventsZoomSelectedUserOption == 'select') ? 'CHECKED' : ''; ?>> <?php _e("Show all meetings/webinars created by the following users:", 'woocommerce-events'); ?></label>
                            <br/><br/>
                            <select name="globalWooCommerceEventsZoomSelectedUsers[]" id="globalWooCommerceEventsZoomSelectedUsers" multiple class="fooevents-multiselect" <?php echo ($globalWooCommerceEventsZoomSelectedUserOption !== 'select') ? 'disabled' : ''; ?>>
                                <?php
                                    foreach ( $globalWooCommerceEventsZoomUsers as $user ) {
                                ?>
                                        <option value="<?php echo $user['id']; ?>" <?php echo !empty($globalWooCommerceEventsZoomSelectedUsers) && in_array($user['id'], $globalWooCommerceEventsZoomSelectedUsers) ? 'SELECTED' : ''; ?>><?php echo $user['first_name'] . ' ' . $user['last_name'] . ' - ' . $user['email']; ?></option>
                                <?php
                                    }
                                ?>
                            </select>
                            <p><?php _e('Please note that meeting/webinar load times will increase as more users are selected.', 'woocommerce-events'); ?></p>
                        <?php endif; ?>
                    </div>                    
                </td>
            </tr> 
            <?php echo $eventbriteOptions; ?>
            <?php endif; ?>
            
        </table>
        <?php submit_button(); ?>
    </form>
</div>

    