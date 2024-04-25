const API_URL = '/api';
export async function fetchWeatherData(lat, lon) {
  try {
    let [weatherPromise, forcastPromise] = await Promise.all([
      fetch(
        `${API_URL}/weather?lat=${lat}&lon=${lon}`
      ),
      fetch(
        `${API_URL}/forecast?lat=${lat}&lon=${lon}`
      ),
    ]);

    const weatherResponse = await weatherPromise.json();
    const forcastResponse = await forcastPromise.json();
    return [weatherResponse, forcastResponse];
  } catch (error) {
    console.log(error);
  }
}

export async function fetchCities(input) {
  try {
    const response = await fetch(`${API_URL}/cities?location=${input}`);

    const data = await response.json();
    return data;
  } catch (error) {
    console.log(error);
    return;
  }
}
