<?php

require $_SERVER['DOCUMENT_ROOT'] . '/src/SpeedexAPI/SpeedexAPI.php';

use SpeedexAPI\SpeedexAPI;
$options = [
    'cache' => 2
];
$sp_api = new SpeedexAPI($options);
$voucher_id = '211111111112';
$voucher = $sp_api->get_voucher($voucher_id);

?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Speedex API Tests</title>
</head>
<body>
    <pre><code>
        <?=var_dump($voucher->get_data())?>
    </code></pre>
</body>
</html>