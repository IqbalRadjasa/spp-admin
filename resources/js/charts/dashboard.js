export function renderMonthlyIncomeChart(months, totals) {
    const chartElement = document.querySelector("#monthly-income-chart");

    if (!chartElement) {
        return;
    }

    const options = {
        chart: {
            type: "bar",
            height: 250,

            toolbar: {
                show: false,
            },
        },

        colors: ["#3B82F6"],

        plotOptions: {
            bar: {
                borderRadius: 6,
                columnWidth: "50%",
            },
        },

        dataLabels: {
            enabled: false,
        },

        series: [
            {
                name: "Income",
                data: totals,
            },
        ],

        xaxis: {
            categories: months,
        },

        tooltip: {
            y: {
                formatter: function (value) {
                    return "Rp " + value.toLocaleString();
                },
            },
        },
    };

    const chart = new ApexCharts(chartElement, options);

    chart.render();
}

export function renderBillStatusChart(paid, unpaid) {
    const chartElement = document.querySelector("#bill-status-chart");

    if (!chartElement) {
        return;
    }

    const options = {
        chart: {
            type: "donut",
            height: 280,
        },
        series: [paid, unpaid],
        colors: ["#4d7eff", "#22c55e"],
        labels: ["Paid", "Unpaid"],
        legend: {
            position: "bottom",
        },
        plotOptions: {
            pie: {
                donut: {
                    labels: {
                        show: true,
                    },
                },
            },
        },
    };

    const chart = new ApexCharts(chartElement, options);

    chart.render();
}

export function renderPaymentMethodChart(labels, totals) {
    const chartElement = document.querySelector("#payment-method-chart");

    if (!chartElement) {
        return;
    }

    const options = {
        chart: {
            type: "donut",
            height: 280,

            toolbar: {
                show: false,
            },
        },
        colors: ["#4d7eff", "#22c55e"],
        labels: labels,
        series: totals,
        legend: {
            position: "bottom",
        },
        dataLabels: {
            enabled: true,
        },
        tooltip: {
            y: {
                formatter: function (value) {
                    return value + " payments";
                },
            },
        },
        plotOptions: {
            pie: {
                donut: {
                    labels: {
                        show: true,
                    },
                },
            },
        },
    };

    const chart = new ApexCharts(chartElement, options);

    chart.render();
}
