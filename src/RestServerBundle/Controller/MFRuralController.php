<?php

namespace RestServerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\CssSelector\CssSelectorConverter;
use RestServerBundle\Document\MFRural;

class MFRuralController extends FOSRestController
{
    private $mfrural;

    public function __construct(){
        $this->mfrural = new MFRural();
        $this->mfrural->setTermos(['milho','soja','feijao']);

    }


    /**
    * @Rest\Get("/api/mfrural/getpaginas")
    */
    public function getPaginasAction()
    {
        $data = new DateTime();
        $inicio = $data->getTimestamp();

        //efetua conexao com site usando a URI específica e retorna o conteudo da pagina
        $client = new Client(['base_uri' => $this->mfrural->getUriBase()]);

        /* @var $params string */
        //recebe dados da pegina
        $response = $client->get($this->mfrural->getUriSearch());
        //prepada dados para filtros
        $crawler = new Crawler($response->getBody()->getContents());
        //verifica paginaão da pagina de busta para colher todos os dados
        $pagination = $crawler->filter('.pagination li a ')->each(function (Crawler $node) {
            return $node->attr('href');
        });
        //unset($crawler);
        //define a ultima pagina
      list($uri, $quantidade) = explode('&pg=', $pagination[count($pagination) - 1]);
        $final = $data->getTimestamp();
      
      return 
     [ 'tipo'=>'analizando',
        'detalhes'=> 'A consulta com os temos ['.$this->mfrural->getTermos(true).'] retornou '.$quantidade.' páginas.',
        'tempoGasto' =>($final-$inicio),
        'hora'=>$data->getTimestamp()
      ];
    }
 
}