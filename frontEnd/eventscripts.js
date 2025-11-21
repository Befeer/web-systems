//for the popup modal in calendar
const modal = document.getElementById('modal');
const modalDate = document.getElementById('modal-date');
const closeBtn = document.querySelector('.modal-close');
const days = document.querySelectorAll('.day');

// Show modal on day click
days.forEach(day => {
    day.addEventListener('click', () => {
        const date = day.getAttribute('data-date');
        modalDate.textContent = date; 
        modal.style.display = 'flex';
    });
});

// Close modal on close button
closeBtn.addEventListener('click', () => {
    modal.style.display = 'none';
});

// Close modal if clicked outside content
modal.addEventListener('click', e => {
    if(e.target === modal) modal.style.display = 'none';
});

//for password confirmation
function validatePassword() {
    let password = document.getElementById("admin_password").value;
    let confirm = document.getElementById("admin_confirm").value;

    if (password !== confirm) {
        alert("Error: Passwords do not match.");
        return false; // stops submission
    }
    return true;
}

//for event validation
function validateEvent() {
    let start = document.getElementById("event_start").value;
    let end = document.getElementById("event_end").value;

    if (new Date(end) < new Date(start)) {
        alert("Error: Event End Date cannot be earlier than the Start Date.");
        return false;
    }
    return true;
}


