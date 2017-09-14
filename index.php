<?php
@session_start();
if (isset($_GET['upload'])){
    session_destroy();
    @session_start();
}
if (isset($_POST['submit'])) {
    $search = $_POST['account_no'];
    if (isset($_SESSION['filecsv'])){
        jj_readcsv($_SESSION['filecsv'], $search);
    }else {

        $target_dir = "uploads/";
        $_SESSION['filecsv'] = $target_file = $target_dir . date("YmdHis") . basename($_FILES["csvfile"]["name"]);
        if (move_uploaded_file($_FILES["csvfile"]["tmp_name"], $target_file)) {

            jj_readcsv($_SESSION['filecsv'], $search);
            //echo "The file ". $target_file. " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

}
?>

<form action="index.php" method="post" enctype="multipart/form-data">
    <?php
if (!isset($_SESSION['filecsv'])){
    ?>
    <label>Choose File csv</label>
    <input type="file" name="csvfile">
    <?php
} ?>
    <label>Account Number</label>
    <input type="text" name="account_no">
    <input type="submit" name="submit">
    <br>
    <?php
if (isset($_SESSION['filecsv'])){
    echo "<a href='?upload=1'>Upload new file</a>";
}
    ?>
</form>


<?php
//echo date();


function jj_readcsv($filename,$search, $header = false)
{
    global $array;
    $amount1=0;
    $amount2=0;
    $row = 1;
    if (($handle = fopen($filename, "r")) !== FALSE) {
        //echo '<table>'; //display header row if true if ($header) {
        while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
            //print_r($data);
            $num = count($data);
           // echo '<tr>';
            for ($c=0; $c < $num; $c++) {
                if($row > 1){
                    if ($c==0 and $data[$c]==$search){
                        //echo $data[1];
                        $amount1=$amount1+toInt($data[1]);
                    }
                    if ($c==2 and $data[$c]==$search){
                        //echo $data[1];
                        $amount2=$amount2+toInt($data[1]);
                    }
                   // echo "<td>$data[$c]</td>";
                }
                else{
                    //echo "<th>$data[$c]</th>";
                }


                if($c==2)
                    $array[$data[$c]]= $data[1];

            }
            //echo '</tr>';
            $row++;
        }
        /*echo $amount1;
        echo "<br>";
        echo $amount2;
        echo "<br>";*/
        echo $amount1-$amount2;
        fclose($handle);
    }
}
function toInt($str)
{
    return preg_replace("/([^0-9\\.])/i", "", $str);
}


?>