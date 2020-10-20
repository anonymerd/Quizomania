import Subject from './models/Subject.js';
import Question from './models/Question.js';
import Game from './models/Game.js';

import * as GameView from './views/gameView.js';
import {
    elements
} from './views/base.js';


// * Event Listeners that should be implemented after the certain sections are rendered

const optionsEventListener = async (name, questionElements, answer, questionCount) => {

    // Updating answers in token
    const response = await Question.updateAnswer(localStorage['token'], answer);

    let newToken = localStorage['token']; // TODO Change on adding result section. 

    if (!response['result']) {
        // Answer not updated in the token
        alert(`Error: ${response['error']}\nMessage: ${response['message']}`);
    } else {

        const updatedData = response['data'];
        newToken = updatedData['token'];

        // Generating question
        if (updatedData['QNo'] < questionCount) {

            // Question is allowed to render.
            GameView.renderQuestion(questionElements, updatedData['randomQues'], updatedData['QNo'], newToken);
        } else {
            // Game Over.

            // Removing the question section.
            questionElements['section'].remove();

            // Render Results Section.
            loadResultsSection(name, updatedData['subID'], questionCount, updatedData['randomQues'], updatedData['answers']);
        }

    }

    return newToken;

}

const subjectsEventListener = async (name, subjectElements, subID, questionCount) => {
    const response = await Question.getRandomQuestions(subID, questionCount, localStorage['token']);
    let newToken = localStorage['token'];

    if (!response['result']) {
        // Problem on generating random questions.
        window.location.href = './index.html';
    } else {
        // All good

        const updatedData = response['data'];
        newToken = response['token'];

        // Removing subjects section.
        subjectElements['section'].remove();

        await loadQuestionSection(name, updatedData['randomQues'], updatedData['QNo'], newToken, questionCount);

    }
    return newToken;
}


// * Loading different sections

const loadSubjectsSection = async (name, questionCount) => {

    // Show loader
    elements.loaderContainer.style.display = 'block';

    // Getting all subjects.
    const subjectResponse = await Subject.getAllSubjects();
    // console.log(subjects);

    // Checking the response
    if (!subjectResponse['result']) {
        // Invalid Token/ Database Error
        // console.log(subjectResponse);
        window.location.href = './index.html';
    } else {

        // All good

        const subjects = subjectResponse['data'];
        const subjectElements = await GameView.renderSubjectSection(subjects);

        // Event listener for the subject elements, available once the subject section has rendered.
        subjectElements['subjects'].forEach(element => {
            element.addEventListener('click', event => {
                // Getting the subject choosen by the user.
                const subID = event.currentTarget.id;
                // Listening and handling the event of clicking on an subject.
                // This function returns a new token with updated state of the game.
                subjectsEventListener(name, subjectElements, subID, questionCount)
                    .then(newToken => {
                        // Updating the token that contains the info about the progress of the game.
                        localStorage['token'] = newToken;
                    });
            });
        });
    }

    // Remove loader
    elements.loaderContainer.style.display = 'none';
}

const loadQuestionSection = async (name, randomQues, QNo, newToken, questionCount) => {

    // Show loader
    elements.loaderContainer.style.display = 'block';

    // Rendering Question Section.
    const questionElements = GameView.renderQuestionSection();

    // Generating first question
    GameView.renderQuestion(questionElements, randomQues, QNo, newToken);

    // Event listener for options, only available once the question section renders
    questionElements['Options'].forEach(option => {
        option.addEventListener('click', event => {

            // Show loader
            elements.loaderContainer.style.display = 'block';

            // Loose focus -- For mobile screen
            event.currentTarget.blur();

            // Getting the users choice
            const answer = event.currentTarget.id;
            // Listening and handling the event of clicking on an option.
            // This function returns a new token with updated QNo.
            optionsEventListener(name, questionElements, answer, questionCount)
                .then(newToken => {
                    // Updating the token.
                    localStorage['token'] = newToken;

                    // Remove loader
                    elements.loaderContainer.style.display = 'none';
                });
        });
    });

    // Remove loader
    elements.loaderContainer.style.display = 'none';
}

const loadResultsSection = async (name, subID, questionCount, questions, answers) => {

    // Show loader
    elements.loaderContainer.style.display = 'block';

    // Saving the game details in the database
    const response = await Game.saveGame(localStorage['token'], subID, questionCount, questions, answers);

    // Updating the token
    localStorage['token'] = response['token'];

    const results = response['data'];


    // Rendering the result section
    const resultElements = GameView.renderResultSection(name, results);

    // Event listeners for result section
    resultElements['playAgainButton'].addEventListener('click', () => {

        // Removing Results Section.
        resultElements['section'].remove();

        // Loading Subjects Section.
        loadSubjectsSection(name, questionCount);
    });

    // Event Listener for LogOut Button.
    resultElements['logOutButton'].addEventListener('click', () => {
        // Clearing the Local Storage
        localStorage.clear();

        // Redirecting to the homepage.
        window.location.href = './';
    });

    // Remove loader
    elements.loaderContainer.style.display = 'none';
}


// * For loading the game from where we have left or to start from the beginning.

const loadGame = async token => {
    // Checking the token for finding any unsaved game.
    const gameResponse = await Game.checkGameToken(token);

    if (!gameResponse['result']) {

        // Invalid token/ Database error.
        window.location.href = './index.html';
    } else {

        // Valid token.
        const state = gameResponse['data'];
        const name = state['name'];

        // **********  QUESTION COUNT  *********************************************************************************************************************
        const questionCount = 5;
        // **********  QUESTION COUNT  *********************************************************************************************************************

        // Displaying the name of the user.
        elements.nameContainer.textContent = name;

        if ('gameOver' in state && state['gameOver'] !== false) {

            // Game has ended. Displaying the subject section again
            // Game has ended. Displaying the subject section again
            await loadResultsSection(name, state['subID'], questionCount, state['randomQues'], state['answers']);
        } else if ('subID' in state && state['subID'] !== false) {
            // Game has already started.

            // Loading Question Section
            await loadQuestionSection(name, state['randomQues'], state['QNo'], token, questionCount);

        } else {

            // Loading Subjects section.

            await loadSubjectsSection(name, questionCount);
        }
    }
};


// * THE MAIN

if ('token' in window.localStorage) {
    loadGame(window.localStorage['token']);
} else {
    // The token does not exists. Redirecting to homepage
    window.location.href = './index.html';
}