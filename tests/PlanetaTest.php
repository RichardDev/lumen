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
    
     public function testCriarPlaneta() {        
        $data = [            
            'nome' => 'Alderaan',
            'clima' => 'arid',
            'terreno' => 'desert'
        ];

        $response = $this->json('POST', 'planetas/create', $data);

        $this->seeStatusCode(200);
        $this->seeJsonStructure(            
            [   
            "_id",
            "nome",
            "clima",
            "num_filmes"
            ]                        
        );
    }

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
        $res = $this->get('/planetas/nome/Alderaan');        
        $this->seeStatusCode(200);
        $this->seeJson(            
            [                  
              "nome" => 'Alderaan'
            ]            
        );
    }

    public function testRetornaPlanetaID() {
        $this->get('/planetas/id/1');
        $this->seeStatusCode(200);
        $this->seeJsonStructure(            
            ['*'=>
              [   
                "_id",
                "nome",
                "clima",
                "num_filmes"
              ]            
            ]
        );
    }

    public function testDeletaPlaneta() {  
        $data = ['_id' => 1];
        $this->json('POST', '/planetas/delete', $data);
        $this->seeStatusCode(200);
        $this->seeJson(['removido' => true]); 
    }
}
