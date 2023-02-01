<?php
    class func{
        /*
         * Function that checks if a user is logged in DB 
         *  @conn DBConnection Instance
         */
        public static function checkLogin( $conn ){
            //Check if user is already logged in (session start or remember me)
            //SESSION ACTIVE
            if ( !isset( $_SESSION ) ) {
                ini_set('session.cookie_httponly', 1); //modify php.ini to admit only http secure cookies
                session_start();
                
            }

            /*PERSISTENT LOGIN ACTIVE
             * The user only will be considered logged in when:
             *  1. The session cookies exists and match the session record in database (PERSISTENT)
             *  2. The session cookies don't exists but we have session variables that match (NON PERSISTEN)
             */
            if ( isset( $_COOKIE['user_id'] ) && isset( $_COOKIE['token'] ) ) {
                //Check if session cookies match session fields (DB)
                $user_id = $_COOKIE['user_id'];
                $token = $_COOKIE['token'];

                $sql = "SELECT * FROM sessions where session_userid = :userid AND session_token = :token";
                $stmt = $conn->prepare( $sql );
                $stmt->execute([':userid'=>$user_id, ':token'=>$token]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);  //Retrieves an associative array
                if (isset($row['session_userid']) && $row['session_userid'] > 0) {
                    //There is a session in db (PERSISTENT)
                    //Create a new token
                    $newToken = func::createRandom(32);

                    //Get the expired date of session
                    $newDate = new DateTime($row['session_date']);
                    $date_of_expiring_session = $newDate->add(new DateInterval('P2D'));

                    //Update the sessions table
                    $sql = "UPDATE `sessions` SET `session_token`=:session_token WHERE `session_id` = :sessionid";
                    //echo $sql;exit;
                    $stmt = $conn->prepare( $sql );

                    $stmt->bindValue(':sessionid', $row['session_id']);
                    $stmt->bindValue(':session_token', $newToken);

                    $stmt->execute();

                    //Create a session with the new data
                    func::createSession($_COOKIE['user_id'], $_COOKIE['username'], $_COOKIE['token']);

                    //The user is logged in
                    return true;
                }

            }else{
                //There are no cookies, try to check if we have a open session
                if( isset( $_SESSION['user_id'] ) && isset( $_SESSION['token'])){

                    $user_id = $_SESSION['user_id'];
                    $token = $_SESSION['token'];

                    //Check if there is an open session in DB
                    $query = 'SELECT * FROM `sessions` WHERE `session_userid` = :userid AND `session_token` = :token;';
                    $stmt = $conn->prepare( $query );

                    $stmt->bindValue(':userid', $user_id);
                    $stmt->bindValue(':token', $token);

                    $stmt->execute();

                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ( $row ) {
                        return true;
                    }
                }
            }
        }

        /*
         * Function that creates a new session
         *  for the logged user
         *  @user_id   Int    User primary key
         *  @username  String User name
         *  @token     String Token of username session 
         */
        public static function createSession( $user_id, $username, $token ){
            //Check if user is already logged in (session start or remember me)
            if ( !isset( $_SESSION ) ) {
                session_start();
            }
            //Set values to session variables
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $username;
            $_SESSION['token'] = $token;
        }

        /*
         * Function that creates the cookies for
         *  login persistent implementation
         *  @user_id   Int    User primary key
         *  @username  String User name
         *  @token     String Token of username session 
         */
        public static function createCookies( $user_id, $username, $token ){
            setcookie('user_id', $user_id, time() + ( 3600 * 24 * 2 ), "/");
            setcookie('user_name', $username, time() + ( 3600 * 24 * 2 ), "/");
            setcookie('token', $token, time() + ( 3600 * 24 * 2 ), "/");
        }

        /*
         * Function that erases the cookies for
         * login persistent implementation
         */
        public static function deleteCookies(){
            setcookie('user_id', '', time() - 3600, "/" );
            setcookie('user_name', '', time() - 3600, "/" );
            setcookie('token', '', time() - 3600, "/" );
            setcookie('PHPSESSID', '', time() - 3600, "/" );
        }

        /*
         * Function that records a new session in the database
         *  @dbc       Object Data Connection
         *  @user_id   Int    User primary key
         *  @username  String User name
         *  @remember  Bool   Token of username session 
         */
        public static function recordSession( $conn, $user_id, $username, $remember ){
            //Delete the old session if exists
            $conn->prepare("DELETE FROM sessions WHERE `session_userid` = :session_userid")->execute([':session_userid'=> $user_id]);
            //Create a sesssion token
            $token = func::createRandom(32);
            //Create a session cookies if remember me is active
            if( $remember == 1 ){
                func::createCookies( $user_id, $username, $token );
            }
            //Create session
            func::createSession($user_id, $username, $token);
            //Restore session in DB with the new data
            $sql = "INSERT INTO `sessions`(`session_id`, `session_userid`, `session_token`, `session_serial`, `session_date`) 
            VALUES (NULL, :session_userid, :token, :session_serial, now() )";
            $stmt = $conn->prepare( $sql );
            $stmt->execute([':token'=>$token, ':session_serial'=>$token , ':session_userid'=>$user_id] );
        }

        /*
         * Function that generates a new random string
         * @len Int Length of string generates
         */
        public static function createRandom( $len ){
            $random = "";
            mt_srand( time() ); //function to change the seed of the random number
            $phrase = "ZMqpaDJClhz5wosAkEIB67WxG9nQeiSdg4jcbru3XNmOKRU8HVTfFvty2P1LY";
            for ( $i=1; $i <= $len; $i++ ) { 
                $position = mt_rand( 0, strlen( $phrase )-1 );
                $random .= substr( $phrase, $position, 1 );

            }   
            return $random;
        }

        /*
         * Function that retrieves a error message depending on error code
         * @error Int Error code
         */
        public static function showError( $error ){
            switch ($error) {
                case 0:
                    $errormsg = "Success...";
                    break;
                case 1:
                    $errormsg = "There is nothing to search...";
                    break;
                case 2:
                    $errormsg = "Something went wrong. DataBase unreacheable...";
                    break;
                case 3:
                    $errormsg = "User name or password invalid";
                    break;
                case 4:
                    $errormsg = "This user is already registered";
                    break;
                case 5:
                    $errormsg = "Something wrong happened...";
                    break;
            }
            return $errormsg;
        }
    }


?>