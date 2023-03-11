<?php

    if (isset($_POST['submit'])) {

        // if (isset(!$_POST['name'])) {
        //     echo 'Please enter name';  
        // }
        // else if (isset(!$_POST['surname'])) {
        //     echo 'Please enter surname';   
        // }
        // else if (isset(!$_POST['id'])) {
        //     echo 'Please enter id';   
        // }
        // else if (isset(!$_POST['dob'])) {
        //     echo 'Please enter dob';   
        // }else{
        //     echo 'Successful Capture of items';
        // }

        echo $_POST['name'];
        
        
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