<div id="fooevents_printing" class="panel woocommerce_options_panel">
    <p><h2><b><?php _e('Stationery Builder', 'woocommerce-events'); ?></b></h2></p>
    <div id="WooCommercePrintTicketMessage"></div>
    <div class="fooevents_printing_container">
        <div class="fooevents_printing_container_inner" id="fooevents_printing_container_inner_left">
            <p class="form-field">
                <label><?php _e('Format:', 'woocommerce-events'); ?></label>
                <select name="WooCommercePrintTicketSize" id="WooCommercePrintTicketSize">
                    <optgroup label="Tickets">
                        <option value="tickets_avery_letter_10"<?php echo ($WooCommercePrintTicketSize == 'tickets_avery_letter_10')? 'SELECTED' : ''; ?>><?php _e("10 tickets per sheet (Letter size)", 'woocommerce-events'); ?></option>
                        <option value="tickets_letter_10"<?php echo ($WooCommercePrintTicketSize == 'tickets_letter_10')? 'SELECTED' : ''; ?>><?php _e("10 tickets per sheet 5.5in x 1.75in (Avery 16154 Tickets Letter size)", 'woocommerce-events'); ?></option>
                        <option value="tickets_a4_10"<?php echo ($WooCommercePrintTicketSize == 'tickets_a4_10')? 'SELECTED' : ''; ?>><?php _e("10 tickets per sheet (A4 size)", 'woocommerce-events'); ?></option>
                    </optgroup>
                    <br />
                    <optgroup label="Badges">
                        <option value="letter_6"<?php echo ($WooCommercePrintTicketSize == 'letter_6')? 'SELECTED' : ''; ?>><?php _e('6 badges per sheet 4in x 3in (Avery 5392/5393 Letter size)', 'woocommerce-events'); ?></option>
                        <option value="letter_10"<?php echo ($WooCommercePrintTicketSize == 'letter_10')? 'SELECTED' : ''; ?>><?php _e('10 badges per sheet 4.025in x 2in (Avery 5163/8163 Letter size)', 'woocommerce-events'); ?></option>
                        <option value="a4_12" <?php echo ($WooCommercePrintTicketSize == 'a4_12')? 'SELECTED' : ''; ?>><?php _e("12 badges per sheet 63.5mm x 72mm (Microsoft W233 A4 size)", 'woocommerce-events'); ?></option>
                        <option value="a4_16" <?php echo ($WooCommercePrintTicketSize == 'a4_16')? 'SELECTED' : ''; ?>><?php _e("16 badges per sheet 99mm x 33.9mm (Microsoft W121 A4 size)", 'woocommerce-events'); ?></option>
                        <option value="a4_24" <?php echo ($WooCommercePrintTicketSize == 'a4_24')? 'SELECTED' : ''; ?>><?php _e("24 badges per sheet 35mm x 70mm (Microsoft W110 A4 size)", 'woocommerce-events'); ?></option>
                        <option value="letter_30" <?php echo ($WooCommercePrintTicketSize == 'letter_30')? 'SELECTED' : ''; ?>><?php _e("30 badges per sheet 2.625in x 1in (Avery 5160/8160 Letter size)", 'woocommerce-events'); ?></option>
                        <option value="a4_39" <?php echo ($WooCommercePrintTicketSize == 'a4_39')? 'SELECTED' : ''; ?>><?php _e("39 badges per sheet 66mm x 20.60mm (Microsoft W239 A4 size)", 'woocommerce-events'); ?></option>
                        <option value="a4_45" <?php echo ($WooCommercePrintTicketSize == 'a4_45')? 'SELECTED' : ''; ?>><?php _e("45 badges per sheet 38.5mm x 29.9mm (Microsoft W115 A4 size)", 'woocommerce-events'); ?></option>
                    </optgroup>
                    <optgroup label="Wraparound Labels/Wristbands">
                        <option value="letter_labels_5"<?php echo ($WooCommercePrintTicketSize == 'letter_labels_5')? 'SELECTED' : ''; ?>><?php _e('5 labels per sheet 9-3/4" x 1-1/4" (Avery 22845 Letter size)', 'woocommerce-events'); ?></option>
                    </optgroup>
                </select>
                <img class="help_tip" data-tip="<?php _e('The number of items to print per sheet as well as the page format.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
            </p>
            <p class="form-field">
                <label><?php _e('Number of columns:', 'woocommerce-events'); ?></label>
                <input type="number" min="1" max="3" id="WooCommercePrintTicketNrColumns" name="WooCommercePrintTicketNrColumns" value="<?php echo (empty($WooCommercePrintTicketNrColumns))? '3' : $WooCommercePrintTicketNrColumns; ?>" >
                <img class="help_tip" data-tip="<?php _e('The number of columns to display in the stationery layout area. The recommended number of columns will be set by default but this can be adjusted manually.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
            </p>
            <p class="form-field">
                <label><?php _e('Number of rows:', 'woocommerce-events'); ?></label>
                <input type="number" min="1" max="3" id="WooCommercePrintTicketNrRows" name="WooCommercePrintTicketNrRows" value="<?php echo (empty($WooCommercePrintTicketNrRows))? '3' : $WooCommercePrintTicketNrRows; ?>" >
                <img class="help_tip" data-tip="<?php _e('The number of rows to display in the stationery layout area. The recommended number of rows will be set by default but this can be adjusted manually.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
            </p>
            <p class="form-field">                 
                <label><?php _e('Include cut lines?', 'woocommerce-events'); ?></label>
                <input type="checkbox" name="WooCommerceEventsCutLinesPrintTicket" id="WooCommerceEventsCutLinesPrintTicket" <?php echo (empty($WooCommerceEventsCutLinesPrintTicket) || $WooCommerceEventsCutLinesPrintTicket == 'on')? ' checked="checked"' : ''; ?>>
                <img class="help_tip" data-tip="<?php _e('Display ticket cut lines on page.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />        
                <br /><br />       <br />    
            </p>
        </div>
        <div class="fooevents_printing_container_inner" id="fooevents_printing_container_inner_right">
            <p class="form-field">                 
                <label><?php _e('Include all attendees', 'woocommerce-events'); ?></label>
                <input type="checkbox" name="WooCommerceEventsPrintAllTickets" id="WooCommerceEventsPrintAllTickets">
                <img class="help_tip" data-tip="<?php _e('Include all the attendees for this event in the selected stationery.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
            </p>
            <p class="form-field">
                <label><?php _e('Specific ticket number(s):', 'woocommerce-events'); ?></label>
                <input type="text" class="short" style="" name="WooCommercePrintTicketNumbers" id="WooCommercePrintTicketNumbers" value="<?php echo $WooCommercePrintTicketNumbers; ?>">
                <img class="help_tip" data-tip="<?php _e('Enter the ticket number(s) that will be used to populate the selected stationery, separated by commas (,). If both the ticket number and order number fields are empty, then all the attendees for this event will be included.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
            </p>
            <p class="form-field">
                <label><?php _e('Specific order number(s):', 'woocommerce-events'); ?></label>
                <input type="text" class="short" style="" name="WooCommercePrintTicketOrders" id="WooCommercePrintTicketOrders" value="<?php echo $WooCommercePrintTicketOrders; ?>">
                <img class="help_tip" data-tip="<?php _e('Enter the order number(s) that will be used to populate the selected stationery, separated by commas (,). If both the ticket number and order number fields are empty, then all the attendees for this event will be included.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
            </p>
            <p class="form-field">
                <label><?php _e('Sort order:', 'woocommerce-events'); ?></label>
                <select name="WooCommercePrintTicketSort" id="WooCommercePrintTicketSort">
                    <option value="most_recent"<?php echo ($WooCommercePrintTicketSort == 'most_recent')? 'SELECTED' : ''; ?>><?php _e("Most recent tickets first", 'woocommerce-events'); ?></option>
                    <option value="oldest"<?php echo ($WooCommercePrintTicketSort == 'oldest')? 'SELECTED' : ''; ?>><?php _e("Oldest tickets first", 'woocommerce-events'); ?></option>
                    <option value="a_z1"<?php echo ($WooCommercePrintTicketSort == 'a_z1')? 'SELECTED' : ''; ?>><?php _e("Alphabetical by Attendee First Name", 'woocommerce-events'); ?></option>
                    <option value="a_z2"<?php echo ($WooCommercePrintTicketSort == 'a_z2')? 'SELECTED' : ''; ?>><?php _e("Alphabetical by Attendee Last Name", 'woocommerce-events'); ?></option>
                </select>
                <img class="help_tip" data-tip="<?php _e('Choose the sort order for how the selected stationery will be arranged when printed.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
            </p>
        </div>    
        <div class="clearfix"></div>
    </div>
    <?php if(!empty($post->ID)) :?>
    
    <button type="button" class="button-primary" id="fooevents-add-printing-widgets"><?php _e('+ Expand Fields', 'woocommerce-events'); ?></button>
    <div id="fooevents_printing_widgets">
        <h3>General Fields</h3>
        <div>
            <div class="fooevents_printing_widget fooevents_printing_widget_init" data-order="1">
                <span data-name="logo"><?php _e('Logo/Image', 'woocommerce-events'); ?><span class="fooevents_printing_arrow fooevents_printing_arrow_closed"></span></span>
                <div class="fooevents_printing_widget_options">
                    <input id="WooCommerceEventsPrintTicketLogo" class="text uploadfield" type="text" size="40" name="WooCommerceEventsPrintTicketLogo" value="" />				
                    <span class="uploadbox">
                        <input class="upload_image_button_woocommerce_events button" type="button" value="Upload file" />
                        <img class="help_tip" data-tip="<?php _e('Select the logo or other image that you would like to display in tickets.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                        <div class="clearfix"></div>
                    </span>
                    <a href="#" class="upload_reset"><?php _e('Clear', 'woocommerce-events'); ?></a><span> | </span>
                    <a href="javascript:void(0);" class="fooevents_printing_widget_remove" name="fooevents_printing_widget_remove">Delete</a>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="fooevents_printing_widget fooevents_printing_widget_init" data-order="2">
                <span data-name="custom"><?php _e('Custom Text', 'woocommerce-events'); ?><span class="fooevents_printing_arrow fooevents_printing_arrow_closed"></span></span>	
                <div class="fooevents_printing_widget_options">
                    <textarea name="WooCommerceEventsPrintTicketCustom" id="WooCommerceEventsPrintTicketCustom"></textarea>
                    <select class="fooevents_printing_ticket_select">
                        <option value="small"><?php _e("Small regular text", 'woocommerce-events'); ?></option>
                        <option value="small_uppercase"><?php _e("Small uppercase text", 'woocommerce-events'); ?></option>
                        <option value="medium"><?php _e("Medium regular text", 'woocommerce-events'); ?></option>
                        <option value="medium_uppercase"><?php _e("Medium uppercase text", 'woocommerce-events'); ?></option>
                        <option value="large"><?php _e("Large regular text", 'woocommerce-events'); ?></option>       
                        <option value="large_uppercase"><?php _e("Large uppercase text", 'woocommerce-events'); ?></option>       
                    </select>
                    <a href="javascript:void(0);" class="fooevents_printing_widget_remove" name="fooevents_printing_widget_remove">Delete</a>  
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="fooevents_printing_widget fooevents_printing_widget_init" data-order="3">
                <span data-name="spacer"><?php _e('Empty Spacer', 'woocommerce-events'); ?><span class="fooevents_printing_arrow fooevents_printing_arrow_closed"></span></span>
                <div class="fooevents_printing_widget_options">
                    <a href="javascript:void(0);" class="fooevents_printing_widget_remove" name="fooevents_printing_widget_remove">Delete</a>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <h3>Event Fields</h3>
        <div>
            <div class="fooevents_printing_widget fooevents_printing_widget_init" data-order="4">
                <span data-name="event"><?php _e('Event Name Only', 'woocommerce-events'); ?><span class="fooevents_printing_arrow fooevents_printing_arrow_closed"></span></span>
                <div class="fooevents_printing_widget_options">
                    <select class="fooevents_printing_ticket_select">
                        <option value="small"><?php _e("Small regular text", 'woocommerce-events'); ?></option>
                        <option value="small_uppercase"><?php _e("Small uppercase text", 'woocommerce-events'); ?></option>
                        <option value="medium"><?php _e("Medium regular text", 'woocommerce-events'); ?></option>
                        <option value="medium_uppercase"><?php _e("Medium uppercase text", 'woocommerce-events'); ?></option>
                        <option value="large"><?php _e("Large regular text", 'woocommerce-events'); ?></option>       
                        <option value="large_uppercase"><?php _e("Large uppercase text", 'woocommerce-events'); ?></option>       
                    </select>
                    <a href="javascript:void(0);" class="fooevents_printing_widget_remove" name="fooevents_printing_widget_remove">Delete</a>   
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="fooevents_printing_widget fooevents_printing_widget_init" data-order="5">
                <span data-name="event_var"><?php _e('Event Name/Variation', 'woocommerce-events'); ?><span class="fooevents_printing_arrow fooevents_printing_arrow_closed"></span></span>
                <div class="fooevents_printing_widget_options">
                    <select class="fooevents_printing_ticket_select">
                        <option value="small"><?php _e("Small regular text", 'woocommerce-events'); ?></option>
                        <option value="small_uppercase"><?php _e("Small uppercase text", 'woocommerce-events'); ?></option>
                        <option value="medium"><?php _e("Medium regular text", 'woocommerce-events'); ?></option>
                        <option value="medium_uppercase"><?php _e("Medium uppercase text", 'woocommerce-events'); ?></option>
                        <option value="large"><?php _e("Large regular text", 'woocommerce-events'); ?></option>       
                        <option value="large_uppercase"><?php _e("Large uppercase text", 'woocommerce-events'); ?></option>       
                    </select>
                    <a href="javascript:void(0);" class="fooevents_printing_widget_remove" name="fooevents_printing_widget_remove">Delete</a>  
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="fooevents_printing_widget fooevents_printing_widget_init" data-order="6">
                <span data-name="var_only"><?php _e('Variation Only', 'woocommerce-events'); ?><span class="fooevents_printing_arrow fooevents_printing_arrow_closed"></span></span>
                <div class="fooevents_printing_widget_options">
                    <select class="fooevents_printing_ticket_select">
                        <option value="small"><?php _e("Small regular text", 'woocommerce-events'); ?></option>
                        <option value="small_uppercase"><?php _e("Small uppercase text", 'woocommerce-events'); ?></option>
                        <option value="medium"><?php _e("Medium regular text", 'woocommerce-events'); ?></option>
                        <option value="medium_uppercase"><?php _e("Medium uppercase text", 'woocommerce-events'); ?></option>
                        <option value="large"><?php _e("Large regular text", 'woocommerce-events'); ?></option>       
                        <option value="large_uppercase"><?php _e("Large uppercase text", 'woocommerce-events'); ?></option>       
                    </select>
                    <a href="javascript:void(0);" class="fooevents_printing_widget_remove" name="fooevents_printing_widget_remove">Delete</a> 
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="fooevents_printing_widget fooevents_printing_widget_init" data-order="7">
                <span data-name="location"><?php _e('Event Location', 'woocommerce-events'); ?><span class="fooevents_printing_arrow fooevents_printing_arrow_closed"></span></span>
                <div class="fooevents_printing_widget_options">
                    <select class="fooevents_printing_ticket_select">
                        <option value="small"><?php _e("Small regular text", 'woocommerce-events'); ?></option>
                        <option value="small_uppercase"><?php _e("Small uppercase text", 'woocommerce-events'); ?></option>
                        <option value="medium"><?php _e("Medium regular text", 'woocommerce-events'); ?></option>
                        <option value="medium_uppercase"><?php _e("Medium uppercase text", 'woocommerce-events'); ?></option>
                        <option value="large"><?php _e("Large regular text", 'woocommerce-events'); ?></option>       
                        <option value="large_uppercase"><?php _e("Large uppercase text", 'woocommerce-events'); ?></option>       
                    </select>
                    <a href="javascript:void(0);" class="fooevents_printing_widget_remove" name="fooevents_printing_widget_remove">Delete</a>  
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <h3>Ticket Fields</h3>
        <div>
            <div class="fooevents_printing_widget fooevents_printing_widget_init" data-order="8">
                <span data-name="barcode"><?php _e('Barcode', 'woocommerce-events'); ?><span class="fooevents_printing_arrow fooevents_printing_arrow_closed"></span></span>
                <div class="fooevents_printing_widget_options">
                    <a href="javascript:void(0);" class="fooevents_printing_widget_remove" name="fooevents_printing_widget_remove">Delete</a>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="fooevents_printing_widget fooevents_printing_widget_init" data-order="9">
                <span data-name="ticketnr"><?php _e('Ticket Number', 'woocommerce-events'); ?><span class="fooevents_printing_arrow fooevents_printing_arrow_closed"></span></span>
                <div class="fooevents_printing_widget_options">
                    <select class="fooevents_printing_ticket_select">
                        <option value="small"><?php _e("Small regular text", 'woocommerce-events'); ?></option>
                        <option value="small_uppercase"><?php _e("Small uppercase text", 'woocommerce-events'); ?></option>
                        <option value="medium"><?php _e("Medium regular text", 'woocommerce-events'); ?></option>
                        <option value="medium_uppercase"><?php _e("Medium uppercase text", 'woocommerce-events'); ?></option>
                        <option value="large"><?php _e("Large regular text", 'woocommerce-events'); ?></option>       
                        <option value="large_uppercase"><?php _e("Large uppercase text", 'woocommerce-events'); ?></option>       
                    </select>
                    <a href="javascript:void(0);" class="fooevents_printing_widget_remove" name="fooevents_printing_widget_remove">Delete</a> 
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <h3>Attendee Fields</h3>
        <div>
            <div class="fooevents_printing_widget fooevents_printing_widget_init" data-order="10">
                <span data-name="name"><?php _e('Attendee Name', 'woocommerce-events'); ?><span class="fooevents_printing_arrow fooevents_printing_arrow_closed"></span></span>
                <div class="fooevents_printing_widget_options">
                    <select class="fooevents_printing_ticket_select">
                        <option value="small"><?php _e("Small regular text", 'woocommerce-events'); ?></option>
                        <option value="small_uppercase"><?php _e("Small uppercase text", 'woocommerce-events'); ?></option>
                        <option value="medium"><?php _e("Medium regular text", 'woocommerce-events'); ?></option>
                        <option value="medium_uppercase"><?php _e("Medium uppercase text", 'woocommerce-events'); ?></option>
                        <option value="large"><?php _e("Large regular text", 'woocommerce-events'); ?></option>       
                        <option value="large_uppercase"><?php _e("Large uppercase text", 'woocommerce-events'); ?></option>       
                    </select>
                    <a href="javascript:void(0);" class="fooevents_printing_widget_remove" name="fooevents_printing_widget_remove">Delete</a>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="fooevents_printing_widget fooevents_printing_widget_init" data-order="11">
                <span data-name="email"><?php _e('Attendee Email', 'woocommerce-events'); ?><span class="fooevents_printing_arrow fooevents_printing_arrow_closed"></span></span>
                <div class="fooevents_printing_widget_options">
                    <select class="fooevents_printing_ticket_select">
                        <option value="small"><?php _e("Small regular text", 'woocommerce-events'); ?></option>
                        <option value="small_uppercase"><?php _e("Small uppercase text", 'woocommerce-events'); ?></option>
                        <option value="medium"><?php _e("Medium regular text", 'woocommerce-events'); ?></option>
                        <option value="medium_uppercase"><?php _e("Medium uppercase text", 'woocommerce-events'); ?></option>
                        <option value="large"><?php _e("Large regular text", 'woocommerce-events'); ?></option>       
                        <option value="large_uppercase"><?php _e("Large uppercase text", 'woocommerce-events'); ?></option>       
                    </select>
                    <a href="javascript:void(0);" class="fooevents_printing_widget_remove" name="fooevents_printing_widget_remove">Delete</a>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="fooevents_printing_widget fooevents_printing_widget_init" data-order="12">
                <span data-name="phone"><?php _e('Attendee Phone', 'woocommerce-events'); ?><span class="fooevents_printing_arrow fooevents_printing_arrow_closed"></span></span>
                <div class="fooevents_printing_widget_options">
                    <select class="fooevents_printing_ticket_select">
                        <option value="small"><?php _e("Small regular text", 'woocommerce-events'); ?></option>
                        <option value="small_uppercase"><?php _e("Small uppercase text", 'woocommerce-events'); ?></option>
                        <option value="medium"><?php _e("Medium regular text", 'woocommerce-events'); ?></option>
                        <option value="medium_uppercase"><?php _e("Medium uppercase text", 'woocommerce-events'); ?></option>
                        <option value="large"><?php _e("Large regular text", 'woocommerce-events'); ?></option>       
                        <option value="large_uppercase"><?php _e("Large uppercase text", 'woocommerce-events'); ?></option>       
                    </select>
                    <a href="javascript:void(0);" class="fooevents_printing_widget_remove" name="fooevents_printing_widget_remove">Delete</a> 
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="fooevents_printing_widget fooevents_printing_widget_init" data-order="13">
                <span data-name="company"><?php _e('Attendee Company', 'woocommerce-events'); ?><span class="fooevents_printing_arrow fooevents_printing_arrow_closed"></span></span>
                <div class="fooevents_printing_widget_options">
                    <select class="fooevents_printing_ticket_select">
                        <option value="small"><?php _e("Small regular text", 'woocommerce-events'); ?></option>
                        <option value="small_uppercase"><?php _e("Small uppercase text", 'woocommerce-events'); ?></option>
                        <option value="medium"><?php _e("Medium regular text", 'woocommerce-events'); ?></option>
                        <option value="medium_uppercase"><?php _e("Medium uppercase text", 'woocommerce-events'); ?></option>
                        <option value="large"><?php _e("Large regular text", 'woocommerce-events'); ?></option>       
                        <option value="large_uppercase"><?php _e("Large uppercase text", 'woocommerce-events'); ?></option>       
                    </select>
                    <a href="javascript:void(0);" class="fooevents_printing_widget_remove" name="fooevents_printing_widget_remove">Delete</a>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="fooevents_printing_widget fooevents_printing_widget_init" data-order="14">
                <span data-name="designation"><?php _e('Attendee Designation', 'woocommerce-events'); ?><span class="fooevents_printing_arrow fooevents_printing_arrow_closed"></span></span>
                <div class="fooevents_printing_widget_options">
                    <select class="fooevents_printing_ticket_select">
                        <option value="small"><?php _e("Small regular text", 'woocommerce-events'); ?></option>
                        <option value="small_uppercase"><?php _e("Small uppercase text", 'woocommerce-events'); ?></option>
                        <option value="medium"><?php _e("Medium regular text", 'woocommerce-events'); ?></option>
                        <option value="medium_uppercase"><?php _e("Medium uppercase text", 'woocommerce-events'); ?></option>
                        <option value="large"><?php _e("Large regular text", 'woocommerce-events'); ?></option>       
                        <option value="large_uppercase"><?php _e("Large uppercase text", 'woocommerce-events'); ?></option>       
                    </select>
                    <a href="javascript:void(0);" class="fooevents_printing_widget_remove" name="fooevents_printing_widget_remove">Delete</a>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="fooevents_printing_widget fooevents_printing_widget_init" data-order="15">
                <span data-name="seat"><?php _e('Attendee Seat', 'woocommerce-events'); ?><span class="fooevents_printing_arrow fooevents_printing_arrow_closed"></span></span>
                <div class="fooevents_printing_widget_options">
                    <select class="fooevents_printing_ticket_select">
                        <option value="small"><?php _e("Small regular text", 'woocommerce-events'); ?></option>
                        <option value="small_uppercase"><?php _e("Small uppercase text", 'woocommerce-events'); ?></option>
                        <option value="medium"><?php _e("Medium regular text", 'woocommerce-events'); ?></option>
                        <option value="medium_uppercase"><?php _e("Medium uppercase text", 'woocommerce-events'); ?></option>
                        <option value="large"><?php _e("Large regular text", 'woocommerce-events'); ?></option>       
                        <option value="large_uppercase"><?php _e("Large uppercase text", 'woocommerce-events'); ?></option>       
                    </select>
                    <a href="javascript:void(0);" class="fooevents_printing_widget_remove" name="fooevents_printing_widget_remove">Delete</a>  
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <h3>Custom Attendee Fields</h3>
        <div>
            <?php $i = 16;
            foreach( $cf_array as $key => $value) :
            ?>
                <div class="fooevents_printing_widget fooevents_printing_widget_init" data-order="<?php echo $i; ?>">
                <span data-name="<?php echo $key; ?>"><?php echo $value; ?><span class="fooevents_printing_arrow fooevents_printing_arrow_closed"></span></span>
                <div class="fooevents_printing_widget_options">
                    <select class="fooevents_printing_ticket_select">
                        <option value="small"><?php _e("Small regular text", 'woocommerce-events'); ?></option>
                        <option value="small_uppercase"><?php _e("Small uppercase text", 'woocommerce-events'); ?></option>
                        <option value="medium"><?php _e("Medium regular text", 'woocommerce-events'); ?></option>
                        <option value="medium_uppercase"><?php _e("Medium uppercase text", 'woocommerce-events'); ?></option>
                        <option value="large"><?php _e("Large regular text", 'woocommerce-events'); ?></option>       
                        <option value="large_uppercase"><?php _e("Large uppercase text", 'woocommerce-events'); ?></option>       
                    </select>
                    <a href="javascript:void(0);" class="fooevents_printing_widget_remove" name="fooevents_printing_widget_remove">Delete</a>  
                    <div class="clearfix"></div>
                </div>
            </div>
            <?php
            $i++;
            endforeach; ?>
            <div class="clearfix"></div>
        </div>
    </div>
    <br /><br />
    <p class="form-field">
        <label><?php _e('Stationery layout:', 'woocommerce-events'); ?></label>
        <img class="help_tip layout_help_tip" data-tip="<?php _e('Drag the desired fields from above into the layout blocks below.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
    </p>
    <table id="fooevents_printing_layout_block" cellpadding="0" cellspacing="0" align="center">
        <tr>
            <td class="fooevents_printing_slot" id="TopLeft">
                <?php if (!empty($WooCommerceBadgeFieldTopLeft)): ?>
                    <div class="fooevents_printing_widget">
                        <span data-name="<?php echo $WooCommerceBadgeFieldTopLeft; ?>">
                            <?php echo $WooHelper->widget_label($WooCommerceBadgeFieldTopLeft, $cf_array); ?>
                            <span class="fooevents_printing_arrow fooevents_printing_arrow_closed"></span>
                        </span>
                        <div class="fooevents_printing_widget_options">
                            <?php if ($WooCommerceBadgeFieldTopLeft != "barcode" && $WooCommerceBadgeFieldTopLeft != "logo" && $WooCommerceBadgeFieldTopLeft != "spacer"): ?>
                                <?php if ($WooCommerceBadgeFieldTopLeft == "custom"): ?>
                                    <textarea name="WooCommerceBadgeFieldTopLeft_custom" id="WooCommerceBadgeFieldTopLeft_custom"><?php echo $WooCommerceBadgeFieldTopLeft_custom; ?></textarea>
                                <?php endif; ?>
                            <select class="fooevents_printing_ticket_select" name="WooCommerceBadgeFieldTopLeft_font" id="WooCommerceBadgeFieldTopLeft_font">
                                <option value="small" <?php echo ($WooCommerceBadgeFieldTopLeft_font == 'small')? 'SELECTED' : ''; ?>><?php _e("Small regular text", 'woocommerce-events'); ?></option>
                                <option value="small_uppercase" <?php echo ($WooCommerceBadgeFieldTopLeft_font == 'small_uppercase')? 'SELECTED' : ''; ?>><?php _e("Small uppercase text", 'woocommerce-events'); ?></option>
                                <option value="medium" <?php echo ($WooCommerceBadgeFieldTopLeft_font == 'medium')? 'SELECTED' : ''; ?>><?php _e("Medium regular text", 'woocommerce-events'); ?></option>
                                <option value="medium_uppercase" <?php echo ($WooCommerceBadgeFieldTopLeft_font == 'medium_uppercase')? 'SELECTED' : ''; ?>><?php _e("Medium uppercase text", 'woocommerce-events'); ?></option>
                                <option value="large" <?php echo ($WooCommerceBadgeFieldTopLeft_font == 'large')? 'SELECTED' : ''; ?>><?php _e("Large regular text", 'woocommerce-events'); ?></option>       
                                <option value="large_uppercase" <?php echo ($WooCommerceBadgeFieldTopLeft_font == 'large_uppercase')? 'SELECTED' : ''; ?>><?php _e("Large uppercase text", 'woocommerce-events'); ?></option>       
                            </select>
                            <?php endif; ?>
                            <?php if ($WooCommerceBadgeFieldTopLeft == "logo"): ?>
                            <?php $WooCommerceBadgeFieldTopLeft_logo = (empty($WooCommerceBadgeFieldTopLeft_logo))? $globalWooCommerceEventsTicketLogo : $WooCommerceBadgeFieldTopLeft_logo; ?>           
                            <input id="WooCommerceBadgeFieldTopLeft_logo" class="text uploadfield" type="text" size="40" name="WooCommerceBadgeFieldTopLeft_logo" value="<?php echo $WooCommerceBadgeFieldTopLeft_logo; ?>" />				
                            <span class="uploadbox">
                                <input class="upload_image_button_woocommerce_events button" type="button" value="Choose file" />
                                <div class="clearfix"></div>
                            </span>
                            <a href="#" class="upload_reset"><?php _e('Clear', 'woocommerce-events'); ?></a><span> | </span>
                            <?php endif; ?>
                            <a href="javascript:void(0);" class="fooevents_printing_widget_remove" name="fooevents_printing_widget_remove">Delete</a>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                <?php endif; ?>
            </td>
            <td class="fooevents_printing_slot hide_col_1" id="TopMiddle">
                <?php if (!empty($WooCommerceBadgeFieldTopMiddle)): ?>
                    <div class="fooevents_printing_widget">
                        <span data-name="<?php echo $WooCommerceBadgeFieldTopMiddle; ?>">
                            <?php echo $WooHelper->widget_label($WooCommerceBadgeFieldTopMiddle, $cf_array); ?>
                            <span class="fooevents_printing_arrow fooevents_printing_arrow_closed"></span>
                        </span>
                        <div class="fooevents_printing_widget_options">
                            <?php if ($WooCommerceBadgeFieldTopMiddle != "barcode" && $WooCommerceBadgeFieldTopMiddle != "logo" && $WooCommerceBadgeFieldTopMiddle != "spacer"): ?>
                                <?php if ($WooCommerceBadgeFieldTopMiddle == "custom"): ?>
                                    <textarea name="WooCommerceBadgeFieldTopMiddle_custom" id="WooCommerceBadgeFieldTopMiddle_custom"><?php echo $WooCommerceBadgeFieldTopMiddle_custom; ?></textarea>
                                <?php endif; ?>
                            <select class="fooevents_printing_ticket_select" name="WooCommerceBadgeFieldTopMiddle_font" id="WooCommerceBadgeFieldTopMiddle_font">
                                <option value="small" <?php echo ($WooCommerceBadgeFieldTopMiddle_font == 'small')? 'SELECTED' : ''; ?>><?php _e("Small regular text", 'woocommerce-events'); ?></option>
                                <option value="small_uppercase" <?php echo ($WooCommerceBadgeFieldTopMiddle_font == 'small_uppercase')? 'SELECTED' : ''; ?>><?php _e("Small uppercase text", 'woocommerce-events'); ?></option>
                                <option value="medium" <?php echo ($WooCommerceBadgeFieldTopMiddle_font == 'medium')? 'SELECTED' : ''; ?>><?php _e("Medium regular text", 'woocommerce-events'); ?></option>
                                <option value="medium_uppercase" <?php echo ($WooCommerceBadgeFieldTopMiddle_font == 'medium_uppercase')? 'SELECTED' : ''; ?>><?php _e("Medium uppercase text", 'woocommerce-events'); ?></option>
                                <option value="large" <?php echo ($WooCommerceBadgeFieldTopMiddle_font == 'large')? 'SELECTED' : ''; ?>><?php _e("Large regular text", 'woocommerce-events'); ?></option>       
                                <option value="large_uppercase" <?php echo ($WooCommerceBadgeFieldTopMiddle_font == 'large_uppercase')? 'SELECTED' : ''; ?>><?php _e("Large uppercase text", 'woocommerce-events'); ?></option>       
                            </select>
                            <?php endif; ?>
                            <?php if ($WooCommerceBadgeFieldTopMiddle == "logo"): ?>
                            <?php $WooCommerceBadgeFieldTopMiddle_logo = (empty($WooCommerceBadgeFieldTopMiddle_logo))? $globalWooCommerceEventsTicketLogo : $WooCommerceBadgeFieldTopMiddle_logo; ?>           
                            <input id="WooCommerceBadgeFieldTopMiddle_logo" class="text uploadfield" type="text" size="40" name="WooCommerceBadgeFieldTopMiddle_logo" value="<?php echo $WooCommerceBadgeFieldTopMiddle_logo; ?>" />				
                            <span class="uploadbox">
                                <input class="upload_image_button_woocommerce_events button" type="button" value="Choose file" />
                                <div class="clearfix"></div>
                            </span>
                            <a href="#" class="upload_reset"><?php _e('Clear', 'woocommerce-events'); ?></a><span> | </span>
                            <?php endif; ?>
                            <a href="javascript:void(0);" class="fooevents_printing_widget_remove" name="fooevents_printing_widget_remove">Delete</a>
                            <div class="clearfix"></div>
                        </div>     
                    </div>
                <?php endif; ?>
            </td>
            <td class="fooevents_printing_slot hide_col_1 hide_col_2" id="TopRight">
                <?php if (!empty($WooCommerceBadgeFieldTopRight)): ?>
                    <div class="fooevents_printing_widget">
                        <span data-name="<?php echo $WooCommerceBadgeFieldTopRight; ?>">
                            <?php echo $WooHelper->widget_label($WooCommerceBadgeFieldTopRight, $cf_array); ?>
                            <span class="fooevents_printing_arrow fooevents_printing_arrow_closed"></span>
                        </span>
                        <div class="fooevents_printing_widget_options">
                            <?php if ($WooCommerceBadgeFieldTopRight != "barcode" && $WooCommerceBadgeFieldTopRight != "logo" && $WooCommerceBadgeFieldTopRight != "spacer"): ?>
                                <?php if ($WooCommerceBadgeFieldTopRight == "custom"): ?>
                                    <textarea name="WooCommerceBadgeFieldTopRight_custom" id="WooCommerceBadgeFieldTopRight_custom"><?php echo $WooCommerceBadgeFieldTopRight_custom; ?></textarea>
                                <?php endif; ?>
                            <select class="fooevents_printing_ticket_select" name="WooCommerceBadgeFieldTopRight_font" id="WooCommerceBadgeFieldTopRight_font">
                                <option value="small" <?php echo ($WooCommerceBadgeFieldTopRight_font == 'small')? 'SELECTED' : ''; ?>><?php _e("Small regular text", 'woocommerce-events'); ?></option>
                                <option value="small_uppercase" <?php echo ($WooCommerceBadgeFieldTopRight_font == 'small_uppercase')? 'SELECTED' : ''; ?>><?php _e("Small uppercase text", 'woocommerce-events'); ?></option>
                                <option value="medium" <?php echo ($WooCommerceBadgeFieldTopRight_font == 'medium')? 'SELECTED' : ''; ?>><?php _e("Medium regular text", 'woocommerce-events'); ?></option>
                                <option value="medium_uppercase" <?php echo ($WooCommerceBadgeFieldTopRight_font == 'medium_uppercase')? 'SELECTED' : ''; ?>><?php _e("Medium uppercase text", 'woocommerce-events'); ?></option>
                                <option value="large" <?php echo ($WooCommerceBadgeFieldTopRight_font == 'large')? 'SELECTED' : ''; ?>><?php _e("Large regular text", 'woocommerce-events'); ?></option>       
                                <option value="large_uppercase" <?php echo ($WooCommerceBadgeFieldTopRight_font == 'large_uppercase')? 'SELECTED' : ''; ?>><?php _e("Large uppercase text", 'woocommerce-events'); ?></option>       
                            </select>
                            <?php endif; ?>
                            <?php if ($WooCommerceBadgeFieldTopRight == "logo"): ?>
                            <?php $WooCommerceBadgeFieldTopRight_logo = (empty($WooCommerceBadgeFieldTopRight_logo))? $globalWooCommerceEventsTicketLogo : $WooCommerceBadgeFieldTopRight_logo; ?>           
                            <input id="WooCommerceBadgeFieldTopRight_logo" class="text uploadfield" type="text" size="40" name="WooCommerceBadgeFieldTopRight_logo" value="<?php echo $WooCommerceBadgeFieldTopRight_logo; ?>" />				
                            <span class="uploadbox">
                                <input class="upload_image_button_woocommerce_events button" type="button" value="Choose file" />
                                <div class="clearfix"></div>
                            </span>
                            <a href="#" class="upload_reset"><?php _e('Clear', 'woocommerce-events'); ?></a><span> | </span>
                            <?php endif; ?>
                            <a href="javascript:void(0);" class="fooevents_printing_widget_remove" name="fooevents_printing_widget_remove">Delete</a>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td class="fooevents_printing_slot hide_row_1" id="MiddleLeft">
                <?php if (!empty($WooCommerceBadgeFieldMiddleLeft)): ?>
                    <div class="fooevents_printing_widget">
                        <span data-name="<?php echo $WooCommerceBadgeFieldMiddleLeft; ?>">
                            <?php echo $WooHelper->widget_label($WooCommerceBadgeFieldMiddleLeft, $cf_array); ?>
                            <span class="fooevents_printing_arrow fooevents_printing_arrow_closed"></span>
                        </span>
                        <div class="fooevents_printing_widget_options">
                            <?php if ($WooCommerceBadgeFieldMiddleLeft != "barcode" && $WooCommerceBadgeFieldMiddleLeft != "logo" && $WooCommerceBadgeFieldMiddleLeft != "spacer"): ?>
                                <?php if ($WooCommerceBadgeFieldMiddleLeft == "custom"): ?>
                                    <textarea name="WooCommerceBadgeFieldMiddleLeft_custom" id="WooCommerceBadgeFieldMiddleLeft_custom"><?php echo $WooCommerceBadgeFieldMiddleLeft_custom; ?></textarea>
                                <?php endif; ?>
                            <select class="fooevents_printing_ticket_select" name="WooCommerceBadgeFieldMiddleLeft_font" id="WooCommerceBadgeFieldMiddleLeft_font">
                                <option value="small" <?php echo ($WooCommerceBadgeFieldMiddleLeft_font == 'small')? 'SELECTED' : ''; ?>><?php _e("Small regular text", 'woocommerce-events'); ?></option>
                                <option value="small_uppercase" <?php echo ($WooCommerceBadgeFieldMiddleLeft_font == 'small_uppercase')? 'SELECTED' : ''; ?>><?php _e("Small uppercase text", 'woocommerce-events'); ?></option>
                                <option value="medium" <?php echo ($WooCommerceBadgeFieldMiddleLeft_font == 'medium')? 'SELECTED' : ''; ?>><?php _e("Medium regular text", 'woocommerce-events'); ?></option>
                                <option value="medium_uppercase" <?php echo ($WooCommerceBadgeFieldMiddleLeft_font == 'medium_uppercase')? 'SELECTED' : ''; ?>><?php _e("Medium uppercase text", 'woocommerce-events'); ?></option>
                                <option value="large" <?php echo ($WooCommerceBadgeFieldMiddleLeft_font == 'large')? 'SELECTED' : ''; ?>><?php _e("Large regular text", 'woocommerce-events'); ?></option>       
                                <option value="large_uppercase" <?php echo ($WooCommerceBadgeFieldMiddleLeft_font == 'large_uppercase')? 'SELECTED' : ''; ?>><?php _e("Large uppercase text", 'woocommerce-events'); ?></option>       
                            </select>
                            <?php endif; ?>
                            <?php if ($WooCommerceBadgeFieldMiddleLeft == "logo"): ?>
                            <?php $WooCommerceBadgeFieldMiddleLeft_logo = (empty($WooCommerceBadgeFieldMiddleLeft_logo))? $globalWooCommerceEventsTicketLogo : $WooCommerceBadgeFieldMiddleLeft_logo; ?>           
                            <input id="WooCommerceBadgeFieldMiddleLeft_logo" class="text uploadfield" type="text" size="40" name="WooCommerceBadgeFieldMiddleLeft_logo" value="<?php echo $WooCommerceBadgeFieldMiddleLeft_logo; ?>" />				
                            <span class="uploadbox">
                                <input class="upload_image_button_woocommerce_events button" type="button" value="Choose file" />
                                <div class="clearfix"></div>
                            </span>
                            <a href="#" class="upload_reset"><?php _e('Clear', 'woocommerce-events'); ?></a><span> | </span>
                            <?php endif; ?>
                            <a href="javascript:void(0);" class="fooevents_printing_widget_remove" name="fooevents_printing_widget_remove">Delete</a>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                <?php endif; ?>
            </td>
            <td class="fooevents_printing_slot hide_row_1 hide_col_1" id="MiddleMiddle">
                <?php if (!empty($WooCommerceBadgeFieldMiddleMiddle)): ?>
                    <div class="fooevents_printing_widget">
                        <span data-name="<?php echo $WooCommerceBadgeFieldMiddleMiddle; ?>">
                            <?php echo $WooHelper->widget_label($WooCommerceBadgeFieldMiddleMiddle, $cf_array); ?>
                            <span class="fooevents_printing_arrow fooevents_printing_arrow_closed"></span>
                        </span>
                        <div class="fooevents_printing_widget_options">
                            <?php if ($WooCommerceBadgeFieldMiddleMiddle != "barcode" && $WooCommerceBadgeFieldMiddleMiddle != "logo" && $WooCommerceBadgeFieldMiddleMiddle != "spacer"): ?>
                                <?php if ($WooCommerceBadgeFieldMiddleMiddle == "custom"): ?>
                                    <textarea name="WooCommerceBadgeFieldMiddleMiddle_custom" id="WooCommerceBadgeFieldMiddleMiddle_custom"><?php echo $WooCommerceBadgeFieldMiddleMiddle_custom; ?></textarea>
                                <?php endif; ?>
                                <select class="fooevents_printing_ticket_select" name="WooCommerceBadgeFieldMiddleMiddle_font" id="WooCommerceBadgeFieldMiddleMiddle_font">
                                    <option value="small" <?php echo ($WooCommerceBadgeFieldMiddleMiddle_font == 'small')? 'SELECTED' : ''; ?>><?php _e("Small regular text", 'woocommerce-events'); ?></option>
                                    <option value="small_uppercase" <?php echo ($WooCommerceBadgeFieldMiddleMiddle_font == 'small_uppercase')? 'SELECTED' : ''; ?>><?php _e("Small uppercase text", 'woocommerce-events'); ?></option>
                                    <option value="medium" <?php echo ($WooCommerceBadgeFieldMiddleMiddle_font == 'medium')? 'SELECTED' : ''; ?>><?php _e("Medium regular text", 'woocommerce-events'); ?></option>
                                    <option value="medium_uppercase" <?php echo ($WooCommerceBadgeFieldMiddleMiddle_font == 'medium_uppercase')? 'SELECTED' : ''; ?>><?php _e("Medium uppercase text", 'woocommerce-events'); ?></option>
                                    <option value="large" <?php echo ($WooCommerceBadgeFieldMiddleMiddle_font == 'large')? 'SELECTED' : ''; ?>><?php _e("Large regular text", 'woocommerce-events'); ?></option>       
                                    <option value="large_uppercase" <?php echo ($WooCommerceBadgeFieldMiddleMiddle_font == 'large_uppercase')? 'SELECTED' : ''; ?>><?php _e("Large uppercase text", 'woocommerce-events'); ?></option>       
                                </select>
                            <?php endif; ?>
                            <?php if ($WooCommerceBadgeFieldMiddleMiddle == "logo"): ?>
                            <?php $WooCommerceBadgeFieldMiddleMiddle_logo = (empty($WooCommerceBadgeFieldMiddleMiddle_logo))? $globalWooCommerceEventsTicketLogo : $WooCommerceBadgeFieldMiddleMiddle_logo; ?>           
                            <input id="WooCommerceBadgeFieldMiddleMiddle_logo" class="text uploadfield" type="text" size="40" name="WooCommerceBadgeFieldMiddleMiddle_logo" value="<?php echo $WooCommerceBadgeFieldMiddleMiddle_logo; ?>" />				
                            <span class="uploadbox">
                                <input class="upload_image_button_woocommerce_events button" type="button" value="Choose file" />
                                <div class="clearfix"></div>
                            </span>
                            <a href="#" class="upload_reset"><?php _e('Clear', 'woocommerce-events'); ?></a><span> | </span>
                            <?php endif; ?>
                            <a href="javascript:void(0);" class="fooevents_printing_widget_remove" name="fooevents_printing_widget_remove">Delete</a>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                <?php endif; ?>
            </td>
            <td class="fooevents_printing_slot hide_row_1 hide_col_1 hide_col_2" id="MiddleRight">
                <?php if (!empty($WooCommerceBadgeFieldMiddleRight)): ?>
                    <div class="fooevents_printing_widget">
                        <span data-name="<?php echo $WooCommerceBadgeFieldMiddleRight; ?>">
                            <?php echo $WooHelper->widget_label($WooCommerceBadgeFieldMiddleRight, $cf_array); ?>
                            <span class="fooevents_printing_arrow fooevents_printing_arrow_closed"></span>
                        </span>
                        <div class="fooevents_printing_widget_options">
                            <?php if ($WooCommerceBadgeFieldMiddleRight != "barcode" && $WooCommerceBadgeFieldMiddleRight != "logo" && $WooCommerceBadgeFieldMiddleRight != "spacer"): ?>
                                <?php if ($WooCommerceBadgeFieldMiddleRight == "custom"): ?>
                                    <textarea name="WooCommerceBadgeFieldMiddleRight_custom" id="WooCommerceBadgeFieldMiddleRight_custom"><?php echo $WooCommerceBadgeFieldMiddleRight_custom; ?></textarea>
                                <?php endif; ?>
                            <select class="fooevents_printing_ticket_select" name="WooCommerceBadgeFieldMiddleRight_font" id="WooCommerceBadgeFieldMiddleRight_font">
                                <option value="small" <?php echo ($WooCommerceBadgeFieldMiddleRight_font == 'small')? 'SELECTED' : ''; ?>><?php _e("Small regular text", 'woocommerce-events'); ?></option>
                                <option value="small_uppercase" <?php echo ($WooCommerceBadgeFieldMiddleRight_font == 'small_uppercase')? 'SELECTED' : ''; ?>><?php _e("Small uppercase text", 'woocommerce-events'); ?></option>
                                <option value="medium" <?php echo ($WooCommerceBadgeFieldMiddleRight_font == 'medium')? 'SELECTED' : ''; ?>><?php _e("Medium regular text", 'woocommerce-events'); ?></option>
                                <option value="medium_uppercase" <?php echo ($WooCommerceBadgeFieldMiddleRight_font == 'medium_uppercase')? 'SELECTED' : ''; ?>><?php _e("Medium uppercase text", 'woocommerce-events'); ?></option>
                                <option value="large" <?php echo ($WooCommerceBadgeFieldMiddleRight_font == 'large')? 'SELECTED' : ''; ?>><?php _e("Large regular text", 'woocommerce-events'); ?></option>       
                                <option value="large_uppercase" <?php echo ($WooCommerceBadgeFieldMiddleRight_font == 'large_uppercase')? 'SELECTED' : ''; ?>><?php _e("Large uppercase text", 'woocommerce-events'); ?></option>       
                            </select>
                            <?php endif; ?>
                            <?php if ($WooCommerceBadgeFieldMiddleRight == "logo"): ?>
                            <?php $WooCommerceBadgeFieldMiddleRight_logo = (empty($WooCommerceBadgeFieldMiddleRight_logo))? $globalWooCommerceEventsTicketLogo : $WooCommerceBadgeFieldMiddleRight_logo; ?>           
                            <input id="WooCommerceBadgeFieldMiddleRight_logo" class="text uploadfield" type="text" size="40" name="WooCommerceBadgeFieldMiddleRight_logo" value="<?php echo $WooCommerceBadgeFieldMiddleRight_logo; ?>" />				
                            <span class="uploadbox">
                                <input class="upload_image_button_woocommerce_events button" type="button" value="Choose file" />
                                <div class="clearfix"></div>
                            </span>
                            <a href="#" class="upload_reset"><?php _e('Clear', 'woocommerce-events'); ?></a><span> | </span>
                            <?php endif; ?>
                            <a href="javascript:void(0);" class="fooevents_printing_widget_remove" name="fooevents_printing_widget_remove">Delete</a>
                            <div class="clearfix"></div>
                        </div>    
                    </div>
                <?php endif; ?>
            </td>
        </tr>
        <tr>    
            <td class="fooevents_printing_slot hide_row_1 hide_row_2" id="BottomLeft">
                <?php if (!empty($WooCommerceBadgeFieldBottomLeft)): ?>
                    <div class="fooevents_printing_widget">
                        <span data-name="<?php echo $WooCommerceBadgeFieldBottomLeft; ?>">
                            <?php echo $WooHelper->widget_label($WooCommerceBadgeFieldBottomLeft, $cf_array); ?>
                            <span class="fooevents_printing_arrow fooevents_printing_arrow_closed"></span>
                        </span>
                        <div class="fooevents_printing_widget_options">
                            <?php if ($WooCommerceBadgeFieldBottomLeft != "barcode" && $WooCommerceBadgeFieldBottomLeft != "logo" && $WooCommerceBadgeFieldBottomLeft != "spacer"): ?>
                                <?php if ($WooCommerceBadgeFieldBottomLeft == "custom"): ?>
                                    <textarea name="WooCommerceBadgeFieldBottomLeft_custom" id="WooCommerceBadgeFieldBottomLeft_custom"><?php echo $WooCommerceBadgeFieldBottomLeft_custom; ?></textarea>
                                <?php endif; ?>
                                <select class="fooevents_printing_ticket_select" name="WooCommerceBadgeFieldBottomLeft_font" id="WooCommerceBadgeFieldBottomLeft_font">
                                    <option value="small" <?php echo ($WooCommerceBadgeFieldBottomLeft_font == 'small')? 'SELECTED' : ''; ?>><?php _e("Small regular text", 'woocommerce-events'); ?></option>
                                    <option value="small_uppercase" <?php echo ($WooCommerceBadgeFieldBottomLeft_font == 'small_uppercase')? 'SELECTED' : ''; ?>><?php _e("Small uppercase text", 'woocommerce-events'); ?></option>
                                    <option value="medium" <?php echo ($WooCommerceBadgeFieldBottomLeft_font == 'medium')? 'SELECTED' : ''; ?>><?php _e("Medium regular text", 'woocommerce-events'); ?></option>
                                    <option value="medium_uppercase" <?php echo ($WooCommerceBadgeFieldBottomLeft_font == 'medium_uppercase')? 'SELECTED' : ''; ?>><?php _e("Medium uppercase text", 'woocommerce-events'); ?></option>
                                    <option value="large" <?php echo ($WooCommerceBadgeFieldBottomLeft_font == 'large')? 'SELECTED' : ''; ?>><?php _e("Large regular text", 'woocommerce-events'); ?></option>       
                                    <option value="large_uppercase" <?php echo ($WooCommerceBadgeFieldBottomLeft_font == 'large_uppercase')? 'SELECTED' : ''; ?>><?php _e("Large uppercase text", 'woocommerce-events'); ?></option>       
                                </select>
                            <?php endif; ?>
                            <?php if ($WooCommerceBadgeFieldBottomLeft == "logo"): ?>
                            <?php $WooCommerceBadgeFieldBottomLeft_logo = (empty($WooCommerceBadgeFieldBottomLeft_logo))? $globalWooCommerceEventsTicketLogo : $WooCommerceBadgeFieldBottomLeft_logo; ?>           
                            <input id="WooCommerceBadgeFieldBottomLeft_logo" class="text uploadfield" type="text" size="40" name="WooCommerceBadgeFieldBottomLeft_logo" value="<?php echo $WooCommerceBadgeFieldBottomLeft_logo; ?>" />				
                            <span class="uploadbox">
                                <input class="upload_image_button_woocommerce_events button" type="button" value="Choose file" />
                                <div class="clearfix"></div>
                            </span>
                            <a href="#" class="upload_reset"><?php _e('Clear', 'woocommerce-events'); ?></a><span> | </span>
                            <?php endif; ?>
                            <a href="javascript:void(0);" class="fooevents_printing_widget_remove" name="fooevents_printing_widget_remove">Delete</a>
                            <div class="clearfix"></div>
                        </div> 
                    </div>
                <?php endif; ?>
            </td>
            <td class="fooevents_printing_slot hide_row_1 hide_row_2 hide_col_1" id="BottomMiddle">
                <?php if (!empty($WooCommerceBadgeFieldBottomMiddle)): ?>
                    <div class="fooevents_printing_widget">
                        <span data-name="<?php echo $WooCommerceBadgeFieldBottomMiddle; ?>">
                            <?php echo $WooHelper->widget_label($WooCommerceBadgeFieldBottomMiddle, $cf_array); ?>
                            <span class="fooevents_printing_arrow fooevents_printing_arrow_closed"></span>
                        </span>
                        <div class="fooevents_printing_widget_options">
                            <?php if ($WooCommerceBadgeFieldBottomMiddle != "barcode" && $WooCommerceBadgeFieldBottomMiddle != "logo" && $WooCommerceBadgeFieldBottomMiddle != "spacer"): ?>
                                <?php if ($WooCommerceBadgeFieldBottomMiddle == "custom"): ?>
                                    <textarea name="WooCommerceBadgeFieldBottomMiddle_custom" id="WooCommerceBadgeFieldBottomMiddle_custom"><?php echo $WooCommerceBadgeFieldBottomMiddle_custom; ?></textarea>
                                <?php endif; ?>
                            <select class="fooevents_printing_ticket_select" name="WooCommerceBadgeFieldBottomMiddle_font" id="WooCommerceBadgeFieldBottomMiddle_font">
                                <option value="small" <?php echo ($WooCommerceBadgeFieldBottomMiddle_font == 'small')? 'SELECTED' : ''; ?>><?php _e("Small regular text", 'woocommerce-events'); ?></option>
                                <option value="small_uppercase" <?php echo ($WooCommerceBadgeFieldBottomMiddle_font == 'small_uppercase')? 'SELECTED' : ''; ?>><?php _e("Small uppercase text", 'woocommerce-events'); ?></option>
                                <option value="medium" <?php echo ($WooCommerceBadgeFieldBottomMiddle_font == 'medium')? 'SELECTED' : ''; ?>><?php _e("Medium regular text", 'woocommerce-events'); ?></option>
                                <option value="medium_uppercase" <?php echo ($WooCommerceBadgeFieldBottomMiddle_font == 'medium_uppercase')? 'SELECTED' : ''; ?>><?php _e("Medium uppercase text", 'woocommerce-events'); ?></option>
                                <option value="large" <?php echo ($WooCommerceBadgeFieldBottomMiddle_font == 'large')? 'SELECTED' : ''; ?>><?php _e("Large regular text", 'woocommerce-events'); ?></option>       
                                <option value="large_uppercase" <?php echo ($WooCommerceBadgeFieldBottomMiddle_font == 'large_uppercase')? 'SELECTED' : ''; ?>><?php _e("Large uppercase text", 'woocommerce-events'); ?></option>       
                            </select>
                            <?php endif; ?>
                            <?php if ($WooCommerceBadgeFieldBottomMiddle == "logo"): ?>
                            <?php $WooCommerceBadgeFieldBottomMiddle_logo = (empty($WooCommerceBadgeFieldBottomMiddle_logo))? $globalWooCommerceEventsTicketLogo : $WooCommerceBadgeFieldBottomMiddle_logo; ?>           
                            <input id="WooCommerceBadgeFieldBottomMiddle_logo" class="text uploadfield" type="text" size="40" name="WooCommerceBadgeFieldBottomMiddle_logo" value="<?php echo $WooCommerceBadgeFieldBottomMiddle_logo; ?>" />				
                            <span class="uploadbox">
                                <input class="upload_image_button_woocommerce_events button" type="button" value="Choose file" />
                                <div class="clearfix"></div>
                            </span>
                            <a href="#" class="upload_reset"><?php _e('Clear', 'woocommerce-events'); ?></a><span> | </span>
                            <?php endif; ?>
                            <a href="javascript:void(0);" class="fooevents_printing_widget_remove" name="fooevents_printing_widget_remove">Delete</a>
                            <div class="clearfix"></div>
                        </div>     
                    </div>
                <?php endif; ?>
            </td>
            <td class="fooevents_printing_slot hide_row_1 hide_row_2 hide_col_1 hide_col_2" id="BottomRight">
                <?php if (!empty($WooCommerceBadgeFieldBottomRight)): ?>
                    <div class="fooevents_printing_widget">
                        <span data-name="<?php echo $WooCommerceBadgeFieldBottomRight; ?>">
                            <?php echo $WooHelper->widget_label($WooCommerceBadgeFieldBottomRight, $cf_array); ?>
                            <span class="fooevents_printing_arrow fooevents_printing_arrow_closed"></span>
                        </span>
                        <div class="fooevents_printing_widget_options">
                            <?php if ($WooCommerceBadgeFieldBottomRight != "barcode" && $WooCommerceBadgeFieldBottomRight != "logo" && $WooCommerceBadgeFieldBottomRight != "spacer"): ?>
                                <?php if ($WooCommerceBadgeFieldBottomRight == "custom"): ?>
                                    <textarea name="WooCommerceBadgeFieldBottomRight_custom" id="WooCommerceBadgeFieldBottomRight_custom"><?php echo $WooCommerceBadgeFieldBottomRight_custom; ?></textarea>
                                <?php endif; ?>
                            <select class="fooevents_printing_ticket_select" name="WooCommerceBadgeFieldBottomRight_font" id="WooCommerceBadgeFieldBottomRight_font">
                                <option value="small" <?php echo ($WooCommerceBadgeFieldBottomRight_font == 'small')? 'SELECTED' : ''; ?>><?php _e("Small regular text", 'woocommerce-events'); ?></option>
                                <option value="small_uppercase" <?php echo ($WooCommerceBadgeFieldBottomRight_font == 'small_uppercase')? 'SELECTED' : ''; ?>><?php _e("Small uppercase text", 'woocommerce-events'); ?></option>
                                <option value="medium" <?php echo ($WooCommerceBadgeFieldBottomRight_font == 'medium')? 'SELECTED' : ''; ?>><?php _e("Medium regular text", 'woocommerce-events'); ?></option>
                                <option value="medium_uppercase" <?php echo ($WooCommerceBadgeFieldBottomRight_font == 'medium_uppercase')? 'SELECTED' : ''; ?>><?php _e("Medium uppercase text", 'woocommerce-events'); ?></option>
                                <option value="large" <?php echo ($WooCommerceBadgeFieldBottomRight_font == 'large')? 'SELECTED' : ''; ?>><?php _e("Large regular text", 'woocommerce-events'); ?></option>       
                                <option value="large_uppercase" <?php echo ($WooCommerceBadgeFieldBottomRight_font == 'large_uppercase')? 'SELECTED' : ''; ?>><?php _e("Large uppercase text", 'woocommerce-events'); ?></option>       
                            </select>
                            <?php endif; ?>
                            <?php if ($WooCommerceBadgeFieldBottomRight == "logo"): ?>
                            <?php $WooCommerceBadgeFieldBottomRight_logo = (empty($WooCommerceBadgeFieldBottomRight_logo))? $globalWooCommerceEventsTicketLogo : $WooCommerceBadgeFieldBottomRight_logo; ?>           
                            <input id="WooCommerceBadgeFieldBottomRight_logo" class="text uploadfield" type="text" size="40" name="WooCommerceBadgeFieldBottomRight_logo" value="<?php echo $WooCommerceBadgeFieldBottomRight_logo; ?>" />				
                            <span class="uploadbox">
                                <input class="upload_image_button_woocommerce_events button" type="button" value="Choose file" />
                                <div class="clearfix"></div>
                            </span>
                            <a href="#" class="upload_reset"><?php _e('Clear', 'woocommerce-events'); ?></a><span> | </span>
                            <?php endif; ?>
                            <a href="javascript:void(0);" class="fooevents_printing_widget_remove" name="fooevents_printing_widget_remove">Delete</a>
                            <div class="clearfix"></div>
                        </div>     
                    </div>
                <?php endif; ?>
            </td>
        </tr>
    </table>

    <input type="hidden" name="WooCommerceBadgeFieldTopLeft" id="WooCommerceBadgeFieldTopLeft" value="<?php echo $WooCommerceBadgeFieldTopLeft; ?>" />
    <input type="hidden" name="WooCommerceBadgeFieldTopMiddle" id="WooCommerceBadgeFieldTopMiddle" value="<?php echo $WooCommerceBadgeFieldTopMiddle; ?>" />
    <input type="hidden" name="WooCommerceBadgeFieldTopRight" id="WooCommerceBadgeFieldTopRight" value="<?php echo $WooCommerceBadgeFieldTopRight; ?>" />
    <input type="hidden" name="WooCommerceBadgeFieldMiddleLeft" id="WooCommerceBadgeFieldMiddleLeft" value="<?php echo $WooCommerceBadgeFieldMiddleLeft; ?>" />
    <input type="hidden" name="WooCommerceBadgeFieldMiddleMiddle" id="WooCommerceBadgeFieldMiddleMiddle" value="<?php echo $WooCommerceBadgeFieldMiddleMiddle; ?>" />
    <input type="hidden" name="WooCommerceBadgeFieldMiddleRight" id="WooCommerceBadgeFieldMiddleRight" value="<?php echo $WooCommerceBadgeFieldMiddleRight; ?>" />
    <input type="hidden" name="WooCommerceBadgeFieldBottomLeft" id="WooCommerceBadgeFieldBottomLeft" value="<?php echo $WooCommerceBadgeFieldBottomLeft; ?>" />
    <input type="hidden" name="WooCommerceBadgeFieldBottomMiddle" id="WooCommerceBadgeFieldBottomMiddle" value="<?php echo $WooCommerceBadgeFieldBottomMiddle; ?>" />
    <input type="hidden" name="WooCommerceBadgeFieldBottomRight" id="WooCommerceBadgeFieldBottomRight" value="<?php echo $WooCommerceBadgeFieldBottomRight; ?>" />
    <br /><br />  
    <input type="button" id="fooevents_printing_save" class='button button-primary' value='<?php echo esc_attr( 'Save Changes', 'woocommerce-events'); ?>' />
    <a href="<?php echo site_url(); ?>/wp-admin/admin-ajax.php?action=woocommerce_events_attendee_badges&attendee_show=tickets&event=<?php echo $post->ID ?>" id="fooevents_printing_print" class="button" target="_BLANK"><?php _e('Print Items', 'woocommerce-events'); ?></a>
    <br /><br /><br />  
    <?php endif; ?>
</div>