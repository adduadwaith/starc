document.addEventListener('DOMContentLoaded', function() {
    const seeMoreButtons = document.querySelectorAll('.see-more-btn');

    seeMoreButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const targetDropdown = document.getElementById(targetId);

            if (targetDropdown) {
                targetDropdown.classList.toggle('show');
            } else {
                console.error(`Dropdown with ID '${targetId}' not found.`);
            }
        });
    });
});