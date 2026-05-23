function escapeHtml(value) {
  return String(value ?? "")
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}

function parseAppDate(value) {
  if (!value || typeof value !== "string") return null;

  const trimmed = value.trim();
  const mysqlDateMatch = trimmed.match(
    /^(\d{4})-(\d{2})-(\d{2})(?:[ T](\d{2}):(\d{2})(?::(\d{2}))?)?/,
  );

  if (mysqlDateMatch) {
    const [, year, month, day, hour = "00", minute = "00", second = "00"] =
      mysqlDateMatch;
    return new Date(
      Number(year),
      Number(month) - 1,
      Number(day),
      Number(hour),
      Number(minute),
      Number(second),
    );
  }

  const frDateMatch = trimmed.match(/^(\d{2})\/(\d{2})\/(\d{4})$/);
  if (frDateMatch) {
    const [, day, month, year] = frDateMatch;
    return new Date(Number(year), Number(month) - 1, Number(day));
  }

  const date = new Date(trimmed);
  return Number.isNaN(date.getTime()) ? null : date;
}

function isDateLikeKey(key) {
  const normalized = String(key)
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "")
    .toLowerCase();

  return (
    normalized.includes("date") ||
    normalized.includes("created") ||
    normalized.includes("updated") ||
    normalized.includes("cree le") ||
    normalized.includes("commande le") ||
    normalized.includes("cloture le") ||
    normalized.includes("cloturee le")
  );
}

function formatAppDateTimeHtml(value) {
  const date = parseAppDate(value);

  if (!date) {
    return escapeHtml(value);
  }

  const dateLabel = new Intl.DateTimeFormat("fr-SN", {
    day: "2-digit",
    month: "long",
    year: "numeric",
  }).format(date);

  const hasTime =
    typeof value === "string" &&
    /[ T]\d{2}:\d{2}(?::\d{2})?/.test(value.trim());

  const timeLabel = hasTime
    ? new Intl.DateTimeFormat("fr-SN", {
      hour: "2-digit",
      minute: "2-digit",
    }).format(date)
    : "";

  return `
    <span class="date-time-stack d-inline-flex flex-column align-items-center">
      <span>${escapeHtml(dateLabel)}</span>
      ${timeLabel ? `<small>${escapeHtml(timeLabel)}</small>` : ""}
    </span>
  `;
}

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
        if (isDateLikeKey(key)) {
          td.innerHTML = formatAppDateTimeHtml(row[key]);
        } else {
          td.textContent = row[key];
        }
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
  titleText = "Monthly Sales",
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
      responsive: true,
      maintainAspectRatio: false,
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
        text: titleText,
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

const notificationBellSelector = "#notification-bell";
const notificationBadgeSelector = "#notification-badge";
const notificationPanelSelector = "#notification-panel";
const notificationListSelector = "#notification-list";
const notificationSummarySelector = "#notification-summary";
const notificationRefreshBtnSelector = "#notification-refresh";

const getNotificationBell = () => document.querySelector(notificationBellSelector);
const getNotificationBadge = () => document.querySelector(notificationBadgeSelector);
const getNotificationPanel = () => document.querySelector(notificationPanelSelector);
const getNotificationList = () =>
  document.querySelector(notificationListSelector) ||
  document.querySelector(".notification-panel-body");
const getNotificationSummary = () => document.querySelector(notificationSummarySelector);
const getNotificationRefreshBtn = () => document.querySelector(notificationRefreshBtnSelector);

let lowStockProduits = [];

const fetchLowStockNotifications = async () => {
  const serverRes = await fetchApi(
    "http://localhost:8081/routes/produits/get_low_stock.php",
    "GET",
  );

  console.log(serverRes);
  if (!serverRes.success) {
    return;
  }


  lowStockProduits = serverRes.data || [];
  console.log("low stock Produits: " + lowStockProduits);

  renderLowStockNotification();
};


const getStockFromLowStockRow = (row) =>
  Number(row.Stock ?? row.stock ?? row.quantite ?? 0);

const getSeuilFromLowStockRow = (row) =>
  Number(
    row["Seuil Critique"] ?? row.seuil_critique ?? row.seuilCritique ?? 0,
  );

const renderLowStockNotification = () => {
  const notificationBell = getNotificationBell();
  const notificationBadge = getNotificationBadge();
  const notificationSummary = getNotificationSummary();
  const notificationList = getNotificationList();

  if (!notificationBell || !notificationBadge || !notificationList) return;

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

  if (notificationSummary) {
    notificationSummary.textContent =
      count > 0
        ? `${count} produit${count > 1 ? "s" : ""} à réapprovisionner`
        : "Aucun produit en alerte";
  }

  notificationList.innerHTML = "";

  if (count === 0) {
    notificationList.innerHTML = `
      <div class="notification-empty">
        Tout va bien côté stock. On respire un peu.
      </div>
    `;
    return;
  }

  lowStockProduits.forEach((produit) => {
    const item = document.createElement("div");
    item.className = "notification-item";
    const stock = getStockFromLowStockRow(produit);
    const seuil = getSeuilFromLowStockRow(produit);

    item.innerHTML = `
      <div>
        <strong>${escapeHtml(produit.nom ?? "Produit sans nom")}</strong>
        <small>Seuil critique : ${seuil}</small>
      </div>
      <span class="notification-stock-pill">Stock ${stock}</span>
    `;
    notificationList.appendChild(item);
  });
};


const initLowStockNotifications = () => {
  const notificationBell = getNotificationBell();
  const notificationPanel = getNotificationPanel();
  const notificationRefreshBtn = getNotificationRefreshBtn();

  notificationBell?.addEventListener("click", () => {
    if (!notificationPanel) return;
    const isHidden = notificationPanel.classList.toggle("d-none");
    notificationBell.setAttribute("aria-expanded", String(!isHidden));
  });

  notificationRefreshBtn?.addEventListener("click", fetchLowStockNotifications);

  document.addEventListener("click", (event) => {
    if (
      !notificationPanel ||
      !notificationBell ||
      notificationPanel.classList.contains("d-none")
    ) {
      return;
    }

    const target = event.target;
    if (
      target instanceof Node &&
      !notificationPanel.contains(target) &&
      !notificationBell.contains(target)
    ) {
      notificationPanel.classList.add("d-none");
      notificationBell.setAttribute("aria-expanded", "false");
    }
  });

  fetchLowStockNotifications();
  setInterval(fetchLowStockNotifications, 60000);
};

globalThis.initLowStockNotifications = initLowStockNotifications;

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initLowStockNotifications);
} else {
  initLowStockNotifications();
}

