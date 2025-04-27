document.addEventListener("DOMContentLoaded", () => {
    fetchAllGems();
  });
  
  function fetchAllGems() {
    fetch('getGemExpensesData.php?action=fetchGems')
      .then(res => res.text())
      .then(text => {
        console.log("Raw response:", text);
        try {
          const data = JSON.parse(text);
          const select = document.getElementById('stone_id');
          select.innerHTML = '<option value="" disabled selected>Select a Gem</option>';
          data.forEach(gem => {
            const option = document.createElement('option');
            option.value = gem.stone_id;
            option.textContent = `ID: ${gem.stone_id} | Type: ${gem.type} | Availability: ${gem.availability} | Visibility: ${gem.visibility}`;
            select.appendChild(option);
          });
        } catch (err) {
          console.error("Failed to parse JSON:", err);
          alert("Error fetching gem data. Please check the console for details.");
        }
      })
      .catch(err => {
        console.error("Fetch error:", err);
        alert("Failed to load gems.");
      });
  }
  
  function selectGemExpensesData() {
    const gemId = document.getElementById('stone_id').value;
    if (!gemId) return alert("Please select a gem.");
  
    fetch(`getGemExpensesData.php?action=fetchExpenses&stone_id=${gemId}`)
      .then(res => res.json())
      .then(data => {
        const { gem, expenses } = data;
  
        if (!gem) {
          return alert("Gem not found.");
        }
  
        // Set gem details
        document.getElementById('gemId').textContent = gem.stone_id;
        document.getElementById('gemType').textContent = gem.type;
        document.getElementById('gemAvailability').textContent = gem.availability;
        document.getElementById('gemVisibility').textContent = gem.visibility;
  
        // Safely extract values or use 0
        const getExpenseValue = (key) => parseFloat(expenses[key] ?? 0).toFixed(2);
  
        document.getElementById("cuttingPolishing").value = getExpenseValue("Cutting & Polishing");
        document.getElementById("certificationFees").value = getExpenseValue("Certifications");
        document.getElementById("marketing").value = getExpenseValue("Marketing");
        document.getElementById("logistics").value = getExpenseValue("Logistics");
        document.getElementById("otherExpenses").value = getExpenseValue("Other");
  
        const totalCost = Object.values(expenses).reduce((sum, val) => sum + parseFloat(val || 0), 0);
        document.getElementById("totalCost").value = totalCost.toFixed(2);
      })
      .catch(err => {
        console.error("Error fetching expense data:", err);
        alert("Failed to fetch expense data.");
      });
  }
  
  function generateGemExpensesReport() {
    const gemId = document.getElementById("gemId").textContent;

    // Check if gem is selected
    if (!gemId || gemId.trim() === "") {
        // Display error message if no gem is selected
        alert("Please select a gem before generating the report.");
        return;  // Exit the function to prevent redirection
    }

    const reportData = {
        gem: {
            stone_id: gemId,
            type: document.getElementById("gemType").textContent,
            availability: document.getElementById("gemAvailability").textContent,
            visibility: document.getElementById("gemVisibility").textContent
        },
        totals: {
            "Cutting and Polishing": parseFloat(document.getElementById("cuttingPolishing").value) || 0,
            "Certifications": parseFloat(document.getElementById("certificationFees").value) || 0,
            "Marketing": parseFloat(document.getElementById("marketing").value) || 0,
            "Logistics": parseFloat(document.getElementById("logistics").value) || 0,
            "Other": parseFloat(document.getElementById("otherExpenses").value) || 0
        }
    };

    // Calculate total cost
    reportData.totalCost = Object.values(reportData.totals).reduce((sum, value) => sum + value, 0);

    // Save to localStorage with correct key
    localStorage.setItem("gemExpensesReportData", JSON.stringify(reportData));

    // Redirect to preview page
    window.location.href = "gemexpensespreview.html";
}

  
  
  