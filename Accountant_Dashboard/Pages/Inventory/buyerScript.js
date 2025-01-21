document.addEventListener('DOMContentLoaded', () => {
    const buyerDropdown = document.getElementById('buyer');
  
    fetch('./getBuyer.php')
        .then(response => response.json())
        .then(buyers => {
            buyers.forEach(buyer => {
                const option = document.createElement('option');
                option.value = buyer.buyer_id;
                option.textContent = buyer.email;
                buyerDropdown.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error fetching buyers:', error);
        });
  });
  
  