<?php

namespace Api\V1\Rpc;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Api\Helper\EntityManagerFactory;
use Api\Entities\Task;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;
use DateTime;
use Exception;
use Api\Entities\Ientity;
use Laminas\Config\Reader\Json;
use Laminas\Http\PhpEnvironment\Response;
use Laminas\Http\Response as HttpResponse;

class TaskController extends AbstractActionController
{
    private $entityManager;
    protected $entity;
    public function __construct(EntityManagerInterface $entityManager, Ientity $entity)
    {
        $this->entityManager = $entityManager;
        $this->entity = $entity;
    }
    public function indexAction()
    {
        
    }
    public function getTasksAction(){
        $entityManager = $this->entityManager;
        $repository = $entityManager->getRepository(get_class($this->entity));
        //print_r($repository->findAll());
        $request = $this->getEvent()->getRouteMatch();
        $fields = $request->getParams();
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
        $repository = $entityManager->getRepository(get_class($this->entity));
        $data =  $repository->findBy($fields);
       $payload = [];
       foreach($data as $index => $entity){
            $payload[] = $entity->toArray();
        }
        return new JsonModel($payload);
    }
    public function deleteTaskAction(){
        $request = $this->getEvent()->getRouteMatch();
        $queryParams = $request->getParams();
        // return new JsonModel($queryParams);
        $entityManager = $this->entityManager;
        $respository = $entityManager->getRepository(get_class($this->entity));
        $response = new HttpResponse;
        $task = $respository->find($queryParams["id"]);
        if(empty($queryParams["id"]) || empty($task)){
            $response->setStatusCode(404);
            $response->setReasonPhrase("Recurso não encontrado");
        }else{
            $entityManager->remove($task);
            $entityManager->flush();
            $response->setStatusCode(200);
            $response->setReasonPhrase("Task deleted");
        }
        return $response;
        
    }
    public function updateTaskAction(){
        $request = $this->getRequest();
        $fields = $request->getContent();
        $fields = json_decode($fields, true);
        $response = new HttpResponse;
        if(empty($fields["id"])){
            $response->setStatusCode(404);
            $response->setReasonPhrase("Tarefa não encontrado");
        }
        $entityManager = $this->entityManager;
        $respository = $entityManager->getRepository(get_class($this->entity));
        $task = $respository->find($fields["id"]);
        foreach ($fields as $index => $value){
            switch ($index) {
                case 'status':
                    $task->setStatus($value);
                    break;
                case 'nome':
                    $task->setNome($value);
                    break;
                case 'detalhes':
                    $task->setDetalhes($value);
                    break;
                
                default:
                    # code...
                    break;
            }
        }
        $entityManager->flush();
        unset($task);
        $response->setStatusCode(201);
        $response->setReasonPhrase("sucess");
        $response->setContent(json_encode($task->ToArray()));
        return $response;
    }
}
