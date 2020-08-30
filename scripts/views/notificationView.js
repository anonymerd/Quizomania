import {
    elements
} from './base.js';


export const showLogInNotification = (type = 'error', Msg) => {
    // Adding/Changing Styles.
    elements.logInNotificationBox.style.width = '60%';
    elements.logInNotificationBox.style.padding = '5px';

    // Writing the error message.
    let messageBox = `
    <h4>`;

    if (type === 'success') {
        messageBox += `<i class = "fas fa-check"> </i>`;
        elements.logInNotificationBox.style.backgroundColor = '#2ecc70ad';
        elements.logInNotificationBox.style.color = '#00662a';
    }
    else {
        messageBox += `<i class = "fa fa-times"> </i>`;
    }
    messageBox += `
    </h4>
    <p>
        ${Msg}
    </p>`;

    elements.logInNotificationBox.innerHTML = messageBox;
};

export const showSignUpNotification = (type = 'error', Msg) => {
    // Adding/Changing Styles.
    elements.signUpNotificationBox.style.width = '60%';
    elements.signUpNotificationBox.style.padding = '5px';

    // Writing the error message.
    let messageBox = `
    <h4>`;

    if (type === 'success') {
        messageBox += `<i class = "fas fa-check"> </i>`;
        elements.signUpNotificationBox.style.backgroundColor = '#2ecc70ad';
        elements.signUpNotificationBox.style.color = '#00662a';
    }
    else {
        messageBox += `<i class = "fa fa-times"> </i>`;
    }
    messageBox += `
    </h4>
    <p>
        ${Msg}
    </p>`;

    elements.signUpNotificationBox.innerHTML = messageBox;

};


export const showMessageNotification = (type = 'error', Msg) => {
    // Adding/Changing Styles.
    elements.messageNotificationBox.style.width = '30%';
    elements.messageNotificationBox.style.padding = '5px';

    // Writing the error message.
    let messageBox = `
    <h4>`;

    if (type === 'success') {
        messageBox += `<i class = "fas fa-check"> </i>`;
        elements.messageNotificationBox.style.backgroundColor = '#2ecc70ad';
        elements.messageNotificationBox.style.color = '#00662a';
    }
    else {
        messageBox += `<i class = "fa fa-times"> </i>`;
    }
    messageBox += `
    </h4>
    <p>
        ${Msg}
    </p>`;

    elements.messageNotificationBox.innerHTML = messageBox;
};


export const showResetPasswordNotification = (type = 'error', Msg) => {
    // Adding/Changing Styles.
    elements.resetPasswordNotificationBox.style.width = '60%';
    elements.resetPasswordNotificationBox.style.padding = '5px';

    // Writing the error message.
    let messageBox = `
    <h4>`;

    if (type === 'success') {
        messageBox += `<i class = "fas fa-check"> </i>`;
        elements.resetPasswordNotificationBox.style.backgroundColor = '#2ecc70ad';
        elements.resetPasswordNotificationBox.style.color = '#00662a';
    }
    else {
        messageBox += `<i class = "fa fa-times"> </i>`;
    }
    messageBox += `
    </h4>
    <p>
        ${Msg}
    </p>`;

    elements.resetPasswordNotificationBox.innerHTML = messageBox;
};

