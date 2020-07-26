<div id="fooevents_exports" class="panel woocommerce_options_panel">
    <p><h2><b><?php _e('Event Export', 'woocommerce-events'); ?></b></h2></p>
    <?php if(!empty($post->ID)) :?>
        <div class="options_group">
            <p><b><?php _e('Export attendees', 'woocommerce-events'); ?></b></p>
            <div id="WooCommerceEventsExportMessage"></div>
            <p class="form-field">
                <label><?php _e('Include unpaid tickets:', 'woocommerce-events'); ?></label><input type="checkbox" id="WooCommerceEventsExportUnpaidTicketsExport" name="WooCommerceEventsExportUnpaidTickets" value="on" <?php echo ($WooCommerceEventsExportUnpaidTickets == 'on')? 'CHECKED' : ''; ?>> <img class="help_tip" data-tip="<?php _e('Include unpaid tickets in exported file.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" /><br />
                <label><?php _e('Include billing details:', 'woocommerce-events'); ?></label><input type="checkbox" id="WooCommerceEventsExportBillingDetailsExport" name="WooCommerceEventsExportBillingDetails" value="on" <?php echo ($WooCommerceEventsExportBillingDetails == 'on')? 'CHECKED' : ''; ?>> <img class="help_tip" data-tip="<?php _e('Include billing details in exported file.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" /><br /><br />
                <a href="<?php echo site_url(); ?>/wp-admin/admin-ajax.php?action=woocommerce_events_csv&event=<?php echo $post->ID; ?><?php echo ($WooCommerceEventsExportUnpaidTickets == 'on')? '&exportunpaidtickets=true' : ''; ?><?php echo ($WooCommerceEventsExportBillingDetails == 'on')? '&exportbillingdetails=true' : ''; ?>" class="button" target="_BLANK"><?php _e('Download CSV of attendees', 'woocommerce-events'); ?></a>
            </p>
        </div>
        <?php endif; ?>
</div>