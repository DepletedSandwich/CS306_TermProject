<!DOCTYPE html>
<html>
    <head>
        <title>Delete Operation</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" href="deletestyle.css">
        <script defer src="../Misc/redirect.js"></script>
    </head>
    <body>
        <h1><span><button id="redirectbtn" onclick="redirect_table_index('<?php echo $_GET['id']?>')"><i class="arrow left"></i></button></span>Deleting a row from <span id="tblname"><?php echo $_GET["id"];?></span></h1>
        <?php
        set_include_path("/xampp/htdocs/CS306TermProject/CS306TermProject/Misc");
        include 'config.php';
        if (empty($_POST)===true) {
            include 'datatable.php';
        }
        ?>
        <form action="delete.php?id=<?php echo $_GET["id"]?>" method="post">
            <div id="adj_id">
                <label for="lbl_id">Select primary key:</label>
                    <div id="Loc_id">
                    <select id="lbl_id" name="primary_key">
                        <?php
                        $primary_keys_query = "select sta.column_name from information_schema.tables as tab inner join information_schema.statistics as sta on sta.table_schema = tab.table_schema and sta.table_name = tab.table_name and sta.index_name = 'primary' where tab.table_type = 'BASE TABLE' and tab.table_name='".$_GET["id"]."'";
                        
                        $primary_key_array = array();
                        if ($result = $conn -> query($primary_keys_query)){
                            while ($obj = $result -> fetch_array()) {
                                array_push($primary_key_array,$obj[0]);
                            }  
                        }

                        $primary_val_query = "SELECT ";
                        for ($i=0; $i < count($primary_key_array)-1; $i++) { 
                            $primary_val_query = $primary_val_query.$primary_key_array[$i].",";
                        }

                        $primary_val_query = $primary_val_query.$primary_key_array[count($primary_key_array)-1]." FROM ".$_GET["id"];
                        $option_array = array();
                        if ($result = $conn -> query($primary_val_query)){
                            while ($obj = $result -> fetch_row()) {
                                $option_item = "";
                                for ($i=0; $i < count($obj)-1; $i++) { 
                                    $option_item = $option_item.$obj[$i]."||";
                                }
                                $option_item = $option_item.$obj[count($obj)-1];
                                array_push($option_array,$option_item);
                            }  
                        }
                        sort($option_array);
                        for ($i=0; $i < count($option_array); $i++) { 
                            echo "<option>".$option_array[$i]."</option>";
                        }
                        ?>
                    </select>
                    <input type="submit" value="Delete">
                </div>
            </div>
        </form>
        <?php
        /*Assuming primary key is the first column*/
        if (!empty($_POST["primary_key"])) {
            $primary_keys = $_POST["primary_key"];
            $deletion_query= "DELETE FROM ".$_GET["id"]." WHERE ";
            for ($i=0; $i < count($primary_key_array); $i++) { 
                $deletion_query = $deletion_query.$primary_key_array[$i]."=";
                if (strpos($primary_keys,"||")==true) {
                    $deletion_query = $deletion_query.substr($primary_keys,0,strpos($primary_keys,"|"))." AND ";
                    $primary_keys = substr($primary_keys,strpos($primary_keys,"|")+2,strlen($primary_keys)-strpos($primary_keys,"|"));
                }
                else{
                    $deletion_query = $deletion_query.$primary_keys;
                }
            }

            if ($conn->query($deletion_query) === TRUE) {
                $conn->close();
                header("Location:http://localhost/CS306TermProject/CS306TermProject/index/index.php?id=".$_GET["id"]);
            }
        }
        ?>
    </body>
</html>