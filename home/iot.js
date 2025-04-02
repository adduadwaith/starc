document.addEventListener("DOMContentLoaded", function () {
    const editButton = document.getElementById("editButton");
    const personalInfo = document.getElementById("personalInfo");
    const emergencyContacts = document.getElementById("emergencyContacts");
    let isEditing = false;

    editButton.addEventListener("click", function () {
        isEditing = !isEditing;

        if (isEditing) {
            editButton.textContent = "Save";
            makeEditable(personalInfo);
            makeEditable(emergencyContacts);
        } else {
            editButton.textContent = "Edit";
            makeUneditable(personalInfo);
            makeUneditable(emergencyContacts);
        }
    });

    function makeEditable(element) {
        element.querySelectorAll("span").forEach(span => {
            span.contentEditable = true;
        });
    }

    function makeUneditable(element) {
        element.querySelectorAll("span").forEach(span => {
            span.contentEditable = false;
        });
    }
});