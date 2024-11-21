<?php
    function PokazPodstrone($id, $db){
        $id_clear = htmlspecialchars($id);

        $query = "SELECT * FROM page_list WHERE id=".$id_clear." LIMIT 1";
        $result = mysqli_query($db, $query);
        $row = mysqli_fetch_array($result);

        if(empty($row['id'])){
            $web = "[nie znaleziono strony]";
        }else{
            $web = $row['page_content'];
        }
        return $web;
    }
?>