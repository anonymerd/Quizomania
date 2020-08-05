export const elements = {
    // Sections
    allSections: document.querySelectorAll(".section"),
    headerSection: document.querySelector(".section-header"),
    aboutSection: document.querySelector(".section-about"),

    logInSection: document.querySelector(".section-login-form"),
    signUpSection: document.querySelector(".section-signup-form"),

    formsContainer: document.querySelector(".forms-container"), // The container that blurs the background.

    // Buttons
    navbarButton: document.querySelector(".navbar-button"),
    takeAQuizButton: document.querySelector(".main-btn-primary"),
    signUpButton: document.querySelector(".nav-btn-signup"),
    logInButton: document.querySelector(".nav-btn-login"),
    closeButton: document.querySelectorAll(".close-btn-container"), // Two buttons

    // Forms
    logInForm: document.querySelector("#login-form"),
    signUpForm: document.querySelector("#signup-form"),

    // Home Form error boxes
    logInErrorBox: document.querySelector(".login-error-box"),
    signUpErrorBox: document.querySelector(".signup-error-box"),

    // Action Buttons
    signUpButtonAction: document.querySelector(".signup-btn-action"),
    logInButtonAction: document.querySelector(".login-btn-action"),

    // Form Input elements
    logInEmail: document.querySelector("#login-email"),
    logInPassword: document.querySelector("#login-pass"),

    signUpName: document.querySelector("#signup-name"),
    signUpEmail: document.querySelector("#signup-email"),
    signUpPassword: document.querySelector("#signup-pass"),
    signUpConfirmPassword: document.querySelector("#signup-confirm-pass"),
};

// .section-login-form {
// display: none;
// }

// .section - signup - form {
//         display: none;
//     }

//     .main.forms - container {
//         /* width: 100%;
//         height: 100%;
//         backdrop-filter: blur(5px); */

//         display: none;
//     }

//     .section {
//         display: block;
//     }
