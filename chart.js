// Functie om de gegevens op te halen met een AJAX-request
async function fetchData(url) {
    const response = await fetch(url);
    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }
    return await response.json();
  }
  
  // Functie om de grafiek te genereren met Chart.js
  function createChart(ctx, data) {
    // Formatteer de gegevens voor Chart.js (bijvoorbeeld labels en datasets)
    const labels = data.map(item => item.label); // Vervang 'label' door de juiste kolomnaam
    const dataset = data.map(item => item.value); // Vervang 'value' door de juiste kolomnaam
  
    const chart = new Chart(ctx, {
      type: 'line', // of een ander grafiektype, zoals 'bar' of 'pie'
      data: {
        labels: labels,
        datasets: [{
          label: 'Dataset Label', // Voeg een geschikte label voor de dataset toe
          data: dataset,
          // Voeg aanvullende opties toe zoals kleuren, lijnstijlen, enz.
        }]
      },
      options: {
        // Configureer de opties voor interactieve functionaliteiten en
        // andere grafiekinstellingen
        responsive: true,
        scales: {
          x: {
            type: 'linear',
            title: {
              display: true,
              text: 'X-as titel' // Voeg een titel toe voor de X-as
            }
          },
          y: {
            title: {
              display: true,
              text: 'Y-as titel' // Voeg een titel toe voor de Y-as
            }
          }
        }
      }
    });
  }
  
  // Functie om de grafiek te initialiseren
  async function initChart() {
    try {
      const data = await fetchData('data.php'); // Vervang 'data.php' door de URL van je PHP-script
      const ctx = document.getElementById('myChart').getContext('2d');
      createChart(ctx, data);
    } catch (error) {
      console.error('Error fetching data:', error);
    }
  }
  
  // Initialiseer de grafiek wanneer de pagina is geladen
  window.addEventListener('DOMContentLoaded', initChart);  