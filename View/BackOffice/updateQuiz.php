<?php
// Include necessary files and instantiate the controller
include '../../controller/QuizController.php';
include '../../controller/QuizCController.php';

$quizContentController = new QuizContentController();
$quizController = new QuizController();  // Instantiate the QuizController

$error = null;  // Initialize error variable

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {

    // Loop through each question
    if (isset($_POST['questionText'])) {
        foreach ($_POST['questionText'] as $index => $questionText) {
            if (empty($questionText) || empty($_POST['option1'][$index]) || empty($_POST['option2'][$index]) || empty($_POST['correctOption'][$index])) {
                $error = "Missing information for question " . ($index + 1);
                break;
            }
            if (strlen($questionText) > 255) {
                $error = "Question text is too long for question " . ($index + 1);
                break;
            }

            $quizContent = new QuizContent(
                null,
                $_POST['contentId'][$index],
                $_POST['id'],
                htmlspecialchars($questionText),
                htmlspecialchars($_POST['option1'][$index]),
                htmlspecialchars($_POST['option2'][$index]),
                isset($_POST['option3'][$index]) ? htmlspecialchars($_POST['option3'][$index]) : null,
                isset($_POST['option4'][$index]) ? htmlspecialchars($_POST['option4'][$index]) : null,
                (int)$_POST['correctOption'][$index]
            );

            try {
                $quizContentController->updateQuizContent(
                    $quizContent->getContentId(), 
                    $quizContent->getQuestionText(),
                    $quizContent->getOption1(),
                    $quizContent->getOption2(),
                    $quizContent->getOption3(),
                    $quizContent->getOption4(),
                    $quizContent->getCorrectOption()
                );
            } catch (Exception $e) {
                $error = "Error updating quiz content: " . $e->getMessage();
                break;
            }
        }
    }

    if ($error) {
        echo "<div class='alert alert-danger'>$error</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Update Quiz - Dashboard</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">

    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-danger sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-text mx-3">Quiz Management</div>
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item active">
                <a class="nav-link" href="quizList.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Back to Quiz List</span></a>
            </li>
        </ul>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow"></nav>
                <!-- Page Content -->
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Update the Quiz with Id = <?php echo $_POST['id'] ?> </h1>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-md-6 mb-4">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <?php
                                        if (isset($_POST['id'])) {
                                            $quiz = $quizController->showQuiz($_POST['id']);
                                            $quizContentArray = $quizContentController->getQuizContentByQuizId($_POST['id']);
                                        ?>
                                            <form id="updateQuizForm" action="" method="POST">
                                                <label for="id">ID:</label>
                                                <input class="form-control form-control-user" type="text" id="id" name="id" readonly value="<?php echo $_POST['id'] ?>">

                                                <label for="title">Title:</label>
                                                <input class="form-control form-control-user" type="text" id="title" name="title" value="<?php echo htmlspecialchars($quiz['Title']) ?>"><br>

                                                <label for="description">Description:</label>
                                                <textarea class="form-control form-control-user" id="description" name="description"><?php echo htmlspecialchars($quiz['Description']) ?></textarea><br>

                                                <label for="author">Author:</label>
                                                <input class="form-control form-control-user" type="text" id="author" name="author" value="<?php echo htmlspecialchars($quiz['Author']) ?>"><br>

                                                <?php foreach ($quizContentArray as $index => $content) { ?>
                                                    <div class="question-section">
                                                        <hr>
                                                        <h5>Question <?php echo $index + 1; ?></h5>
                                                        <input type="hidden" name="contentId[]" value="<?php echo htmlspecialchars($content->getContentId()); ?>">

                                                        <label for="questionText">Question Text:</label>
                                                        <input class="form-control form-control-user" type="text" name="questionText[]" value="<?php echo htmlspecialchars($content->getQuestionText()); ?>"><br>

                                                        <label for="option1">Option 1:</label>
                                                        <input class="form-control form-control-user" type="text" name="option1[]" value="<?php echo htmlspecialchars($content->getOption1()); ?>"><br>

                                                        <label for="option2">Option 2:</label>
                                                        <input class="form-control form-control-user" type="text" name="option2[]" value="<?php echo htmlspecialchars($content->getOption2()); ?>"><br>

                                                        <label for="option3">Option 3 (Optional):</label>
                                                        <input class="form-control form-control-user" type="text" name="option3[]" value="<?php echo htmlspecialchars($content->getOption3()); ?>"><br>

                                                        <label for="option4">Option 4 (Optional):</label>
                                                        <input class="form-control form-control-user" type="text" name="option4[]" value="<?php echo htmlspecialchars($content->getOption4()); ?>"><br>

                                                        <label for="correctOption">Correct Option:</label>
                                                        <select class="form-control form-control-user" name="correctOption[]">
                                                            <option value="1" <?php echo $content->getCorrectOption() == 1 ? 'selected' : ''; ?>>Option 1</option>
                                                            <option value="2" <?php echo $content->getCorrectOption() == 2 ? 'selected' : ''; ?>>Option 2</option>
                                                            <option value="3" <?php echo $content->getCorrectOption() == 3 ? 'selected' : ''; ?>>Option 3</option>
                                                            <option value="4" <?php echo $content->getCorrectOption() == 4 ? 'selected' : ''; ?>>Option 4</option>
                                                        </select><br>
                                                    </div>
                                                <?php } ?>

                                                <button class="btn btn-danger btn-user btn-block" type="submit">Save Changes</button>
                                            </form>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>
</html>
