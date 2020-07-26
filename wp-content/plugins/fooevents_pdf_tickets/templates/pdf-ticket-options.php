<p><b><?php _e('PDF settings', 'woocommerce-events'); ?></b></p>
<div class="options_group">
    <div style="padding-left: 30px; padding-right: 30px;">
        <p class="form-field">
           <label><?php _e('Email text:', 'fooevents-pdf-tickets'); ?></label>
           <?php wp_editor( $FooEventsPDFTicketsEmailText, 'FooEventsPDFTicketsEmailText' ); ?>
        </p>
    </div>
</div>
<div class="options_group">
    <p class="form-field">
       <label><?php _e('Ticket footer text:', 'fooevents-pdf-tickets'); ?></label>
       <textarea name="FooEventsTicketFooterText" id="FooEventsTicketFooterText"><?php echo esc_attr($FooEventsTicketFooterText); ?></textarea>
    </p>
</div>