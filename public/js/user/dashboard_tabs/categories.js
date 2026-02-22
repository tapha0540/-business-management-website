const fetchProduitsCategorie = async () => {
  const serverRes = await fetchApi(
    "http://localhost:8081/routes/categories/get_all.php",
    "GET"
  );
  return serverRes.data;
};
