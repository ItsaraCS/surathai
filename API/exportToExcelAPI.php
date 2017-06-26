<?php
    $data = $_POST['image'];
    $imgName = date("l");

    if(!file_exists('../export/report'))
        mkdir('../export/report', 0777, true);

    file_put_contents('../export/report/'.$imgName.'.png', base64_decode(str_replace('data:image/png;base64,', '', $data)));
    echo json_encode(array("ImageName"=>$imgName));
?>

