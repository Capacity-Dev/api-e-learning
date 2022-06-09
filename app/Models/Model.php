<?php
namespace App\Models;
use App\Database\Database;
use \App\Database\Tables\Table;
use \ArrayAccess;
use Exception;


class Model{

    protected $db;
    protected $table;
    protected $table_class_name;

    public function __construct(){
        $this->db=Database::getInstance();
        $class=explode('\\',get_class($this));
        $table=end($class);
        $table=str_replace('Model','',$table); //name of the table
        $table_class_name='\\App\\Database\\Tables\\'.$table.'Table';//name of class used by getTableInstances

        $this->table=lcfirst($table);
        $this->table_class_name=$table_class_name;
    }
    public function getAll($case=null,$table=null,$where=null){
        if($case==null){

            $data= $this->db->query('SELECT * FROM '.$this->table);
            return $data->fetchAll();
        }
        else{
            $cases=join(',',$case);
            $data= $this->db->query("SELECT $cases FROM $this->table");
            return $data->fetchAll();

        }

    }
    public function update($data,$table=null,$where=null){
        is_null($table)?$table=$this->table:false;
        if(is_array($data)){
            $string=$this->getKeyValues($data,'string');
            
            if(is_array($where)){
                $data=array_merge($data,$where);
                $whereString=$this->getKeyValues($where,'string');
            }
            $request=$this->prepare('UPDATE '.$table.' SET '.$string.(is_null($where)?'':' WHERE '.$whereString),$data);
        }
    }
    public function delete($data,$table=null){
        is_null($table)?$table=$this->table:false;
        if(is_array($data))
        {
            $string=$this->getKeyValues($data,'string');
            
            $req=$this->prepare('DELETE FROM '.$table.' WHERE '.$string,$data);
            return $req;
        }
        else if(is_int($data)){
            $req=$this->prepare('DELETE FROM '.$table.' WHERE id=:id ',array('id'=>$data));
            return $req;
        }
    }
    public function insert($data,$table=null){
        is_null($table)?$table=$this->table:false;
        if(is_array($data))
        {
            $string=$this->getKeyValues($data);
            
            $req=$this->prepare('INSERT INTO '.$table.' ('.$string['params'].') VALUES('.$string['values'].')',$data);
            return $req;
        }
        else {
            throw new Exception('$data must be an array !!!!','0x101');
        }
    }
    public function getInsertedId(){
        return $this->query('SELECT LAST_INSERT_ID()');
    }
    /**
     * construct the string of key and one of values for the prepared sql request
     * @param string|null $type type of the returned value
     * @param string|null $separator used if close WHERE are used too
     * @return array|string array('params'=>string,'values'=>string):if $type==default -- string:if $type==='string'
     */
    public function getKeyValues(Array $data,$type='array',$separator=',')
    {
        
        if($type=='string'){
            $string='';
            foreach ($data as $key=>$value)
            {
                if(!$string)
                {
                    $string.=$key.'=:'.$key;
                }
                else{
                    $string.=' '.$separator.$key.'=:'.$key;
                }
            }
            return $string;
        }else{
            $values='';
            $params='';
            foreach ($data as $key=>$value)
            {
                if(!$params)
                {
                    $params=$params.$key;
                }
                else{
                    $params=$params.','.$key;
                }
                if(!$values){
                    $values=$values.':'.$key;
                }
                else{
                    $values=$values.',:'.$key;
                }
            }
            return array(
                'params'=>$params,
                'values'=>$values
            );
        }
        
    }
    /**
     * add data on the table instance
     * 
     */
    public function getTableInstances($data,$one=false){

        if($data){
            if($one){
                
                $instance=new $this->table_class_name();
                $instance->setData($data);
                return $instance;
            } 
            else{
                $instances=[];
                foreach ($data as $entry) {
                    $instance=new $this->table_class_name();
                    $instance->setData($entry);
                    array_push($instances,$instance);
                    
                }
                return $instances;
            }
        }
        else{
            if($one){
                
                $instance=new $this->table_class_name();
                return $instance;
            } 
            else{
                return array();
            }
        }

        
        


    }
    public function query($statement){

        return $this->db->query($statement);

    }
    public function prepare($statement,$data){

        return $this->db->prepare($statement,$data);

    }
    
    
    
}