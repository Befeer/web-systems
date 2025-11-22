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
    const error = document.getElementById('error');


    if (password !== confirm) {
        error.style.display = "block";  // show error
        return false; // prevent form submission
    }

    error.style.display = "none"; // hide error if valid
    return true;
}

function validateEvent() {
    const start = document.getElementById('event_start').value;
    const end = document.getElementById('event_end').value;
    const error = document.getElementById('error');

    if (new Date(end) < new Date(start)) {
        error.style.display = "block";  // show error
        return false; // prevent form submission
    }

    error.style.display = "none"; // hide error if valid
    return true;
}

function openAddModal() {
    document.getElementById('eventAddModal').style.display = 'flex';
}
function closeModal() {
    const add = document.getElementById("eventAddModal");
    const edit = document.getElementById("eventEditModal");
    if (add) add.style.display = "none";
    if (edit) edit.style.display = "none";
}
