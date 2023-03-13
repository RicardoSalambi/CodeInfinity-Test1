<?php

    if (isset($_POST['submit'])) {

        // connect to mongodb
        try{

            $connection = new MongoDB\Driver\Manager("mongodb://localhost:27017");
            echo "Connection to database successfully <br/>";

        } catch (MongoDBDriverExceptionException $e) {

            echo 'Failed to connect to MongoDB?<br /><br />';
            echo $e -> getMessage();
            exit();
        }	

        // select a database
        $command = new MongoDB\Driver\Command(['listDatabases' => 1]);
        try {

            // Excecute command against admin database
            $result = $connection -> executeCommand('admin', $command);
            $dbArray = $result -> toArray()[0];

        } catch(MongoDB\Driver\Exception $e) {

            echo $e->getMessage().'<br />';
            exit;
        }
        

        // create CodeInfinity database
        try {
            if(!array_search('codeinfinity', array_column( $dbArray -> databases, 'name' ))){
                
                echo 'codeinfinity database doesn\'t exist, creating it <br/>';

                $row = new MongoDB\Driver\BulkWrite();
                $row -> insert( array('dbName'=>'codeinfinity', 'message'=> 'This collection verifys codeinfinity Database creation') );
                $connection -> executeBulkWrite( 'codeinfinity.createDB', $row );
                echo 'Created Code Infinity Database <br/>';
                
            } 
        } catch(MongoDB\Driver\Exception $e) {
            echo $e -> getMessage().'<br />';
        }
        finally{

            $errors = [];

            $filter = ['ID' => $_POST['id'],];
            $options = ['projection' => ['_id' => 0],];

            $dob = DateTime::createFromFormat('d/m/Y', $_POST['dob']);
            

            // Query a specific input
            $query = new MongoDB\Driver\Query( $filter, $options );
            $idValidationResult = $connection -> executeQuery('codeinfinity.data', $query);

            

            if (!$idValidationResult -> isDead() ) {
                // The ID value exists in the database

                foreach ($idValidationResult as $document) {
                    // var_dump($document);
                    echo 'Duplicate ID ' . $document->ID . ' Found !';
                    $errors[] = 'Duplicate ID ' . $document->ID . ' Found !';
                }

            }
            elseif (strlen($_POST['id']) != 13) {

                echo 'ID ' . $_POST['id'] . ' is not 13 characters long !';
                $errors[] = 'ID must be exactly 13 characters long';

            }
            elseif (!$dob) {
            
                echo 'Invalid date of birth format. Please enter the date in the format dd/mm/YYYY.';
                $errors[] = 'Invalid date of birth format. Please enter the date in the format dd/mm/YYYY.';
                                

            }
            elseif (preg_match('/^[A-Za-z -]+$/', $_POST['name']) === 0) {

                echo 'Name can only contain alphabetic characters, spaces, and hyphens';
                $errors[] = 'Name can only contain alphabetic characters, spaces, and hyphens';

            }
            
            elseif (preg_match('/^[A-Za-z -]+$/', $_POST['surname']) === 0) {

                echo 'Surname can only contain alphabetic characters, spaces, and hyphens';
                $errors[] = 'Surname can only contain alphabetic characters, spaces, and hyphens';

            } else {

                echo 'No Duplicates';

                $row = new MongoDB\Driver\BulkWrite();
                $row -> insert( array( 'Name' => $_POST['name'], 'Surname' => $_POST['surname'],'ID' => $_POST['id'], 'Date of Birth' => $_POST['dob'] ) );
                $connection -> executeBulkWrite( 'codeinfinity.data', $row );

                unset($_POST['name']);
                unset($_POST['surname']);
                unset($_POST['id']);
                unset($_POST['dob']);

            }

            

        }
        
        
        
    }

    if (isset($_POST['cancel'])) {
        // Unset the values of the $_POST superglobal variables
        unset($_POST['name']);
        unset($_POST['surname']);
        unset($_POST['id']);
        unset($_POST['dob']);
    }

?>

<html>
    <head>

        <link rel="stylesheet" href="style.css">

    </head>

    <body>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            
            <div class="fields">

                <div class="header"> <h2>Proficieny Test 1</h2> </div>

                <div><input type="text" id="name" name="name" placeholder="Name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>"></div>
                
                <div><input type="text" id="surname" name="surname" placeholder="Surname" value="<?php echo isset($_POST['surname']) ? $_POST['surname'] : ''; ?>"></div>

                <div><input type="number" id="id" name="id" placeholder="ID Number" maxlength="13" value="<?php echo isset($_POST['id']) ? $_POST['id'] : ''; ?>"></div>

                <div><input type="text" id="dob" name="dob" placeholder="Date of Birth" value="<?php echo isset($_POST['dob']) ? $_POST['dob'] : ''; ?>"></div>

                <div><button type="submit" name="submit"> Submit </button></div>

                <div><button name="cancel"> Cancel </button></div>

                <div><input class="errorsArea" type="textarea" name="errors" value="<?php echo isset($_POST['errors']) ? $_POST['errors'] : ''; ?>"></div>

            </div>
            

        </form>
        

    </body>
    


    
    
</html>