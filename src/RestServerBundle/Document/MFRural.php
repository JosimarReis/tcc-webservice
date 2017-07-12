<?php
namespace RestServerBundle\Document;

class MFRural {
    private $nome;
    private $uri_base;
    private $uri_search;
    private $termos;
    
    /**
     * Undocumented function
     *
     * @param array $termos
     */
    public function __construct(){
        $this->nome = "MFRural";
        $this->uri_base = "http://www.mfrural.com.br/";
    }


    public function getNome(){
        return $this->nome;
    }
    public function getTermos($string = false){
        if(!$string){
            $termo = "";
            foreach($this->termos as $termo){
                $termo .= (empty($termo)?$termo:", $termo");
            }
            return $termo;
        }
        return $this->termos;
    }
    /**
     * Undocumented function
     *
     * @param array $termos
     * @return this Class
     */
    public function setTermos(array $termos){
        $this->termos = $termos;
        $this->setUriSearch();
        return $this;
    }
    public function getUriBase(){
        return $this->uri_base;
    }

    /**
     * array $termos 
     * boolean $uri_base
    */

    public function setUriSearch(){

        $stermo = "";
        foreach($this->termos as $termo){
            $stermo .= (empty($stermo)) 
                    ? $termo 
                    : "+$termo";
        }
        $this->uri_search= "/busca.aspx?palavras=$stermo";
        return $this;
    }
    public function getUriSearch($uri_base = false){
       return ($uri_base) ? $this->uri_base + $this->uri_search :  $this->uri_search;
    }

    public function _toArray(){
        return [
            'nome' => $this->nome,
            'uri_base '=>$this->uri_base,
            'uri_search' => $this->uri_search
            
        ];
    }

}