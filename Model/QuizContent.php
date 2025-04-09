<?php
class QuizContent {
    private $contentId;
    private $id;               
    private $quizId;           // Quiz ID (the associated quiz's ID)
    private $questionText;     // The question text
    private $option1;          // Option 1
    private $option2;          // Option 2
    private $option3;          // Option 3
    private $option4;          // Option 4
    private $correctOption;    // The correct option

    public function __construct($id, $contentId, $quizId, $questionText, $option1, $option2, $option3, $option4, $correctOption) {
        $this->id = $id;
        $this->contentId = $contentId;
        $this->quizId = $quizId;
        $this->questionText = $questionText;
        $this->option1 = $option1;
        $this->option2 = $option2;
        $this->option3 = $option3;
        $this->option4 = $option4;
        $this->correctOption = $correctOption;
    }

    // Getter methods
    public function getId() {
        return $this->id;
    }

    public function getQuizId() {
        return $this->quizId;
    }

    public function getContentId() {
        return $this->contentId;  // Return ContentID
    }

    public function getQuestionText() {
        return $this->questionText;
    }

    public function getOption1() {
        return $this->option1;
    }

    public function getOption2() {
        return $this->option2;
    }

    public function getOption3() {
        return $this->option3;
    }

    public function getOption4() {
        return $this->option4;
    }

    public function getCorrectOption() {
        return $this->correctOption;
    }

    // Setter methods
    public function setQuizId($quizId) {
        $this->quizId = $quizId;
    }

    public function setContentId($contentId) {
        $this->contentId = $contentId;  // Set ContentID
    }

    public function setQuestionText($questionText) {
        $this->questionText = $questionText;
    }

    public function setOption1($option1) {
        $this->option1 = $option1;
    }

    public function setOption2($option2) {
        $this->option2 = $option2;
    }

    public function setOption3($option3) {
        $this->option3 = $option3;
    }

    public function setOption4($option4) {
        $this->option4 = $option4;
    }

    public function setCorrectOption($correctOption) {
        $this->correctOption = $correctOption;
    }
}
?>
