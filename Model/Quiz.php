<?php
class Quiz {
    private $id;
    private $title;
    private $description;
    private $author;

    public function __construct($id, $title, $description, $author) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->author = $author;
        
    }

    // Getter methods
    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getAuthor() {
        return $this->author;
    }

   
}
?>
