$(function () {
    var options = {
        series: [
            {
                name: "Sales",
                data: [
                    {
                        x: "January",
                        y: 1000,
                    },
                    {
                        x: "February",
                        y: 900,
                    },
                    {
                        x: "March",
                        y: 500,
                    },
                    {
                        x: "April",
                        y: 7700,
                    },
                    {
                        x: "May",
                        y: 3500,
                    },
                    {
                        x: "June",
                        y: 4500,
                    },
                    {
                        x: "July",
                        y: 88,
                    },
                    {
                        x: "August",
                        y: 1200,
                    },
                    {
                        x: "September",
                        y: 1560,
                    },
                    {
                        x: "October",
                        y: 1000,
                    },
                    {
                        x: "November",
                        y: 100,
                    },
                    {
                        x: "December",
                        y: 10,
                    },
                ],
            },
        ],
        chart: {
            type: "bar",
            height: 350,
            stacked: true,
        },
        stroke: {
            width: 1,
            colors: ["#fff"],
        },
        dataLabels: {
            formatter: (val) => {
                return val / 1000 + "K";
            },
        },
        plotOptions: {
            bar: {
                horizontal: false,
            },
        },

        fill: {
            opacity: 1,
        },
        colors: ["#e0b220"],
        yaxis: {
            labels: {
                formatter: (val) => {
                    return val / 1000 + "K";
                },
            },
        },

        xaxis: {
            axisBorder: {
                show: false,
            },
            axisTicks: {
                show: false,
            },
        },

        legend: {
            position: "top",
            horizontalAlign: "left",
        },
    };
    var chart = new ApexCharts(document.querySelector("#salechart"), options);
    chart.render();
});

$(function () {
    var options = {
        series: [
            {
                name: "Sales",
                data: [
                    {
                        x: "January",
                        y: 1000,
                    },
                    {
                        x: "February",
                        y: 900,
                    },
                    {
                        x: "March",
                        y: 500,
                    },
                    {
                        x: "April",
                        y: 7700,
                    },
                    {
                        x: "May",
                        y: 3500,
                    },
                    {
                        x: "June",
                        y: 4500,
                    },
                    {
                        x: "July",
                        y: 88,
                    },
                    {
                        x: "August",
                        y: 1200,
                    },
                    {
                        x: "September",
                        y: 1560,
                    },
                    {
                        x: "October",
                        y: 1000,
                    },
                    {
                        x: "November",
                        y: 100,
                    },
                    {
                        x: "December",
                        y: 10,
                    },
                ],
            },
        ],
        chart: {
            type: "bar",
            height: 350,
            stacked: true,
        },
        stroke: {
            width: 1,
            colors: ["#fff"],
        },
        dataLabels: {
            formatter: (val) => {
                return val / 1000 + "K";
            },
        },
        plotOptions: {
            bar: {
                horizontal: false,
            },
        },

        fill: {
            opacity: 1,
        },
        colors: ["#e0b220"],
        yaxis: {
            labels: {
                formatter: (val) => {
                    return val / 1000 + "K";
                },
            },
        },

        xaxis: {
            axisBorder: {
                show: false,
            },
            axisTicks: {
                show: false,
            },
        },

        legend: {
            position: "top",
            horizontalAlign: "left",
        },
    };
    var chart = new ApexCharts(document.querySelector("#visitchart"), options);
    chart.render();
});
