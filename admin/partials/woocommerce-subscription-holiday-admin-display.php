<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.shiftinteraction.com/
 * @since      1.0.0
 *
 * @package    Woocommerce_Subscription_Holiday
 * @subpackage Woocommerce_Subscription_Holiday/admin/partials
 */

?>



<div class="wrap">

    <h1>WooCommerce Subscriptions Holiday Setup</h1>

    <div id="poststuff">

        <!-- main content -->
        <div id="post-body" class="metabox-holder columns-1">

            <div id="post-body-content">

                <div class="meta-box-sortables ui-sortable">

                    <div class="postbox">

                        <h2><span>View valid subscriptions, users and status</span></h2>

                        <div class="inside">
                            <p>These are the users and subscriptions which can be paused. Try out setting some holidays here!</p>
                        </div>
                        <!-- .inside -->

                    </div>
                    <!-- .postbox -->

                </div>
                <!-- .meta-box-sortables .ui-sortable -->


                <table class="widefat">
                    <thead>
                    <tr>
                        <th class="row-title">Subscription</th>
                        <th>Sub ID</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach($active_users as $active_user){

//                       var_dump($active_user);
                       echo '<br /><br />';

                    }

                    ?>

                    <?php for($i=0; $i < count($subscriptions); $i++): ?>

                        <?php

                            $display_date = '';

                            if($subscriptions[$i]['resume_date'] && count($subscriptions[$i]['resume_date']) > 0){

                                $date_code = explode('||', $subscriptions[$i]['resume_date'][0]);
                                $display_date = date('d/m/Y', $date_code[0]);

                            }

                        ?>

                        <tr class="wcsh-admin-row">
                            <td class="row-title"><label for="tablecell"><?php echo $subscriptions[$i]['user']->user_email; ?></label></td>
                            <td class="row-title"><label for="tablecell"><?php echo $subscriptions[$i]['order_id']; ?></label></td>
                            <td><?php echo $subscriptions[$i]['status']; ?></td>
                            <td><input type="text" class="wcsh-date" value="<?php echo $display_date; ?>" /></td>
                            <td><a class="wcsh-set-holiday-status button-primary" href="<?php echo $api_path; ?>" data-id="<?php echo $subscriptions[$i]['user']->id; ?>" data-key="<?php echo $subscriptions[$i]['sub_key'] ?>">Set</a></td>
                        </tr>

                    <?php endfor; ?>


                    </tbody>
                </table>

            </div>


        </div>


    </div>

</div>



