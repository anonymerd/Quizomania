import User from './models/User.js';
import Message from './models/Message.js';
import Game from './models/Game.js';
import Leaderboard from './models/Leaderboard.js';
import {
    elements
} from './views/base.js';
import * as SectionView from './views/sectionView.js';
import * as NotificationView from './views/notificationView.js';
import Subject from './models/Subject.js';



{
    /* <li><a class="btn nav-btn-signup">Sign Up</a></li>
    <li><a class="btn nav-btn-login">Log In</a></li> */
}

let navigationButtons = `
    <li><a href="#about" class="btn nav-btn-ghost">About</a></li>
    <li><a href="#message" class="btn nav-btn-ghost">Message Us</a></li>
`;

const notLoggedIn = `
    <li><a class="btn nav-btn-signup">Sign Up</a></li>
    <li><a class="btn nav-btn-login">Log In</a></li>
`;

// Checking the token for finding any unsaved game.
if ('token' in window.localStorage) {
    // There is some token. Checking the token.
    // console.log(window.localStorage['token']);

    Game.checkGameToken(window.localStorage['token'])
        .then(gameResponse => {

            if (gameResponse['result']) {
                // Some user is already logged in.
                // console.log(gameResponse);

                // Showing Log Out Button.
                navigationButtons += `
                    <li><a class="btn nav-btn-logout">Log Out</a></li>
                `;
                elements.navbarButtonsContainerList.innerHTML = navigationButtons;

                // Showing Continue Quiz.
                elements.takeAQuizButton.textContent = 'Continue QUIZ!';

                elements.takeAQuizButton.removeEventListener('click', SectionView.showLogInSection);
                elements.takeAQuizButton.setAttribute('href', './game.html');

                // Event Listener for LogOut Button.
                document.querySelector('.nav-btn-logout').addEventListener('click', () => {
                    // Clearing the Local Storage
                    localStorage.clear();

                    // Redirecting to the homepage.
                    window.location.href = './';
                });

            }
        });
}




// *  -----------------------  The SVG Loader  ----------------------------------

// Credits for the loader: https://codepen.io/aurer/pen/jEGbA
const loader = `
    <div class="loader loader--style6" title="5">
        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="24px" height="30px"
            viewBox="0 0 24 30" style="enable-background:new 0 0 50 50;" xml:space="preserve">
            <rect x="0" y="13" width="4" height="5" fill="#333">
                <animate attributeName="height" attributeType="XML" values="5;21;5" begin="0s"
                    dur="0.6s" repeatCount="indefinite" />
                <animate attributeName="y" attributeType="XML" values="13; 5; 13" begin="0s"
                    dur="0.6s" repeatCount="indefinite" />
            </rect>
            <rect x="10" y="13" width="4" height="5" fill="#333">
                <animate attributeName="height" attributeType="XML" values="5;21;5" begin="0.15s"
                    dur="0.6s" repeatCount="indefinite" />
                <animate attributeName="y" attributeType="XML" values="13; 5; 13" begin="0.15s"
                    dur="0.6s" repeatCount="indefinite" />
            </rect>
            <rect x="20" y="13" width="4" height="5" fill="#333">
                <animate attributeName="height" attributeType="XML" values="5;21;5" begin="0.3s"
                    dur="0.6s" repeatCount="indefinite" />
                <animate attributeName="y" attributeType="XML" values="13; 5; 13" begin="0.3s"
                    dur="0.6s" repeatCount="indefinite" />
            </rect>
        </svg>
    </div>
`;


// *  -----------------------  Animations and Actions on Scrolling  ----------------------------------

// Making the sticky navigation visible and invisible whenever we scroll to about section.
let waypoint = new Waypoint({
    element: elements.aboutSection,
    handler: direction => {
        if (direction === 'down')
            elements.headerSection.classList.add('fixed');
        else
            elements.headerSection.classList.remove('fixed');
    },
    offset: 60
});

// animating the features.
waypoint = new Waypoint({
    element: elements.aboutSection,
    handler: () => {
        elements.aboutFeatures.forEach((ele) => {
            ele.classList.add("animate__animated", "animate__fadeInUp");
        })
    },
    offset: 60
});

// animating the message body textarea.
waypoint = new Waypoint({
    element: elements.messageSection,
    handler: () => {
        elements.messageBody.classList.add("animate__animated", "animate__wobble");
    },
    offset: 60
});

// *  -----------------------  Fetching Leaderboard  ----------------------------------

// Function that filters the fetched leaderboard to the subject leaderboard.
const getSubjectLeaderboard = (leaderboard, subjectID) => {
    // Filtering Leaderboard as per the subject.
    const subjectLeaderboard = leaderboard.filter(game => game.SubID == subjectID);

    // Getting the top 10 games.
    return subjectLeaderboard.sort((a, b) => b.Score - a.Score).slice(0, 10);
}


async function loadLeaderboard() {

    try {
        // Fetching all subjects for subjects dropdown.
        const subjectResponse = await Subject.getAllSubjects();
        // console.log(subjectResponse);
        const subjects = subjectResponse.data;

        let subjectList = ``;
        subjects.forEach(subject => {
            subjectList += `
                <div class = "subject-option" id="${subject.SubID}">${subject.SubjectName}</div>
            `;
        });
        elements.subjectDropdownContent.innerHTML = subjectList;

        // Fetching all the unsorted leaderboard from the server.
        const leaderboard = await Leaderboard.getLeaderboard();

        // Rendering the default subject name (subjectID = 1).
        elements.currentSubject.innerHTML = subjects[0].SubjectName;

        // Getting the default leaderboard(subjectID = 1).
        const currentLeaderboard = getSubjectLeaderboard(leaderboard.data, 1);
        // Rendering Leaderboard.
        SectionView.renderLeaderboard(currentLeaderboard);

        // Event Listener for the dropdown button.
        elements.subjectDropdownButton.addEventListener('click', () => {
            // toggling the dropdown content.
            elements.subjectDropdownContent.classList.toggle('dropdown-hidden');
            elements.subjectDropdownContent.classList.toggle('animate__slideInLeft');
        })


        // Subject Dropdown Event Listener.
        document.querySelectorAll('.subject-option').forEach(option => {
            option.addEventListener('click', event => {
                const subID = event.currentTarget.id;

                // Changing the current subject name.
                elements.currentSubject.innerHTML = event.currentTarget.innerHTML;

                // hiding the dropdown content.
                elements.subjectDropdownContent.classList.toggle('dropdown-hidden');
                elements.subjectDropdownContent.classList.toggle('animate__slideInLeft');

                // Getting the leaderboard of the selected subject.
                const currentLeaderboard = getSubjectLeaderboard(leaderboard.data, subID);
                console.log(subID);
                console.log(currentLeaderboard);

                // Rendering the leaderboard.
                SectionView.renderLeaderboard(currentLeaderboard);

            });
        });

    } catch (error) {
        console.log(error);
    }
}

// Loading the leaderboard on page load.
loadLeaderboard();


// *  -----------------------  Event Listeners  ----------------------------------

// Show Mobile Navigation (Hamburger Menu).
elements.navbarButton.addEventListener('click', SectionView.toggleMobileNavigation);

// Show Login Form.
elements.logInButton.addEventListener('click', SectionView.showLogInSection);
elements.takeAQuizButton.addEventListener('click', SectionView.showLogInSection);
elements.openLogIn.addEventListener('click', SectionView.openLogInSection);

// Show SignUp Form
elements.signUpButton.addEventListener('click', SectionView.showSignUpSection);
elements.openSignUp.addEventListener('click', SectionView.openSignUpSection);

// Show Reset Passwod Form
elements.openResetPassword.addEventListener('click', SectionView.openResetPasswordSection)

// Hiding all forms (The cross button function)
elements.closeButton.forEach(button => {
    button.addEventListener('click', SectionView.hideFormsSection);
});

// Login Button
elements.logInButtonAction.addEventListener('click', event => {
    // Preventing default submission of the form.
    event.preventDefault();

    if (elements.logInForm.checkValidity()) {
        // Creating data from input.
        const data = {
            Email: elements.logInEmail.value,
            Password: elements.logInPassword.value,
        };

        // Calling async logIn method.
        User.logInUser(data).then(response => {
            // Checking the response.
            if (response['result']) {
                // Storing the token in Local Storage.
                localStorage.setItem('token', response['data']['token']);
                window.location.href = './game.html';
            } else {
                // Showing errors
                NotificationView.showLogInNotification('error', response['error']);
            }
        });
    } else {
        // Report the form validity.
        elements.logInForm.reportValidity();
    }
});

// SignUp Button.
elements.signUpButtonAction.addEventListener('click', event => {
    // Preventing default submission of the form.
    event.preventDefault();

    if (elements.signUpForm.checkValidity()) {
        // Checking whether both passwords match.
        if (elements.signUpPassword.value === elements.signUpConfirmPassword.value) {
            // Creating data object from inputs
            const data = {
                Name: elements.signUpName.value,
                Email: elements.signUpEmail.value,
                Password: elements.signUpPassword.value,
            };

            // Calling the async createUser method
            User.createUser(data).then(response => {
                // Checking the response
                if (response['result']) {
                    // Successfully Created new user.

                    // Moving to login form with success message.
                    NotificationView.showLogInNotification('success', 'Account Created Successfully. Please LogIn to continue!');
                    SectionView.openLogInSection();
                    // alert('User created successfuly.');
                } else {
                    // Showing errors
                    NotificationView.showSignUpNotification('error', response['error']);
                }
            });
        } else {
            // when passwords don't match.
            NotificationView.showSignUpNotification('error', 'Passwords do not match.');
        }
    } else {
        // Report the form validity.
        elements.signUpForm.reportValidity();
    }
});

// Send Message Form.
elements.sendMessageButtonAction.addEventListener('click', event => {
    // Preventing the default submissions of the form.
    event.preventDefault();

    if (elements.messageForm.checkValidity()) {
        // If the form is valid.

        // console.log(elements.messageName.value, elements.messageEmail.value, elements.messageBody.value);

        // Saving the container elements.
        const btnHTML = elements.messageButtonContainer.innerHTML;
        // Showing loader.
        elements.messageButtonContainer.innerHTML = loader;

        const message = new Message(elements.messageName.value, elements.messageEmail.value, elements.messageBody.value);
        message.sendMessage()
            .then(response => {
                // console.log(response);
                if (!response['result'])
                    NotificationView.showMessageNotification('error', response['error']);
                else {
                    NotificationView.showMessageNotification('success', response['message']);
                }

                // Removing loader.
                elements.messageButtonContainer.innerHTML = btnHTML;
            });

    } else {
        // Report the form validity.
        elements.messageForm.reportValidity();
    }
});

elements.resetPasswordButtonAction.addEventListener('click', event => {
    // Preventing the default submissions of the form.
    event.preventDefault();

    if (elements.resetPasswordForm.checkValidity()) {
        // If the form is valid.

        // Saving the container elements.
        const btnHTML = elements.resetPasswordButtonContainer.innerHTML;
        // Showing loader.
        elements.resetPasswordButtonContainer.innerHTML = loader;

        Message.sendResetLink(elements.resetPasswordEmail.value)
            .then(response => {
                if (!response['result'])
                    NotificationView.showResetPasswordNotification('error', response['message']);
                else
                    NotificationView.showResetPasswordNotification('success', response['message']);

                // Removing the loader.
                elements.resetPasswordButtonContainer.innerHTML = btnHTML;
            })
            .catch(error => {
                console.log(error);
            });

    } else {
        // Report the form validity.
        elements.resetPasswordForm.reportValidity();
    }
})