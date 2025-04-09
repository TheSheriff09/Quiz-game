<?php
include '../../controller/QuizController.php';
include '../../controller/QuizCController.php';

$error = "";
$quizContentController = new QuizContentController();
$quizController = new QuizController();

if (!isset($_GET['quizId'])) {
    die("Quiz ID not provided.");
}

$quizId = $_GET['quizId'];

// Check if data is posted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['questionText'], $_POST['option1'], $_POST['option2'], $_POST['option3'], $_POST['option4'], $_POST['correctOption'])) {
        $questions = $_POST['questionText'];
        $options1 = $_POST['option1'];
        $options2 = $_POST['option2'];
        $options3 = $_POST['option3'];
        $options4 = $_POST['option4'];
        $correctOptions = $_POST['correctOption'];

        for ($i = 0; $i < count($questions); $i++) {
            if (!empty($questions[$i]) && !empty($options1[$i]) && !empty($options2[$i]) && !empty($options3[$i]) && !empty($options4[$i]) && !empty($correctOptions[$i])) {
                // Create a new quiz content instance
                $quizContent = new QuizContent(
                    null, // This will be ignored since the field is auto-incremented
                    null, // QuizID is passed separately
                    $quizId,
                    htmlspecialchars($questions[$i]),
                    htmlspecialchars($options1[$i]),
                    htmlspecialchars($options2[$i]),
                    htmlspecialchars($options3[$i]),
                    htmlspecialchars($options4[$i]),
                    htmlspecialchars($correctOptions[$i])
                );

                // Add the quiz content using the controller
                if (!$quizContentController->addQuizContent($quizContent)) {
                    $error = "Failed to add quiz content for question " . ($i + 1);
                    break;
                }
            } else {
                $error = "One or more questions are missing required fields.";
                break;
            }
        }

        if (empty($error)) {
            // Redirect back to the quiz list page
            header('Location: quizList.php');
            exit;
        }
    } else {
        $error = "No questions were submitted.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Add Quiz Content - Dashboard</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8d7da; /* Light red background for the red theme */
            color: #721c24; /* Dark red text for warnings or errors */
        }

        .form-control {
            border: 1px solid #f5c6cb; /* Light red border */
        }

        .btn-primary {
            background-color: #dc3545; /* Bootstrap danger red */
            border-color: #dc3545;
        }

        .btn-secondary {
            background-color: #f5c6cb;
            color: #721c24;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }

        .question-block h5 {
            color: #721c24; /* Red text for section titles */
        }
    </style>
    <script>
        function addQuestion() {
            const formContainer = document.getElementById('questions-container');
            const questionCount = document.getElementsByClassName('question-block').length;
            const newQuestionHTML = `
                <div class="question-block">
                    <h5>Question ${questionCount + 1}</h5>
                    <label for="questionText-${questionCount}">Question Text:</label>
                    <textarea class="form-control" id="questionText-${questionCount}" name="questionText[]"></textarea><br>
                    
                    <label for="option1-${questionCount}">Option 1:</label>
                    <input class="form-control" type="text" id="option1-${questionCount}" name="option1[]"><br>
                    
                    <label for="option2-${questionCount}">Option 2:</label>
                    <input class="form-control" type="text" id="option2-${questionCount}" name="option2[]"><br>
                    
                    <label for="option3-${questionCount}">Option 3:</label>
                    <input class="form-control" type="text" id="option3-${questionCount}" name="option3[]"><br>
                    
                    <label for="option4-${questionCount}">Option 4:</label>
                    <input class="form-control" type="text" id="option4-${questionCount}" name="option4[]"><br>
                    
                    <label for="correctOption-${questionCount}">Correct Option:</label>
                    <input class="form-control" type="text" id="correctOption-${questionCount}" name="correctOption[]"><br>
                    
                    <hr>
                </div>`;
            formContainer.insertAdjacentHTML('beforeend', newQuestionHTML);
        }
    </script>
</head>

<body>
    <div id="wrapper">
        <form action="" method="POST">
            <?php if (!empty($error)) : ?>
                <div class="alert alert-danger"><?= $error; ?></div>
            <?php endif; ?>

            <div id="questions-container">
                <div class="question-block">
                    <h5>Question 1</h5>
                    <label for="questionText-0">Question Text:</label>
                    <textarea class="form-control" id="questionText-0" name="questionText[]"></textarea><br>
                    
                    <label for="option1-0">Option 1:</label>
                    <input class="form-control" type="text" id="option1-0" name="option1[]"><br>
                    
                    <label for="option2-0">Option 2:</label>
                    <input class="form-control" type="text" id="option2-0" name="option2[]"><br>
                    
                    <label for="option3-0">Option 3:</label>
                    <input class="form-control" type="text" id="option3-0" name="option3[]"><br>
                    
                    <label for="option4-0">Option 4:</label>
                    <input class="form-control" type="text" id="option4-0" name="option4[]"><br>
                    
                    <label for="correctOption-0">Correct Option:</label>
                    <input class="form-control" type="text" id="correctOption-0" name="correctOption[]"><br>
                </div>
            </div>

            <button type="button" class="btn btn-secondary btn-user btn-block" onclick="addQuestion()">Add More Questions</button><br>
            <button type="submit" class="btn btn-primary btn-user btn-block">Submit All Questions</button>
        </form>
    </div>
</body>

</html>
