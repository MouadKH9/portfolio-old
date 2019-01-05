<?php
    class Project{
        var $id;
        var $name;
        var $description;
        var $image;
        var $date;
        var $tags = array();
        var $views;
        public function __construct($id,$name,$description,$image,$date,$tags,$views) {
            $this->id = $id;
            $this->name = $name;
            $this->description = $description;
            $this->image = $image;
            $this->date = $date;
            $this->views = $views;
            $tags_arr = explode(",",$tags);
            foreach ($tags_arr as $tag)
                array_push($this->tags,$tag);
        }
    }
?>