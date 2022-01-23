<?php

use App\Models\EquipmentApi;
use Slim\Psr7\Environment as Environment; 
use Slim\Psr7\Request as Request;

class TodoTest extends PHPUnit_Framework_TestCase
{

    protected $app;

    public function setUp()
    {
        $this->app = (new App\Models\EquipmentApi())->get();
    }

    public function testAddOrder()  {

        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI'    => '/orders/add',
            'CONTENT_TYPE'   => 'application/x-www-form-urlencoded',
        ]);
        $req = Request::createFromEnvironment($env)->withParsedBody([$body]);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
        $result = json_decode($response->getBody(), true);
        $this->assertSame($result["message"], "Todo ".$id." updated successfully");
    }

    public function testTimeline() {
         
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/timeline'.'/'.$id,
            'CONTENT_TYPE'   => 'application/x-www-form-urlencoded',
            ]);
        $req = Request::createFromEnvironment($env)->withParsedBody([]);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
        $result = json_decode($response->getBody(), true);
        $this->assertSame($result["message"], "Todo ".$id." updated successfully");
    } 

    public function testCountInHand() {
         
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/equipmentsInHand'.'/'.$id,
            'CONTENT_TYPE'   => 'application/json',
            'QUERY_STRING'   => 'application/x-www-form-urlencoded'
            ]);
        $req = Request::createFromEnvironment($env)->withParsedBody([]);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
        $result = json_decode($response->getBody(), true);
        $this->assertSame($result["message"], "Todo ".$id." updated successfully");
    } 

}

?>