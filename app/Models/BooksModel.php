<?php

namespace App\Models;

class BooksModel extends Model{

    
    public function getBook($index){
        if(is_int($index)){
            $data=$this->prepare("SELECT * FROM $this->table WHERE id=:id",array('id'=>$index));
        }
        if(is_string($index)){
            $data=$this->prepare("SELECT * FROM $this->table WHERE titre=:titre",array('titre'=>$index));
        }
        return $data->fetch();
    }
    public function addBook($data){

        $this->insert($data);

    }
    public function updateBook($data,$id){

        $this->update($data,null,array('id'=>$id));

    }
    public function search($q){
        return $this->prepare("SELECT * FROM $this->table WHERE titre LIKE %:q% OR auteur LIKE %:q% OR ville LIKE %:q% OR genre LIKE %:q% OR bref LIKE %:q%",array('q',$q));
    }
    public function getStatistic(){
        $statistic=$this->query("SELECT COUNT(*) as books,SUM(nbr) as items FROM $this->table");
        return $statistic->fetch();
    }
    public function lend($data){
        $this->insert($data,'lended_books');
        $nbr=isset($data['nbr'])?$data['nbr']:1;
        $this->prepare("UPDATE books SET nbr=nbr-$nbr WHERE id=:id",array('id'=>$data['id_livre']));
    }
    public function lendedBooks($id=null){
        if($id){

            $q=$this->query("SELECT titre,lend_id,nom_client,adresse_client,nbr_items,date_livraison,date_remise,classe_eleve,option_eleve FROM lended_books INNER JOIN books ON lended_books.id_livre=books.id WHERE lend_id=$id");
            return $q->fetch();
        }
        $q=$this->query("SELECT id,titre,lend_id,nom_client,adresse_client,nbr_items,date_livraison FROM books INNER JOIN lended_books WHERE books.id=lended_books.id_livre");
        return $q->fetchAll();
    }
    
    
}