import User from "./models/User.js";
import { elements } from "./views/base.js";
import * as SearchView from "./views/sectionView.js";
import * as ErrorView from "./views/errorView.js";

// * Unfinished
// Scrolling to about section displays the fixed navigation bar.
window.addEventListener("scroll", () => {
    if (elements.aboutSection.offsetTop - window.scrollY <= 80)
        elements.headerSection.classList.add("fixed");
    else elements.headerSection.classList.remove("fixed");
});

// Toggling the hamburger menu.
elements.navbarButton.addEventListener("click", () => {
    alert("navbar event triggered");
});

// *  -----------------------  Event Listeners  ----------------------------------

// Show Login Form.
elements.logInButton.addEventListener("click", SearchView.showLogInSection);
elements.takeAQuizButton.addEventListener("click", SearchView.showLogInSection);

// Show SignUp Form
elements.signUpButton.addEventListener("click", SearchView.showSignUpSection);

// Hiding all forms (The cross button function)
elements.closeButton.forEach((button) => {
    button.addEventListener("click", SearchView.hideFormsSection);
});

// Login Button
elements.logInButtonAction.addEventListener("click", (event) => {
    // Preventing default submission of the form.
    event.preventDefault();

    if (elements.logInForm.checkValidity()) {
        // Creating data from input.
        const data = {
            Email: elements.logInEmail.value,
            Password: elements.logInPassword.value,
        };

        // Calling async logIn method.
        User.logInUser(data).then((response) => {
            // Checking the response.
            if (response["result"]) {
                // Storing the token in Local Storage.
                localStorage.setItem("token", response["data"]["token"]);
                window.location.href = "./game.html";
            } else {
                // Showing errors
                ErrorView.showLogInError(response["error"]);
            }
        });
    } else {
        // Report the form validity.
        elements.logInForm.reportValidity();
    }
});

// SignUp Button.
elements.signUpButtonAction.addEventListener("click", (event) => {
    // Preventing default submission of the form.
    event.preventDefault();

    if (elements.signUpForm.checkValidity()) {
        // Checking whether both passwords match.
        if (
            elements.signUpPassword.value ===
            elements.signUpConfirmPassword.value
        ) {
            // Creating data object from inputs
            const data = {
                Name: elements.signUpName.value,
                Email: elements.signUpEmail.value,
                Password: elements.signUpPassword.value,
            };

            // Calling the async createUser method
            User.createUser(data).then((response) => {
                // Checking the response
                if (response["result"]) {
                    console.log(response);
                    alert("User created successfuly.");
                } else {
                    // Showing errors
                    ErrorView.showSignUpError(response["error"]);
                }
            });
        } else {
            // when passwords don't match.
            ErrorView.showSignUpError("Passwords do not match.");
        }
    } else {
        // Report the form validity.
        elements.signUpForm.reportValidity();
    }
});
