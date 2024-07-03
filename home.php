<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question Generation and Progress Tracker</title>
   <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <ul>
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
            <li><a href="#" id="Test-link">ONLINE IQ TEST</a></li>
            <li><a href="#" id="home-link">Home</a></li>
            <li><a href="#" id="iq-test-link">IQ Test</a></li>
            <li><a href="#" id="about-link">About</a></li>
            <li><a href="#" id="progress-link">Your Progress</a></li>
           <li><a href="logout.php">Logout</a></li> 
        </ul>

       
    </nav>

    <div id="progress-bar">
        <div id="progress"></div>
    </div>

    <div id="content">
        <section id="home-section">
            <h1>Welcome to the Online IQ Test</h1>
            <p>This application allows you to take an IQ test to assess your intelligence quotient. The test consists of a series of questions that will evaluate your problem-solving abilities and logical reasoning.</p>
            <button id="start-iq-test">Start IQ Test</button>
            <h2>What is an IQ test?</h2>
            <p>IQ is an abbreviation of Intelligence Quotient. So, OK, but what is IQ? The IQ is a measurement of your intelligence and is expressed in a number. It’s an estimate also, there will always be a given amount of measurement error.
               A person’s IQ can be calculated by having the person take an intelligence test. The average IQ is 100 by definition. If you achieve a score higher than 100, you did better than the average person, and a lower score means you (somewhat) performed less.</p>
            <h2>What is a good IQ score?</h2>
            <p>The average IQ score is always 100, and your personal score tells you your IQ ranking compared to the average. IQ scores are based on comparisons with other people who took the test: the norm group. As 100 is the average score, your score tells you how your IQ score ranks compared to other people. Most people score between 85 and 115.

                About 2% of the population has an IQ score lower than 69. Such a low IQ score often is hard to measure using a regular intelligence test. Very high IQ scores, say over 150, are also hard to determine accurately. This is because you need a lot of reference measurements to determine a specific score reliably. As very high and very low IQ scores simply do not occur often, it is hard to form such a reference group.</p>
            <h2>Can I practice IQ tests?</h2>
            <p>You can practice IQ tests, it will not make you more intelligent but it will increase your IQ score on a job test for example. Be sure to try any IQ test we offer for free in our IQ tests section. Especially for assessment preparation this will be very useful!</p>
            <h2>What IQ test types are there?</h2>
            <p>In a classic IQ test you will generally find questions related to numerical reasoning, logical reasoning, verbal intelligence and spatial intelligence. An IQ test that primarily measures abstract reasoning, either inductive or deductive, will often be a non-verbal IQ test. The latter is then called culture-fair because of that.

                Both classic and non-verbal IQ tests make for valid and reliable IQ score measurements, although their definitions of intelligence do vary. Generally an IQ test consisting of more questions will have a higher reliability than shorter tests.</p>
            
        </section>

        <section id="question-section" style="display:none;">
            <h1>IQ Test Questions</h1>
            <div id="question-container"></div>
            <button id="next-question">Next Question</button>
        </section>

        <section id="progress-section" style="display:none;">
            <h1 style="text-align: center;">Please answer the questions below</h1>
            <form id="user-info-form">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select><br>
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" min="1" max="100" required><br>
                <label for="education">Educational Level:</label>
                <select id="education" name="education" required>
                    <option value="">Select Education Level</option>
                    <option value="highschool">High School</option>
                    <option value="bachelor">Bachelor's</option>
                    <option value="master">Master's</option>
                    <option value="doctorate">Doctorate</option>
                    <option value="other">Other</option>
                </select><br>
                <button type="submit">Submit</button>
            </form>
            <p id="progress-text" style="display: none; text-align: center; font-weight: 700;">You have answered <span id="correct-count">0</span> questions correctly out of <span id="total-count">0</span>.</p>
            <div id="progress-chart-container" style="display:none;">
                <canvas id="progress-chart"></canvas>
            </div>
            <div id="iq-result" style="display:none; margin-top: -250px; font-weight: bold; font-size: xx-large;">
                Congratulations Your estimated IQ: <span id="iq-score"></span>
            </div>
            <button id="restart" style="display:none; margin-bottom: 200px; align-items: center;">Restart</button>
        </section>

        <section id="about-section" style="display:none; font-size:20px; font-weight: bold;">
            <h1>About This App</h1>
            <p>This application generates random questions to test your knowledge and track your progress. It allows you to answer multiple-choice questions and keeps a record of your correct answers and total attempts.</p>
            <p>Features:</p>
            <ul>
                <li>Random question generation</li>
                <li>Multiple-choice answers</li>
                <li>Progress tracking</li>
                <li>Progress bar indicating completion</li>
                <li>Ability to restart the quiz at any time</li>
            </ul>
            <p>Use this app to challenge yourself and improve your knowledge!</p>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const homeLink = document.getElementById('home-link');
            const iqTestLink = document.getElementById('iq-test-link');
            const aboutLink = document.getElementById('about-link');
            const progressLink = document.getElementById('progress-link');
            const homeSection = document.getElementById('home-section');
            const questionSection = document.getElementById('question-section');
            const progressSection = document.getElementById('progress-section');
            const aboutSection = document.getElementById('about-section');
            const questionContainer = document.getElementById('question-container');
            const nextQuestionButton = document.getElementById('next-question');
            const startIqTestButton = document.getElementById('start-iq-test');
            const correctCountSpan = document.getElementById('correct-count');
            const totalCountSpan = document.getElementById('total-count');
            const progressBar = document.getElementById('progress');
            const restartButton = document.getElementById('restart');
            const progressChart = document.getElementById('progress-chart').getContext('2d');
            const iqScoreSpan = document.getElementById('iq-score');
            const iqResultDiv = document.getElementById('iq-result');

            const questions = [
                {
                    id: 1,
                    question: "Q. What is the capital of France?",
                    options: ["Paris", "London", "Berlin", "Madrid"],
                    correctAnswer: 0
                },
                {
                    id: 2,
                    question: "Q. What comes next in the series 5,6,9,14,21?",
                    options: ["25", "30", "31", "36"],
                    correctAnswer: 1
                },
                {
                    id: 3,
                    question: "Q. What comes next in the series 40,30,22,16?",
                    options: ["12", "10", "14", "30"],
                    correctAnswer: 0
                },
                {
                    id: 4,
                    question: "Q. What comes next in the series 1,3,6,11,18?",
                    options: ["20", "24", "25", "29"],
                    correctAnswer: 3
                },
                {
                    id: 5,
                    question: "Q. Which fraction is the biggest?",
                    options: ["3/5", "5/8", "1/2", "4/7"],
                    correctAnswer: 1
                },
                {
                    id: 6,
                    question: "Q. Which number is missing 8, 10, 14, 18, ?, 34, 50, 66",
                    options: ["20", "26", "28", "30"],
                    correctAnswer: 1
                },
                {
                    id: 7,
                    question: "Q. What comes before three day from sunday.",
                    options: ["Monday", "Thursday", "Tuesday", "Friday"],
                    correctAnswer: 1
                },
                {
                    id: 8,
                    question: "Q. What is the name give to a group of hourses.",
                    options: ["Husk", "Harras", "Mute", "Rush"],
                    correctAnswer: 1
                },
                {
                    id: 9,
                    question: "Q. Which number should be add to make sum is 3rd number 16 + ? = 47.",
                    options: ["31", "32", "21", "28"],
                    correctAnswer: 0
                },
                {
                    id: 10,
                    question: "Q. How many seconds in day.",
                    options: ["86400", "86470", "86444", "86422"],
                    correctAnswer: 0
                }
            ];

            let correctCount = parseInt(localStorage.getItem('correctCount')) || 0;
            let totalCount = parseInt(localStorage.getItem('totalCount')) || 0;
            let askedQuestions = JSON.parse(localStorage.getItem('askedQuestions')) || [];

            correctCountSpan.textContent = correctCount;
            totalCountSpan.textContent = totalCount;

            updateProgressBar();

            homeLink.addEventListener('click', function() {
                homeSection.style.display = 'block';
                questionSection.style.display = 'none';
                progressSection.style.display = 'none';
                aboutSection.style.display = 'none';
            });

            iqTestLink.addEventListener('click', function() {
                homeSection.style.display = 'none';
                questionSection.style.display = 'block';
                progressSection.style.display = 'none';
                aboutSection.style.display = 'none';
            });

            aboutLink.addEventListener('click', function() {
                homeSection.style.display = 'none';
                questionSection.style.display = 'none';
                progressSection.style.display = 'none';
                aboutSection.style.display = 'block';
            });

            progressLink.addEventListener('click', function() {
                homeSection.style.display = 'none';
                questionSection.style.display = 'none';
                aboutSection.style.display = 'none';
                progressSection.style.display = 'block';
                updateProgressChart();
            });

            startIqTestButton.addEventListener('click', function() {
                homeSection.style.display = 'none';
                questionSection.style.display = 'block';
                generateQuestion();
            });

            function generateQuestion() {
                if (askedQuestions.length === questions.length) {
                    showProgressAndRestart();
                    return;
                }

                let question;
                let randomIndex;
                do {
                    randomIndex = Math.floor(Math.random() * questions.length);
                    question = questions[randomIndex];
                } while (askedQuestions.includes(question.id));

                questionContainer.innerHTML = `
                    <p>${question.question}</p>
                    <form id="question-form">
                        ${question.options.map((option, index) => `
                            <label>
                                <input type="radio" name="answer" value="${index}">
                                ${option}
                            </label><br>
                        `).join('')}
                        <button type="submit">Submit Answer</button>
                    </form>
                `;

                const questionForm = document.getElementById('question-form');
                questionForm.addEventListener('submit', function(event) {
                    event.preventDefault();
                    const formData = new FormData(questionForm);
                    const selectedOption = formData.get('answer');

                    totalCount++;
                    if (question.correctAnswer === parseInt(selectedOption)) {
                        correctCount++;
                    }

                    correctCountSpan.textContent = correctCount;
                    totalCountSpan.textContent = totalCount;

                    localStorage.setItem('correctCount', correctCount);
                    localStorage.setItem('totalCount', totalCount);

                    askedQuestions.push(question.id);
                    localStorage.setItem('askedQuestions', JSON.stringify(askedQuestions));

                    updateProgressBar();
                    generateQuestion();
                });
            }

            nextQuestionButton.addEventListener('click', generateQuestion);

            restartButton.addEventListener('click', function() {
                localStorage.removeItem('correctCount');
                localStorage.removeItem('totalCount');
                localStorage.removeItem('askedQuestions');
                location.reload();
            });

            function updateProgressBar() {
                const progress = (totalCount / questions.length) * 100;
                progressBar.style.width = progress + '%';
            }

            function showProgressAndRestart() {
                questionSection.style.display = 'none';
                progressSection.style.display = 'block';
                const userInfoForm = document.getElementById('user-info-form');
                userInfoForm.addEventListener('submit', function(event) {
                    event.preventDefault();
                    const gender = document.getElementById('gender').value;
                    const age = document.getElementById('age').value;
                    const education = document.getElementById('education').value;
                    if (gender && age && education) {
                        document.getElementById('progress-text').style.display = 'block';
                        document.getElementById('progress-chart-container').style.display = 'block';
                        document.getElementById('restart').style.display = 'block';
                        iqResultDiv.style.display = 'block';
                        updateProgressChart();
                        calculateAndDisplayIQ();
                    } else {
                        alert('Please fill in all the details.');
                    }
                });
            }

            function updateProgressChart() {
                new Chart(progressChart, {
                    type: 'pie',
                    data: {
                        labels: ['Correct', 'Incorrect'],
                        datasets: [{
                            data: [correctCount, totalCount - correctCount],
                            backgroundColor: ['#4caf50', '#f44336']
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const labelIndex = context.dataIndex;
                                        const label = context.chart.data.labels[labelIndex];
                                        const value = context.raw;
                                        return `${label}: ${value}`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            function calculateAndDisplayIQ() {
                // Simple calculation based on correct answers
                const maxScore = questions.length;
                const scorePercentage = (correctCount / maxScore) * 100;
                let iq = 100; // base IQ

                if (scorePercentage >= 90) {
                    iq = 130;
                } else if (scorePercentage >= 75) {
                    iq = 115;
                } else if (scorePercentage >= 50) {
                    iq = 100;
                } else if (scorePercentage >= 25) {
                    iq = 85;
                } else {
                    iq = 70;
                }

                iqScoreSpan.textContent = iq;
            }
        });
    </script>
</body>
</html>