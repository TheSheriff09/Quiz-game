<?php
require_once(__DIR__ . '/../config.php');
include(__DIR__ . '/../Model/QuizContent.php');

class QuizContentController {

    private $db;

    public function __construct() {
        $this->db = config::getConnexion(); // Use the existing connection method
    }

    // List all quizzes
    public function listQuizzes() {
        $sql = "SELECT * FROM quizzes";
        $db = config::getConnexion();
        try {
            $stmt = $db->query($sql);
            $quizzes = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $quizzes[] = new Quiz(
                    $row['QuizID'],
                    $row['Title'],
                    $row['Description'],
                    $row['Author'],
                    new DateTime($row['CreatedAt'])
                );
            }
            return $quizzes;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // Delete a quiz by ID
    public function deleteQuiz($id) {
        $sql = "DELETE FROM quizzes WHERE QuizID = :id";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id', $id);

        try {
            $req->execute();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // Add a new quiz content to the database
    public function addQuizContent($quizContent) {
        try {
            // Updated query with the correct column names (QuizID, QuestionText, etc.)
            $query = "INSERT INTO quizcontent (QuizID, QuestionText, option1, option2, option3, option4, CorrectOption) 
                      VALUES (:QuizID, :QuestionText, :option1, :option2, :option3, :option4, :CorrectOption)";
            
            $stmt = $this->db->prepare($query);
    
            // Get values and assign to variables
            $quizID = $quizContent->getQuizId();  // Make sure getQuizId() method exists and works
            $questionText = $quizContent->getQuestionText();
            $option1 = $quizContent->getOption1();
            $option2 = $quizContent->getOption2();
            $option3 = $quizContent->getOption3();
            $option4 = $quizContent->getOption4();
            $correctOption = $quizContent->getCorrectOption();
    
            // Bind the data to prevent SQL injection
            $stmt->bindParam(':QuizID', $quizID);
            $stmt->bindParam(':QuestionText', $questionText);
            $stmt->bindParam(':option1', $option1);
            $stmt->bindParam(':option2', $option2);
            $stmt->bindParam(':option3', $option3);
            $stmt->bindParam(':option4', $option4);
            $stmt->bindParam(':CorrectOption', $correctOption);
    
            // Execute the statement and return the result
            if ($stmt->execute()) {
                return true; // Success
            } else {
                // Log and/or output error information
                error_log("Error inserting quiz content.");
                return false; // Failure
            }
        } catch (PDOException $e) {
            // Handle any potential errors by logging or displaying the exception message
            error_log("Error inserting quiz content: " . $e->getMessage());
            return false; // Return false on failure
        }
    }

    // Get quiz content by quiz ID
    public function getQuizContentByQuizId($quizId) {
        $query = "SELECT * FROM quizcontent WHERE QuizID = :QuizID";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':QuizID', $quizId);
        $stmt->execute();
        $content = $stmt->fetchAll();
        $quizContentArray = [];
        foreach ($content as $item) {
            $quizContentArray[] = new QuizContent(
                null, $item['ContentID'], $item['QuizID'], $item['QuestionText'], 
                $item['Option1'], $item['Option2'], $item['Option3'], 
                $item['Option4'], $item['CorrectOption']
            );
        }
        return $quizContentArray;
    }

    // Update quiz content
    public function updateQuizContent($contentId, $questionText, $option1, $option2, $option3, $option4, $correctOption) {
        // SQL query to update quiz content
        $sql = "UPDATE quizcontent SET 
                    QuestionText = :questionText,
                    Option1 = :option1,
                    Option2 = :option2,
                    Option3 = :option3,
                    Option4 = :option4,
                    CorrectOption = :correctOption
                WHERE ContentID = :contentId";

        // Prepare the query
        $stmt = $this->db->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':contentId', $contentId);
        $stmt->bindParam(':questionText', $questionText);
        $stmt->bindParam(':option1', $option1);
        $stmt->bindParam(':option2', $option2);
        $stmt->bindParam(':option3', $option3);
        $stmt->bindParam(':option4', $option4);
        $stmt->bindParam(':correctOption', $correctOption);

        // Execute the query
        try {
            $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Error updating question with ContentID $contentId: " . $e->getMessage());
        }
    }
}
?>
