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
    credentials: isCrendentialIncluded ? "include" : "omit",
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

function toBase64(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result);
        reader.onerror = error => reject(error);
    });
}
