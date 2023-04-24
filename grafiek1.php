<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Energieverbruik Grafieken</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "energy_db";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM energy_users";
        $result = $conn->query($sql);

        $data = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            echo "0 results";
        }

        $conn->close();
    ?>

    <canvas id="energyConsumptionChart" width="100" height="50"></canvas>
    <canvas id="contractTypeChart" width="100" height="50"></canvas>
    <canvas id="energyPriceChart" width="100" height="50"></canvas>
    <canvas id="contractDateChart" width="100" height="50"></canvas>

    <script>
        function createChart(type, canvasId, chartLabel, chartData, backgroundColor, borderColor) {
            const ctx = document.getElementById(canvasId).getContext('2d');
            const chart = new Chart(ctx, {
                type: type,
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: chartLabel,
                        data: chartData.values,
                        backgroundColor: backgroundColor,
                        borderColor: borderColor,
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        const userData = <?php echo json_encode($data); ?>;
        const labels = userData.map(user => user.name);

        createChart('bar', 'energyConsumptionChart', 'Energieverbruik (kWh)', { labels: labels, values: userData.map(user => user.energy_consumption) }, 'rgba(75, 192, 192, 0.2)', 'rgba(75, 192, 192, 1)');

        function countOccurrences(arr) {
            return arr.reduce((acc, curr) => {
                acc[curr] = (acc[curr] || 0) + 1;
                return acc;
            }, {});
        }

        const contractTypeData = userData.map(user => user.contract_type);
        const occurrences = countOccurrences(contractTypeData);
        const uniqueContractTypes = Array.from(new Set(contractTypeData));
        const contractTypeValues = uniqueContractTypes.map(type => occurrences[type]);

        createChart('pie', 'contractTypeChart', '', {
            labels: uniqueContractTypes,
            values: contractTypeValues
        }, ['rgba(255, 99, 132, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(255, 206, 86, 0.5)', 'rgba(75, 192, 192, 0.5)', 'rgba(153, 102, 255, 0.5)', 'rgba(255, 159, 64, 0.5)']);

        createChart('bar', 'energyPriceChart', 'Energieprijs (€)', { labels: labels, values: userData.map(user => user.energy_price) }, 'rgba(54, 162, 235, 0.2)', 'rgba(54, 162, 235, 1)');

        function countOccurrencesPerYear(arr) {
            return arr.reduce((acc, curr) => {
                acc[curr] = (acc[curr] || 0) + 1;
                return acc;
            }, {});
        }

        const yearData = userData.map(user => new Date(user.contract_date).getFullYear());
        const occurrencesPerYear = countOccurrencesPerYear(yearData);
        const years = Array.from({ length: 2026 - 2010 }, (_, i) => i + 2010);
        const yearValues = years.map(year => occurrencesPerYear[year] || 0);

        createChart('line', 'contractDateChart', 'Contractdatum', {
            labels: years,
            values: yearValues
        }, 'rgba(153, 102, 255, 0.2)', 'rgba(153, 102, 255, 1)');
    </script>
</body>
</html>