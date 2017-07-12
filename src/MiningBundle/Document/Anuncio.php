<?php

namespace MiningBundle\Document;

class Anuncio {
    private $id;
    private $titulo;
    private $url;
    private $preco;
    private $localizacao;

    private $cidade;
    
    private $uf;
    
    private $latitude;
    
    private $longitude;
    private $descricao;
    private $anunciante;
    private $nome;
    private $telefone;
    private $email;
    private $site;
    
    public function getId() {
        return $this->id;
    }
    
    public function setId($id) {
        $this->id = $id;
        return $this;
    }
    
    public function getLocalizacao() {
        return "{
            'cidade' => {$this->cidade},
            'uf' => {$this->uf},
            'latitude' => {$this->latitude},
            'longitude' => {$this->longitude},
        }";
    }
    
    public function getTitulo() {
        return $this->titulo;
    }
    
    public function getUrl() {
        return $this->url;
    }
    
    public function getPreco() {
        return $this->preco;
    }
    
    public function getCidade() {
        return $this->cidade;
    }
    
    public function getUf() {
        return $this->uf;
    }
    
    public function getLatitude() {
        return $this->latitude;
    }
    
    public function getLongitude() {
        return $this->longitude;
    }
    
    public function getDescricao() {
        return $this->descricao;
    }
    public function getAnunciante() {
          return "{
            'nome' => {$this->nome},
            'telefone' => {$this->telefone},
            'email' => {$this->email},
            'site' => {$this->site},
        }";
    }
    public function getNome() {
        return $this->nome;
    }
    public function getTelefone() {
        return $this->telefone;
    }
    public function getEmail() {
        return $this->Email;
    }
    public function getSite() {
        return $this->site;
    }
    
    public function setTitulo($titulo) {
        $this->titulo = utf8_encode($titulo);
        return $this;
    }
    
    public function setUrl($url) {
        $this->url = urlencode($url);
        return $this;
    }
    
    public function setPreco($preco) {
        $this->preco = $preco;
        return $this;
    }
    
    public function setCidade($cidade) {
        $this->cidade = utf8_encode($cidade);
        return $this;
    }
    
    public function setUf($uf) {
        $this->uf = utf8_encode($uf);
        return $this;
    }
    
    public function setLatitude($latitude) {
        $this->latitude = $latitude;
        return $this;
    }
    
    public function setLongitude($longitude) {
        $this->longitude = $longitude;
        return $this;
    }
    
    public function setDescricao($descricao) {
        $this->descricao = utf8_encode($descricao);
        return $this;
    }
    public function setAnunciante($anunciante) {
        $this->anunciante = $anunciante;
        return $this;
    }
    public function setTelefone($telefone) {
        $this->telefone = $telefone;
        return $this;
    }
    public function setEmail($email) {
        $this->email=$email;
        return $this;
    }
    public function setSite($site) {
        $this->site = $site;
        return $this;
    }
    
    public function setData($array) {
        
        $this->titulo = utf8_encode($array['titulo']);
        $this->id = $array['_id'];
        $this->url = urlencode($array['url']);
        
        $this->cidade = utf8_encode($array['localizacao']['cidade']);
        $this->uf = $array['localizacao']['uf'];
        $this->latitude = $array['localizacao']['latitude'];
        $this->longitude = $array['localizacao']['longitude'];
        
        $this->preco = $array['preco'];
        $this->descricao = utf8_encode($array['descricao']);
        
        $this->nome = $array['anunciante']['nome'];
        $this->telefone = $array['anunciante']['telefone'];
        $this->email = $array['anunciante']['email'];
        $this->site = urlencode($array['anunciante']['site']);
    }
    
    public function toArray() {
        return ['_id'=> $this->id,
        'titulo' => $this->titulo,
        'url' => $this->url,
        'localizacao' => [
        'cidade' => $this->cidade,
        'uf' => $this->uf,
        'latitude' => $this->latitude,
        'longitude' => $this->longitude
        ],
        'preco' => $this->preco,
        'descricao' => $this->descricao,
        'anunciante' => [
        'nome' =>$this->nome,
        'telefone' =>$this->telefone,
        'email' =>$this->email,
        'site' =>$this->site,
        ]
        ];
    }
    
    public function __toString() {
        
        return "{ 'titulo':'{$this->titulo}',"
            . "'url':'{$this->url}',"
            . "'localizacao': [
            'cidade' : {$this->cidade},
            'uf' : {$this->uf},
            'latitude' : {$this->latitude},
            'longitude' : {$this->longitude},
            ],"
            . "'preco':'{$this->preco}',"
            . "'descricao':'{$this->descricao}',"
            ."'anunciante: [
            'nome':'{$this->nome}',
            'telefone':'{$this->telefone}',
            'email':'{$this->email}',
            'site':'{$this->site}',
            ]'"
        . "}";
    }

    /**
     * Set localizacao
     *
     * @param collection $localizacao
     * @return $this
     */
    public function setLocalizacao($localizacao)
    {
        $this->localizacao = $localizacao;
        return $this;
    }
}
