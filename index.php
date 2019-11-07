<?php
require("vendor/autoload.php");

use GuzzleHttp\Client;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Captures id from URL
$id = $_GET['id'];

// Creates http client
$client = new Client(['headers' => ['Accept' => 'application/json']]);

// Calls Unicorns api
$res = $client->request('GET', 'http://unicorns.idioti.se/' . $id);

// Converts JSON response
$data = json_decode($res->getBody(), true);


// Creates log file visits.log
$log = new Logger('unicorns');
$log->pushHandler(new StreamHandler('visits.log', Logger::INFO));
$logString = ' Requested info about: ';

?>
<!doctype html>
<html lang="sv">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Enhörningar</title>
</head>

<body>

<div class="container">
        <h1>Enhörningar</h1>
        <!-- Get form used to fetch unicorns -->
        <form name="apiForm" method="get">
            <div class="form-group row">
                <label for="id" class="col-sm-2 col-form-label">id på enhörning</label>
                <div class="col-10">
                    <input type="number" class="form-control" id="id" name="id">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-2">
                    <button type="submit" class="btn btn-primary">Visa enhörning</button>
                </div>
                <div class="col-3">
                    <a href="/" class="btn btn-success">Visa alla enhörningar</a>
                </div>
            </div>
        </form>

        <?php
        // Checks if id is set in url
        // If it's set, a specific unicorn is fetched
        if (isset($_GET['id'])) {
            echo   "<div class='row'>
                        <div class='col-6'>
                            <h1>" . $data['name'] . "</h1>
                            <p>" . $data['spottedWhen'] . "</p>
                            <p>" . $data['description'] . "</p>
                            <p> <b>Rapporterad av: </b> " . $data['reportedBy'] . "</p>
                        </div>
                        <div class='col-6'>
                            <img src= " . $data['image'] . "  alt='Bild på " . $data['name'] . "' class='img-fluid'>
                        </div>
                    </div>";
        // Writes line to log file
        $log->info($logString . $data['name']);
        } else {

            $idArray = array();
            $nameArray = array();
            $detailsArray = array();
            foreach ($data as $item) {
                array_push($idArray, $item['id']);
                array_push($nameArray, $item['name']);
                array_push($detailsArray, $item['details']);
            };
            echo "   
                    <table class='table'>";
            for ($i = 0; $i < count($data); ++$i) {
                echo"
                    <tbody>
                    <tr>
                    <td>$idArray[$i] : $nameArray[$i]</td>

                    <td><a href='http://localhost/?id=$idArray[$i]' class='btn btn-light float-right'> Läs mer </a></td>
                    </tr>
                    </tbody>";
            }
        // Writes line to log file
        $log->info($logString . 'all uncorns');
        };
        echo "</table>";
        ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>
