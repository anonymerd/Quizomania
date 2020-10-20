import {
    elements
} from './base.js';

export const hideAllSections = () => {
    elements.allSections.forEach((section) => {
        section.style.display = 'none';
    });
};

export const showAllSections = () => {
    elements.allSections.forEach((section) => {
        section.style.display = 'block';
    });

    elements.headerSection.style.display = 'flex';
};

export const toggleMobileNavigation = () => {
    if (elements.navbarButtonsContainer.classList.contains('mobile-nav')) {
        // Closing the Mobile Navigation.
        elements.navbarButtonsContainer.classList.remove('animate__fadeInRightBig');
        elements.navbarButtonsContainer.classList.add('animate__fadeOutRight');
        elements.navbarButtonIcon.classList.remove('fas', 'fa-times');
        elements.navbarButtonIcon.classList.add('fas', 'fa-hamburger');
        setTimeout(() => {
            elements.navbarButtonsContainer.classList.remove('animate__fadeOutRight');
            elements.navbarButtonsContainer.classList.add('animate__fadeInRightBig');
            elements.navbarButtonsContainer.classList.remove('mobile-nav');
        }, 1000);
    } else {
        // Opening the Mobile Navigation.
        elements.navbarButtonsContainer.classList.add('mobile-nav');
        elements.navbarButtonsContainer.classList.remove('animate__fadeOutRight');
        elements.navbarButtonsContainer.classList.add('animate__fadeInRightBig');
        elements.navbarButtonIcon.classList.remove('fas', 'fa-hamburger');
        elements.navbarButtonIcon.classList.add('fas', 'fa-times');
    }

}

// This shows reset password section from the login form
export const openResetPasswordSection = () => {
    // Animating LogIn Section exit.
    elements.logInSection.classList.add('animate__animated', 'animate__flipOutY', 'animate__fast');


    // Showing Reset Password Section after 0.8 sec.
    setTimeout(() => {
        // Removing the animation from login section.
        elements.logInSection.classList.remove('animate__animated', 'animate__flipOutY', 'animate__fast');

        // Hiding the logIn Section.
        elements.logInSection.style.display = 'none';

        // Showing Reset Password Section.
        elements.resetPasswordSection.style.display = 'block';
        // Animating resetPassword Section.
        elements.resetPasswordSection.classList.add('animate__animated', 'animate__flipInY', 'animate__fast');
    }, 800);
};

// This shows login section from the main section
export const showLogInSection = () => {
    // Hiding all sections
    hideAllSections();

    // Showing the blurred div
    elements.formsContainer.style.display = 'block';

    // Showing the LogIn Section.
    elements.logInSection.style.display = 'block';
    // Animating the logIn Section entrance.
    elements.logInSection.classList.add('animate__animated', 'animate__bounceInLeft', 'animate__fast');
};

// This shows login section from the signup form
export const openLogInSection = () => {
    // Hiding Forms section.
    hideFormsSection();

    // Showing LogIn Section after 0.8 sec delay.
    setTimeout(showLogInSection, 800);
};



// This shows signup section from the main section.
export const showSignUpSection = () => {
    // Hiding all sections
    hideAllSections();

    // Showing the blurred div
    elements.formsContainer.style.display = 'block';

    // Showing the SignUp Section.
    elements.signUpSection.style.display = 'block';
    // Animating the SignUp section entrance.
    elements.signUpSection.classList.add('animate__animated', 'animate__bounceInRight', 'animate__fast');
};

// This shows signup section from the login form.
export const openSignUpSection = () => {

    // Hiding Forms Section.
    hideFormsSection();

    // Showing SignUp Section after 0.8 sec delay.
    setTimeout(showSignUpSection, 800);
};

export const hideFormsSection = () => {

    // Adding animation class
    elements.logInSection.classList.add('animate__animated', 'animate__bounceOutRight', 'animate__fast');
    elements.signUpSection.classList.add('animate__animated', 'animate__bounceOutLeft', 'animate__fast');
    elements.resetPasswordSection.classList.add('animate__animated', 'animate__flipOutY', 'animate__fast');


    // After 0.8 sec of animation.
    setTimeout(() => {

        // Hiding the blurred div
        elements.formsContainer.style.display = 'none';

        // Removing animation class.
        elements.logInSection.classList.remove('animate__animated', 'animate__bounceOutRight', 'animate__fast');
        elements.signUpSection.classList.remove('animate__animated', 'animate__bounceOutLeft', 'animate__fast');
        elements.resetPasswordSection.classList.remove('animate__animated', 'animate__flipOutY', 'animate__fast');

        // Hiding all the forms.
        elements.logInSection.style.display = 'none';
        elements.signUpSection.style.display = 'none';
        elements.resetPasswordSection.style.display = 'none';

        // Showing/Unhiding all the sections.
        showAllSections();
    }, 800);
};

// Showing Leaderboard Section
export const renderLeaderboard = leaderboard => {
    // Rendering top 3 players
    let top3Players = `
        <div class = "top-player-card rank-2">
            <i class = "fas fa-medal"></i> 
            <span class = "player-name">${leaderboard[1].Name}</span> 
            <span class = "player-score">${leaderboard[1].Score}</span> 
        </div>
        <div class = "top-player-card rank-1">
            <i class = "fas fa-medal"></i> 
            <span class = "player-name">${leaderboard[0].Name}</span> 
            <span class = "player-score">${leaderboard[0].Score}</span> 
        </div>
        <div class = "top-player-card rank-3">
            <i class = "fas fa-medal"></i> 
            <span class = "player-name">${leaderboard[2].Name}</span> 
            <span class = "player-score">${leaderboard[2].Score}</span> 
        </div>
    `;

    elements.top3.innerHTML = top3Players;

    // Rendering rest of the players.

    let top10Plaeyers = ``;
    const length = leaderboard.length < 10 ? leaderboard.length : 10;
    for (let i = 3; i < length; i++) {
        top10Plaeyers += `
            <div class = "player-rank-bar">
            <span class = "player-rank">${i+1}</span> 
            <span class = "player-name">${leaderboard[i].Name}</span> 
            <span class = "player-score">${leaderboard[i].Score}</span> 
            </div>
        `;
    }

    elements.top10.innerHTML = top10Plaeyers;
}