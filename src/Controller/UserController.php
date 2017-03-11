<?php
namespace ApiMaster\Controller;

use ApiMaster\Controller\AppController;
use ApiMaster\Model\User;
use Illuminate\Hashing\BcryptHasher;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Request;
use ApiMaster\Service\SanitizerValidatorValues\ValidateValues;

class UserController extends AppController
{
    public function get($id = null)
    {
        $users = $this->getDoctrineService()
            ->getRepository('ApiMaster\Model\User');
        if (is_null($id)){
            $users = $users->findAll();
        } else {
            $id = (int) $id;
            $users = $users->find($id);
        }
        $build = SerializerBuilder::create()->build()->serialize($users, 'json');
        return $build;
    }
    public function create(Request $request)
    {
        $data = $request->request->all();
        $user = new User();
        $validation = new ValidateValues();
        $name = $validation->string($data['name']);
        $phone = $validation->phone($data['celphone']);
        $email = $validation->email($data['email']);
        $website = $validation->url($data['website']);
        $password = new BcryptHasher();
        $password = $password->make($data['password']);
        $user->setName($name)
            ->setCelphone($phone)
            ->setEmail($email)
            ->setWebsite($website)
            ->setPassword($password)
            ->setCreatedAt($this->getDateTimeNow())
            ->setUpdatedAt($this->getDateTimeNow());
        $orm = $this->getDoctrineService();
        $orm->persist($user);
        $orm->flush();
        return json_encode(['msg'=>'beer saved sucessfull']);
    }
    public function update(Request $request)
    {
        $data = $request->request->all();
        if (!isset($data['id']) || is_null($data['id'])){
            return json_encode(["msg" => "ID nao informado"]);
        }
        $orm = $this->getDoctrineService();
        $user = $orm->getRepository('ApiMaster\Model\User')
            ->find($data['id']);
        foreach ($data as $key=>$value) {
            $set = "set" . ucfirst($key);
            if ($set == "setPassword") {
                $password = new BcryptHasher();
                $password = $password->make($data['password']);
                $user->setPassword($password);
            } else {
                $user->$set($value);
            }
        }
        $user->setUpdatedAt($this->getDateTimeNow());
        $orm->merge($user);
        $orm->flush();
        return json_encode(["msg"=>"beer sucessfull updatad at"]);
    }
    public function delete($id = null)
    {
        if (!isset($id) || is_null($id)){
            return json_encode(["msg" => "ID nao informado"]);
        }
        $orm = $this->getDoctrineService();
        $user = $orm->getRepository('ApiMaster\Model\User')
            ->find($id);
        $orm->remove($user);
        $orm->flush();
        return json_encode(["msg"=>"beer sucessfull deleted at"]);
    }
}