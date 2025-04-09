<?php
include '../../controller/QuizController.php';

// Check if the `id` parameter is provided in the URL
if (isset($_GET["id"])) {
    $quizId = $_GET["id"];
} else {
    // Redirect to the quiz list page if no ID is provided
    header('Location:quizList.php?error=missing_id');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["confirm_delete"])) {
        $quizController = new QuizController();
        $quizController->deleteQuiz($quizId);
        header('Location:quizList.php?message=quiz_deleted');
        exit();
    } elseif (isset($_POST["cancel"])) {
        // Redirect back to the quiz list if deletion is canceled
        header('Location:quizList.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Quiz</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-danger text-white">
                <h3 class="text-center">Delete Quiz</h3>
            </div>
            <div class="card-body">
                <p>Are you sure you want to delete this quiz?</p>
                <form method="POST" action="deleteQuiz.php?id=<?= htmlspecialchars($quizId); ?>">
                    <button type="submit" name="confirm_delete" class="btn btn-danger">Yes, Delete</button>
                    <button type="submit" name="cancel" class="btn btn-secondary">Cancel</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
</body>
</html>
