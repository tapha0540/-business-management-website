const homeForm = document.getElementById("dashboard-form");
const errorMsg = document.getElementById("home-error-message");
const tableFirstRow = document.getElementById("first-row");
const homeSpinner = document.getElementById("home-spinner");
const homeDisplayTableBtn = document.getElementById("display-table-btn");

const formatMonthFrSn = (monthKey) => {
  const [year, month] = String(monthKey || "").split("-");
  if (!year || !month) return monthKey || "";

  return new Intl.DateTimeFormat("fr-SN", {
    month: "long",
    year: "numeric",
  }).format(new Date(Number(year), Number(month) - 1, 1));
};

const fetchHomeTableData = async (e) => {
  if (e) {
    e.preventDefault();
  }
  homeSpinner.style.display = "inline";
  try {
    const formData = {
      limit: homeForm["limit"].value,
      search: homeForm["search"].value,
      from: homeForm["from"].value,
      to: homeForm["to"].value,
    };
    const serverRes = await fetchApi(
      "http://localhost:8081/routes/dashboard/get_data.php",
      "POST",
      formData,
    );

    if (!serverRes.success) {
      errorMsg.textContent =
        serverRes.message || "Erreur le serveur ne repond pas.";
      return;
    }

    errorMsg.textContent = "";
    renderTable(serverRes.data, "home-table");
  } catch (err) {
    console.error(err);
    errorMsg.textContent = "Problème de connexion.";
  } finally {
    setTimeout(() => (homeSpinner.style.display = "none"), 500);
  }
};

homeForm.onsubmit = (e) => fetchHomeTableData(e);
homeForm.onsubmit();

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
    const chart = drawChart(
      "home-canvas",
      "bar",
      serverRes.data.map((item) => formatMonthFrSn(item.mois_annee)),
      serverRes.data.map((item) => item.chiffre_affaire),
      "Chiffre d'affaires mensuel",
    );
  }
};
getMonthlyRevenue();
