<?php
session_start(); // Start the session to access user information
include '../../controller/QuizController.php';
$quizController = new QuizController();

$userId = $_SESSION['user_id'] ?? null; // Assuming user ID is stored in session after login



// Check if 'sort' is set in the URL, and use it; otherwise, default to 'asc'
$sortOrder = isset($_GET['sort']) && $_GET['sort'] === 'desc' ? 'desc' : 'asc';
$list = $quizController->sortQuizzes($sortOrder);

// Fetch quizzes the user has already completed
$completedQuizzes = $quizController->getCompletedQuizzesByUser($userId); // Example method
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Quiz List</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa; /* Light background */
        }
        .header {
            background: linear-gradient(to right, #ff4d4d, #cc0000); /* Red gradient */
            color: white;
            padding: 20px;
            text-align: center;
        }
        .btn-red {
            background-color: #ff4d4d;
            border-color: #cc0000;
            color: white;
        }
        .btn-red:hover {
            background-color: #cc0000;
            color: white;
        }
        .table thead {
            background-color: #cc0000;
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Available Quizzes</h1>
    </div>

    <div class="container mt-4">
        <!-- Sort Buttons -->
        <div class="d-flex justify-content-end mb-3">
            <a href="?sort=asc" class="btn btn-red btn-sm mx-1">Sort A-Z</a>
            <a href="?sort=desc" class="btn btn-red btn-sm mx-1">Sort Z-A</a>
        </div>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Author</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($list as $quiz): ?>
                    <tr>
                        <td><?= htmlspecialchars($quiz->getId()); ?></td>
                        <td><?= htmlspecialchars($quiz->getTitle()); ?></td>
                        <td><?= htmlspecialchars($quiz->getDescription()); ?></td>
                        <td><?= htmlspecialchars($quiz->getAuthor()); ?></td>
                        <td>
                            <?php if (in_array($quiz->getId(), $completedQuizzes)): ?>
                                <button class="btn btn-secondary btn-sm" disabled>Completed</button>
                            <?php else: ?>
                                <a href="quizContent.php?id=<?= htmlspecialchars($quiz->getId()); ?>" class="btn btn-red btn-sm">Take Quiz</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
