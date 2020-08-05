import {
    elements
} from './base.js';


export const hideAllSections = () => {

    elements.allSections.forEach(section => {
        section.style.display = 'none';
    });
}

export const showAllSections = () => {

    elements.allSections.forEach(section => {
        section.style.display = 'block';
    });
}

export const showLogInSection = () => {

    // Hiding all sections
    hideAllSections();

    // Showing the blurred div
    elements.formsContainer.style.display = 'block';

    // Showing the LogIn Section.
    elements.logInSection.style.display = 'block';
};

export const showSignUpSection = () => {

    // Hiding all sections
    hideAllSections();

    // Showing the blurred div
    elements.formsContainer.style.display = 'block';
    // Showing the SignUp Section.
    elements.signUpSection.style.display = 'block';
};

export const hideFormsSection = () => {

    // Hiding the blurred div
    elements.formsContainer.style.display = 'none';

    // Hiding both the forms.
    elements.logInSection.style.display = 'none';
    elements.signUpSection.style.display = 'none';

    // Showing/Unhiding all the sections.
    showAllSections();
};