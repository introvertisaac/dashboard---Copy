(function ($) {

    var tfLineChart = (function () {

        var chartBar = function () {

            var options = {
                chart: {
                    height: 291,
                    type: "area",
                    zoom: {
                        enabled: true
                    },
                    toolbar: {
                        show: false,
                    },
                },
                title: {
                    text: 'API Calls Trend',
                    align: 'left'
                },
                subtitle: {
                    text: 'Last 14 days',
                    align: 'left'
                },


                dataLabels: {
                    enabled: false
                },
                colors: ["#E76C21"],
                series: [
                    {
                        name: "",
                        data: [45, 52, 38, 45, 49, 43, 40, 45, 52, 38, 45, 190]
                    }
                ],
                fill: {
                    type: "gradient",
                    gradient: {
                        shadeIntensity: 0.1,
                        opacityFrom: 0.8,
                        opacityTo: 0.2,
                        stops: [0, 90, 100]
                    }
                },
                yaxis: {
                    show: true,
                    title: {
                        text: 'Number of API calls'
                    }
                },
                xaxis: {
                    title: {
                        text: 'Days'
                    },
                    labels: {
                        style: {
                            colors: '#95989D',
                        },
                    },
                    categories: [
                        "Jan",
                        "Feb",
                        "Mar",
                        "Apr",
                        "May",
                        "Jun",
                        "Jul",
                        "Aug",
                        "Sep",
                        "Oct",
                        "Nov",
                        "Dec",
                    ]
                }
            };

            chart = new ApexCharts(
                document.querySelector("#line-chart-5"),
                options
            );
            if ($("#line-chart-5").length > 0) {
                chart.render();
            }
        };

        /* Function ============ */
        return {
            init: function () {
            },

            load: function () {
                chartBar();
            },
            resize: function () {
            },
        };
    })();

    jQuery(document).ready(function () {
    });

    jQuery(window).on("load", function () {
        tfLineChart.load();
    });

    jQuery(window).on("resize", function () {
    });
})(jQuery);
