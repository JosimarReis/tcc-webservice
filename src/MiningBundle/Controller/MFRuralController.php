<?php

namespace MiningBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Pool;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\CssSelector\CssSelectorConverter;
use MiningBundle\Document\MFRural;

class MFRuralController extends Controller
{
    private $mfrural;
    private $anuncios;
    private $data;

    public function __construct(){
        $this->data = new \DateTime();
        
        $this->mfrural = new MFRural();
        $this->mfrural->setTermos(['milho','soja','feijao']);
    }

    /**
    * @Route("/api/mfrural/get/paginas/detalhes")
    */
    public function getPaginasDetalhes(){
        $fs = new Filesystem();
        try{
            $dir = 'json/importacoes/listaPaginas/';

            $finder = new Finder();
            $finder->files()->in($dir)->date('> now - 4 hours');

            foreach ($finder as $file) {
                // Dump the absolute path
                #var_dump($file->getRealPath());

                
                // Dump the relative path to the file
               $arquivo = $file->getRelativePathname();
            }

            $content = file_get_contents($dir.$arquivo);
            $listaLinksAnuncios = json_decode($content, false, 512, JSON_UNESCAPED_UNICODE);
            echo ('<pre>');
            var_dump($listaLinksAnuncios);
            die;
            //inicia o cliente para conexao
        $client = new Client();
     
        //percorre por todas as paginas da busca para colher anuncios

            $requests = function ($lista) use ($client) {
                foreach($lista as $item) {

                   $uri = $item['href'];
                   
                    yield function() use ($client,$uri) {
                        return $client->getAsync($uri);
                    };
                }
            };
            $paginas = array();

        $pool = new Pool($client,$requests($listaLinksAnuncios),[
            'fulfilled'=>function($response,$index){
                $crawler = new Crawler($response->getBody()->getContents());
                
                    $filter = '//div[contains(@class, "detalhesBox")]';
                    $temp['titulo'] = $link['title'];
                    $temp['url'] = $link['href'];
                    $temp['detalhes'] = $crawler
                            ->filterXPath($filter)
                            ->each(function (Crawler $node) {
                        return $this->getDetalheAnuncio($node);
                    });
                    $temp['detalhes'] = (!empty($temp['detalhes'][0])) ? $temp['detalhes'][0] : $temp['detalhes'];
                    $paginas[] = $temp;
            }
        ]);
        // Initiate the transfers and create a promise
        $promise = $pool->promise();

        // Force the pool of requests to complete.
        $promise->wait();

         $fs = new Filesystem();
        try{
            $fs->dumpFile('json/importacoes/anuncios/'.$this->data->format('Ymd_His').'.json',
            json_encode($paginas, JSON_UNESCAPED_UNICODE));
            
        }catch(IOException $e){

        }


        $retorno = ['retorno'=>
            [ 'tipo'=>'preparando',
                'detalhes'=> count($paginas) .' anuncios foram salvos. Iniciando salvamento dos dados.',
                'tempo'=>number_format(2,(microtime(true)-$inicio)),
                'hora'=> $this->data->format('H:i:s'),
                
        ]];

       return new Response(
        json_encode($retorno, JSON_UNESCAPED_UNICODE),
        201,
         array('Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json; charset=utf-8;')
       );
             
        }catch(IOException $e){

        }

    }

    /**
    * @Route("/api/mfrural/get/paginas/quantidade")
    * Este metodo retorna a quantidade de páginas encontradas
    */
    public function getPaginasQuantidade(Request $request)
    {

        $termos = $request->get('termos');

        $inicio = microtime(true);

        //efetua conexao com site usando a URI específica e retorna o conteudo da pagina
        $client = new Client(['base_uri' => $this->mfrural->getUriBase()]);
        //recebe dados da pegina
        $response = $client->get($this->mfrural->getUriSearch());
        //prepada dados para filtros
        $crawler = new Crawler($response->getBody()->getContents());
        //verifica paginaão da pagina de busta para colher todos os dados
        $pagination = $crawler->filter('.pagination li a ')->each(function (Crawler $node) {
            return $node->attr('href');
        });
        unset($crawler);
        //define a ultima pagina
      $quantidade = (empty($pagination))? 1 : explode('&pg=', $pagination[count($pagination) - 1]);
      $quantidade = (is_array($quantidade)) ? $quantidade[1] : $quantidade;
      $retorno = ['retorno'=>
     [ 'tipo'=>'analizando',
        'detalhes'=> 'A consulta com os termos ['.$this->mfrural->getTermos(true).'] retornou '.$quantidade.' páginas.',
        'tempo'=>number_format(2,(microtime(true)-$inicio)),
        'hora'=> $this->data->format('H:i:s'),
        'quantidade'=>$quantidade
      ]];

       return new Response(
        json_encode($retorno, JSON_UNESCAPED_UNICODE),
        201,
         array('Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json; charset=utf-8;')
       );
    }

    /**
    * @Route("/api/mfrural/get/paginas/listas/{quantidadePaginas}")
    */
  public function obterPaginasLinks($quantidadePaginas) {

        $inicio = microtime(true);


            //efetua conexao com site usando a URI específica e retorna o conteudo da pagina
        $client = new Client();
     
        //percorre por todas as paginas da busca para colher anuncios

            $requests = function ($total) use ($client) {
                for ($i = 0; $i <= $total; $i++) {
                   $uri_search = ($i == 1) ?  $this->mfrural->getUriSearch()
                    : $this->mfrural->getUriSearch(). '&pg=' . $i;

                   $uri = $this->mfrural->getUriBase().$uri_search;

                    yield function() use ($client, $uri) {
                        return $client->getAsync($uri);

                        
                    };
                }
            };

        
        $pool = new Pool($client,$requests($quantidadePaginas),[
            'fulfilled'=>function($response,$index){
                $crawler = new Crawler($response->getBody()->getContents());
                        $filter = '//h2/a[contains(@class, "boxAnunciosTitulo")]';

                        $this->anuncios[] = $crawler
                        ->filterXPath($filter)
                        ->each(function (Crawler $node) {
                            return [
                                'title' => $node->attr('title'),
                                'href' => urlencode($node->attr('href'))
                            ];
                        });
            }
        ]);
        // Initiate the transfers and create a promise
        $promise = $pool->promise();

        // Force the pool of requests to complete.
        $promise->wait();
        $temp = [];
        for($i=0;$i<count($this->anuncios);$i++){
            foreach($this->anuncios[$i] as $anuncio){
                $temp[]=$anuncio;
            }
        }
        $this->anuncios=$temp;

        //salva copia da consulta

        $fs = new Filesystem();
        try{
            $fs->dumpFile('json/importacoes/listaPaginas/'.$this->data->format('Y_m_d_His').'.json',
            json_encode($this->anuncios, JSON_UNESCAPED_UNICODE));
            
        }catch(IOException $e){

        }


              $retorno = ['retorno'=>
                    [ 'tipo'=>'analizando',
                        'detalhes'=> count($this->anuncios) .' anuncios foram encontrados. Aguarde um momento que estamos preparando a captura de detalhes.',
                        'tempo'=>number_format(2,(microtime(true)-$inicio)),
                        'hora'=> $this->data->format('H:i:s'),
                        'anuncios' =>$this->anuncios
                ]];

       return new Response(
        json_encode($retorno, JSON_UNESCAPED_UNICODE),
        201,
         array('Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json; charset=utf-8;')
       );

    }

private function getDetalheAnuncio(Crawler $node) {
//filtros
        $images = '//img[contains(@class, "active")]';
        $preco = '//div[contains(@class,"produto-preco")]';
        $mapa = '//button[contains(@class, "detalhesMapa")]';
        $descricao = 'div[contains(@class, "divDescricao")]';
        $anuncio = array();
//titulo
        $anuncio['descricao'] = $node->filter(".divDescricao")->each(function(Crawler $nod) {
            return $nod->html();
        });
//        $anuncio['descricao'] = $anuncio['descricao'][0];
//        $anuncio['imagem'] = $node->filterXPath($images)->each(function(Crawler $nod) {
//            return $nod->attr('src');
//        });
        $anuncio['preco'] = $node->filterXPath($preco)->each(function(Crawler $nod) {
            return $nod->text();
        });
//        $anuncio['preco'] = $anuncio['preco'][0];
//        $anuncio['preco'] = str_replace('R$', '', $anuncio['preco']);
//        $anuncio['preco'] = trim($anuncio['preco']);
//        $anuncio['preco'] = str_replace(',', '.', $anuncio['preco']);
//        $anuncio['preco'] = (float) $anuncio['preco'];

        $mapa = $node->filterXPath($mapa)->each(function(Crawler $nod) {
            return $nod->attr('onclick');
        });
        $cidade = explode(',', $mapa[0]);
        $cidade = explode('=', $cidade[0]);
        $cidade = explode('/', str_replace("'", '', $cidade[1]));

        $anuncio['cidade'] = $cidade[count($cidade) - 2];
        $anuncio['uf'] = $cidade[count($cidade) - 1];

        return $anuncio;
    }




}