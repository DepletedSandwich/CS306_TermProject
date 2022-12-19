<!DOCTYPE html>
<html>
    <head>
        <title>Admin Panel</title>
        <link rel="stylesheet" href="adminstyle.css">
        <script defer src="../Misc/redirect.js"></script>
    </head>
    <body>
        <h1>Welcome, admin!</h1>
        <?php 
        set_include_path("/xampp/htdocs/CS306TermProject/CS306TermProject/Misc");
        include 'config.php';
        $dbtable_query="SHOW FULL TABLES WHERE Table_Type = 'BASE TABLE'";
        $querytableitem = array();
        if($result = $conn -> query($dbtable_query)){
            while ($obj = $result -> fetch_array()) {
                array_push($querytableitem,$obj[0]);
            }
        }
        ?>
        <table>
            <tr><th>Tables of database</th></tr>
            <?php
            for ($i=0; $i < count($querytableitem); $i++) {
                $val_string = str_replace("_"," ",$querytableitem[$i]);
                if (strpos($val_string," ")==true) {
                    $val_string[strpos($val_string," ")+1]=ucfirst($val_string[strpos($val_string," ")+1]);
                }
                ?><tr><td><a href="http://localhost/CS306TermProject/CS306TermProject/index/index.php?id=<?php echo $querytableitem[$i];?>" id="<?php echo $querytableitem[$i];?>"><?php echo ucfirst($val_string);?></a></td></tr> <?php
            }
            ?>
        </table>
        <span id = "placement"><button id="lgt_btn" onclick="redirect_logout()"><span id = "btn_effct">Log out</span></button></span>
    </body>
</html>