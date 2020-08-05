import {
    elements
} from './base.js';


export const showLogInError = errorMsg => {
    // Adding/Changing Styles.
    elements.logInErrorBox.style.width = '60%';
    elements.logInErrorBox.style.padding = '5px';

    // Writing the error message.
    elements.logInErrorBox.innerHTML = `
    <h4>
        <i class="fa fa-times"></i>
    </h4>
    <p>
        ${errorMsg}
    </p>`;
};

export const showSignUpError = errorMsg => {
    // Adding/Changing Styles.
    elements.signUpErrorBox.style.width = '60%';
    elements.signUpErrorBox.style.padding = '5px';

    // Writing the error message.
    elements.signUpErrorBox.innerHTML = `
    <h4>
        <i class = "fa fa-times"> </i> 
    </h4>
    <p>
        ${errorMsg}
    </p>`;
};