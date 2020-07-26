<?php settings_fields('fooevents-calendar-settings-calendar'); ?>
<?php do_settings_sections('fooevents-calendar-settings-calendar'); ?>
<tr valign="top">
    <th scope="row"><h2><?php _e('Calendar', 'fooevents-calendar'); ?></h2></th>
    <td></td>
    <td></td>
</tr>
<tr valign="top">
    <th scope="row"><?php _e('Enable 24-hour time format', 'fooevents-calendar'); ?></th>
    <td>
        <input type="checkbox" name="globalFooEventsTwentyFourHour" id="globalFooEventsTwentyFourHour" value="yes" <?php echo $globalFooEventsTwentyFourHourChecked; ?>>
        <img class="help_tip fooevents-tooltip" title="<?php _e('Uses the 24-hour time format on the calendar instead of 12-hour format.', 'fooevents-calendar'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
    </td>
</tr>
<tr valign="top">
    <th scope="row"><?php _e('Only display start day', 'fooevents-calendar'); ?></th>
    <td>
        <input type="checkbox" name="globalFooEventsStartDay" id="globalFooEventsStartDay" value="yes" <?php echo $globalFooEventsStartDayChecked; ?>>
        <img class="help_tip fooevents-tooltip" title="<?php _e('When the Multi-day plugin is active the calendar will only display the event start date.', 'fooevents-calendar'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
    </td>
</tr>
<tr valign="top">
    <th scope="row"><?php _e('Enable full-day events', 'fooevents-calendar'); ?></th>
    <td>
        <input type="checkbox" name="globalFooEventsAllDayEvent" id="globalFooEventsAllDayEvent" value="yes" <?php echo $globalFooEventsAllDayEventChecked; ?>>
        <img class="help_tip fooevents-tooltip" title="<?php _e('Removes the event time from calendar event titles.', 'fooevents-calendar'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
    </td>
</tr>
<tr valign="top">
    <th scope="row"><?php _e('Calendar theme', 'fooevents-calendar'); ?></th>
    <td>
        <select name="globalFooEventsCalendarTheme" id="globalFooEventsCalendarTheme">
            <option value="default" <?php echo $globalFooEventsCalendarTheme == 'default' ? "Selected" : "" ?>>Default</option>
            <option value="light" <?php echo $globalFooEventsCalendarTheme == 'light' ? "Selected" : "" ?>>Light</option>
            <option value="dark" <?php echo $globalFooEventsCalendarTheme == 'dark' ? "Selected" : "" ?>>Dark</option>
            <option value="flat" <?php echo $globalFooEventsCalendarTheme == 'flat' ? "Selected" : "" ?>>Flat</option>
            <option value="minimalist" <?php echo $globalFooEventsCalendarTheme == 'minimalist' ? "Selected" : "" ?>>Minimalist</option>
        </select>
        <img class="help_tip fooevents-tooltip" title="<?php _e('Select the calendar theme to be used on your website.', 'fooevents-calendar'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
    </td>
</tr>
<tr valign="top">
    <th scope="row"><?php _e('Events list theme', 'fooevents-calendar'); ?></th>
    <td>
        <select name="globalFooEventsCalendarListTheme" id="globalFooEventsCalendarListTheme">
            <option value="default" <?php echo $globalFooEventsCalendarListTheme == 'default' ? "Selected" : "" ?>>Default</option>
            <option value="light-card" <?php echo $globalFooEventsCalendarListTheme == 'light-card' ? "Selected" : "" ?>>Light Card</option>
            <option value="dark-card" <?php echo $globalFooEventsCalendarListTheme == 'dark-card' ? "Selected" : "" ?>>Dark Card</option>
        </select>
        <img class="help_tip fooevents-tooltip" title="<?php _e('Select the events list theme to be used on your website.', 'fooevents-calendar'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
    </td>
</tr>
<tr valign="top">
    <th scope="row"><?php _e('Associate with post types', 'fooevents-calendar'); ?></th>
    <td>
        <select multiple name="globalFooEventsCalendarPostTypes[]" id="globalFooEventsCalendarPostTypes">
            <?php foreach($post_types as $post_type) :?>
            <option value="<?php echo $post_type; ?>" <?php echo in_array($post_type, $globalFooEventsCalendarPostTypes) || empty($globalFooEventsCalendarPostTypes) ? "Selected" : "" ?>><?php echo $post_type; ?></option>
            <?php endforeach; ?>
        </select>
        <img class="help_tip fooevents-tooltip" title="<?php _e('Select which post types will be associated with events.', 'fooevents-calendar'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
    </td>
</tr>