import Subject from "./models/Subject.js";

let subjects = null;
if ("token" in window.localStorage) {
    // Getting all subjects.
    Subject.getAllSubjects(window.localStorage["token"]).then((response) => {
        // Checking the response
        if (!response["result"]) {
            // Invalid Token/ Database Error
            window.location.href = "./index.html";
        } else {
            // All good
            subjects = response["data"];

            const numberOfSubjects = subjects.length;
            console.log(numberOfSubjects);

            let subjectsSection = document.createElement("div");
            subjectsSection.classList.add("section-subjects");

            let headingContainer = document.createElement("div");
            headingContainer.classList.add("heading", "about-heading");

            let aboutHeading = document.createElement("h2");
            aboutHeading.appendChild(
                document.createTextNode("Choose a Subject")
            );

            const headingHr = document.createElement("hr");
            headingHr.classList.add("heading-hr");

            headingContainer.appendChild(aboutHeading);
            headingContainer.appendChild(headingHr);

            const subjectsContainer = document.createElement("div");
            subjectsContainer.classList.add("subjects-container");

            for (let i = 0; i < numberOfSubjects; i++) {
                // const subjectID = subjects[i]["SubID"];
                // const subjectName = subjects[i]["SubjectName"];

                const subjectElement = document.createElement("div");
                subjectElement.classList.add("subject-element");

                const svgContainer = document.createElement("figure");

                const svgIcon = document.createElement("svg");
                svgIcon.setAttribute("viewBox", "0 0 100 100");
                svgIcon.setAttribute(
                    "alt",
                    `${subjects[i]["SubjectName"]} Icon`
                );

                // const svgElement = document.createElement("use");
                // svgElement.setAttribute(
                //     "xlink:href",
                //     `./icons/${subjectName.toLowerCase()}.svg#${subjectName.toLowerCase()}`
                // );

                const subjectName = document.createElement("h3");
                subjectName.classList.add("subject-name");
                subjectName.textContent = subjects[i]["SubjectName"];

                const subjectDescription = document.createElement("p");
                subjectDescription.classList.add("subject-desc");
                subjectDescription.textContent = `Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.`;

                // svgIcon.appendChild(svgElement);
                svgContainer.appendChild(svgIcon);
                subjectElement.appendChild(svgContainer);
                subjectElement.appendChild(subjectName);
                subjectElement.appendChild(subjectDescription);

                subjectsContainer.appendChild(subjectElement);

                // subjectContainer += `
                // <div class="subject-element" id="${subjectID}">
                //     <figure>
                //         <svg viewBox="0 0 100 100" alt="${subjectName} Icon">
                //             <use xlink:href="./icons/${subjectName.toLowerCase()}.svg#${subjectName.toLowerCase()}"></use>
                //         </svg>
                //     </figure>
                //     <h3 class="subject-name">${subjectName}</h3>
                //     <p class="subject-desc">
                // Lorem Ipsum is simply dummy text of the printing and
                // typesetting industry. Lorem Ipsum has been the
                // industry's standard dummy text ever since the 1500s,
                // when an unknown printer took a galley of type and
                // scrambled it to make a type specimen book.
                //     </p>
                // </div>\n`;
            }

            subjectsSection.appendChild(headingContainer);
            subjectsSection.appendChild(subjectsContainer);
            document.body.appendChild(subjectsSection);

            // console.log(response);
        }
    });
} else {
    // The token does not exists. Redirecting to homepage
    window.location.href = "./index.html";
}

// const subjects = [
//     {
//         SubID: "1",
//         SubjectName: "History",
//         TotalQ: "8",
//         CreatedAt: null,
//         CreatedBy: null,
//     },
//     {
//         SubID: "2",
//         SubjectName: "Geography",
//         TotalQ: "7",
//         CreatedAt: null,
//         CreatedBy: null,
//     },
//     {
//         SubID: "3",
//         SubjectName: "Political Science",
//         TotalQ: "4",
//         CreatedAt: null,
//         CreatedBy: null,
//     },
//     {
//         SubID: "4",
//         SubjectName: "Economics",
//         TotalQ: "1",
//         CreatedAt: null,
//         CreatedBy: null,
//     },
//     {
//         SubID: "5",
//         SubjectName: "Physics",
//         TotalQ: "7",
//         CreatedAt: null,
//         CreatedBy: null,
//     },
//     {
//         SubID: "6",
//         SubjectName: "Chemistry",
//         TotalQ: "8",
//         CreatedAt: null,
//         CreatedBy: null,
//     },
//     {
//         SubID: "7",
//         SubjectName: "Maths",
//         TotalQ: "0",
//         CreatedAt: null,
//         CreatedBy: null,
//     },
//     {
//         SubID: "8",
//         SubjectName: "Biology",
//         TotalQ: "0",
//         CreatedAt: null,
//         CreatedBy: null,
//     },
// ];

if (subjects !== null) {
    window.onload = () => {
        // Creating the Subject Section
        /* <div class="section-subjects">
        <div class="heading about-heading">
            <h2>
                Choose a Subject
            </h2>
            <hr class="heading-hr" />
        </div>
        
        <div class="subjects-container">
            <div class="subject-element">
                <figure>
                    <svg viewBox="0 0 100 100" alt="Physics Icon">
                        <use xlink:href="./icons/physics.svg#physics"></use>
                    </svg>
                </figure>
                <h3 class="subject-name">Physics</h3>
                <p class="subject-desc">
                    Lorem Ipsum is simply dummy text of the printing and
                    typesetting industry. Lorem Ipsum has been the
                    industry's standard dummy text ever since the 1500s,
                    when an unknown printer took a galley of type and
                    scrambled it to make a type specimen book.
                </p>
            </div>
        </div>
        </div> */
    };
}

{
}
