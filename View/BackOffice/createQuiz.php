<?php
include_once '../../controller/QuizController.php';  // Correct the path as needed
include_once '../../Model/Quiz.php';  // If necessary, include the model here as well

$error = "";
$quizController = new QuizController();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate required fields
    $requiredFields = ["title", "description", "author"];
    $missingFields = [];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $missingFields[] = $field;
        }
    }

    if (empty($missingFields)) {
        // Create a new quiz instance
        $quiz = new Quiz(
            null,
            htmlspecialchars($_POST['title']),
            htmlspecialchars($_POST['description']),
            htmlspecialchars($_POST['author'])
        );

        // Add the quiz using the controller
        $quizId = $quizController->addQuiz($quiz);

        // Redirect to the quiz content page with the quiz ID
        header("Location: createQuizContent.php?quizId=$quizId");
        exit;
    } else {
        $error = "The following fields are missing: " . implode(", ", $missingFields);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Create Quiz - Dashboard</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8d7da;
            color: #721c24;
        }
        .form-control {
            border: 1px solid #721c24;
            background-color: #f5c6cb;
            color: #721c24;
        }
        .btn-primary {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .btn-primary:hover {
            background-color: #a71d2a;
            border-color: #981b24;
        }
        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div id="wrapper" class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-danger text-white">
                <h4 class="text-center">Create Quiz</h4>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <?php if (!empty($error)) : ?>
                        <div class="alert alert-danger"><?= $error; ?></div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input class="form-control" type="text" id="title" name="title" value="<?= $_POST['title'] ?? ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea class="form-control" id="description" name="description"><?= $_POST['description'] ?? ''; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="author">Author:</label>
                        <input class="form-control" type="text" id="author" name="author" value="<?= $_POST['author'] ?? ''; ?>">
                    </div>

                    <button type="submit" class="btn btn-primary btn-user btn-block">Create Quiz</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
