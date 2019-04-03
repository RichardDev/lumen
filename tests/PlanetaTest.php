<?php

use Faker\Factory;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class PlanetaTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

     protected $faker;     
    
    public function testRetornaPlanetas() {
        $this->get('/planetas');
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            ['*'=>
            [   "_id",
                "nome",
                "clima",
                "num_filmes"
            ]            
            ]
        );
    }

    public function testRetornaPlanetaNome() {
        $this->get('/planetas/nome/Alderaan');
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            ['*' =>
                [  
                "_id",
                "nome",
                "clima",
                "num_filmes"
                ]            
            ]
        );
    }

    public function testRetornaPlanetaID() {
        $this->get('/planetas/id/Alderaan');
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            ['*' =>
                [   
                "_id",
                "nome",
                "clima",
                "num_filmes"
                ]            
            ]
        );
    }

    public function testCriarPlaneta() {        
        $data = [            
            'nome' => 'Tatooine',
            'clima' => 'arid',
            'terreno' => 'desert'
        ];

        /*$response = $this->json('POST', 'planetas/create', $data);
        print_r($response->response->data);
        $this->seeStatusCode(200);*/
        
        $res = $this->post('/planetas/create', $data);
        print_r($res);
        $this->seeStatusCode(200);
        
        /*$this->seeJsonStructure(
            ['*' =>
                [                                
                "nome",
                "clima",
                "terreno",
                "num_filmes",
                "_id"
                ]            
            ]
        );*/
    }
}
