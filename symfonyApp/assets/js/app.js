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
        var studentsAvailable =" " ;
        var checkBox = document.querySelectorAll('input[name="question[]"]:checked');

        var students = document.querySelectorAll('input[name="student[]"]:checked');

        for(var i=0; i<checkBox.length; i++){
            questionIds += checkBox[i].value + ',';
        }

        for(var i=0; i<students.length; i++){
            studentsAvailable += students[i].value + ',';
        }
        const teacherId = event.target.getAttribute('data-id');

        axios.post('/teacher/make_exam/' + teacherId
            + '/make_exam_selected/' + questionIds + '/'
            + examTitle + '/' + studentsAvailable )
        .then(response => location.reload());
}

function publishExam(event){
    const examId = event.target.getAttribute('data-id');
    axios.post('/teacher/exam/publish/' + examId + '/set')
        .then(response => location.reload());
}

function deleteExam(event){
    const examId = event.target.getAttribute('data-id');
    axios.delete('/teacher/exam/delete/' + examId )
        .then(response => location.reload());
}

function filterCategory(){
    var categoryArraySelected = [];
    var categoryArrayAll = [];
    var checkboxChekced = document.querySelectorAll('input[id="filter"]:checked');
    var checkboxAll = document.querySelectorAll('input[id="filter"]');
    for (var i = 0; i < checkboxChekced.length; i++) {
        categoryArraySelected.push(checkboxChekced[i].value);
    }
    for (var i = 0; i < checkboxAll.length; i++) {
        categoryArrayAll.push(checkboxAll[i].value);
    }


    if(!categoryArraySelected.includes("All")){
        for(var k=0; k<categoryArrayAll.length; k++){
            if(!categoryArraySelected.includes(categoryArrayAll[k])){
                var categoryDiv = document.getElementsByName(categoryArrayAll[k]);
                for(var j=0;j<categoryDiv.length;j++){
                    categoryDiv[j].style.display = "none";
                }
            }
            else{
                var categoryDiv = document.getElementsByName(categoryArrayAll[k]);
                for(var j=0;j<categoryDiv.length;j++){
                    categoryDiv[j].style.display = "block";
                }
            }
        }
    }
    else if(categoryArraySelected.length === 0){
        for(var i=0;i<categoryArrayAll.length;i++){
            var categoryDiv = document.getElementsByName(categoryArrayAll[i]);
            for(var j=0;j<categoryDiv.length;j++){
                categoryDiv[j].style.display = "none";
            }
        }
    }
    else{
        for(var i=0;i<categoryArrayAll.length;i++){
            var categoryDiv = document.getElementsByName(categoryArrayAll[i]);
            for(var j=0;j<categoryDiv.length;j++){
                categoryDiv[j].style.display = "block";
            }
        }
    }
}

let deleteButtons = document.querySelectorAll('#deleteButton');
deleteButtons.forEach(button => button.addEventListener('click', deleteQuestion));

let makeExamButton = document.querySelectorAll('#makeExamButton');
makeExamButton.forEach(button => button.addEventListener('click', makeExam));

let publishButton = document.querySelectorAll('#publishButton');
publishButton.forEach(button => button.addEventListener('click', publishExam));

let deleteExamButton = document.querySelectorAll('#deleteExam');
deleteExamButton.forEach(button => button.addEventListener('click', deleteExam));

let filterButton = document.querySelectorAll('.filterButton');
filterButton.forEach(button => button.addEventListener('click', filterCategory));
