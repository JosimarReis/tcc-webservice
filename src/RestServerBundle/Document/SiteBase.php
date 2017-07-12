<?php
namespace RestServerBundle\Document;

class SiteBase {
    private $nome;
    private $uri_base;
    private $uri_search;
    private $paginacao;






    

    public function toArray(){
        return [
            'nome' => $this->nome,
            'uri'=> [
                'base' => $this->uri_base,
                'search' => $this->uri_base
            ]
        ];
    }

}