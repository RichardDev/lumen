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

        $this->getnumeroFilmes('Alderaan');

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
        if (!$request->isJson()) {            
            return response()->json(['mensagem' => 'Formato inválido, o corpo da requisição dever um JSON.']);
        }
        
        $data = $request->json()->all();

        $totalfilmes = $this->getnumeroFilmes($data['nome']);

        if ($totalfilmes['total'] > 0) {
            $planeta = new Planeta();
            $planeta->nome = $data['nome'];
            $planeta->clima = $data['clima'];
            $planeta->terreno = $data['terreno'];
            $planeta->num_filmes = ($totalfilmes['total'] > 0) ?$totalfilmes['total']  : 0;

            $planeta->save();
            
            return response()->json($planeta);
        }
        return response()->json([]);
    }
    
    /**
     * Deleta um planeta do banco de dados.
     * 
     * @params Request $request Requisição http com o id do planeta a ser removido.
     * 
     * @return Retorna JSON string com mensagem de sucesso da remoção ou não.
     */
    public function delete(Request $request) {
        if (!$request->isJson()) {            
            return response()->json(['mensagem' => 'Formato inválido, o corpo da requisição dever um JSON.']);
        }
        

        $data = $request->json()->all();

        if (!empty($data)) {
            $planeta = Planeta::find($data['_id']);
            
            if ($planeta->delete()) {
                return response()->json(['Planeta removido com sucesso!']);
            } else {
                return response()->json(['=( Não foi possível remover o planeta.']);
            }
        }
    }

    public function viewByID($id) {        
        $planeta = Planeta::find($id);  
        return response()->json($planeta);
    }

    public function viewByName($nome) {        
        $planeta = Planeta::where('nome', 'like', '%' . $nome . '%')->get();
        return response()->json($planeta);
    }
}
