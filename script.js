function updateDateTime() {
    const now = new Date();
    document.getElementById('date-time').innerHTML = now.toLocaleString('hr-HR');
}
setInterval(updateDateTime, 1000);

async function fetchWeather() {
    if ('geolocation' in navigator) {
        navigator.geolocation.getCurrentPosition(async position => {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;
            const apiKey = '3124d5e885e9900568e63a998c026522';
            const url = `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${apiKey}&units=metric&lang=hr`;

            try {
                const response = await fetch(url);
                const data = await response.json();
                document.getElementById('weather').innerHTML = `
                Lokacija: ${data.name} <br>
                Temperatura: ${data.main.temp} °C<br>
                Vlaznost zraka: ${data.main.humidity}% <br>
                Stanje neba: ${data.weather[0].description}
                `;
            } catch (error) {
                console.error('Error fetching weather:', error);
            }
        });
    } else {
        console.log('Geolocation is not supported by this browser.');
    }
}

fetchWeather();
