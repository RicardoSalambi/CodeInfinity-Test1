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
            echo $e->getMessage().'<br />';
        }
        finally{

            $row = new MongoDB\Driver\BulkWrite();
            $row -> insert( array( 'Name' => $_POST['name'], 'Surname' => $_POST['surname'],'ID' => $_POST['id'], 'Date of Birth' => $_POST['dob'] ) );
            $connection -> executeBulkWrite( 'codeinfinity.data', $row );

        }
        
        
        
    }

?>

<html>
    <head>
        <!-- style=" background-color: rgb(61, 65, 61); color: white" -->
        <div class="header"> 
            <h2>Proficieny Test 1</h2>
        </div>

        <link rel="stylesheet" href="style.css">

    </head>

    <body>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            
            <div class="fields">

                <div><input type="text" id="name" name="name" placeholder="Name"></div>
                
                <div><input type="text" id="surname" name="surname" placeholder="Surname"></div>

                <div><input type="number" id="id" name="id" placeholder="ID Number" maxlength="13"></div>

                <div><input type="text" id="dob" name="dob" placeholder="Date of Birth"></div>

                <div><button type="submit" name="submit"> Submit </button></div>

                <div><button name="cancel"> Cancel </button></div>

            </div>
            

        </form>
        

    </body>
    


    
    
</html>