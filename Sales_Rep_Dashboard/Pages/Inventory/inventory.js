document.addEventListener("DOMContentLoaded", function () {
    const deleteButtons = document.querySelectorAll(".delete-btn");
    
    deleteButtons.forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            const confirmed = confirm("Are you sure you want to delete this item?");
            
            if (confirmed) {
                const row = button.closest("tr");
                row.remove();
            }
        });
    });
});



document.addEventListener("DOMContentLoaded", function () {
  function setError(inputId, errorMessage) {
    const errorElement = document.getElementById(`${inputId}-error`);
    errorElement.textContent = errorMessage; 
  }

  function clearError(inputId) {
    const errorElement = document.getElementById(`${inputId}-error`);
    errorElement.textContent = ""; 
  }

  document.getElementById("weight").addEventListener("input", function () {
    const value = parseFloat(this.value);
    if (isNaN(value) || value <= 0) {
      setError("weight", "Weight must be greater than 0.");
    } else {
      clearError("weight");
    }
  });

  document.getElementById("amount").addEventListener("input", function () {
    const value = parseFloat(this.value);
    if (isNaN(value) || value <= 0) {
      setError("amount", "Amount must be greater than 0.");
    } else {
      clearError("amount");
    }
  });

  document.getElementById("amountSettled").addEventListener("input", function () {
    const settledValue = parseFloat(this.value);
    const amountValue = parseFloat(document.getElementById("amount").value);
    if (isNaN(settledValue) || settledValue <= 0) {
      setError("amountSettled", "Amount Settled must be greater than 0.");
    } else if (settledValue > amountValue) {
      setError("amountSettled", "Amount Settled cannot exceed Amount.");
    } else {
      clearError("amountSettled");
    }
  });

  document.getElementById("shape").addEventListener("input", function () {
    const value = this.value.trim();
    if (!/^[a-zA-Z\s]+$/.test(value)) {
      setError("shape", "Shape must contain only letters.");
    } else {
      clearError("shape");
    }
  });

  document.getElementById("colour").addEventListener("input", function () {
    const value = this.value.trim();
    if (!/^[a-zA-Z\s]+$/.test(value)) {
      setError("colour", "Colour must contain only letters.");
    } else {
      clearError("colour");
    }
  });

  document.getElementById("origin").addEventListener("input", function () {
    const value = this.value.trim();
    if (!/^[a-zA-Z\s]+$/.test(value)) {
      setError("origin", "Origin must contain only letters.");
    } else {
      clearError("origin");
    }
  });

  document.getElementById("addgemForm").addEventListener("submit", function (e) {
    const size = parseFloat(document.getElementById("size").value);
    const weight = parseFloat(document.getElementById("weight").value);
    const amount = parseFloat(document.getElementById("amount").value);
    const amountSettled = parseFloat(document.getElementById("amountSettled").value);
    const shape = document.getElementById("shape").value.trim();
    const colour = document.getElementById("colour").value.trim();
    const origin = document.getElementById("origin").value.trim();

    let isValid = true;

    if (isNaN(weight) || weight <= 0) {
      setError("weight", "Weight must be greater than 0.");
      isValid = false;
    }
    if (isNaN(amount) || amount <= 0) {
      setError("amount", "Amount must be greater than 0.");
      isValid = false;
    }
    if (isNaN(amountSettled) || amountSettled <= 0 || amountSettled > amount) {
      setError("amountSettled", "Amount Settled must be greater than 0 and not exceed Amount.");
      isValid = false;
    }
    if (!/^[a-zA-Z\s]+$/.test(shape)) {
      setError("shape", "Shape must contain only letters.");
      isValid = false;
    }
    if (!/^[a-zA-Z\s]+$/.test(colour)) {
      setError("colour", "Colour must contain only letters.");
      isValid = false;
    }
    if (!/^[a-zA-Z\s]+$/.test(origin)) {
      setError("origin", "Origin must contain only letters.");
      isValid = false;
    }

    if (!isValid) {
      e.preventDefault();
    }
  });
});



document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("editgemForm");

  if (!form) return;

  form.addEventListener("submit", function (e) {
    let isValid = true;

    const size = document.getElementById("size").value.trim();
    const shape = document.getElementById("shape").value.trim();
    const colour = document.getElementById("colour").value.trim();
    const type = document.getElementById("type").value.trim();
    const weightElement = document.getElementById("weight");
    const origin = document.getElementById("origin").value.trim();
    const amountElement = document.getElementById("amount");
    const image = document.getElementById("image").value.trim();
    const certificate = document.getElementById("certificate").value.trim();
    const description = document.getElementById("description").value.trim();
    const visibility = document.getElementById("visibility").value.trim();
    const availability = document.getElementById("availability").value.trim();
    const buyer_id = document.getElementById("buyer").value.trim();

    const weight = weightElement ? parseFloat(weightElement.value.trim()) : NaN;
    const amount = amountElement ? parseFloat(amountElement.value.trim()) : NaN;

    const requiredFields = [
      { field: size, id: "size", name: "Size" },
      { field: shape, id: "shape", name: "Shape" },
      { field: colour, id: "colour", name: "Colour" },
      { field: type, id: "type", name: "Type" },
      { field: origin, id: "origin", name: "Origin" },
      { field: description, id: "description", name: "Description" },
      { field: visibility, id: "visibility", name: "Visibility" },
      { field: availability, id: "availability", name: "Availability" },
      { field: buyer_id, id: "buyer", name: "Buyer ID" }
    ];

    requiredFields.forEach(({ field, id, name }) => {
      if (!field) {
        setError(id, `${name} is required.`);
        isValid = false;
      } else {
        clearError(id);
      }
    });

    if (!isNaN(weight) && weight <= 0) {
      setError("weight", "Weight must be greater than 0.");
      isValid = false;
    } else {
      clearError("weight");
    }

    if (!isNaN(amount) && amount <= 0) {
      setError("amount", "Amount must be greater than 0.");
      isValid = false;
    }

    if (!/^[a-zA-Z\s]+$/.test(shape)) {
      setError("shape", "Shape must contain only letters.");
      isValid = false;
    } else {
      clearError("shape");
    }

    if (!/^[a-zA-Z\s]+$/.test(colour)) {
      setError("colour", "Colour must contain only letters.");
      isValid = false;
    } else {
      clearError("colour");
    }

    if (!/^[a-zA-Z\s]+$/.test(origin)) {
      setError("origin", "Origin must contain only letters.");
      isValid = false;
    } else {
      clearError("origin");
    }

    if (!isValid) {
      e.preventDefault();
    }
  });

  function setError(inputId, errorMessage) {
    let errorElement = document.getElementById(`${inputId}-error`);
    if (!errorElement) {
        errorElement = document.createElement("span");
        errorElement.id = `${inputId}-error`;
        errorElement.style.color = "red";
        errorElement.style.fontSize = "12px";
        document.getElementById(inputId).after(errorElement);
    }
    errorElement.textContent = errorMessage;
}

function clearError(inputId) {
    const errorElement = document.getElementById(`${inputId}-error`);
    if (errorElement) {
        errorElement.textContent = "";
    }
}
  
});
