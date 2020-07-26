<img src="<?php echo $themePacksURL.'starter_theme/images/'; ?>ticket.png">
<br><br>
*<?php _e('Ticket details', 'woocommerce-events'); ?>*
<br><br>
<?php if($ticket['WooCommerceEventsTicketDisplayBarcode'] != 'off') :?>
<img src="<?php echo $barcodeURL; ?><?php if($ticket['name'] == __('Preview Event', 'woocommerce-events')) echo $ticket['WooCommerceEventsTicketID']; else echo $ticket['barcodeFileName']; ?>.png">
<br><br>
<?php endif; ?> 
Ticket ID: <?php echo $ticket['WooCommerceEventsTicketID']; ?>
<br>
<?php if($ticket['WooCommerceEventsTicketPurchaserDetails'] != 'off') :?>																
<?php if(!empty($ticket['customerFirstName'])) :?>
Name: <?php echo $ticket['customerFirstName']; ?> <?php echo $ticket['customerLastName']; ?>
<br>
<?php endif; ?> 
<?php if(!empty($ticket['WooCommerceEventsAttendeeTelephone'])) :?>
Telephone: <?php echo $ticket['WooCommerceEventsAttendeeTelephone']; ?>
<br>
<?php endif; ?>
<?php if(!empty($ticket['WooCommerceEventsAttendeeCompany'])) :?>
Company: <?php echo $ticket['WooCommerceEventsAttendeeCompany']; ?>
<br>
<?php endif; ?>
<?php if(!empty($ticket['WooCommerceEventsAttendeeDesignation'])) :?>
Designation: <?php echo $ticket['WooCommerceEventsAttendeeDesignation']; ?>
<br>
<?php endif; ?>
<?php if(!empty($ticket['WooCommerceEventsTicketType'])) :?>
Ticket Type: <?php echo $ticket['WooCommerceEventsTicketType']; ?>
<br>
<?php endif; ?>
<?php if($ticket['WooCommerceEventsTicketDisplayPrice'] != 'off' && $ticket['name'] != __('Preview Event', 'woocommerce-events')) :?>
Price: <?php echo $ticket['WooCommerceEventsPrice']; ?>
<br>
<?php endif; ?>   
<?php if(!empty($ticket['WooCommerceEventsVariations'])) : ?>
Variations:
<br>
<?php foreach($ticket['WooCommerceEventsVariations'] as $variationName => $variationValue) :?>
<?php 
	$variationNameOutput = str_replace('attribute_', '', $variationName);
	$variationNameOutput = str_replace('pa_', '', $variationNameOutput);
	$variationNameOutput = str_replace('_', ' ', $variationNameOutput);
	$variationNameOutput = str_replace('-', ' ', $variationNameOutput);
	$variationNameOutput = str_replace('Pa_', '', $variationNameOutput);
	$variationNameOutput = ucwords($variationNameOutput);
	$variationValueOutput = str_replace('_', ' ', $variationValue);
	$variationValueOutput = str_replace('-', ' ', $variationValueOutput);
	$variationValueOutput = ucwords($variationValueOutput);
?>
<?php if($variationNameOutput != 'Ticket Type') :?>
<?php echo $variationNameOutput . $variationValueOutput; ?>
<?php endif; ?>
<?php endforeach; ?>        
<br>
<?php endif; ?>    
<?php if(!empty($ticket['fooevents_custom_attendee_fields_options']) && $ticket['name'] != __('Preview Event', 'woocommerce-events')) :
if($ticket['WooCommerceEventsIncludeCustomAttendeeDetails'] != 'off') : ?>
Custom fields:<br>- <?php echo $ticket['fooevents_custom_attendee_fields_options']; ?><br>
<?php endif; endif; ?>    
<?php if(!empty($ticket['fooevents_custom_attendee_fields_options']) && $ticket['name'] != __('Preview Event', 'woocommerce-events')) :?>
Seats:<br>- <?php echo $ticket['fooevents_seating_options']; ?><br>
<?php endif; ?>
<?php endif; ?> 
<br><br>
...........................................................................................................................................................................
<br><br>