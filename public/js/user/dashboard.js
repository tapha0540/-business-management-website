function renderTable(data, tableId, addCheckboxes = false) {
  const table = document.getElementById(tableId);
  table.innerHTML = "";

  if (!data || data.length === 0) return;

  const thead = document.createElement("thead");
  const headerRow = document.createElement("tr");

  // Checkbox header
  if (addCheckboxes) {
    const thCheck = document.createElement("th");
    const selectAll = document.createElement("input");
    selectAll.type = "checkbox";

    selectAll.addEventListener("change", function () {
      const checkboxes = table.querySelectorAll("tbody input[type='checkbox']");
      checkboxes.forEach((cb) => (cb.checked = this.checked));
    });

    thCheck.appendChild(selectAll);
    headerRow.appendChild(thCheck);
  }

  if ("imgUrl" in data[0]) {
    const thImg = document.createElement("th");
    thImg.textContent = "Image";
    headerRow.appendChild(thImg);
  }

  Object.keys(data[0]).forEach((key) => {
    if (key !== "imgUrl") {
      const th = document.createElement("th");
      th.textContent = key;
      headerRow.appendChild(th);
    }
  });

  thead.appendChild(headerRow);
  table.appendChild(thead);

  const tbody = document.createElement("tbody");

  const defaultSVG = `
    <svg width="50" height="50" viewBox="0 0 24 24" fill="none"
         xmlns="http://www.w3.org/2000/svg">
      <rect width="24" height="24" fill="#e9ecef"/>
      <path d="M7 17l3-3 2 2 3-4 2 5H7z" fill="#6c757d"/>
      <circle cx="9" cy="9" r="2" fill="#6c757d"/>
    </svg>
  `;

  data.forEach((row, index) => {
    const tr = document.createElement("tr");
    tr.classList.add("border-3", "border-primary");

    // Checkbox column
    if (addCheckboxes) {
      const tdCheck = document.createElement("td");
      const checkbox = document.createElement("input");
      checkbox.type = "checkbox";
      checkbox.value = row.id; // ou row.id si disponible
      tdCheck.appendChild(checkbox);
      tr.appendChild(tdCheck);
    }

    if ("imgUrl" in row) {
      const tdImg = document.createElement("td");

      if (row.imgUrl) {
        const img = document.createElement("img");
        img.src = row.imgUrl;
        img.style.width = "50px";
        img.style.height = "50px";
        img.style.objectFit = "cover";

        img.onerror = function () {
          tdImg.innerHTML = defaultSVG;
        };

        tdImg.appendChild(img);
      } else {
        tdImg.innerHTML = defaultSVG;
      }

      tr.appendChild(tdImg);
    }

    Object.keys(row).forEach((key) => {
      if (key !== "imgUrl") {
        const td = document.createElement("td");
        td.textContent = row[key];
        tr.appendChild(td);
      }
    });

    tbody.appendChild(tr);
  });

  table.appendChild(tbody);
  headerRow.classList.add("bg-primary", "text-primary");
  headerRow.style.color = "red";
}

function drawChart(
  canvasId,
  type,
  labels,
  data,
  label = "Dataset",
  othersDatasets = [],
) {
  const ctx = document.getElementById(canvasId).getContext("2d");

  return new Chart(ctx, {
    type: type, // "bar", "line", "pie", "doughnut", "radar", etc.
    data: {
      labels: labels,
      datasets: [
        {
          label: label,
          data: data,
          borderColor: "transparent", // couleur de la ligne
          backgroundColor: "#ff4d00", // couleur de remplissage si fill:true
          borderWidth: 2,
          fill: true, // remplir la zone sous la courbe
          tension: 0.4,
          borderRadius: 8,
        },
        ...othersDatasets,
      ],
    },
    options: {
      responsive: false,
      maintainAspectRatio: true,
      scales:
        type === "bar" || type === "line"
          ? {
              x: {
                ticks: {
                  color: "#ff4d00", // X-axis labels color
                },
              },
              y: {
                beginAtZero: true,
                ticks: {
                  color: "#", // Y-axis labels color
                },
              },
            }
          : {},
    },
    plugins: {
      title: {
        display: true,
        text: "Monthly Sales",
        color: "red", // Title color
        font: { size: 18 },
      },
      legend: {
        labels: {
          color: "blue", // Legend text color
        },
      },
    },
  });
}

const notificationBell = document.getElementById("notification-bell");
const notificationBadge = document.getElementById("notification-badge");
let lowStockProduits = [];

const getStockFromLowStockRow = (row) =>
  Number(row.Stock ?? row.stock ?? row.quantite ?? 0);

const getSeuilFromLowStockRow = (row) =>
  Number(
    row["Seuil Critique"] ?? row.seuil_critique ?? row.seuilCritique ?? 0,
  );

const renderLowStockNotification = () => {
  if (!notificationBell || !notificationBadge) return;

  const count = lowStockProduits.length;

  if (count > 0) {
    notificationBadge.textContent = String(count);
    notificationBadge.classList.remove("d-none");
    notificationBell.title = `Produits en seuil critique: ${count}`;
  } else {
    notificationBadge.textContent = "0";
    notificationBadge.classList.add("d-none");
    notificationBell.title = "Aucun produit en seuil critique";
  }
};

const fetchLowStockNotifications = async () => {
  const serverRes = await fetchApi(
    "http://localhost:8081/routes/produits/get_low_stock.php",
    "POST",
    { limit: 10 },
  );

  if (!serverRes.success) {
    return;
  }

  lowStockProduits = serverRes.data || [];
  renderLowStockNotification();
};

notificationBell?.addEventListener("click", () => {
  if (!lowStockProduits.length) {
    alert("Aucun produit n'est actuellement en seuil critique.");
    return;
  }

  const details = lowStockProduits
    .map(
      (produit, index) =>
        `${index + 1}. ${produit.nom} (stock: ${getStockFromLowStockRow(produit)}, seuil: ${getSeuilFromLowStockRow(produit)})`,
    )
    .join("\n");

  alert(`Produits en seuil critique:\n\n${details}`);
});

fetchLowStockNotifications();
setInterval(fetchLowStockNotifications, 60000);

