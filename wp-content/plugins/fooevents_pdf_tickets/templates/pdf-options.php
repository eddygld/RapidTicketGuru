<?php settings_fields('fooevents-settings-pdf'); ?>
<?php do_settings_sections('fooevents-settings-pdf'); ?>
<tr valign="top">
    <th scope="row"><h2><?php _e('PDF Tickets', 'woocommerce-events'); ?></h2></th>
    <td></td>
    <td></td>
</tr>
<tr valign="top">
    <th scope="row"><?php _e('Enable PDF tickets', 'fooevents-pdf-tickets'); ?></th>
    <td>
        <input type="checkbox" name="globalFooEventsPDFTicketsEnable" id="globalFooEventsPDFTicketsEnable" value="yes" <?php echo ($globalFooEventsPDFTicketsEnable == 'yes') ? 'CHECKED' : ''; ?>>
        <img class="help_tip fooevents-tooltip" title="<?php _e('Adds PDF ticket attachments to ticket emails.', 'fooevents-pdf-tickets'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
    </td>
</tr> 
<tr valign="top">
    <th scope="row"><?php _e('Enable PDF ticket downloads', 'fooevents-pdf-tickets'); ?></th>
    <td>
        <input type="checkbox" name="globalFooEventsPDFTicketsDownloads" id="globalFooEventsPDFTicketsDownloads" value="yes" <?php echo ($globalFooEventsPDFTicketsDownloads == 'yes') ? 'CHECKED' : ''; ?>>
        <img class="help_tip fooevents-tooltip" title="<?php _e('Allows purchasers to download a copy of their PDF tickets from the My Account page.', 'fooevents-pdf-tickets'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
    </td>
</tr> 
<tr valign="top">
    <th scope="row"><?php _e('Attach PDF ticket to HTML ticket email', 'fooevents-pdf-tickets'); ?></th>
    <td>
        <input type="checkbox" name="globalFooEventsPDFTicketsAttachHTMLTicket" id="globalFooEventsPDFTicketsAttachHTMLTicket" value="yes" <?php echo ($globalFooEventsPDFTicketsAttachHTMLTicket == 'yes') ? 'CHECKED' : ''; ?>>
        <img class="help_tip fooevents-tooltip" title="<?php _e('Attaches the PDF ticket to the HTML ticket email when sent.', 'fooevents-pdf-tickets'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
    </td>
</tr>
<tr valign="top">
    <th scope="row"><?php _e('Font', 'fooevents-pdf-tickets'); ?></th>
    <td>
        <select name="globalFooEventsPDFTicketsFont" id="globalFooEventsPDFTicketsFont">
            <option value="DejaVu Sans" <?php echo $globalFooEventsPDFTicketsFont == 'DejaVu Sans' ? "Selected" : "" ?>>DejaVu Sans</option>
            <option value="Firefly Sung" <?php echo $globalFooEventsPDFTicketsFont == 'Firefly Sung' ? "Selected" : "" ?>>Firefly Sung</option>
        </select>
        <img class="help_tip fooevents-tooltip" title="<?php _e('DejaVu Sans is the default PDF font. Firefly Sung supports CJK (Chinese, Japanese, Korean) characters', 'fooevents-pdf-tickets'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
    </td>
</tr>