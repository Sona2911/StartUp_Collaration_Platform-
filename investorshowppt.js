document.addEventListener('DOMContentLoaded', function() {
    const pptList = document.getElementById('pptList');

    // Fetch submitted PPT files from the backend
    fetch('investorshowppt.php')
        .then(response => response.json())
        .then(data => {
            data.forEach(ppt => {
                const listItem = document.createElement('li');
                const link = document.createElement('a');
                link.href = ppt.file;
                link.textContent = ppt.fileName;
                listItem.appendChild(link);

                const ratingForm = document.createElement('form');
                ratingForm.action = 'rate_ppt.php';
                ratingForm.method = 'POST';
                ratingForm.innerHTML = `
                    <label for="rating">Rate (out of 10): </label>
                    <input type="number" name="rating" min="0" max="10" required>
                    <input type="hidden" name="ppt_file" value="${ppt.file}">
                    <button type="submit">Submit Rating</button>
                `;
                listItem.appendChild(ratingForm);

                pptList.appendChild(listItem);
            });
        })
        .catch(error => console.error('Error fetching PPT files:', error));
});
