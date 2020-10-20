import Question from '../models/Question.js';
import * as Config from '../config.js';

import { elements } from './base.js';

export const renderSubjectSection = async subjects => {
    const numberOfSubjects = subjects.length;

    // The container div
    const subjectsSection = document.createElement('div');
    subjectsSection.classList.add('section-subjects');
    subjectsSection.setAttribute('id', 'section-subjects');

    // The heading container
    const headingContainer = document.createElement('div');
    headingContainer.classList.add('heading', 'about-heading');

    const aboutHeading = document.createElement('h2');
    aboutHeading.appendChild(document.createTextNode('Choose a Subject'));

    const headingHr = document.createElement('hr');
    headingHr.classList.add('heading-hr');

    headingContainer.appendChild(aboutHeading);
    headingContainer.appendChild(headingHr);

    const subjectsContainer = document.createElement('div');
    subjectsContainer.classList.add('subjects-container');

    const subjectElementsArray = [];
    for (let i = 0; i < numberOfSubjects; i++) {
        const subjectElement = document.createElement('div');
        subjectElement.classList.add('subject-element');
        subjectElement.setAttribute('id', `${subjects[i]['SubID']}`);

        const svgContainer = document.createElement('figure');

        try {
            let svgIcon = await fetch(
                `${Config.clientAddress}icons/${subjects[i]['SubjectName'].toLowerCase()}.svg`
            );

            // console.log(svgIcon);

            if (svgIcon.status !== 200) {
                svgIcon = await fetch(`${Config.clientAddress}icons/error.svg`);
            }
            svgContainer.innerHTML = await svgIcon.text();
        } catch (error) {
            console.log(error);
        }

        const subjectName = document.createElement('h3');
        subjectName.classList.add('subject-name');
        subjectName.textContent = subjects[i]['SubjectName'];

        const subjectDescription = document.createElement('p');
        subjectDescription.classList.add('subject-desc');
        subjectDescription.textContent = `Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.`;

        subjectElement.appendChild(svgContainer);
        subjectElement.appendChild(subjectName);
        subjectElement.appendChild(subjectDescription);

        subjectsContainer.appendChild(subjectElement);
        subjectElementsArray.push(subjectElement)
    }

    subjectsSection.appendChild(headingContainer);
    subjectsSection.appendChild(subjectsContainer);

    elements.gameContainer.appendChild(subjectsSection);

    return {
        section: subjectsSection,
        subjects: subjectElementsArray
    }
};

export const renderQuestionSection = () => {
    const questionsSection = document.createElement('div');
    questionsSection.classList.add('section-question');
    questionsSection.setAttribute('id', 'section-question')

    const questionContainer = document.createElement('div');
    questionContainer.classList.add('question-container');

    const questionElement = document.createElement('div');
    questionElement.classList.add('question-element');

    const questionBgImage = document.createElement('img');
    questionBgImage.setAttribute('src', `${Config.clientAddress}img/question-background2.png`);
    questionBgImage.setAttribute('alt', 'Question Background');

    const ques = document.createElement('div');
    ques.classList.add('question');

    const quesNo = document.createElement('h3');
    quesNo.setAttribute('id', 'ques-no');

    const questionBody = document.createElement('p');
    questionBody.setAttribute('id', 'question-body');

    ques.appendChild(quesNo);
    ques.appendChild(questionBody);

    questionElement.appendChild(questionBgImage);
    questionElement.appendChild(ques);

    questionContainer.appendChild(questionElement);

    const optionsContainer = document.createElement('div');
    optionsContainer.classList.add('options-container');

    let optionsArray = []; // Array to contain all the option elements.
    const noOfOptions = 4;

    for (let i = 0; i < noOfOptions; i++) {
        const option = document.createElement('div');
        option.classList.add('option');
        option.setAttribute('id', String.fromCharCode(65 + i));

        optionsContainer.appendChild(option);
        optionsArray.push(option);
    }

    const skipOption = document.createElement('div');
    skipOption.classList.add('option', 'skip-option');
    skipOption.setAttribute('id', 'Z');
    skipOption.textContent = 'Skip';
    optionsArray.push(skipOption);

    optionsContainer.appendChild(skipOption);

    questionContainer.appendChild(optionsContainer);

    questionsSection.appendChild(questionContainer);

    elements.gameContainer.appendChild(questionsSection);

    return {
        section: questionsSection,
        QNo: quesNo,
        QuestionBody: questionBody,
        Options: optionsArray
    };
};


export const renderQuestion = async (questionElements, randomQues, qNo, token) => {

    const qID = randomQues[qNo];
    const newQues = new Question(qID);
    let question = await newQues.getQuestion(token);

    // console.log(question);

    questionElements['QNo'].textContent = qNo + 1;
    questionElements['QuestionBody'].textContent = question['data']['Question'];
    questionElements['Options'][0].textContent = question['data']['OptionA'];
    questionElements['Options'][1].textContent = question['data']['OptionB'];
    questionElements['Options'][2].textContent = question['data']['OptionC'];
    questionElements['Options'][3].textContent = question['data']['OptionD'];

}

export const renderResultSection = (name, result) => {

    // The section container
    const resultSection = document.createElement('div');
    resultSection.classList.add('section-results');

    // The heading container
    const headingContainer = document.createElement('div');
    headingContainer.classList.add('heading', 'about-heading');

    const aboutHeading = document.createElement('h2');
    aboutHeading.appendChild(document.createTextNode('Results'));

    const headingHr = document.createElement('hr');
    headingHr.classList.add('heading-hr');

    headingContainer.appendChild(aboutHeading);
    headingContainer.appendChild(headingHr);

    // The Summary Table Container
    const resultSummary = document.createElement('div');
    resultSummary.classList.add('result-summary');

    // The Summary Table
    let summaryTable = `
    <table class="result-table result-summary-table">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Total Ques</th>
                <th scope="col">Attempted Ques</th>
                <th scope="col">Correct Ans</th>
                <th scope="col">Score</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>${name}</td>
                <td>${result['totalQues']}</td>
                <td>${result['attemptedQues']}</td>
                <td>${result['correctAns']}</td>
                <td>${result['score']}</td>
            </tr>
            </tbody>
        </table>
    </div>`;

    resultSummary.innerHTML = summaryTable;

    // The details table container.
    const resultDetails = document.createElement('div');
    resultDetails.classList.add('result-details');

    // The Details Table.
    let detailsTable = `
    <div class="result-details">
        <table class="result-table result-details-table">
            <thead>
                <tr>
                    <th scope="col">Q#</th>
                    <th scope="col">Correct Ans</th>
                    <th scope="col">Your Response</th>
                    <th scope="col">Result</th>
                </tr>
            </thead>
            <tbody>`;

    for (let i = 0; i < result['totalQues']; i++) {
        detailsTable += `
        <tr>
            <th scope="row">${i + 1}</th>
            <td>${result['correctResponse'][i]}</td>
            <td>${result['userResponse'][i] !== 'Z' ? result['userResponse'][i] : 'Not Attempted'}</td>     
            <td>${result['scoresArray'][i]}</td>
        </tr>`;
    }

    detailsTable += `
            </tbody>
        </table>
    </div>`;

    resultDetails.innerHTML = detailsTable;

    const resultButtonContainer = document.createElement('div');
    resultButtonContainer.classList.add('result-btn-container');

    const playAgainButton = document.createElement('a');
    playAgainButton.classList.add('btn', 'play-again-btn');
    playAgainButton.textContent = 'Play Again?';

    const logOutButton = document.createElement('a');
    logOutButton.classList.add('btn', 'logout-btn');
    logOutButton.textContent = 'LogOut';

    resultButtonContainer.appendChild(playAgainButton);
    resultButtonContainer.appendChild(logOutButton);

    resultSection.appendChild(headingContainer);
    resultSection.appendChild(resultSummary);
    resultSection.appendChild(resultDetails);
    resultSection.appendChild(resultButtonContainer);

    elements.gameContainer.appendChild(resultSection);

    return {
        section: resultSection,
        playAgainButton: playAgainButton,
        logOutButton: logOutButton
    };
}



