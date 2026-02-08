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
    console.log(serverRes.data);

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
