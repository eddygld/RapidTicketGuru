<tr valign="top">
    <th scope="row" colspan="3"><h3 class="fooevents-settings-section-title"><?php _e('Eventbrite', 'fooevents-calendar'); ?></h3></th>
</tr>
<tr valign="top">
    <th scope="row"><?php _e('Eventbrite private token', 'fooevents-calendar'); ?></th>
    <td>
        <input type="text" name="globalFooEventsEventbriteToken" id="globalFooEventsEventbriteToken" value="<?php echo esc_html($globalFooEventsEventbriteToken); ?>">
        <img class="help_tip fooevents-tooltip" title="<?php _e('Optional API key that is used to add events to your Eventbrite account.', 'fooevents-calendar'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
    </td>
</tr>
<?php if(!empty($globalFooEventsEventbriteToken)) :?>
<tr valign="top">
    <th scope="row"><?php _e('Import Eventbrite events', 'fooevents-calendar'); ?></th>
    <td>
        <a class="button" id="fooevents-eventbrite-import" href="#">Import</a>
        <div id="fooevents-eventbrite-import-output"></div>
    </td>
</tr>
<?php endif; ?>