const fetchApi = async (
  url,
  method = "GET",
  data = null,
  isCrendentialIncluded = false,
) => {
  const options = {
    method,
    headers: {
      "Content-Type": "application/json",
    },
    credentials: "include",
  };

  if (data) {
    options.body = JSON.stringify(data);
  }
  try {
    const response = await fetch(url, options);
    return response.json();
  } catch (error) {
    console.error("Fetch API Error:", error);
    return {
      message: "Network error occurred",
      success: false,
    };
  }
};
