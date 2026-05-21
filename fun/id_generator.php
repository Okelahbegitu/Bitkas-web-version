<?php
    function generateID ($uniq ){
        return $uniq . '-' . date('Y-m-d') . '-' . rand(1000, 9999);
    }
?>