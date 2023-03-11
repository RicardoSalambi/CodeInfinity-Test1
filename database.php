<?php
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
    if(!array_search('codeinfinity', array_column( $dbArray -> databases, 'name' ))){
        
		echo 'codeinfinity database doesn\'t exist, creating it <br/>';

        $row = new MongoDB\Driver\BulkWrite();
        $row -> insert( array('dbName'=>'codeinfinity', 'message'=> 'This collection verifys codeinfinity Database creation') );
        $connection -> executeBulkWrite( 'codeinfinity.createDB', $row );
        echo 'Created Code Infinity Database <br/>';
        
	} 
	
   echo "Database codeinfinity selected <br/>";
?>

