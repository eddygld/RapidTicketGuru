<tr>
    <td><strong><a class="row-title" href="<?php echo esc_url(admin_url()); ?>post.php?post=<?php echo $ticket->ID; ?>&action=edit" aria-label="<?php echo $ticket->post_title; ?> (Edit)" target="_BLANK"><?php echo $ticket->post_title; ?></a></strong></td>
    <td><strong><a class="row-title" href="<?php echo esc_url(admin_url()); ?>post.php?post=<?php echo get_post_meta($ticket->ID, 'WooCommerceEventsOrderID', true); ?>&action=edit" aria-label="<?php echo $ticket->post_title; ?> (Edit)" target="_BLANK"><?php echo get_post_meta($ticket->ID, 'WooCommerceEventsOrderID', true); ?></a></strong></td>
    <td><span class="fooevents-express-check-in-mobile"><?php echo __( 'Purchaser', 'fooevents-express-check-in' ); ?>: </span><?php echo get_post_meta($ticket->ID, 'WooCommerceEventsPurchaserFirstName', true); ?> <?php echo get_post_meta($ticket->ID, 'WooCommerceEventsPurchaserLastName', true); ?> </td>
    <td><span class="fooevents-express-check-in-mobile"><?php echo __( 'Attendee', 'fooevents-express-check-in' ); ?>: </span><?php echo get_post_meta($ticket->ID, 'WooCommerceEventsAttendeeName', true); ?> <?php echo get_post_meta($ticket->ID, 'WooCommerceEventsAttendeeLastName', true); ?></td>
    <td><span class="fooevents-express-check-in-event-name"><?php echo(!empty($event->post_title))? $event->post_title : '';  ?></span></td>
    <td>
        <?php if(is_array($ticketVariations)) : ?>
            <?php foreach($ticketVariations as $variation_name => $variation_value) :?>
                <b><?php echo $variation_name;  ?></b>: <?php echo $variation_value;  ?><br />
            <?php endforeach;    ?>
        <?php endif; ?>
    </td>
    <td><span id="fooevents-express-check-in-status-<?php echo $ticket->ID; ?>" class="fooevents-express-check-in-status fooevents-express-check-in-status-<?php echo $ticket_status_class; ?>"><?php echo $ticket_status; ?></span></td>
    <td class="fooevents-express-check-in-button-td"><button id="fooevents-express-check-in-cancel-<?php echo $ticket->ID; ?>" class="button button-secondary fooevents-express-check-in-control fooevents-express-check-in-cancel"><?php echo __( 'Cancel', 'fooevents-express-check-in' ); ?></button></td>
    <td class="fooevents-express-check-in-button-td"><button id="fooevents-express-check-in-reset-<?php echo $ticket->ID; ?>" class="button fooevents-express-check-in-control fooevents-express-check-in-reset">Reset</button></td>
    <td class="fooevents-express-check-in-button-td"><button id="fooevents-express-check-in-confirm-<?php echo $ticket->ID; ?>" class="button <?php echo ($ticket_status != 'Checked In')? 'button-primary' : ''; ?>  fooevents-express-check-in-control fooevents-express-check-in-confirm"><?php echo __( 'Confirm', 'fooevents-express-check-in' ); ?></button></td>
</tr>