<?php
namespace App\DTO;

class SchoolSearch
{

    public $search;

    /**
     * Get the value of search
     */ 
    public function getSearch()
    {
        return $this->search;
    }
    /**
     * Set the value of search
     *
     * @return  self
     */ 
    public function setSearch($search)
    {
        $this->search = $search;
        return $this;
    }

}

?>