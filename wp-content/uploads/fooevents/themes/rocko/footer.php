<?php
    if (empty($ticket['type'])) {
        $ticket['type'] = "HTML";
    }
    
    if ($ticket['type'] == "PDF") {
        $ticket['type'] .= "Single";
    }
?>

<?php if ($ticket['type'] == "HTML" || ($ticket['type'] == "PDFMultiple" && $ticket['ticketNumber'] % 3 != 0)) : ?>  
    <?php if(!empty($ticket['FooEventsTicketFooterText'])) :?>
    <!--[if mso]>
    <table style="border-collapse: collapse;" border="0" align="center" width="600" cellpadding="10" bgcolor="#<?php echo $ticket['WooCommerceEventsTicketBackgroundColor']; ?>"><tr><td>
        <p style="padding:0 15px;text-align:center; padding: 10px 0; font-size:12px; color:#ffffff;font-family:  'DejaVu Sans', 'Helvetica', 'sans-serif';"><?php echo $ticket['FooEventsTicketFooterText'];?></p>
    </td></tr></table>
    <![endif]-->
    <!--[if !mso]><!---->
    <div class="footer" style="border-bottom:1px solid #282d33; border-left: 1px solid #282d33; border-right: 1px solid #282d33;max-width: 598px; margin: 0 auto; width: 100%; background-color:<?php echo $ticket['WooCommerceEventsTicketBackgroundColor']; ?>; text-align:center; padding: 10px 0; font-size:12px; color:#ffffff;font-family:  'DejaVu Sans', 'Helvetica', 'sans-serif';">
        <p style="padding:0 15px"><?php echo $ticket['FooEventsTicketFooterText'];?></p>
    </div>
    <br /><br />
    <![endif]-->
    <?php endif; ?>
    <?php if($ticket['name'] == __('Preview Event', 'woocommerce-events')): ?>
    <!--[if mso]>
    <table style="border-collapse: collapse;" border="0" align="center" width="600" cellpadding="10" bgcolor="#<?php echo $ticket['WooCommerceEventsTicketBackgroundColor']; ?>"><tr><td>
        <p style="padding:0 15px;text-align:center; padding: 10px 0; font-size:12px; color:#ffffff;font-family:  'DejaVu Sans', 'Helvetica', 'sans-serif';"><?php _e('Contact us for questions and concerns.', 'woocommerce-events'); ?></p>
    </td></tr></table>
    <![endif]-->
    <!--[if !mso]><!---->
    <div class="footer" style="border-bottom:1px solid #282d33; border-left: 1px solid #282d33; border-right: 1px solid #282d33;max-width: 598px; margin: 0 auto; width: 100%; background-color:<?php echo $ticket['WooCommerceEventsTicketBackgroundColor']; ?>; text-align:center; padding: 10px 0; font-size:12px; color:#ffffff;font-family:  'DejaVu Sans', 'Helvetica', 'sans-serif';">
        <p style="padding:0 15px"><?php _e('Contact us for questions and concerns.', 'woocommerce-events'); ?></p>
    </div>
    <br /><br /><br /><br />
    <![endif]-->
    <?php endif; ?>
    
<?php endif; ?>


</body>