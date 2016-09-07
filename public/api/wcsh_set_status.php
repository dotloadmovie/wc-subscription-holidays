<?php

    define('WP_USE_THEMES', false);
    require('../../../../../wp-blog-header.php');


    class Woocommerce_Subscription_Holiday_Admin_API {

        public function set_status($date, $uid, $orderkey){

            delete_user_meta( $uid, 'wcsh_resume_date');

            $success = add_user_meta($uid, 'wcsh_resume_date', $date.'||'.$orderkey, true);

            WC_Subscriptions_Manager::put_subscription_on_hold($uid, $orderkey);

            wc_add_notice('Your subscription has been paused');

            $output = new stdClass();

            $output->state = 'complete';
            $output->value = $success;

            $str_date = mktime($date);

            $this->add_log_entry('Added holiday (date='.date("Y-m-d h:i:s", $date).') for user '.$uid);

            return json_encode($output);


        }

        private function add_log_entry($entry){

            $file = fopen("../../logs/testlog.txt", "a") or die("Unable to open file!");
            $txt = "$entry\n";
            fwrite($file, $txt);
            fclose($file);

        }

    }

    header('Content-Type: application/json');


    if($_POST['date'] && date($_POST['date']) && $_POST['id'] && $_POST['key']){

        $api = new Woocommerce_Subscription_Holiday_Admin_API();

        echo $api->set_status($_POST['date'], $_POST['id'], $_POST['key']);

    }
    else{
        echo '{"status": "error"}';
    }

