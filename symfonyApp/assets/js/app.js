/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// var $ = require('jquery');
import axios from 'axios';


console.log('If this message in seen in the console then it means i extends javascript part in this page');

function deleteQuestion(event){
    if(confirm("Are you sure delete question?")){
    const questionId = event.target.getAttribute('data-id');
    axios.delete('/teacher/delete_question/' + questionId)
        .then(response => location.reload());
    }
    else{
        return ;
    }
}

function makeExam(event){
        var examTitle = document.getElementById('examTitle').value;
        var questionIds = " ";
        var checkBox = document.querySelectorAll('input[type=checkbox]:checked');

        for(var i=0; i<checkBox.length; i++){
            questionIds += checkBox[i].value + ', ';
        }
        const teacherId = event.target.getAttribute('data-id');

        axios.post('/teacher/make_exam/' + teacherId + '/make_exam_selected/' + questionIds + '/' + examTitle )
        .then(response => location.reload());
}

function makeExamRandom(event){
    var examTitle = document.getElementById('examTitle').value;
    var category = document.getElementById('category').value;
    var numbers = document.getElementById('numberOfQuestions').value;
    const teacherId = event.target.getAttribute('data-id');

    axios.post('/teacher/make_exam/random/' + teacherId + '/' + examTitle + '/' + numbers + '/' + category )
        .then(response => location.reload());
}

function publishExam(event){
    const examId = event.target.getAttribute('data-id');
    axios.post('/teacher/exam/publish/' + examId + '/set')
        .then(response => location.reload());
}

let deleteButtons = document.querySelectorAll('#deleteButton');
deleteButtons.forEach(button => button.addEventListener('click', deleteQuestion));

let makeExamButton = document.querySelectorAll('#makeExamButton');
makeExamButton.forEach(button => button.addEventListener('click', makeExam));

let makeExamRandomButton = document.querySelectorAll('#makeExamRandomButton');
makeExamRandomButton.forEach(button => button.addEventListener('click', makeExamRandom));

let publishButton = document.querySelectorAll('.publishButton');
publishButton.forEach(button => button.addEventListener('click', publishExam));