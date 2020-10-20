export const elements = {
    // Sections
    allSections: document.querySelectorAll('.section'),
    headerSection: document.querySelector('.section-header'),
    aboutSection: document.querySelector('.section-about'),
    messageSection: document.querySelector('.section-message'),

    resetPasswordSection: document.querySelector('.section-reset-password-form'),
    logInSection: document.querySelector('.section-login-form'),
    signUpSection: document.querySelector('.section-signup-form'),

    navbarButtonsContainer: document.querySelector('.nav-btn-container'),
    navbarButtonsContainerList: document.querySelector('.nav-btn-container-list'),
    messageButtonContainer: document.querySelector('.message-btn-container'),
    resetPasswordButtonContainer: document.querySelector('.reset-password-btn-container'),

    formsContainer: document.querySelector('.forms-container'), // The container that blurs the background.
    forms: document.querySelector('.forms'),

    navbarButtonIcon: document.querySelector('.navbar-btn-icon'),

    // Buttons
    navbarButton: document.querySelector('.navbar-button'),
    takeAQuizButton: document.querySelector('.main-btn-primary'),
    signUpButton: document.querySelector('.nav-btn-signup'),
    logInButton: document.querySelector('.nav-btn-login'),
    closeButton: document.querySelectorAll('.close-btn-container'), // Two buttons

    // Forms
    logInForm: document.querySelector('#login-form'),
    signUpForm: document.querySelector('#signup-form'),
    messageForm: document.querySelector('#message-form'),
    resetPasswordForm: document.querySelector('#reset-password-form'),

    // Home Form error boxes
    logInNotificationBox: document.querySelector('.login-notification-box'),
    signUpNotificationBox: document.querySelector('.signup-notification-box'),
    messageNotificationBox: document.querySelector('.message-notification-box'),
    resetPasswordNotificationBox: document.querySelector('.reset-password-notification-box'),

    // Action Buttons
    signUpButtonAction: document.querySelector('.signup-btn-action'),
    logInButtonAction: document.querySelector('.login-btn-action'),
    sendMessageButtonAction: document.querySelector('.message-btn-action'),
    resetPasswordButtonAction: document.querySelector('.reset-password-btn-action'),

    // Form Input elements
    logInEmail: document.querySelector('#login-email'),
    logInPassword: document.querySelector('#login-pass'),

    signUpName: document.querySelector('#signup-name'),
    signUpEmail: document.querySelector('#signup-email'),
    signUpPassword: document.querySelector('#signup-pass'),
    signUpConfirmPassword: document.querySelector('#signup-confirm-pass'),

    messageName: document.querySelector('#message-name'),
    messageEmail: document.querySelector('#message-email'),
    messageBody: document.querySelector('#message-body'),

    resetPasswordEmail: document.querySelector('#reset-password-email'),

    // Form Buttons
    openLogIn: document.querySelector('#open-login'),
    openSignUp: document.querySelector('#open-signup'),
    openResetPassword: document.querySelector('#open-reset-password'),

    aboutFeatures: document.querySelectorAll('.feature'),

    // Leaderboard elements
    top3: document.querySelector('.top-3'),
    top10: document.querySelector('.top-10'),
    currentSubject: document.querySelector('.current-subject-name'),
    subjectDropdownButton: document.querySelector('.subject-dropdown-btn'),
    subjectDropdownContent: document.querySelector('.dropdown-content'),
    subjectOptions: document.querySelectorAll('.subject-option'),

    // * ************************************************** ./game.html ********************************************

    gameContainer: document.querySelector('.game-container'),

    loaderContainer: document.querySelector('.loader-container'),

    subjectElements: document.querySelectorAll('.subject-element'),

    nameContainer: document.querySelector('#user-name'),
};