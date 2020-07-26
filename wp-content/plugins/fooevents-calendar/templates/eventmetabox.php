<div class="options_group">
        <p class="form-field">
           <label><?php _e('Is this post an event?:', 'fooevents-calendar'); ?></label>
           <select name="WooCommerceEventsEvent" id="WooCommerceEventsMetaEvent">
                <option value="NotEvent" <?php echo ($WooCommerceEventsEvent == 'NotEvent')? 'SELECTED' : '' ?>><?php _e('No', 'fooevents-calendar'); ?></option>
                <option value="Event" <?php echo ($WooCommerceEventsEvent == 'Event')? 'SELECTED' : '' ?>><?php _e('Yes', 'fooevents-calendar'); ?></option>
           </select>
           <img class="help_tip fooevents-tooltip" title="<?php _e('This option enables event and ticketing functionality.', 'fooevents-calendar'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
        </p>
</div>
<div id="WooCommerceEventsMetaForm" style="display:none;">
    <?php if($multiDayActive): ?>
    <div class="options_group">
        <p class="form-field">
           <label><?php _e('Number of days:', 'fooevents-calendar'); ?></label>
           <select name="WooCommerceEventsNumDays" id="WooCommerceEventsNumDays">
                <?php for($x=1; $x<=30; $x++) :?>
                <option value="<?php echo $x; ?>" <?php echo ($WooCommerceEventsNumDays == $x)? 'SELECTED' : '' ?>><?php echo $x; ?></option>
                <?php endfor; ?>
           </select>
           <img class="help_tip fooevents-tooltip" title="<?php _e('Select the number of days for multi-day events. This setting is used by the Event Check-ins apps to manage daily check-ins.', 'fooevents-calendar'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
        </p>
    </div>
    <div class="options_group" id ="WooCommerceEventsMultiDayTypeHolder">
        <p class="form-field">
            <label><?php _e('Multi-day type:', 'fooevents-multiday-events'); ?></label><br />
            <input type="radio" name="WooCommerceEventsMultiDayType" value="sequential" <?php echo ($WooCommerceEventsMultiDayType !== 'select')? 'CHECKED' : '' ?>> <?php _e('Sequential days', 'fooevents-multiday-events'); ?><br>
            <input type="radio" name="WooCommerceEventsMultiDayType" value="select" <?php echo ($WooCommerceEventsMultiDayType == 'select')? 'CHECKED' : '' ?>> <?php _e('Select days', 'fooevents-multiday-events'); ?><br>
            <img class="help_tip fooevents-tooltip" title="<?php _e("Select the type of multi-day event. 'Sequential' means the days are in consecutive order whereas 'Select' allows you to choose the exact days.", 'fooevents-calendar'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
        </p>
    </div>
    <div class="options_group" id ="WooCommerceEventsSelectDateContainer">
        <?php if(!empty($WooCommerceEventsSelectDate)) :?>
        <?php $x = 1; ?>
        <?php foreach($WooCommerceEventsSelectDate as $eventDate) :?>
        <p class="form-field">
            <label><?php _e('Day:', 'fooevents-calendar'); ?> <?php echo $x; ?></label>
            <input type="text" class="WooCommerceEventsSelectDate" name="WooCommerceEventsSelectDate[]" value="<?php echo esc_attr($eventDate); ?>"/>
        </p>
        <?php $x++; ?>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <?php echo $multiDayType; ?>
    <div class="options_group" id="WooCommerceEventsDateContainer">
        <p class="form-field">
            <label><?php _e('Start date:', 'fooevents-calendar'); ?></label>
            <input type="text" id="WooCommerceEventsMetaBoxDate" class="WooCommerceEventsMetaBoxDate" name="WooCommerceEventsDate" value="<?php echo esc_html($WooCommerceEventsDate); ?>"/>
            <img class="help_tip fooevents-tooltip" title="<?php _e("The date that the event is scheduled to take place. This is used as a label on your website and it's also used by the FooEvents Calendar to display the event.", 'fooevents-calendar'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
        </p>
    </div>
    <?php if($multiDayActive): ?>
    <div class="options_group" id="WooCommerceEventsEndDateContainer">
        <p class="form-field">
           <label><?php _e('End date:', 'fooevents-calendar'); ?></label>
           <input type="text" id="WooCommerceEventsEndDate" class="WooCommerceEventsSelectDate" name="WooCommerceEventsEndDate" value="<?php echo esc_attr($WooCommerceEventsEndDate); ?>"/>
           <img class="help_tip fooevents-tooltip" title="<?php _e("The date that the event is scheduled to end. This is used as a label on your website and it's also used by the FooEvents Calendar to display a multi-day event.", 'fooevents-calendar'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
        </p>
    </div>
    <?php endif; ?>
    <div class="options_group">
        <p class="form-field">
            <label><?php _e('Start time:', 'fooevents-calendar'); ?></label><br />
            <select name="WooCommerceEventsHour" id="WooCommerceEventsHour">
                <?php for($x=0; $x<=23; $x++) :?>
                <?php $x = sprintf("%02d", $x); ?>
                <option value="<?php echo $x; ?>" <?php echo ($WooCommerceEventsHour == $x) ? 'SELECTED' : ''; ?>><?php echo $x; ?></option>
                <?php endfor; ?>
            </select>
            <select name="WooCommerceEventsMinutes" id="WooCommerceEventsMinutes">
                <?php for($x=0; $x<=59; $x++) :?>
                <?php $x = sprintf("%02d", $x); ?>
                <option value="<?php echo $x; ?>" <?php echo ($WooCommerceEventsMinutes == $x) ? 'SELECTED' : ''; ?>><?php echo $x; ?></option>
                <?php endfor; ?>
            </select>
            <select name="WooCommerceEventsPeriod" id="WooCommerceEventsPeriod">
                <option value="">-</option>
                <option value="a.m." <?php echo ($WooCommerceEventsPeriod == 'a.m.') ? 'SELECTED' : ''; ?>>a.m.</option>
                <option value="p.m." <?php echo ($WooCommerceEventsPeriod == 'p.m.') ? 'SELECTED' : ''; ?>>p.m.</option>
            </select>
            <img class="help_tip fooevents-tooltip" title="<?php _e('The time that the event is scheduled to start.', 'fooevents-calendar'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
        </p>
    </div>
    <div class="options_group">
        <p class="form-field">
            <label><?php _e('End time:', 'fooevents-calendar'); ?></label><br />
            <select name="WooCommerceEventsHourEnd" id="WooCommerceEventsHourEnd">
                <?php for($x=0; $x<=23; $x++) :?>
                <?php $x = sprintf("%02d", $x); ?>
                <option value="<?php echo $x; ?>" <?php echo ($WooCommerceEventsHourEnd == $x) ? 'SELECTED' : ''; ?>><?php echo $x; ?></option>
                <?php endfor; ?>
            </select>
            <select name="WooCommerceEventsMinutesEnd" id="WooCommerceEventsMinutesEnd">
                <?php for($x=0; $x<=59; $x++) :?>
                <?php $x = sprintf("%02d", $x); ?>
                <option value="<?php echo $x; ?>" <?php echo ($WooCommerceEventsMinutesEnd == $x) ? 'SELECTED' : ''; ?>><?php echo $x; ?></option>
                <?php endfor; ?>
            </select>
            <select name="WooCommerceEventsEndPeriod" id="WooCommerceEventsEndPeriod">
                <option value="">-</option>
                <option value="a.m." <?php echo ($WooCommerceEventsEndPeriod == 'a.m.') ? 'SELECTED' : ''; ?>>a.m.</option>
                <option value="p.m." <?php echo ($WooCommerceEventsEndPeriod == 'p.m.') ? 'SELECTED' : ''; ?>>p.m.</option>
            </select>
            <img class="help_tip fooevents-tooltip" title="<?php _e('The time that the event is scheduled to end', 'fooevents-calendar'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
        </p>
    </div>
    <div class="options_group">
        <p class="form-field">
            <label><?php _e('Time zone:', 'woocommerce-events'); ?></label><br />
            <select name="WooCommerceEventsTimeZone" id="WooCommerceEventsTimeZone">
                <option value="" <?php if ( $WooCommerceEventsTimeZone == "" ) : ?>SELECTED<?php endif; ?>>(Not set)</option>
            <?php
                $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
                foreach ( $tzlist as $tz ) {
            ?>
                    <option value="<?php echo $tz; ?>" <?php if ( $WooCommerceEventsTimeZone == $tz ) : ?>SELECTED<?php endif; ?>><?php echo str_replace("_", " ", str_replace("/", " / ", $tz)); ?></option>
            <?php
                }
            ?> 
            </select>
            <img class="help_tip fooevents-tooltip" title="<?php _e("The time zone where the event is taking place.", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
        </p>
    </div>
    <?php if($eventbrite_option): ?>
    <div class="options_group">
        <p class="form-field">
            <label><?php _e('Add event to Eventbrite', 'fooevents-calendar'); ?></label>
            <input type="checkbox" id="WooCommerceEventsMetaBoxAddEventbrite" name="WooCommerceEventsAddEventbrite" value="1" <?php echo $WooCommerceEventsAddEventbriteChecked; ?>/>
            <img class="help_tip fooevents-tooltip" title="<?php _e('Checking this option will submit the event to Eventbrite.', 'fooevents-calendar'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
        </p>
    </div>
    <?php endif; ?>
</div>    
<div style="height:100px;"></div>