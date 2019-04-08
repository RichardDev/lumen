<?php

namespace App\Http\Controllers;
use App\Planeta;

use Illuminate\Http\Request;

/**
 * Classe responsável pelo fluxo das ações para api Planeta.
 */
class PlanetaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Retorna todos os planetas cadastrados no banco de dados.
     * @return string JSON com as informações dos planetas ou JSON vazio.
     */
    public function index() {
        
        $planetas = Planeta::all();

        return response()->json($planetas);
    }

    /**
     * Faz uma chamada a api swapi buscando pelo nome do planeta para retornar a quantidade de filmes que o planeta aparece.
     * 
     * @param String $nomeplaneta
     * @return int Total de filmes
     */
    public function getnumeroFilmes($nomeplaneta) {
        $endpoint = "https://swapi.co/api/planets/?search=".$nomeplaneta;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $response = curl_exec($ch);
        curl_close($ch);
        
        $response = json_decode($response, true);
        
        if (!empty($response)) {
            foreach($response['results'] as $res) {
                if ($res['name'] === $nomeplaneta) {
                    $filmes = $res;
                }
            }
        }
    
        if (!empty($filmes['films'])) {
            $total = sizeof($filmes['films']);
            return ['total' => $total] ;
        }

        return false;
    }    

    /**
     * Registra um novo planta no banco de dados 
     * 
     * @params Request $request Requisição http com os dados dos planetas em formato JSON.
     * 
     * @return Retorna um JSON com os dados do planeta inserido
     * 
     */
    public function create(Request $request) {        
        
        $data = $request->all();

        $totalfilmes = $this->getnumeroFilmes($data['nome']);
        
        $res = Planeta::orderBy("_id", "desc")->get(['nome']);   
        
        $planeta = new Planeta();
        $planeta->_id = (isset($res[0]) AND !empty($res[0]->getID())) ? $res[0]->getID() + 1 : 1;
        $planeta->nome = $data['nome'];
        $planeta->clima = $data['clima'];
        $planeta->terreno = $data['terreno'];
        $planeta->num_filmes = ($totalfilmes['total'] > 0) ?$totalfilmes['total']  : 0;

        $planeta->save();
        
        return response()->json($planeta);
        
    }
    
    /**
     * Deleta um planeta do banco de dados.
     * 
     * @params Request $request Requisição http com o id do planeta a ser removido.
     * 
     * @return Retorna JSON string com mensagem de sucesso da remoção ou não.
     */
    public function delete(Request $request) {        
        $data = $request->json()->all();
        $planeta = Planeta::find((int)$data['_id']);
        if (!empty($planeta) AND $planeta !== null) { 
            $planeta->delete();           
            return response()->json(['removido'=> true]);            
        }
        return response()->json([]);
    }

    /**
     * Busca um planeta pelo id do planeta.
     * @params Integer $id
     * @return Retorna JSON string com os dados do planeta.
     */
    public function viewByID($id) { 
        $planeta = Planeta::where('_id', '>=', (int) $id)->get();
        return response()->json($planeta);
    }

    /**
     * Busca um planeta pelo nome do planeta.
     * 
     * @params String $nome
     * 
     *@return Retorna JSON string com os dados do planeta.
     */
    public function viewByName($nome) {        
        $planeta = Planeta::where('nome', 'like', '%' . $nome . '%')->get();
        return response()->json($planeta);
    }
}
