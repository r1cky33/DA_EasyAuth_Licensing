<?php
$config['mysql_hostname'] = 'db';
$config['mysql_username'] = 'root';
$config['sql_password'] = '';
$config['mysql_database'] = 'ezauth';

$config['client_version'] = '1.0.0';

include_once $_SERVER["DOCUMENT_ROOT"]."/auth/helper.php"; //helper file

$link = mysqli_connect($config['mysql_hostname'], $config['mysql_username'], $config['sql_password'], $config['mysql_database'] );

if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    exit;
}

// check if request is valid
if(isset($_GET['license']) && isset($_GET['hwid']) && isset($_GET['version'])){
    $license = $_GET['license'];
    $hwid = $_GET['hwid'];
    $version = $_GET['version'];

    // vulnerable to sql injection but idfc
    $query = "SELECT * FROM licenses WHERE license = '$license'";
    $software_hash_name = "SELECT software_name_hash from products WHERE product_id = (SELECT product_id FROM licenses where license = '$license')";
    

    if ($result = $link->query($query)) {
        // query all results
        while ($row = $result->fetch_row()) {
            // activate license and hwid on first request
            if($row[4] == NULL){
                $query = "UPDATE licenses SET activation_date=NOW() WHERE license = '$license'";
                $link->query($query);

                $query = "UPDATE licenses SET hwid='$hwid' WHERE license = '$license'";
                $link->query($query);

                $query = "UPDATE licenses SET state=1 WHERE license = '$license'";
                $link->query($query);

                // no need to check since the data just got set
                {
                    // check version of loader
                    if(version_compare($version, $config['client_version'], '<')){
                        // license is valid -> update crypted binary
                        exit("outdated");
                    }    

                    $queried_name_hash = $link->query($software_hash_name);
                    $temp = mysqli_fetch_assoc($queried_name_hash);
    
                    $file_path = "../product_binaries/" . strval($temp['software_name_hash']);
                    //$hex = get_file_hex($file_path);
                    //exit($hex);
                    exit(bin2hex(xorEncrypt(get_file_hex($file_path), $hwid)));
                }
            }

            if(strpos($hwid, $row[1]) == 0){
                // check if user was banned
                if($row[7] != 1){
                    exit("banned");
                }

                // get license expiry date
                $expiry_date = new DateTime($row[3]);
                $formated_expiry_date = $expiry_date->format('Y-m-d H:i:s');
                $current_date = new DateTime();
                $formated_current_date = $current_date->format('Y-m-d H:i:s');

                //date('Y-m-d H:i:s')

                // check if license is expired
                if($current_date >= $expiry_date){
                    exit("expired");
                }
            
                // check version of loader
                if(version_compare($version, $config['client_version'], '<')){
                    exit("outdated2");
                }

                //exit("valid2");
                
                $queried_name_hash = $link->query($software_hash_name);
                $temp = mysqli_fetch_assoc($queried_name_hash);

                $file_path = "../product_binaries/" . strval($temp['software_name_hash']);
                //$hex = get_file_hex($file_path);
                //exit($hex);
                exit(bin2hex(xorEncrypt(get_file_hex($file_path), $hwid)));
            }
            exit("invalid_request");
        }
        /* free result set */
        $result->close();
    }
}
else{
    exit("invalid_request");
}

exit("unknown error");

//cleanup
mysqli_close($link);
exit();
?>