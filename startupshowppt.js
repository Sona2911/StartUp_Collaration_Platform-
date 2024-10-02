// Make an AJAX request to fetch the list of submitted PPTs from the backend
fetch('backend/api/submit-ppts')
  .then(response => response.json())
  .then(data => {
    // Iterate over the list of PPTs
    data.forEach(ppt => {
      // Create HTML elements to represent each PPT
      const pptElement = document.createElement('div');
      pptElement.classList.add('ppt');
      pptElement.innerHTML = `
        <h3>${ppt.title}</h3>
        <p>${ppt.description}</p>
        <a href="${ppt.url}" target="_blank">View PPT</a>
      `;
      
      // Append the HTML elements to the container on the dashboard
      document.querySelector('.ppt-container').appendChild(pptElement);
    });
  })
  .catch(error => {
    console.error('Error fetching PPTs:', error);
  });
