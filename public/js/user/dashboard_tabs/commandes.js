const commandesTable = document.getElementById("commandes-table");
const thead = commandesTable.querySelector("thead");
const tbody = commandesTable.querySelector("tbody");

const fetchCommandesTableDonnee = async () => {
  const data = await fetchApi(
    "http://localhost:8081/routes/commandes/get_all.php",
    "POST",
    {
      limit: 10,
    },
  );
  console.log(data);

  if (data) {
  }
};
fetchCommandesTableDonnee();