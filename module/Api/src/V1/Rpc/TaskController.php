<?php

namespace Api\V1\Rpc;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Api\Helper\EntityManagerFactory;
use Api\Model\Task;
use Doctrine\ORM\EntityManager;
use DateTime;
use Exception;
use Laminas\Http\PhpEnvironment\Response;

class TaskController extends AbstractActionController
{
    private $entityManager;
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        //"Configurar o doctrine pra funcionar com docker", 1, (new DateTime("tomorrow"))->format("Y-m-d H:i:s")
    }
    public function indexAction()
    {
        
    }
    public function getTasksAction(){
        $entityManager = $this->entityManager;
        $repository = $entityManager->getRepository(Task::class);
        //print_r($repository->findAll());
        $request = $this->getEvent()->getRouteMatch();
        $fields = $request->getParams();
        //$fields =json_decode($fields, true);
       //return new JsonModel((array) $params);
        if(isset($fields["id"])){
            return $this->getTaskBy(["id"=>$fields["id"]]);
        }
        $list = $repository->findAll();
        $payload = [];
        foreach($list as $index => $entity){
            $payload[] = $entity->toArray();
        }
        return new JsonModel($payload);

    }
    public function getByAction(){
        $request = $this->getRequest();
        if($request->isPost()){
            $fields = $request->getContent();
            $fields =json_decode($fields, true);
            return $this->getTaskBy($fields);
        }else{
            $response = new Response();
            $response->setStatusCode(405);
            $response->setReasonPhrase('Metodo Nao Permitido');
            return $response;
        }

    } 

    public function createTaskAction(){
        $request = $this->getRequest();
        $fields = $request->getContent();
        $fields = json_decode($fields, true);
        if(empty($fields) || empty($fields["nome"])){
            throw new Exception("Parâmetros inválidos");
        }
        $entityManager = $this->entityManager;
        $task = new Task($fields["nome"]);
        unset($fields["nome"]);
        foreach($fields as $index=> $value){
            if(!empty($value)){
                switch ($index) {
                    case 'status':
                        $task->setStatus($value);
                        break;
                    case 'dataPrevista':
                        $dataPrevista = new DateTime($value);
                        // $dataPrevista = $dataPrevista->format("Y-m-d H-i-s");
                        if(!$dataPrevista){
                            throw new Exception("Formato de data Inválido");
                        }
                        $task->setDataPrevista($dataPrevista);
                        break;
                    case 'detalhes':
                        $task->setdetalhes($value);
                        break;
                    default:
                        break;
                }
            }
        }
        $entityManager->persist($task);
        $entityManager->flush();
        $response = new \laminas\Http\Response();
        $response->setStatusCode(201);
        $response->setContent(json_encode($task->toArray()));
        return $response;
    }
    private function getTaskBy($fields){
        $entityManager = $this->entityManager;
        $repository = $entityManager->getRepository(Task::class);
        $data =  $repository->findBy($fields);
       $payload = [];
       foreach($data as $index => $entity){
            $payload[] = $entity->toArray();
        }
        return new JsonModel($payload);
    }
}
