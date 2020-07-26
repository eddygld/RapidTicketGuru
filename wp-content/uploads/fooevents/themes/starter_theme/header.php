**<?php echo $ticket['name'] ?>**
<br><br>
<?php if(!empty($ticket['WooCommerceEventsTicketLogo'])) :?>                    
<img style="display:block;" src="<?php echo $ticket['WooCommerceEventsTicketLogo']; ?>" width="200" style="width: 200px;" />
<?php endif; ?>
<br><br>
<?php if (!empty($ticket['WooCommerceEventsTicketHeaderImage']) && $ticket['name'] != __('Preview Event', 'woocommerce-events')) : ?>
<img style="display:block;" src="<?php echo $ticket['WooCommerceEventsTicketHeaderImage']; ?>" width="500" style="width: 500px;" />
<?php endif; ?>

<br><br>
<?php _e('These are your tickets for', 'woocommerce-events'); ?> <?php echo $ticket['name'] ?>
<br><br>
<?php if(!empty($ticket['WooCommerceEventsTicketText'])) : ?>
	<?php echo nl2br($ticket['WooCommerceEventsTicketText']); ?>
<?php endif; ?>
<?php if((!empty($ticket['WooCommerceEventsTicketDisplayZoom']) && $ticket['WooCommerceEventsTicketDisplayZoom'] != 'off' && !empty($ticket['WooCommerceEventsZoomText']))) : ?>
	<?php echo nl2br($ticket['WooCommerceEventsZoomText']); ?>
<?php endif; ?>
<br><br>
<?php if((!empty($ticket['WooCommerceEventsTicketExtraInfoLink']))) :?>
	<?php echo $ticket['WooCommerceEventsTicketExtraInfoText']; ?> [<?php echo $ticket['WooCommerceEventsTicketExtraInfoLink']; ?>]
<?php endif; ?>
<br><br>
<?php if((!empty($ticket['WooCommerceEventsLocation']))) :?>
Location: <?php echo $ticket['WooCommerceEventsLocation']; ?>
<?php endif; ?>
<br><br>
<?php if(!empty($ticket['WooCommerceEventsDirections'])) :?>
Directions: <?php echo $ticket['WooCommerceEventsDirections']; ?>
<?php endif; ?>
<br><br>
<?php if($ticket['WooCommerceEventsTicketDisplayDateTime'] != 'off') :?>
Date: <?php echo $ticket['WooCommerceEventsDate']; if(!empty($ticket['WooCommerceEventsEndDate'])) : echo " - " . $ticket['WooCommerceEventsEndDate']; endif; ?>
<br><br>

	Time: <?php echo $ticket['WooCommerceEventsHour']; ?>:<?php echo $ticket['WooCommerceEventsMinutes']; ?><?php echo (!empty($ticket['WooCommerceEventsPeriod']))? $ticket['WooCommerceEventsPeriod'] : '' ?>
	<?php if($ticket['WooCommerceEventsHourEnd'] != '00') : ?>
		- <?php echo $ticket['WooCommerceEventsHourEnd']; ?>:<?php echo $ticket['WooCommerceEventsMinutesEnd']; ?><?php echo (!empty($ticket['WooCommerceEventsEndPeriod']))? $ticket['WooCommerceEventsEndPeriod'] : '' ?>
    <?php endif; ?>
    <?php echo (!empty($ticket['WooCommerceEventsTimeZone']))? " " . $ticket['WooCommerceEventsTimeZone'] : '' ?>
<?php endif; ?>
<br><br>
<?php if($ticket['WooCommerceEventsTicketAddCalendar'] != 'off') :?>
<a href="<?php echo site_url(); ?>/wp-admin/admin-ajax.php?action=fooevents_ics&event=<?php echo $ticket['WooCommerceEventsProductID']; ?>"><?php _e('Add to calendar', 'woocommerce-events'); ?></a>
<?php endif; ?>
<br><br>
...........................................................................................................................................................................
<br><br>