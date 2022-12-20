<?php

		function dbconn(){
            $site = "A3f*5I5*";
            $dsn = "mysql:host=localhost;dbname=vtupal";
            $username = "feeders";
            $password = "feed@Masses";
    
            try {
                $conn = new PDO($dsn, $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Could not Connect to Database". $e->getMessage();
            }
    
            
    
            return $conn;
        }


?>