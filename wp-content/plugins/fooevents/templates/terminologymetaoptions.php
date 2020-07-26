<div id="fooevents_terminology" class="panel woocommerce_options_panel">
    <div class="options_group">
        <p><h2><b><?php _e('Event Terminology', 'woocommerce-events'); ?></b></h2></p>
        <p class="form-field fooevents-custom-text-inputs">
            <span><?php _e('Singular', 'woocommerce-events'); ?></span>
            <span><?php _e('Plural', 'woocommerce-events'); ?></span>
        </p>
        <p class="form-field fooevents-custom-text-inputs">
            <label><?php _e('Attendee:', 'woocommerce-events'); ?></label>
            <input type="text" id="WooCommerceEventsAttendeeOverride" name="WooCommerceEventsAttendeeOverride" value="<?php echo esc_attr($WooCommerceEventsAttendeeOverride); ?>"/>
            <input type="text" id="WooCommerceEventsAttendeeOverridePlural" name="WooCommerceEventsAttendeeOverridePlural" value="<?php echo esc_attr($WooCommerceEventsAttendeeOverridePlural); ?>"/>
            <img class="help_tip" data-tip="<?php _e("Change 'Attendee' to your own custom text for this event.", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
        </p>
        <p class="form-field fooevents-custom-text-inputs">
            <label><?php _e('Book ticket:', 'woocommerce-events'); ?></label>
            <input type="text" id="WooCommerceEventsTicketOverride" name="WooCommerceEventsTicketOverride" value="<?php echo esc_attr($WooCommerceEventsTicketOverride); ?>"/>
            <input type="text" id="WooCommerceEventsTicketOverridePlural" name="WooCommerceEventsTicketOverridePlural" value="<?php echo esc_attr($WooCommerceEventsTicketOverridePlural); ?>"/>
            <img class="help_tip" data-tip="<?php _e("Change 'Book ticket' to your own custom text for this event.", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
        </p>
        <?php echo $multidayTerm; ?>
    </div>
</div>