<h1><?php _e( 'Tickets', 'fooevents-pdf-tickets' ); ?></h1>
<table>
<?php foreach($tickets as $ticket) :?>
    <?php $productID = get_post_meta($ticket->ID, 'WooCommerceEventsProductID', true); ?>  
    <?php $WooCommerceEventsTicketID = get_post_meta($ticket->ID, 'WooCommerceEventsTicketID', true); ?>
    <?php $WooCommerceEventsTicketHash = get_post_meta($ticket->ID, 'WooCommerceEventsTicketHash', true); ?>
    <?php $path = ''; ?>
    <?php if(!empty($WooCommerceEventsTicketHash)): ?>
        <?php $path = $this->Config->pdfTicketURL.$WooCommerceEventsTicketHash.'-'.$WooCommerceEventsTicketID.'-'.$WooCommerceEventsTicketHash.'-'.$WooCommerceEventsTicketID.'.pdf'; ?>
    <?php else: ?>
        <?php $path = $this->Config->pdfTicketURL.$WooCommerceEventsTicketID.'-'.$WooCommerceEventsTicketID.'.pdf'; ?>
    <?php endif; ?>
    <tr>
        <td><?php echo $ticket->post_title; ?></td>
        <td><?php echo get_the_title($productID); ?></td>
        <td><a href="<?php echo $path; ?>" target="_BLANK"><?php _e( 'Download', 'fooevents-pdf-tickets' ); ?></a></td>
    </tr>
<?php endforeach; ?>
</table>