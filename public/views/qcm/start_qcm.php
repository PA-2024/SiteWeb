<?php
include '../../header/entete.php';
require_once '../../../vendor/autoload.php';

use GeSign\SessionManager;

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Professeur');

$token = $_SESSION['token'] ?? $_COOKIE['token'];
$qcmId = $_GET['id'] ?? null;

if (!$token || !$qcmId) {
    header('Location: ../auth/login.php');
    exit;
}
$token = str_replace('Bearer ', '', $token);
?>
<head>
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
        }
        .card ul li:hover {
            background: #e7e7e7;
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
        .btn-danger {
            background: #dc3545;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        .hidden {
            display: none;
        }
        .answer-option {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 60px;
            margin: 10px 0;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .answer-option:nth-child(1) {
            background: #ff6f61;
        }
        .answer-option:nth-child(2) {
            background: #6a4c93;
        }
        .answer-option:nth-child(3) {
            background: #ffd700;
        }
        .answer-option:nth-child(4) {
            background: #28a745;
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
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ws = new WebSocket(`wss://apigessignrecette-c5e974013fbd.herokuapp.com/qcm/${<?php echo json_encode($qcmId); ?>}`);
            const token = <?php echo json_encode($token); ?>;
            const professorName = "ProfessorName";

            ws.onopen = function() {
                console.log("Connected to WebSocket");
                const joinMessage = {
                    action: "JOIN_PROFESSOR",
                    token: token,
                    professorName: professorName
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
                        //alert(message.message);
                        break;
                    case "STUDENT_LIST":
                        displayStudents(message.students);
                        break;
                    case "RANKING":
                        displayRanking(message.ranking);
                        break;
                    case "END":
                        displayEndMessage();
                        break;
                    case "INFO_TIMER":
                        displayTimer(message.passTime);
                        break;
                }
            };

            ws.onclose = function() {
                console.log("WebSocket connection closed");
            };

            document.getElementById("startQcmButton").addEventListener("click", function() {
                const startMessage = {
                    action: "START",
                    qcmId: <?php echo json_encode($qcmId); ?>
                };
                ws.send(JSON.stringify(startMessage));
                document.getElementById("startQcmButton").classList.add("hidden");
                document.getElementById("controls").classList.remove("hidden");
                document.getElementById("students").classList.add("hidden");
            });

            document.getElementById("pauseQcmButton").addEventListener("click", function() {
                const pauseMessage = { action: "PAUSE" };
                ws.send(JSON.stringify(pauseMessage));
            });

            document.getElementById("resumeQcmButton").addEventListener("click", function() {
                const playMessage = { action: "PLAY" };
                ws.send(JSON.stringify(playMessage));
            });

            document.getElementById("endQcmButton").addEventListener("click", function() {
                const endMessage = { action: "END" };
                ws.send(JSON.stringify(endMessage));
            });

            window.addEventListener("beforeunload", function() {
                const endMessage = { action: "END" };
                ws.send(JSON.stringify(endMessage));
            });

            function displayQuestion(question) {
                const questionDiv = document.getElementById("question");
                const rankingDiv = document.getElementById("ranking");
                questionDiv.innerHTML = `
                    <div class="card">
                        <h3>${question.text}</h3>
                        <ul>
                            ${question.options.map((option, index) => `<li class="answer-option">${index + 1}. ${option.text}</li>`).join('')}
                        </ul>
                    </div>
                `;
                rankingDiv.classList.add("hidden");
                questionDiv.classList.remove("hidden");
            }

            function displayStudents(students) {
                const studentsDiv = document.getElementById("students");
                studentsDiv.innerHTML = `
                    <div class="card">
                        <h3>Liste des étudiants</h3>
                        ${students.map(student => `<p>${student.name}</p>`).join('')}
                    </div>
                `;
            }

            function displayRanking(ranking) {
                const rankingDiv = document.getElementById("ranking");
                const questionDiv = document.getElementById("question");
                const timerDiv = document.getElementById("timer");
                rankingDiv.innerHTML = `
                    <div class="card">
                        <h3>Classement actuel</h3>
                        ${ranking.map(student => `<p>${student.name}: ${student.score} points</p>`).join('')}
                    </div>
                `;
                questionDiv.classList.add("hidden");
                timerDiv.classList.add("hidden");
                rankingDiv.classList.remove("hidden");
            }

            function displayTimer(time) {
                const timerDiv = document.getElementById("timer");
                timerDiv.innerText = `Temps restant : ${time} secondes`;
                timerDiv.classList.remove("hidden");
            }

            function displayEndMessage() {
                const questionDiv = document.getElementById("question");
                const rankingDiv = document.getElementById("ranking");
                const timerDiv = document.getElementById("timer");
                questionDiv.innerHTML = `
                    <div class="message">
                        Le QCM est terminé ! <span class="emoji">😊</span>
                    </div>
                `;
                questionDiv.classList.remove("hidden");
                rankingDiv.classList.remove("hidden");
                timerDiv.classList.add("hidden");
                document.getElementById("controls").classList.add("hidden");
            }
        });
    </script>
</head>
<body>
    <div class="main-wrapper">
        <?php include '../../header/entete_dashboard.php'; ?>
        <?php include '../../menu/menu_prof.php'; ?>
        <div class="page-wrapper">
            <div class="content">
                <h1>QCM</h1>
                <div id="timer" class="timer"></div>
                <button id="startQcmButton" class="btn">Démarrer le QCM</button>
                <div id="controls" class="hidden">
                    <button id="endQcmButton" class="btn btn-danger">Terminer le QCM</button>
                </div>
                <div id="question" class="mt-4"></div>
                <div id="students" class="mt-4"></div>
                <div id="ranking" class="mt-4 hidden"></div>
            </div>
        </div>
    </div>
    <script src="../../assets/js/jquery-3.7.1.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/select2.min.js"></script>
    <script src="../../assets/js/app.js"></script>
</body>
</html>
