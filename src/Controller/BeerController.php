<?php
namespace ApiMaster\Controller;

use ApiMaster\Model\Beer;
use JMS\Serializer\SerializerBuilder;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BeerController extends AppController
{
//    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function index()
    {
        $beers = $this->app['orm.em']
                        ->getRepository("ApiMaster\Model\Beer")
                        ->findAll();

        $beers = SerializerBuilder::create()->build()->serialize($beers,'json');

        return new Response($beers, 200);
    }

    public function get($id = null)
    {
        $beers = $this->getDoctrineService()
            ->getRepository('ApiMaster\Model\Beer');
        if (is_null($id)) {
            $beers = $beers->findAll();
        } else {
            $id = (int) $id;
            $beers = $beers->find($id);
        }
        $build = SerializerBuilder::create()->build()->serialize($beers, 'json');
        return $build;
    }

    public function create(Request $request)
    {
        $data = $request->request->all();
        $beer = new Beer();
        $beer->setName($data['name'])
            ->setPrice($data['price'])
            ->setType($data['type'])
            ->setMark($data['mark'])
            ->setCreatedAt($this->getDateTimeNow())
            ->setUpdatedAt($this->getDateTimeNow());
        $orm = $this->getDoctrineService();
        $orm->persist($beer);
        $orm->flush();
        return json_encode(['msg'=>'beer saved sucessfull']);
    }

    public function update(Request $request)
    {
        $data = $request->request->all();
        if (!isset($data['id']) || is_null($data['id'])) {
            return json_encode(["msg" => "ID nao informado"]);
        }
        $orm = $this->getDoctrineService();
        $beer = $orm->getRepository('ApiMaster\Model\Beer')
            ->find($data['id']);
        foreach ($data as $key => $value) {
            $set = "set" . ucfirst($key);
            $beer->$set($value);
        }
        $beer->setUpdatedAt($this->getDateTimeNow());
        $orm->merge($beer);
        $orm->flush();
        return json_encode(["msg"=>"beer sucessfull updatad at"]);
    }

    public function delete($id = null)
    {
        if (!isset($id) || is_null($id)) {
            return json_encode(["msg" => "ID nao informado"]);
        }
        $orm = $this->getDoctrineService();
        $beer = $orm->getRepository('ApiMaster\Model\Beer')
            ->find($id);
        $orm->remove($beer);
        $orm->flush();
        return json_encode(["msg"=>"beer sucessfull deleted at"]);
    }
}
