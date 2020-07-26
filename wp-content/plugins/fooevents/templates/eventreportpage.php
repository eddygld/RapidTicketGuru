<div class="wrap" id="WooCommerceEventsReport">
    <h2><?php echo $event->post_title; ?></h2>
    <p> 
        <a href="<?php echo get_admin_url(); ?>admin.php?page=fooevents-reports"><?php echo __('Back to Reports', 'woocommerce-events'); ?></a> | 
        <a href="<?php echo get_admin_url(); ?>post.php?post=<?php echo $id; ?>&action=edit"><?php echo __('Event Settings', 'woocommerce-events'); ?></a> | 
        <a href="<?php echo get_admin_url(); ?>edit.php?post_type=event_magic_tickets&event_id=<?php echo $id; ?>"><?php echo __('View Tickets', 'woocommerce-events'); ?></a>        
    </p>
    <div class="options_group">
        <form method="POST" action="">
            <div class="form-field">
               <label><?php _e('From', 'woocommerce-events'); ?></label>
               <input type="text" class="WooCommerceEventsDate" id="WooCommerceEventsDateFrom" name="WooCommerceEventsDateFrom" value="<?php echo $previousDate;?>"/>
            </div>
            <div class="form-field">
               <label><?php _e('To', 'woocommerce-events'); ?></label>
               <input type="text" class="WooCommerceEventsDate" id="WooCommerceEventsDateTo" name="WooCommerceEventsDateTo" value="<?php echo $todaysDate;?>"/>
            </div>
            <input type="hidden" name="eventID" id="eventID" value="<?php echo $id; ?>" />
            <div class="form-submit">
                <a href="#" name="<?php echo $previousMonth; ?>" class="first"><?php echo __('30 Days', 'woocommerce-events'); ?></a>
                <a href="#" name="<?php echo $previous90days; ?>"><?php echo __('90 Days', 'woocommerce-events'); ?></a>  
                <a href="#" name="<?php echo $previousYear; ?>" class="last"><?php echo __('Year', 'woocommerce-events'); ?></a>  
                <input type="submit" value="Go">
            </div> 
            <div class="clear"></div>
        </form> 
        <div class="stats">
            <div class="stat stat-1">
                <div class="inner">
                    <label><?php echo (!empty($WooCommerceEventsDate)) ? __('Date', 'woocommerce-events')."</label><h3>".$WooCommerceEventsDate."</h3>" : ''; ?>
                </div>
            </div>
            <div class="stat stat-2">
                <div class="inner">
                    <label><?php echo (!empty($WooCommerceEventsLocation)) ? __('Location', 'woocommerce-events')."</label><h3>".$WooCommerceEventsLocation."</h3>" : ''; ?>
                </div>
            </div>
            <div class="stat stat-3">
                <div class="inner">
                    <label><?php _e('Revenue', 'woocommerce-events'); ?></label><h3><span id="WooCommerceTicketsRevenue">--</span></h3>
                </div>
            </div>
            <div class="stat stat-4">
                <div class="inner">
                    <label><?php _e('Tickets', 'woocommerce-events'); ?></label><h3><a href="<?php echo get_admin_url(); ?>edit.php?post_type=event_magic_tickets&event_id=<?php echo $id; ?>"><span id="WooCommerceTicketsSold">--</span></a></h3>
                </div>
            </div>
            <div class="stat stat-5">
                <div class="inner">
                    <label><?php _e('Check-ins', 'woocommerce-events'); ?></label><h3><span id="WooCommerceCheckIns">--</span></h3>
                </div>
            </div>
            <div class="clear"></div>
        </div>        
        <div class="clear"></div>
        <div class="charts">
            <div class="chart">
                <div class="inner">
                    <h3><?php _e('Revenue', 'woocommerce-events'); ?> </h3>
                    <div class="chart-container">
                        <div class="fooevents-report-chart" id="fooevents-report-tickets-revenue" style="width: 100%; height: 300px;"></div>
                    </div>
                </div>
            </div>
            <div class="chart">
                <div class="inner">
                    <h3><?php _e('Tickets Sold', 'woocommerce-events'); ?> </h3>
                    <div class="chart-container">
                        <div class="fooevents-report-chart" id="fooevents-report-tickets-sold" style="width: 100%; height: 300px;"></div>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
            <div class="chart">
                <div class="inner">
                    <h3><?php _e('Check-ins (By Date Range)', 'woocommerce-events'); ?> </h3>
                    <div class="chart-container">
                        <div class="fooevents-report-chart" id="fooevents-report-check-ins" style="width: 100%; height: 300px;"></div>
                    </div>
                </div>
            </div>
            <div class="chart">
                <div class="inner">
                    <h3><?php _e('Check-ins (Past 24 Hours)', 'woocommerce-events'); ?> </h3>
                    <div class="chart-container">
                        <div class="fooevents-report-chart" id="fooevents-report-check-ins-today" style="width: 100%; height: 300px;"></div>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div> 
    <div class="clear"></div>
    <div id="icon-users" class="icon32"></div>
    <h2><?php _e('Attendee Check-in Details', 'woocommerce-events'); ?></h2>
    <?php $checkInListTable->display(); ?>
</div>