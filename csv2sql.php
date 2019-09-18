<?php
$enclose = '"';
if(isset($_POST['username'])&&isset($_POST['mysql'])&&isset($_POST['db'])&&isset($_POST['username']))
{
    $sqlname=$_POST['mysql'];
    $username=$_POST['username'];
    $table=$_POST['table'];
    $fieldseparator = $_POST['fieldseparator'];
    $lineseparator = $_POST['lineseparator'];
    switch ($lineseparator){
        case 'CR':
            $lineseparator = $lineseparator1 = "\r";
            break;
        case 'LF':
            $lineseparator = $lineseparator1 = "\n";
            break;
        case 'CR+LF':
            $lineseparator = $lineseparator1 = "\r\n";
            break;
    }
    //$enclose = $_POST['enclose'];
    //var_dump($fieldseparator,$lineseparator,$enclose);exit;
    if(isset($_POST['password']))
    {
        $password=$_POST['password'];
    }
    else
    {
        $password= '';
    }
    $db=$_POST['db'];
    $file=$_POST['csv'];
    try{
        $pdo = new PDO("mysql:host=$sqlname;dbname=$db", $username, $password, array(
            PDO::MYSQL_ATTR_LOCAL_INFILE => true,
    ));
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    } catch (PDOException $e){
        echo 'conn impossible';
    }

//If the fields in CSV are not seperated by comma(,)  replace comma(,) in the below query with that  delimiting character
//If each tuple in CSV are not seperated by new line.  replace \n in the below query  the delimiting character which seperates two tuples in csv
// for more information about the query http://dev.mysql.com/doc/refman/5.1/en/load-data.html

    $sql = "LOAD DATA LOCAL INFILE ':file' INTO TABLE ':table' SET 'utf8' FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES STARTING BY '' TERMINATED BY '\r'";
    $affectedRows = $pdo->exec
    (
        "LOAD DATA LOCAL INFILE "
        .$pdo->quote($file)
        ." INTO TABLE `$table`FIELDS TERMINATED BY "
        .$pdo->quote($fieldseparator)
        ."ENCLOSED BY "
        .$pdo->quote($enclose)
        ."LINES TERMINATED BY "
        .$pdo->quote($lineseparator)
    );
    $response = "Loaded a total of $affectedRows records from this csv file.\n";
    echo $response;
    exit();
}
else{
    echo "Mysql Server address/Host name ,Username , Database name ,Table name , File name are the Mandatory Fields";
}
?>

