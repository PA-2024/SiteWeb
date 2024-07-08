<?php
include '../../header/entete.php';
require_once '../../../vendor/autoload.php';

use GeSign\SessionManager;

$sessionManager = new SessionManager();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Eleve');

$token = $_SESSION['token'] ?? $_COOKIE['token'];
$qcmId = $_GET['id'] ?? null;

if (!$token || !$qcmId) {
    header('Location: ../auth/login.php');
    exit;
}
$token = str_replace('Bearer ', '', $token);
$studentId = $_SESSION['user_id'];
$studentName = $_SESSION['user_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>QCM</title>
    <style>
        .content {
            padding: 20px;
        }
        .card {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .card h3 {
            margin-bottom: 15px;
        }
        .card ul {
            list-style: none;
            padding: 0;
        }
        .card ul li {
            background: #f7f7f7;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 10px;
            transition: background 0.3s;
            color: white;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card ul li.selected {
            background: #0056b3;
        }
        .card ul li .checkmark {
            display: none;
        }
        .card ul li.selected .checkmark {
            display: inline;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background 0.3s;
            margin-right: 10px;
        }
        .btn:hover {
            background: #0056b3;
        }
        .message {
            font-size: 24px;
            color: #28a745;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
            padding: 20px;
            background: #e9ffe9;
            border-radius: 8px;
            border: 2px solid #28a745;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .message .emoji {
            font-size: 36px;
            margin-left: 10px;
        }
        .timer {
            font-size: 24px;
            color: #007bff;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        .loading {
            font-size: 24px;
            color: #007bff;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }
        .answer-option {
            background: #f7f7f7;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 10px;
            transition: background 0.3s;
            color: white;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .answer-option:nth-child(1) { background: #ff6f61; }
        .answer-option:nth-child(2) { background: #6a4c93; }
        .answer-option:nth-child(3) { background: #ffd700; }
        .answer-option:nth-child(4) { background: #28a745; }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ws = new WebSocket(`wss://apigessignrecette-c5e974013fbd.herokuapp.com/qcm/${<?php echo json_encode($qcmId); ?>}`);
            const studentId = <?php echo json_encode($studentId); ?>;
            const studentName = <?php echo json_encode($studentName); ?>;
            let selectedOptions = [];

            ws.onopen = function() {
                console.log("Connected to WebSocket");
                const joinMessage = {
                    action: "JOIN_STUDENT",
                    studentId: studentId,
                    studentName: studentName
                };
                ws.send(JSON.stringify(joinMessage));
            };

            ws.onmessage = function(event) {
                const message = JSON.parse(event.data);
                console.log("Received: ", message);

                switch (message.action) {
                    case "QUESTION":
                        displayQuestion(message);
                        break;
                    case "INFO":
                    case "ERROR":
                        alert(message.message);
                        break;
                    case "END":
                        displayEndMessage();
                        break;
                }
            };

            ws.onclose = function() {
                console.log("WebSocket connection closed");
            };

            function displayQuestion(question) {
                selectedOptions = [];
                const questionDiv = document.getElementById("question");
                questionDiv.innerHTML = `
                    <div class="card">
                        <h3>${question.text}</h3>
                        <ul>
                            ${question.options.map((option, index) => `
                                <li class="answer-option" data-option-id="${option.id}">
                                    ${index + 1}. ${option.text}
                                    <span class="checkmark">✔</span>
                                </li>`).join('')}
                        </ul>
                        <button id="submitAnswerButton" class="btn">Soumettre la réponse</button>
                    </div>
                `;

                document.querySelectorAll('.answer-option').forEach(option => {
                    option.addEventListener('click', function() {
                        const optionId = parseInt(this.getAttribute('data-option-id'), 10);
                        if (selectedOptions.includes(optionId)) {
                            selectedOptions = selectedOptions.filter(id => id !== optionId);
                            this.classList.remove('selected');
                        } else {
                            selectedOptions.push(optionId);
                            this.classList.add('selected');
                        }
                    });
                });

                document.getElementById('submitAnswerButton').addEventListener('click', function() {
                    if (selectedOptions.length === 0) {
                        alert('Veuillez sélectionner au moins une option.');
                        return;
                    }
                    const answerMessage = {
                        action: "ANSWER",
                        studentId: studentId,
                        answer: selectedOptions.map(Number)
                    };
                    ws.send(JSON.stringify(answerMessage));
                    displayLoading();
                });
            }

            function displayLoading() {
                const questionDiv = document.getElementById("question");
                questionDiv.innerHTML = `
                    <div class="loading">
                        En attente de la prochaine question...
                    </div>
                `;
            }

            function displayEndMessage() {
                const questionDiv = document.getElementById("question");
                questionDiv.innerHTML = `
                    <div class="message">
                        Le QCM est terminé ! <span class="emoji">😊</span>
                    </div>
                `;
            }
        });
    </script>
</head>
<body>
    <div class="main-wrapper">
        <?php include '../../header/entete_dashboard.php'; ?>
        <?php include '../../menu/menu_student.php'; ?>
        <div class="page-wrapper">
            <div class="content">
                <h1>Participer au QCM</h1>
                <div id="timer" class="timer"></div>
                <div id="question" class="mt-4"></div>
            </div>
        </div>
    </div>
    <script src="../../assets/js/jquery-3.7.1.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/select2.min.js"></script>
    <script src="../../assets/js/app.js"></script>
</body>
</html>
