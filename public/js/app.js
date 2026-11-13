/*
 * GymLog – small vanilla JavaScript, no build step (loaded with "defer" in the layout).
 * Each feature is switched on by a data-* attribute in the HTML, so the HTML stays free of inline JS.
 */

// 1. Ask for confirmation before submitting a form with data-confirm="Are you sure?"
document.querySelectorAll('form[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (!window.confirm(form.dataset.confirm)) {
            event.preventDefault(); // "Cancel" clicked: don't send the form
        }
    });
});

// 2. Submit the form as soon as a <select data-autosubmit> changes (e.g. the athlete filter).
document.querySelectorAll('select[data-autosubmit]').forEach((select) => {
    select.addEventListener('change', () => select.form.submit());
});

// 3. Show a preview of the chosen image before uploading:
//    <input type="file" data-preview="preview-id"> + <img id="preview-id">
document.querySelectorAll('input[type=file][data-preview]').forEach((input) => {
    input.addEventListener('change', () => {
        const img = document.getElementById(input.dataset.preview);
        const file = input.files[0];
        if (!img || !file) return;
        img.src = URL.createObjectURL(file); // temporary local URL, nothing is uploaded yet
        img.hidden = false;
    });
});
