function renderTable(data, tableId) {
  const table = document.getElementById(tableId);
  table.innerHTML = "";

  if (!data || data.length === 0) return;

  // header
  const thead = document.createElement("thead");
  const headerRow = document.createElement("tr");
  Object.keys(data[0]).forEach((key) => {
    const th = document.createElement("th");
    th.textContent = key;
    headerRow.appendChild(th);
  });
  thead.appendChild(headerRow);
  table.appendChild(thead);

  // body
  const tbody = document.createElement("tbody");
  data.forEach((row) => {
    const tr = document.createElement("tr");
    Object.values(row).forEach((value) => {
      const td = document.createElement("td");
      td.textContent = value;
      tr.appendChild(td);
      tr.classList.add("border-3", "border-primary");
      tr.style.cursor = "pointer";
    });
    tbody.appendChild(tr);
  });
  table.appendChild(tbody);
}
