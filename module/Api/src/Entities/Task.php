<?php

namespace Api\Entities;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Api\Entities\IEntity;

/**
 * @Entity
 */
class Task implements IEntity
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="IDENTITY")
     * 
     */
    private ?int $id = null;
    /**
     * @Column(type="string")
     */
    private string $nome;
    /**
     * @Column(type="datetime")
     */
    private  DateTime $dataDeCadastro;
    /**
     * @Column(type="datetime", nullable=true)
     */
    private  DateTime $dataDeInicio;
    /**
     * @Column(type="integer")
     */
    private int $status;
    /**
     * @Column(type="datetime")
     */
    private  DateTime $dataPrevista;
    /**
     * @Column(type="datetime", nullable=true)
     */
    private  DateTime $dataDeConclusao;
    /**
     * @Column(type="string", nullable=true)
     */
    private string $detalhes;

    public function __construct(string $nome = null)
    {
        empty($nome)? :$this->setNome($nome);
        $this->dataDeCadastro = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
    }
    
    public function getId()
    {
        if(isset($this->id)){
            return $this->id;
        }else{
            return null;
        }
    }
    public function getNome()
    {
        if(isset($this->nome)){
            return $this->nome;
        }else{
            return null;
        }
    }
    public function setNome(string $nome)
    {
        $this->nome = $nome;
    }
    public function getDataDeCadastro()
    {
        if(isset($this->dataDeCadastro)){
            return $this->dataDeCadastro;
        }else{
            return null;
        }
    }
    public function getStatus()
    {
        if(isset($this->status)){
            return $this->status;
        }else{
            return null;
        }
    }
    public function setStatus(string $status)
    {
        $this->status = $status;
    }
    public function getDataPrevista()
    {
        if(isset($this->dataPrevista)){
            return $this->dataPrevista;
        }else{
            return null;
        }
    }
    public function setDataPrevista(DateTime $dataPrevista)
    {
        $this->dataPrevista= $dataPrevista;
    }
    public function getdataDeConclusao()
    {
        if(isset($this->dataDeConclusao)){
            return $this->dataDeConclusao;
        }else{
            return null;
        }
    }
    public function  setDataDeConclusão(string $data)
    {
        $this->dataDeConclusao = new DateTime(strtotime($data, "Y-m-d H-i-s"));
    }
    public function getdataDeInicio()
    {
        if(isset($this->dataDeInicio)){
            return $this->dataDeInicio;
        }else{
            return null;
        }
    }
    public function  setDataDeInicio(string $data)
    {
        $this->dataDeInicio = new DateTime(strtotime($data, "Y-m-d H-i-s"));
    }
    public function getdetalhes()
    {
        if(isset($this->detalhes)){
            return $this->detalhes;
        }else{
            return null;
        }
    }
    public function setdetalhes(string $detalhes)
    {
        $this->detalhes = $detalhes;
    }
    public function toArray(){
        return [
            "id" => $this->id,
            "nome" =>  $this->nome,
            "dataDeCadastro" => $this->getDataDeCadastro(),
            "dataPrevista" => $this->getDataPrevista(),
            "dataDeInicio" => $this->getDataDeInicio(),
            "dataDeConclusao"=> $this->getDataDeConclusao(),
            "status"=> $this->getStatus(),
            "detalhes" => $this->getdetalhes(),
        ];
    }
}
 