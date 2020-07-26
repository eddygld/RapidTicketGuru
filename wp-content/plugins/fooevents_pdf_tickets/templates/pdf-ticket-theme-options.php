<div class="options_group">
    <p class="form-field">
    <label><?php _e('PDF ticket theme:', 'woocommerce-events'); ?></label>
    <select name="WooCommerceEventsPDFTicketTheme" id="WooCommerceEventsPDFTicketTheme">
        <?php foreach($themes as $theme => $theme_details) :?>
            <option value="<?php echo $theme_details['path']; ?>" <?php echo ($WooCommerceEventsPDFTicketTheme == $theme_details['path'])? 'SELECTED' : '' ?>><?php echo $theme_details['name']; ?></option>
        <?php endforeach; ?>
    </select>
    <img class="help_tip" data-tip="<?php _e('Select the PDF compatible ticket theme that will be used to style the PDF tickets that are attached to ticket emails.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />   
    </p> 
</div>