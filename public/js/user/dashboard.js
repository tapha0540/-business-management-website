function renderTable(data, tableId) {
  const table = document.getElementById(tableId);
  table.innerHTML = "";

  if (!data || data.length === 0) return;

  const thead = document.createElement("thead");
  const headerRow = document.createElement("tr");

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

  data.forEach((row) => {
    const tr = document.createElement("tr");
    tr.classList.add("border-3", "border-primary");
    tr.style.cursor = "pointer";

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
}
