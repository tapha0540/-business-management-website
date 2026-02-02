const fetchApi = async (url, method = "GET", data = null) => {
  const options = {
    method,
    headers: {
      "Content-Type": "application/json",
    },
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
