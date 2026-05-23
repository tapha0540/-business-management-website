let homeForm = null;
let errorMsg = null;
let homeSpinner = null;

const chartConfigs = [
  {
    metric: "latest-orders",
    chartId: "latest-orders-canvas",
    title: "Dernières commandes",
    datasetLabel: "Montant total",
    labelFormatter: (row) => `Commande #${row.Id}`,
    valueKey: "Montant Total",
    type: "bar",
  },
  {
    metric: "best-orders",
    chartId: "best-orders-canvas",
    title: "Meilleures commandes par montant",
    datasetLabel: "Montant total",
    labelFormatter: (row) => `Commande #${row.Id}`,
    valueKey: "Montant Total",
    type: "bar",
  },
  {
    metric: "best-sellers",
    chartId: "best-sellers-canvas",
    targetId: "best-sellers-table",
    title: "Meilleurs vendeurs",
    datasetLabel: "Commandes réalisées",
    labelFormatter: (row) => `${row["Prénom"]} ${row["Nom"]}`,
    valueKey: "Nombre de commandes réalisées",
    type: "bar",
    table: true,
  },
  {
    metric: "most-sold-products",
    chartId: "most-sold-products-canvas",
    title: "Produits les plus vendus",
    datasetLabel: "Quantités commandées",
    labelFormatter: (row) => row.nom,
    valueKey: "Quantités commandées",
    type: "bar",
  },
  {
    metric: "best-customers",
    chartId: "best-customers-canvas",
    targetId: "best-customers-table",
    title: "Meilleurs clients",
    datasetLabel: "Commandes faites",
    labelFormatter: (row) => `${row["Prénom"]} ${row["Nom"]}`,
    valueKey: "Nombre de commandes faites",
    type: "bar",
    table: true,
  },
  {
    metric: "product-at-risk-of-out-of-stock",
    chartId: "product-at-risk-of-out-of-stock-canvas",
    title: "Produits en risque de rupture",
    datasetLabel: "Stock",
    labelFormatter: (row) => row.nom,
    valueKeys: ["Stock", "Seuil Critique"],
    datasetLabels: ["Stock", "Seuil critique"],
    type: "bar",
    multi: true,
  },
];

const chartInstances = {};

const formatMonthFrSn = (monthKey) => {
  const [year, month] = String(monthKey || "").split("-");
  if (!year || !month) return monthKey || "";

  return new Intl.DateTimeFormat("fr-SN", {
    month: "long",
    year: "numeric",
  }).format(new Date(Number(year), Number(month) - 1, 1));
};

const destroyChart = (chartId) => {
  const chart = chartInstances[chartId];
  if (chart) {
    chart.destroy();
    delete chartInstances[chartId];
  }
};

const renderMetricChart = (config, data) => {
  if (!data || !Array.isArray(data) || data.length === 0) {
    destroyChart(config.chartId);
    if (config.table && config.targetId) {
      const target = document.getElementById(config.targetId);
      if (target) target.innerHTML = "<div class=\"empty-table-message\">Aucune donnée disponible</div>";
    }
    return;
  }

  if (config.table && config.targetId) {
    const target = document.getElementById(config.targetId);
    if (!target) return;

    const tableId = `${config.targetId}-inner`;
    target.innerHTML = `<div class=\"table-scroll\"><table id=\"${tableId}\" class=\"table text-center\"></table></div>`;

    renderTable(data, tableId);
    return;
  }

  const labels = data.map((row) => String(config.labelFormatter(row) ?? ""));

  if (config.multi) {
    const primary = data.map((row) => Number(row[config.valueKeys[0]] ?? 0));
    const otherDatasets = config.valueKeys.slice(1).map((key, index) => ({
      label: config.datasetLabels[index + 1] || key,
      data: data.map((row) => Number(row[key] ?? 0)),
      backgroundColor: ["#ff7a59", "#ffd166"][index] || "#80bfff",
    }));

    destroyChart(config.chartId);
    chartInstances[config.chartId] = drawChart(
      config.chartId,
      config.type,
      labels,
      primary,
      config.datasetLabel,
      otherDatasets,
      config.title,
    );
    return;
  }

  const values = data.map((row) => Number(row[config.valueKey] ?? 0));
  destroyChart(config.chartId);
  chartInstances[config.chartId] = drawChart(
    config.chartId,
    config.type,
    labels,
    values,
    config.datasetLabel,
    [],
    config.title,
  );
};

const fetchHomeCharts = async (e) => {
  if (!homeForm) return;
  if (e) {
    e.preventDefault();
  }

  if (homeSpinner) {
    homeSpinner.style.display = "inline";
  }
  if (errorMsg) {
    errorMsg.textContent = "";
  }

  const formData = {
    limit: homeForm["limit"].value,
    from: homeForm["from"].value,
    to: homeForm["to"].value,
  };

  try {
    const requests = chartConfigs.map(async (config) => {
      const response = await fetchApi(
        "http://localhost:8081/routes/dashboard/get_data.php",
        "POST",
        {
          ...formData,
          search: config.metric,
        },
      );
      return { config, response };
    });

    const results = await Promise.all(requests);

    let errorOccurred = false;
    for (const { config, response } of results) {
      if (!response || !response.success) {
        errorOccurred = true;
        destroyChart(config.chartId);
        continue;
      }
      renderMetricChart(config, response.data || []);
    }

    if (errorOccurred) {
      errorMsg.textContent = "Une ou plusieurs métriques n'ont pas pu être chargées.";
    }
  } catch (error) {
    console.error(error);
    errorMsg.textContent = "Problème de connexion.";
  } finally {
    setTimeout(() => (homeSpinner.style.display = "none"), 500);
  }
};

const initHomeDashboard = () => {
  homeForm = document.getElementById("dashboard-form");
  errorMsg = document.getElementById("home-error-message");
  homeSpinner = document.getElementById("home-spinner");

  if (!homeForm) {
    console.error("dashboard-form not found in DOM");
    return;
  }

  homeForm.onsubmit = fetchHomeCharts;
  fetchHomeCharts();
  getMonthlyRevenue();
};

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initHomeDashboard);
} else {
  initHomeDashboard();
}

const getMonthlyRevenue = async () => {
  const serverRes = await fetchApi(
    "http://localhost:8081/routes/dashboard/monthly_revenue.php",
    "GET",
  );

  if (!serverRes.success) {
    errorMsg.textContent =
      serverRes.message || "Erreur le serveur ne repond pas.";
    return;
  }
  if (serverRes.data) {
    chartInstances.homeRevenue = drawChart(
      "home-canvas",
      "bar",
      serverRes.data.map((item) => formatMonthFrSn(item.mois_annee)),
      serverRes.data.map((item) => item.chiffre_affaire),
      "Chiffre d'affaires mensuel",
      [],
      "Chiffre d'affaires mensuel",
    );
  }
};
getMonthlyRevenue();
