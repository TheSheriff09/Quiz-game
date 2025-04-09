<?php
include '../../controller/QuizCController.php';
$quizContentController = new QuizContentController();

// Get the quiz ID from the URL query parameters
$quizId = $_GET['id'] ?? null;

// Fetch the quiz content for the given ID
$questions = $quizContentController->getQuizContentByQuizId($quizId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Take Quiz</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8d7da; /* Light red background */
        }
        .card {
            border-color: #dc3545;
        }
        .card-header, .btn {
            background-color: #dc3545; /* Red for header and buttons */
            color: #fff;
        }
        .form-check-input:checked {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .alert-success {
            background-color: #d4edda; /* Light green */
            color: #155724;
            border-color: #28a745;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #dc3545;
        }
        .hint-btn {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow p-4">
            <h1 class="text-center mb-4">Take the Quiz</h1>
            
            <!-- Timer Display -->
            <div id="timer" class="text-center mb-4">Time remaining: <span id="timeRemaining">30</span> seconds</div>
            
            <?php if (!empty($questions)): ?>
                <form id="quizForm">
                    <?php foreach ($questions as $index => $question): ?>
                        <div class="mb-4">
                            <label class="form-label">
                                <strong><?= ($index + 1) . '. ' . htmlspecialchars($question->getQuestionText()); ?></strong>
                            </label>
                            <div class="form-check">
                                <?php
                                $options = [
                                    1 => $question->getOption1(),
                                    2 => $question->getOption2(),
                                    3 => $question->getOption3(),
                                    4 => $question->getOption4()
                                ];
                                foreach ($options as $optionId => $optionText): ?>
                                    <input 
                                        class="form-check-input mb-2" 
                                        type="radio" 
                                        name="question_<?= htmlspecialchars($question->getContentId()); ?>" 
                                        value="<?= $optionId; ?>" 
                                        data-correct="<?= $question->getCorrectOption(); ?>" 
                                        required>
                                    <label class="form-check-label d-block">
                                        <?= htmlspecialchars($optionText); ?>
                                    </label>
                                <?php endforeach; ?>
                                <!-- Hint button to tick the correct option -->
                                <button type="button" class="btn btn-secondary hint-btn" onclick="showHint(<?= htmlspecialchars($question->getContentId()); ?>)">Show Hint</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <button type="button" id="submitBtn" class="btn btn-danger w-100" onclick="calculateScore()">Submit Quiz</button>
                </form>
                <div id="result" class="alert mt-4 d-none"></div>
                <div id="quizListButton" class="mt-4 text-center d-none">
                    <a href="quizlist.php" class="btn btn-primary">Go to Quiz List</a>
                </div>
            <?php else: ?>
                <p class="text-danger">No questions available for this quiz.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let timeRemaining = 30;  // Set timer to 30 seconds
        const timerElement = document.getElementById('timeRemaining');
        const form = document.getElementById('quizForm');
        const resultDiv = document.getElementById('result');
        const quizListButton = document.getElementById('quizListButton');
        const submitBtn = document.getElementById('submitBtn');

        // Function to start the countdown timer
        function startTimer() {
            const timerInterval = setInterval(function() {
                if (timeRemaining > 0) {
                    timeRemaining--;
                    timerElement.textContent = timeRemaining;
                } else {
                    clearInterval(timerInterval);  // Stop the timer when it reaches 0
                    disableQuiz();
                    resultDiv.classList.remove('d-none', 'alert-success');
                    resultDiv.classList.add('alert-danger');
                    resultDiv.innerHTML = `<strong>Time's up!</strong> You cannot change your answers anymore.`;
                    showQuizListButton();
                }
            }, 1000);
        }

        // Function to disable all radio buttons after time runs out or when user clicks submit
        function disableQuiz() {
            const inputs = form.querySelectorAll('input[type="radio"]');
            inputs.forEach(input => {
                input.disabled = true;
            });
            submitBtn.disabled = true;  // Disable the submit button after submission
        }

        // Function to calculate score and disable inputs after submission
        function calculateScore() {
            const questions = form.querySelectorAll('.mb-4');
            let score = 0;

            // Disable inputs right after submission
            disableQuiz();

            // Calculate score
            questions.forEach(question => {
                const selectedOption = question.querySelector('input[type="radio"]:checked');
                if (selectedOption) {
                    const correctAnswer = selectedOption.dataset.correct;
                    if (selectedOption.value === correctAnswer) {
                        score++;
                    }
                }
            });

            if (questions.length === document.querySelectorAll('input[type="radio"]:checked').length) {
                if (score === questions.length) {
                    resultDiv.classList.remove('d-none', 'alert-danger');
                    resultDiv.classList.add('alert-success');
                    resultDiv.innerHTML = `<strong>Well done!</strong> Your Score: ${score} / ${questions.length}`;
                } else {
                    resultDiv.classList.remove('d-none', 'alert-success');
                    resultDiv.classList.add('alert-danger');
                    resultDiv.innerHTML = `<strong>Incorrect!</strong> Your Score: ${score} / ${questions.length}`;
                }
            } else {
                resultDiv.classList.remove('d-none', 'alert-success');
                resultDiv.classList.add('alert-danger');
                resultDiv.innerHTML = `<strong>Error:</strong> There's no answer!`;
            }

            // Show the "Go to Quiz List" button after submission
            showQuizListButton();
        }

        // Show the "Go to Quiz List" button
        function showQuizListButton() {
            quizListButton.classList.remove('d-none');
        }

        // Function to show the correct option when the hint button is clicked
        function showHint(questionId) {
            // Find the correct option for this question
            const options = document.querySelectorAll(`input[name="question_${questionId}"]`);
            options.forEach(option => {
                if (option.value == option.dataset.correct) {
                    option.checked = true;
                }
            });
        }

        // Start the timer when the page loads
        window.onload = startTimer;
    </script>
    <script> window.chtlConfig = { chatbotId: "4979374878" } </script>
<script async data-id="4979374878" id="chatling-embed-script" type="text/javascript" src="https://chatling.ai/js/embed.js"></script>

</body>
</html>
