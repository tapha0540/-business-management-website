const homeForm = document.getElementById("dashboard-form");
const errorMsg = document.getElementById("home-error-message");
const tableFirstRow = document.getElementById("first-row");
const homeSpinner = document.getElementById("home-spinner");
const homeDisplayTableBtn = document.getElementById("display-table-btn");

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
    }

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
  console.log(serverRes);
   if (!serverRes.success) {
      errorMsg.textContent =
        serverRes.message || "Erreur le serveur ne repond pas.";
    }
  if (serverRes.data) {
    const chart = drawChart(
      "home-canvas",
      "bar",
      serverRes.data.map((item) => `${item.mois_nom} ${item.mois_annee.split('-')[0]}`),
      serverRes.data.map((item) => item.chiffre_affaire),
      "Chiffre d'affaires mensuel",
    );
  }
};
getMonthlyRevenue();