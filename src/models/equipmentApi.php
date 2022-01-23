<?php

namespace App\Models;
 
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selective\BasePath\BasePathMiddleware;
use Slim\Factory\AppFactory;
use App\Models\DB;
use PDO;

require_once __DIR__ . '/../../vendor/autoload.php';

class EquipmentApi
{
    private $app;

    public function __construct() {
        $app = AppFactory::create();

        $app->addBodyParsingMiddleware();
        $app->addRoutingMiddleware();
        $app->add(new BasePathMiddleware($app));
        $app->addErrorMiddleware(true, true, true);

        // Add orders

        $app->post('/orders/add', function (Request $request, Response $response, array $args) {
            
            $data = (array)$request->getParsedBody();
            $startStation = $data["idStartStation"];
            $endStation = $data["idEndStation"];
            $startDate = $data["startDate"];
            $endDate = $data["endDate"];
            $idEquipment = $data["idEquipment"];
            $count = $data["count"];

            // Inserting values into orders table
            $sql = "INSERT INTO orders (idStartStation, idEndStation, startDate, endDate) 
                    VALUES (:startStation, :endStation, :startDate, :endDate)";

            try {
                $db = new Db();
                $conn = $db->connect();         // Database connection
     
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':startStation', $startStation);
                $stmt->bindParam(':endStation', $endStation);
                $stmt->bindParam(':startDate', $startDate);
                $stmt->bindParam(':endDate', $endDate);
   
                $orders = $stmt->execute();
                $idOrder = $conn->lastInsertId();       // Getting the last inserted id order value
        
                for($i = 0; $i<count($idEquipment);$i++){   // Looping through the given equipment array
                    
                    // Inserting equipment and count values into equipment_transaction table for each order
                    $sql = "INSERT INTO equipment_transaction (idOrder, idStartStation, idEndStation, idEquipment, count)
                            VALUES ($idOrder, :startStation, :endStation, $idEquipment[$i], $count[$i])";

                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':startStation', $startStation);
                    $stmt->bindParam(':endStation', $endStation);

                    $transaction = $stmt->execute();
                } 
   
                $db = null;     // Closing database connection
                $response->getBody()->write(json_encode($transaction));     // returning response in json format
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(200);
            } 
            catch (PDOException $e) {
                
                $error = array(
                    "message" => $e->getMessage()
                );
   
                $response->getBody()->write(json_encode($error));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(500);
            }
        });

   
        //  Timeline

        $app->get('/timeline/{idStation}', function (Request $request, Response $response, array $args) {

            $idStation = $request->getAttribute("idStation");
            $date =  $_GET["date"];

            // Getting all equipments in particular station for all future given dates
            $sql = "SELECT station.name station, equipment.name equipment, equipment.id idEquipment, orders.startDate, orders.endDate, et.count
                    FROM equipment_transaction et
                    LEFT JOIN orders ON orders.id = et.idOrder
                    LEFT JOIN station ON station.id = et.idStartStation
                    LEFT JOIN equipment ON equipment.id = et.idEquipment
                    WHERE orders.idStartStation = $idStation AND orders.startDate >= '$date'"; 

            try {
                $db = new Db();
                $conn = $db->connect();
                $stmt = $conn->query($sql);
                $result = $stmt->fetchAll(PDO::FETCH_OBJ);
                $timeline = [];      

                foreach($conn->query($sql) as $row){
                      
                    $startDate = date("Y-m-d", strtotime($row['startDate']));
                    $equipment_name = $row['equipment'];
                    $count = $row['count'];

                    //  Looping through above query result and checking if an equipment repeats on the same day and adding the values
                    if(array_key_exists($startDate, $timeline)){
                        
                        $equipment_count  = $timeline[$startDate];
                
                        if(array_key_exists($equipment_name, $equipment_count)){
                    
                            $equipment_count[$equipment_name] += $count;
                        }
                        else{
                            
                            $equipment_count[$equipment_name] = $count;                    
                        }
                        $timeline[$startDate] = $equipment_count;
                    }
                    else{
                        $equipment_count = [
                            $equipment_name => $count
                        ];
                        $timeline[$startDate] = $equipment_count;
                    }
                }

                $response->getBody()->write(json_encode($timeline));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(200);

                $db = null;
            } 
            catch (PDOException $e) {
                
                $error = array(
                    "message" => $e->getMessage()
                );
   
                $response->getBody()->write(json_encode($error));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(500);
            }
        });



        //  Count in hand
        $app->get('/equipmentsInHand/{idStation}', function (Request $request, Response $response, array $args) {
    
            $idStation = $request->getAttribute("idStation");
            $date = $_GET["date"];
            
            //  Getting all records at given station for all dates before given date
            $sql = "SELECT equipment.name equipment, station.name station, et.idStartStation, et.idEndStation, orders.startDate, orders.endDate, et.count 
                    FROM equipment_transaction et
                    LEFT JOIN orders ON orders.id = et.idOrder
                    LEFT JOIN equipment ON equipment.id = et.idEquipment
                    LEFT JOIN station ON station.id = $idStation
                    WHERE (orders.idStartStation = $idStation AND orders.startDate <= '$date') 
                    OR (orders.idEndStation = $idStation AND orders.endDate <= '$date')";
           
            try {
                $db = new Db();
                $conn = $db->connect();
                $stmt = $conn->query($sql);
                $transactionArr = $stmt->fetchAll(PDO::FETCH_OBJ);
                $db = null;
                $equipment_count = [];

                // Calculating count of equipments in hand for all equipments for the given date
                foreach ($conn->query($sql) as $index => $row) {
                    
                    $transactionStartDate = date("Y-m-d", strtotime($row['startDate']));
                    $transactionEndDate = date("Y-m-d", strtotime($row['endDate']));
                  
                    $equipment_name = $row['equipment'];
                        
                    if(($row['idStartStation'] === $idStation) && ($transactionStartDate <= $date))    {
                        $_count = -($row['count']);
                    }
                    if(($row['idEndStation'] === $idStation) && ($transactionEndDate <= $date))    {
                        $_count = $row['count'];
                    }
                        
                    if(array_key_exists($equipment_name, $equipment_count)){
                
                        $equipment_count[$equipment_name] += $_count;
                    }
                    else{                            
                        $equipment_count[$equipment_name] = $_count;                    
                    }
                                     
                }

                $response->getBody()->write(json_encode($equipment_count));
                return $response
                        ->withHeader('content-type', 'application/json')
                        ->withStatus(200);
            }   
            catch (PDOException $e) {
        
                $error = array(
                    "message" => $e->getMessage()
                );
       
                $response->getBody()->write(json_encode($error));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(500);
            }

        });

        $this->app = $app;
    
    }

    public function get()
    {
        return $this->app;
    }
	
}
?>	