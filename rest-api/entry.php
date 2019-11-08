<?php
// set headers
header('Acces-Control_Allow-Origin: *');
header('Content-Type: application/json');

    // include all functions needed
    include 'includes.php';

    try {
        // switch on SERVER Request Methods.
        switch ($_SERVER["REQUEST_METHOD"] ) {

            // if rest api receives a GET request
                case 'GET' : 

                // start connection
                $conn = Functions::connect();

                // if id is set run function getAllbyId and return all data for that user in json
                if (isset($_GET['ID'])){
                        
                        $dataID = $_GET['ID'];   
                        $userdata = Functions::getAllById($conn, $dataID);
                        // returner data som json
                        echo json_encode($userdata);
                        Functions::close();
                    }

                // if id is not set then getAllUsers data and return it in json
                else{
                        $alldata = Functions::getAllUsers($conn);
                        echo json_encode($alldata);
                        Functions::close();
                }
            break;
         
            // If rest api gets a PUT request
                case 'PUT' : 
                
                // start connection
                $conn = Functions::connect(); 

                // get all json formatted data from response body
                $postData=json_decode(file_get_contents("php://input"));

                // set variabler fra postData
                $dataID = $postData->userid;
                $name = $postData->username;
                $email = $postData->useremail;
                $password = $postData->userpassword;

                // make a test statement to check if ID is valid
                $stmt = $conn->prepare("SELECT * FROM Users WHERE id=?");
                $stmt->execute([$dataID]); 
                $user = $stmt->fetch();

                // set boolean accordingly
                if ($user) {
                    $exists = true;
                } else {
                    $exists = false;
                } 

                // If the user exists, then update that user with new values.
                if ($exists == true){
                        Functions::setUserById($conn, $dataID, $name, $email, $password);
                        echo ("User has been updated");
                        Functions::close();
                    }

                // else echo out the error
                else{
                        echo ("User ID was not found or is not correct. In order to update user, please enter a valid ID");
                        Functions::close();
                    }

            break;
            
            // if rest api receives a POST request
                case 'POST' :
                
                // start connection
                $conn = Functions::connect();
                
                // get post Data from response body
                $postData=json_decode(file_get_contents("php://input"));
                $name = $postData->username;
                $email = $postData->useremail;
                $password = $postData->userpassword;

                    // if name, email and password is not empty, then add new user with values from post Data. 
                    if (!$name == '' && !$email == '' && !$password == ''){
                            Functions::addNewUser($conn, $name, $email, $password);
                            echo ("User has been added to the database");
                            Functions::close();
                    }
                    // else echo out error
                    else{
                            echo ("One or more fields are empty. Please enter 'username', 'useremail' & 'userpassword' ");
                            Functions::close();
                    }

            break;

            // if rest api receives a DELETE request
                case 'DELETE' :
                
                // start connection
                $conn = Functions::connect();

                // if id is set
                if (isset($_GET['ID'])){
                    
                    // get id and delete user with selected id from key value
                    $dataID = $_GET['ID'];   
                    Functions::deleteUserById($conn, $dataID);    
                    echo ("User with id:".$dataID." has been deleted.");
                    Functions::close();
                }

                // else echo out error
                else{
                    echo ("Make sure you enter a valid user ID in order to delete a user.");
                    Functions::close();
            }

            break;
        }
    }
    // error checking for sql error
    catch(PDOException $e){
        die("<br>ERROR: Could not able to execute $sql. " . $e->getMessage());
    }
?>