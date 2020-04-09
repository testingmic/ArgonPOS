var summaryItems = {
    'total-sales': 'totalSales'
};

async function getSalesDetails(salesID) {

    $(".show-modal-title").html("Sale Details");
    $(`div[class="form-content-loader"]`).css("display","flex");
    
    $(".launchModal").modal("show");

    await dOC().then((itResp) => {
        if (itResp == 1) {
            offline = false;
            $(`div[class="connection"]`).css('display', 'none');
        } else {
            offline = true;
            $(`div[class="connection"]`).css('display', 'block');
        }
    }).catch((err) => {
        offline = true;
        $(`div[class="connection"]`).css('display', 'block');
    });

    if (offline) {

        listIDB('sales_details').then(async(salesDetailsResult) => {

            var trData = `
        		<div class="row table-responsive">`;

            var salesInfo = await gIDBR('sales', salesID).then((salesResult) => {

                trData += `<table class="table table-bordered">
						<tr>
							<td><strong>Customer Name</strong>: ${salesResult.customer_fullname}</td>
							<td align='left'><strong>Transaction ID:</strong>: ${salesResult.order_id}</td>
						</tr>
						<tr>
							<td><strong>Contact</strong>: ${salesResult.customer_contact}</td>
							<td align='left'><strong>Transaction Date</strong>: ${salesResult.order_date}</td>
						</tr>
					</table>`;
            });

            trData += `<table class="table table-bordered">
					<thead>
						<tr>
							<td class="text-left">Product</td>
							<td class="text-left">Quantity</td>
							<td class="text-right">Unit Price</td>
							<td class="text-right">Total</td>
						</tr>
					</thead>
					<tbody>`;

            var subTotal = 0,
                discount = 0;

            $.each(salesDetailsResult, function(i, e) {
                if (e.order_id == salesID) {
                    trData += `
            			<tr>
							<td>${e.product_title}</td>
							<td>${e.product_quantity}</td>
							<td class=\"text-right\">${companyVariables.cur} ${e.product_unit_price}</td>
							<td class=\"text-right\">${companyVariables.cur} ${e.product_total}</td>
						</tr>`;
                    subTotal += parseFloat(e.product_total);
                }
            });

            var overall = subTotal - discount;

            trData += `<tr>
					<td style="font-weight:bolder;text-transform:uppercase" colspan="3" class="text-right">Subtotal</td>
					<td style="font-weight:bolder;text-transform:uppercase" class="text-right">
						${companyVariables.cur} ${formatCurrency(subTotal)}
					</td>
				</tr>
				<tr>
					<td style="font-weight:;text-transform:uppercase" colspan="3" class="text-right">Discount</td>
					<td style="font-weight:;text-transform:uppercase" class="text-right">${companyVariables.cur} ${discount}</td>
				</tr>
				<tr>
					<td style="font-weight:bolder;text-transform:uppercase" colspan="3" class="text-right">Overall Total</td>
					<td style="font-weight:bolder;text-transform:uppercase" class="text-right">${companyVariables.cur} ${formatCurrency(overall)}</td>
				</tr>

				</tbody>
				</table>
			</div>
			<div class="card">
				<div class="row">
					<div class="offline-placeholder main-body-loader" style="display: flex">
			            <div class="offline-content text-center">
			                <p>You are offline</p>
			                <button type="button" class="btn cursor btn-warning">Reconnect</button>
			            </div>
			        </div>
		       	</div>
		    </div>`;

            $(".show-modal-body").html(trData);
            $(`div[class="form-content-loader"]`).css("display","none");
            reConnect();
        });
        return false;
    }

    $.ajax({
        url: baseUrl + "aj/dashboardAnalytics/getSalesDetails",
        type: "POST",
        dataType: "json",
        data: { getSalesDetails: true, salesID: salesID },
        beforeSend: function() {
            $(".show-modal-title").html("Sale Details");
        },
        success: function(data) {
            if (data.status == true) {
                $(".show-modal-body").html(data.message);
            } else {
                $(".show-modal-body").html(`
                    <p class="alert alert-danger text-white text-center">No sales records found</p>
                `);
            }
        },
        complete: function() {
            $(`div[class="form-content-loader"]`).css("display","none");
        }, error: function(data) {
            $(`div[class="form-content-loader"]`).css("display","none");
        }
    });
    
}

$(function() {

    var populateBranchData = (branchInfo) => {
        $(`table[class~="branch-overview"]`).dataTable().fnDestroy();
        $(`table[class~="branch-overview"]`).dataTable({
            "aaData": branchInfo,
            "iDisplayLength": 5,
            "buttons": ["copy", "print","csvHtml5"],
            "lengthChange": !1,
            "dom": "Bfrtip",
            "columns": [
                { "data": 'branch_name' },
                { "data": 'total_sales' },
                { "data": 'highest_sales' },
                { "data": 'lowest_sales' },
                { "data": 'average_sales' },
                { "data": 'orders_count'},
                { "data": 'square_feet_sales'}
            ]
        });
    }

    var populateSalesTeamData = (teamInfo, chartLabels, chartData) => {

        $(`div[class="attendant-chart"]`).html(``);
        $(`div[class="attendant-chart"]`).html(`<div id="attendant-performance" class="apex-charts"></div>`);

        $(`table[class~="attendant-performance"]`).dataTable().fnDestroy();
        $(`table[class~="attendant-performance"]`).dataTable({
            "iDisplayLength": 5,
            "aaData": teamInfo,
            "buttons": ["copy", "print","csvHtml5"],
            "lengthChange": !1,
            "dom": "Bfrtip",
            "columns": [
                {"data":'fullname'},
                {"data":'sales.amount'},
                {"data":'sales.orders'},
                {"data":'sales.average_sale'},
                {"data":'targets.target_amount'},
                {"data":'targets.target_percent'},
                {"data":'items.total_items_sold'},
                {"data":'items.average_items_sold'}
            ]
        });

        var options2 = {
            chart: {
                height: 400,
                type: 'bar',
                toolbar: {
                    show: false
                },
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                }
            },
            dataLabels: {
                enabled: false
            },
            series: [{
                name: 'Sales Recorded',
                data: chartData
            }],
            colors: ["#17a2b8"],
            yaxis: {
                axisBorder: {
                    show: true,
                    color: '#bec7e0',
                },
                axisTicks: {
                    show: true,
                    color: '#bec7e0',
                },
            },
            xaxis: {
                categories: chartLabels
            },
            states: {
                hover: {
                    filter: 'none'
                }
            },
            grid: {
                borderColor: '#f1f3fa'
            },
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function(y) {
                        if (typeof y !== "undefined") {
                            return companyVariables.cur + formatCurrency(y);
                        }
                        return y;

                    }
                }
            },
        }

        var apex_bar1_branch = new ApexCharts(
            document.querySelector("#attendant-performance"),
            options2
        );

        apex_bar1_branch.render();
    }

    var populateProductsPerformance = (productsInfo) => {
        $(`table[class~="products-performance"]`).dataTable().fnDestroy();
        $(`table[class~="products-performance"]`).dataTable({
            "iDisplayLength": 7,
            "aaData": productsInfo,
            "buttons": ["copy", "print","csvHtml5"],
            "lengthChange": !1,
            "dom": "Bfrtip",
            "columns": [
                { "data": 'row_id'},
                { "data": 'product_title'},
                { "data": 'orders_count'},
                { "data": 'quantity_sold'},
                { "data": 'total_selling_cost'},
                { "data": 'total_selling_revenue'},
                { "data": 'product_profit'}
            ]
        });
    }

    var branchPerformance = (periodSelected = 'today') => {
        if ($(`table[class~="branch-overview"]`).length) {
            $.ajax({
                type: "POST",
                url: `${baseUrl}aj/reportsAnalytics/generateReport`,
                data: { generateReport: true, queryMetric: "branchPerformance", salesPeriod: periodSelected},
                dataType: "JSON",
                beforeSend: function() {
                },
                success: function(resp) {
                    populateBranchData(resp.result.summary);
                }, complete: function(data) {
                    hL();
                }
            });
        }
    }

    var summaryItems = (periodSelected = 'today') => {
        $.ajax({
            type: "POST",
            url: `${baseUrl}aj/reportsAnalytics/generateReport`,
            data: { generateReport: true, queryMetric: "summaryItems", salesPeriod: periodSelected },
            dataType: "JSON",
            beforeSend: function() {
                sL();
                $(`div[data-report] p[class~="text-truncate"]`).html(`
					Loading <i class="fa fa-spin fa-spinner"></i>
				`);
            },
            success: function(resp) {
                $.each(resp.result, function(i, e) {
                    $(`div[data-report="${e.column}"] h3[class="my-3"]`).html(e.total);
                    $(`div[data-report="${e.column}"] p[class~="text-truncate"]`).html(e.trend);
                });
            },
            error: function(err) {
                console.log(err);
            }
        });
    }

    var salesOverview = (periodSelected = 'today') => {

        $.ajax({
            type: "POST",
            url: `${baseUrl}aj/reportsAnalytics/generateReport`,
            data: { generateReport: true, queryMetric: "salesOverview", salesPeriod: periodSelected },
            dataType: "JSON",
            success: function(resp) {

                $(`div[class="chart-sales"]`).html(``);
                $(`div[class="chart-sales"]`).html(`<div id="sales-overview-chart" class="apex-charts"></div>`);

                $(`p[data-model="highest-sale"] span`).html(resp.result.sales.highest);
                $(`p[data-model="lowest-sale"] span`).html(resp.result.sales.lowest);

                $(`p[data-model="total-sales"] span`).html(resp.result.sales.comparison.total_sales);
                $(`p[data-model="total-actual-sales"] span`).html(resp.result.sales.comparison.total_actual_sales);
                $(`p[data-model="total-credit-sales"] span`).html(resp.result.sales.comparison.total_credit_sales);

                $(`p[data-model="sales-with-discount"] span`).html(resp.result.sales.discount_effect.total_sale);
                $(`p[data-model="sales-without-discount"] span`).html(resp.result.sales.discount_effect.discounted_sale);

                populateProductsPerformance(resp.result.sales.products_performance);

                var thisOpts = {
                    chart: {
                        height: 374,
                        type: 'line',
                        shadow: {
                            enabled: false,
                            color: '#bbb',
                            top: 3,
                            left: 2,
                            blur: 3,
                            opacity: 1
                        },
                        zoom: false
                    },
                   
                    plotOptions: {
                        bar: {
                            columnWidth: '30%'
                        }
                    },
                    stroke: {
                        width: [4, 0],
                        curve: 'smooth'
                    },
                    series: [{
                        type: 'line',
                        name: 'Total Sales With Discount',
                        data: resp.result.sales.discount_effect.with_discount
                    }, {
                        type: 'area',
                        name: 'Total Sales Without Discount',
                        data: resp.result.sales.discount_effect.without_discount
                    }],
                    xaxis: {
                        type: 'datetime',
                        categories: resp.result.labeling,
                        axisBorder: {
                            show: true,
                            color: '#bec7e0',
                        },
                        axisTicks: {
                            show: true,
                            color: '#f1646c',
                        },
                    },   
                    colors: ["#1ecab8", "#f7cda0"],                 
                    markers: {
                        size: 4,
                        opacity: 0.9,
                        colors: ["#ffbc00"],
                        strokeColor: "#fff",
                        strokeWidth: 2,
                        style: 'hollow',
                        hover: {
                            size: 7,
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'Sales Values',
                        },
                    },
                    fill: {
                      type: 'gradient',
                      gradient: {
                        gradientToColors: ['#f1646c'],
                        shadeIntensity: 0.1,
                        type: 'horizontal',
                        opacityFrom: 0.7,
                        opacityTo: 1,
                        stops: [0, 100, 100, 100]
                      },
                    },
                    tooltip: {
                        shared: true,
                        intersect: false,
                        y: {
                            formatter: function(y) {
                                if (typeof y !== "undefined") {
                                    return companyVariables.cur + formatCurrency(y);
                                }
                                return y;

                            }
                        }
                    },
                    grid: {
                      row: {
                        colors: ['transparent', 'transparent'], 
                        opacity: 0.2
                      },
                      borderColor: '#185a9d'
                    }
                }

                if (periodSelected == 'today') {
                    delete thisOpts.xaxis.type;
                }

                var chart = new ApexCharts(
                    document.querySelector("#sales-overview-chart"),
                    thisOpts
                );

                chart.render();


                if($(`div[class="chart-comparison"]`).length){
                   
                    $(`div[class="chart-comparison"]`).html(``);
                    $(`div[class="chart-comparison"]`).html(`<div id="sales-comparison" class="apex-charts"></div>`);
                    var compareSales = {
                        chart: {
                            height: 420,
                            type: 'line',
                            stacked: false,
                            toolbar: {
                                show: true
                            },
                            zoom: false,
                            shadow: {
                                enabled: false,
                                color: '#bbb',
                                top: 3,
                                left: 2,
                                blur: 3,
                                opacity: 1
                            },
                        },
                        stroke: {
                            width: [0, 2, 4],
                            curve: 'smooth'
                        },
                        plotOptions: {
                            bar: {
                                columnWidth: '40%'
                            }
                        },
                        colors: ["#1ecab8", "#fbb624", "#f93b7a"],
                        series: [{
                            name: 'Total Sales',
                            type: 'column',
                            data: resp.result.data
                        }, {
                            name: 'Paid Sales',
                            type: 'area',
                            data: resp.result.sales.actuals
                        }, {
                            name: 'Credit Sales',
                            type: 'line',
                            data: resp.result.sales.credit
                        }],
                        fill: {
                            opacity: [0.85, 0.25, 1],
                            gradient: {
                                inverseColors: false,
                                shade: 'light',
                                type: "vertical",
                                opacityFrom: 0.85,
                                opacityTo: 0.55,
                                stops: [0, 100, 100, 100]
                            }
                        },
                        labels: resp.result.labeling,
                        markers: {
                            size: 2,
                            opacity: 0.9,
                            colors: ["#ffbc00"],
                            strokeColor: "#fff",
                            strokeWidth: 2,
                            style: 'hollow',
                            hover: {
                                size: 7,
                            }
                        },
                        xaxis: {
                            type: 'datetime',
                            axisBorder: {
                                show: true,
                                color: '#bec7e0',
                            },
                            axisTicks: {
                                show: true,
                                color: '#bec7e0',
                            },
                        },
                        yaxis: {
                            title: {
                                text: 'Saes Values',
                            },
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                            y: {
                                formatter: function(y) {
                                    if (typeof y !== "undefined") {
                                        return companyVariables.cur + formatCurrency(y);
                                    }
                                    return y;

                                }
                            }
                        },
                        grid: {
                            borderColor: '#f1f3fa'
                        }
                    }
                    if (periodSelected == 'today') {
                        delete compareSales.xaxis.type;
                    }
                    var compareChart = new ApexCharts(
                        document.querySelector("#sales-comparison"),
                        compareSales
                    );
                    compareChart.render();
                }


                if($(`div[class="payment-chart"]`).length){
                    $(`div[class="payment-chart"]`).html(``);
                    $(`div[class="payment-chart"]`).html(`<div id="payment-options" class="apex-charts"></div>`);
                    var paymentOptions = {
                      chart: {
                          height: 380,
                          type: 'donut',
                      }, 
                      series: resp.result.sales.payment_options.payment_values,
                      legend: {
                          show: true,
                          position: 'bottom',
                          horizontalAlign: 'center',
                          verticalAlign: 'middle',
                          floating: false,
                          fontSize: '14px',
                          offsetX: 0,
                          offsetY: -10
                      },
                      tooltip: {
                        shared: true,
                        intersect: false,
                        y: {
                            formatter: function(y) {
                                if (typeof y !== "undefined") {
                                    return companyVariables.cur + formatCurrency(y);
                                }
                                return y;

                            }
                        }
                      },
                      labels: resp.result.sales.payment_options.payment_option,
                      colors: ["#08aeb0", "#232f5b","#f06a6c", "#f1e299", "#08aeb0"],
                      responsive: [{
                          breakpoint: 600,
                          options: {
                              chart: {
                                  height: 270
                              },
                              legend: {
                                  show: true
                              },
                          }
                      }],
                      fill: {
                          type: 'gradient'
                      }
                    }
                    var paymentChart = new ApexCharts(
                        document.querySelector("#payment-options"),
                        paymentOptions
                    );
                    paymentChart.render();
                }

                if($(`div[class="category-chart"]`).length) {
                    $(`div[class="category-chart"]`).html(``);
                    $(`div[class="category-chart"]`).html(`<div id="category-options" class="apex-charts"></div>`);
                    var productCategoryOptions = {
                      chart: {
                          height: 450,
                          type: 'donut',
                      }, 
                      series: resp.result.sales.category_sales.data,
                      legend: {
                          show: true,
                          position: 'bottom',
                          horizontalAlign: 'center',
                          verticalAlign: 'middle',
                          floating: false,
                          fontSize: '14px',
                          offsetX: 0,
                          offsetY: -10
                      },
                      tooltip: {
                        shared: true,
                        intersect: false,
                        y: {
                            formatter: function(y) {
                                if (typeof y !== "undefined") {
                                    return companyVariables.cur + formatCurrency(y);
                                }
                                return y;

                            }
                        }
                      },
                      labels: resp.result.sales.category_sales.labels,
                      colors: ["#08aeb0", "#232f5b","#f06a6c", "#f1e299", "#08aeb0"],
                      responsive: [{
                          breakpoint: 600,
                          options: {
                              chart: {
                                  height: 270
                              },
                              legend: {
                                  show: true
                              },
                          }
                      }],
                      fill: {
                          type: 'gradient'
                      }
                    }
                    var productCategoryChart = new ApexCharts(
                        document.querySelector("#category-options"),
                        productCategoryOptions
                    );
                    productCategoryChart.render();
                }


                $(`div[class="revenue-chart"]`).html(``);
                $(`div[class="revenue-chart"]`).html(`<div id="revenue-trend" class="apex-charts"></div>`);
                var revenueOptions = {
                  chart: {
                      height: 450,
                      type: 'line',
                      stacked: false,
                      zoom: false,
                      toolbar: {
                          show: false
                      }
                  },
                  dataLabels: {
                      enabled: false
                  },
                  stroke: {
                      width: [0, 0, 2]
                  },
                  plotOptions: {
                    bar: {
                        columnWidth: '40%'
                    }
                  },
                  series: [{
                      name: 'Products Cost Price',
                      type: 'column',
                      data: resp.result.sales.revenue.cost
                  }, {
                      name: 'Products Selling Price',
                      type: 'column',
                      data: resp.result.sales.revenue.selling
                  }, {
                      name: 'Profit Generated',
                      type: 'line',
                      data: resp.result.sales.revenue.profit
                  }],
                  colors: ["#fa5c7c", "#20016c", "#77d0ba"],
                  xaxis: {
                      type: 'datetime',
                      categories: resp.result.labeling,
                      axisBorder: {
                        show: true,
                        color: '#bec7e0',
                      },  
                      axisTicks: {
                        show: true,
                        color: '#bec7e0',
                    }, 
                  },
                  yaxis: [
                      {
                          opposite: true,
                          axisTicks: {
                              show: true,
                          },
                          axisBorder: {
                              show: true,
                              color: '#77d0ba'
                          },
                          labels: {
                              style: {
                                  color: '#77d0ba',
                              }
                          },
                          title: {
                              text: "Products Cost, Selling & Revenue Generated"
                          }
                      },

                  ],
                  tooltip: {
                      followCursor: true,
                      y: {
                          formatter: function (y) {
                              if (typeof y !== "undefined") {
                                  return companyVariables.cur + formatCurrency(y);
                              }
                              return y;
                          }
                      }
                  },
                  grid: {
                      borderColor: '#f1f3fa'
                  },
                  legend: {
                      offsetY: -10,
                  },
                  responsive: [{
                      breakpoint: 600,
                      options: {
                          yaxis: {
                              show: false
                          },
                          legend: {
                              show: false
                          }
                      }
                  }]
                }
                if (periodSelected == 'today') {
                    delete revenueOptions.xaxis.type;
                }
                var revenueChart = new ApexCharts(
                    document.querySelector("#revenue-trend"),
                    revenueOptions
                );
                revenueChart.render();
                $(`div[class~="apexcharts-legend"]`).addClass('hidden');
            },
            error: function(err) {
                console.log(err);
                hL();
            }, complete: function(data) {
                setTimeout(function() {
                    hL();
                    $(`div[class~="apexcharts-legend"]`).removeClass('center hidden');
                }, 1000);
            }
        });
    }

    var salesAttendantHistory = () => {

        $(`div[class="main-content"]`).on('click', `a[class~="view-user-sales"]`, function() {

            var userId = $(this).attr('data-value');
            var fullname = $(this).attr('data-name');
            var recordType = $(this).attr('data-record');

            if (recordType == "attendant") {
                $(`div[class~="attendantHistory"] h5`).html(`${fullname}'s Sales History`);
            } else {
                $(`div[class~="attendantHistory"] h5`).html(`${fullname}'s Purchases History`);
            }
            $(`div[class~="attendantHistory"]`).modal('show');

            $.ajax({
                type: "POST",
                url: `${baseUrl}aj/reportsAnalytics/generateReport`,
                data: { generateReport: true, salesAttendantHistory: true, queryMetric: "salesAttendantPerformance", userId: userId, recordType: recordType },
                dataType: "JSON",
                beforeSend: function() {
                    $(`div[class~="attendantHistory"] div[class~="modal-body"]`).html(`<div align="center">Loading records <i class="fa fa-spin fa-spinner"></i></div>`);
                },
                success: function(resp) {
                    var trData = `<table width="100%" class="table table-bordered orderHistory">`;
                    trData += `<thead>`;
                    trData += `<tr class="text-uppercase">`;
                    trData += `<td>Order ID</td>`;
                    trData += `<td>Customer Name</td>`;
                    trData += `<td>Order Amount</td>`;
                    trData += `<td>Date</td>`;
                    trData += `<td></td>`;
                    trData += `</tr>`;
                    trData += `</thead>`;
                    var creditBadge = ``;
                    $.each(resp.result, function(i, e) {

                        if (e.payment_type == 'cash') {
                            creditBadge = `<span class="text-gray">Cash Sale</span>`;
                        } else if (e.payment_type == 'momo') {
                            creditBadge = `<span class="text-gray">Mobile Money</span>`;
                        } else if (e.payment_type == 'card') {
                            creditBadge = `<span class="text-gray">Card Payment</span>`;
                        } else if (e.payment_type == 'credit') {
                            creditBadge = `<span class="text-gray">Credit</span>`;
                        }

                        trData += `<tr>`;
                        trData += `<td><a onclick="getSalesDetails('${e.order_id}');" class="get-sales-details" data-sales-id="${e.order_id}" href="javascript:void(0)" title="View Order Details">${e.order_id}</a><br>${creditBadge}</td>`;
                        trData += `<td><a onclick="getSalesDetails('${e.order_id}');" data-name="${e.fullname}" href="javascript:void(0);" title="Click to list customer orders history" data-value="${e.customer_id}" class="customer-orders">${e.fullname}</a></td>`;
                        trData += `<td>${companyVariables.cur}${e.order_amount_paid}</td>`;
                        trData += `<td>${e.order_date}</td>`;
                        trData += `<td><a onclick="getSalesDetails('${e.order_id}');" class="get-sales-details" data-sales-id="${e.order_id}" href="${baseUrl}invoices/${e.order_id}" title="View Purchase Details"><i class="fa fa-print"></i></a></td>`;
                        trData += `</tr>`;
                    });

                    trData += `</table>`;

                    $(`div[class~="attendantHistory"] div[class~="modal-body"]`).html(trData);

                    $(`table[class~="orderHistory"]`).DataTable();

                },
                complete: function(data) {},
                error: function(err) {
                    $(`div[class~="attendantHistory"] div[class~="modal-body"]`).html(`
						<p align="center">No records found.</p>
					`);
                }
            });

        });
    }

    var salesAttendantPerformance = (periodSelected = 'today') => {
        if ($(`div[id="attendant-performance"]`).length) {
            $.ajax({
                type: "POST",
                url: `${baseUrl}aj/reportsAnalytics/generateReport`,
                data: { generateReport: true, queryMetric: "salesAttendantPerformance", salesPeriod: periodSelected },
                dataType: "JSON",
                beforeSend: function() {

                },
                success: function(resp) {

                    populateSalesTeamData(resp.result.list, resp.result.names, resp.result.sales);

                },
                complete: function(data) {
                    salesAttendantHistory();
                    $(`div[class~="apexcharts-legend"]`).removeClass('center');
                },
                error: function(err) {
                    console.log(err);
                }
            });
        }
    }

    var ordersCount = (periodSelected = 'today') => {
        $.ajax({
            type: "POST",
            url: `${baseUrl}aj/reportsAnalytics/generateReport`,
            data: { generateReport: true, queryMetric: "ordersCount", salesPeriod: periodSelected },
            dataType: "JSON",
            beforeSend: function() {
                $(`div[class~="orders-loader"]`).html(`
					Loading <i class="fa fa-spin fa-spinner"></i>
				`);
            },
            success: function(resp) {

                $(`div[class~="orders-loader"]`).html(``);

                $(`div[class="apexchart-wrapper"]`).html(``);
                $(`div[class="apexchart-wrapper"]`).html(`<div id="dash_spark_1" class="chart-gutters"></div>`);
                $(`div[data-report="orders-trend"] h3[class="mn-3"]`).html(`${resp.result.count} <small>total orders</small>`);

                var ordersTrend = {
                    chart: {
                        type: 'line',
                        height: 385,
                        sparkline: {
                            enabled: false
                        },
                        toolbar: {
                            show: false
                        },
                        zoom: false
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 2
                    },
                    fill: {
                        opacity: [0.45, 0.25, 1]
                    },
                    series: [{
                        name: 'All Orders',
                        type: 'line',
                        data: resp.result.data
                    }, {
                        name: 'New Customers',
                        type: 'line',
                        data: resp.result.unique_customers
                    }, {
                        name: 'Returning Customers',
                        type: 'line',
                        data:resp.result.returning_customers
                    }],
                    yaxis: {
                        min: 0
                    },
                    xaxis: {
                        type: 'datetime',
                        categories: resp.result.labeling,
                        axisBorder: {
                            show: true,
                            color: '#bec7e0',
                        },
                        axisTicks: {
                            show: true,
                            color: '#bec7e0',
                        },
                    },
                    colors: ["#e6c255", "#1ecab8", "#ffc107"],
                    tooltip: {
                        shared: true,
                        intersect: false
                    },
                    grid: {
                        borderColor: '#f1f3fa'
                    }
                }

                if (periodSelected == 'today') {
                    delete ordersTrend.xaxis.type;
                }
                new ApexCharts(document.querySelector("#dash_spark_1"), ordersTrend).render();

            }, error: function(err) {
                $(`div[class~="orders-loader"]`).html(``);
            }, complete: function(data) {
                $(`div[class~="apexcharts-legend"]`).removeClass('center');
            }
        });
    }

    var topContactsPerformance = (periodSelected = 'today') => {

        if ($(`table[class~="custPerformance"]`).length) {
            $.ajax({
                type: "POST",
                url: `${baseUrl}aj/reportsAnalytics/generateReport`,
                data: { generateReport: true, queryMetric: "topContactsPerformance", salesPeriod: periodSelected },
                dataType: "JSON",
                beforeSend: function() {},
                success: function(resp) {
                    var count = 0,
                        trData = ``;

                    $(`table[class~="custPerformance"]`).dataTable().fnDestroy();
                    $(`table[class~="custPerformance"]`).dataTable({
                        "aaData": resp.result,
                        "iDisplayLength": 10,
                        "buttons": ["copy", "print","csvHtml5"],
                        "lengthChange": !1,
                        "dom": "Bfrtip",
                        "columns": [
                            { "data": 'row_id' },
                            { "data": 'fullname' },
                            { "data": 'orders_count' },
                            { "data": 'total_amount' },
                            { "data": 'total_balance' },
                            { "data": 'action' }
                        ]
                    });
                },
                error: function(err) {},
                complete: function(data) {
                    salesAttendantHistory();
                }
            });
        }
    }

    var fetchSalesRecords = () => {
        var colspan = "7";

        var period = $(`select[name="periodSelected"]`).val();

        $.ajax({
            url: `${baseUrl}aj/dashboardAnalytics/getSales`,
            type: "POST",
            dataType: "json",
            data: { getSales: true, salesPeriod: period },
            beforeSend: function() {
                $(".total-sales-trend, .total-served-trend, .total-products-worth, .total-credit-sales-trend").html(`
                    <span class="fa fa-spin fa-spinner"></span>
                `);
            },
            success: function(data) {

                $(`table[class~="salesLists"]`).dataTable().fnDestroy();
                if (data.status == true) {
                    $(`table[class~="salesLists"]`).dataTable({
                        "aaData": data.message.table,
                        "iDisplayLength": 10,
                        "buttons": ["copy", "print","csvHtml5"],
                        "lengthChange": !1,
                        "dom": "Bfrtip",
                        "columns": [
                            { "data": 'row' },
                            { "data": 'order_id' },
                            { "data": 'fullname' },
                            { "data": 'phone' },
                            { "data": 'date' },
                            { "data": 'amount' },
                            { "data": 'action' }
                        ]
                    });

                    $(".total-sales").html(data.message.totalSales.total);
                    $(".total-sales-trend").html(data.message.totalSales.trend);
                    
                    $(".total-served").html(data.message.totalServed.total);
                    $(".total-served-trend").html(data.message.totalServed.trend);
                    
                    $(".average-sales").html(data.message.averageSales.total);
                    $(".average-sales-trend").html(data.message.averageSales.trend);

                    $(".total-discounts").html(data.message.totalDiscount.total);
                    $(".total-discounts-trend").html(data.message.totalDiscount.trend);
                    
                    $(".total-credit-sales").html(data.message.totalCredit.total);
                    $(".total-credit-sales-trend").html(data.message.totalCredit.trend);

                    $(".total-profit").html(data.message.salesComparison.profit);
                    $(".total-profit-trend").html(data.message.salesComparison.profit_trend);

                    $(".total-selling").html(data.message.salesComparison.selling);
                    $(".total-selling-trend").html(data.message.salesComparison.selling_trend);

                    $(".total-cost").html(data.message.salesComparison.cost);
                    $(".total-cost-trend").html(data.message.salesComparison.cost_trend);
                } else {
                    $(`table[class~="salesLists"]`).dataTable();
                }

            },
            complete: function(data) {
                $(`div[class="main-content"]`).on('click', `a[class~="print-receipt"]`, function(e) {
                    let orderId = $(this).data('sales-id');
                    window.open(
                        `${baseUrl}receipt/${orderId}`,
                        `Sales Invoice - Receipt #${orderId}`,
                        `width=650,height=750,resizable,scrollbars=yes,status=1`
                    );
                });
                hL();
            }, error: function(err) {
            }
        });
    }

    var fetchInventoryRecords = () => {

        if ($(`table[class~="inventoryLists"]`).length) {
            var colspan = "7";

            $.ajax({
                url: `${baseUrl}aj/dashboardAnalytics/fetchInventoryRecords`,
                type: "POST",
                dataType: "json",
                data: { fetchInventoryRecords: true, request: "fetchInventoryRecords" },
                success: function(data) {
                    if (data.status == true) {
                        $(`table[class~="inventoryLists"]`).dataTable().fnDestroy();
                        $(`table[class~="inventoryLists"]`).dataTable({
                            "aaData": data.message,
                            "iDisplayLength": 10,
                            "columns": [
                                { "data": 'row' },
                                { "data": 'product' },
                                { "data": 'quantity' },
                                { "data": 'price' },
                                { "data": 'recordedBy' },
                                { "data": 'orderDate' }
                            ]
                        });
                    }
                },
                complete: function() {}
            });
        }
    }

    var fetchProductThresholds = () => {
        if ($(`table[class~="thresholdLists"]`).length) {
            var colspan = "3";
            $.ajax({
                url: baseUrl + "aj/dashboardAnalytics",
                type: "POST",
                dataType: "json",
                data: { request: "getProductThresholds" },
                success: function(data) {
                    if (data.status == true) {
                        $(`table[class~="thresholdLists"]`).dataTable().fnDestroy();
                        $(`table[class~="thresholdLists"]`).dataTable({
                            "aaData": data.message,
                            "iDisplayLength": 10,
                            "columns": [
                                { "data": 'row' },
                                { "data": 'product' },
                                { "data": 'quantity' }
                            ]
                        });
                    }
                },
                complete: function() {}
            });
        }
    }

    async function dashboardAnalitics() {

        async function loadSalesData() {
            return new Promise((resolve, reject) => {
                var data = listIDB('sales');
                resolve(data);
            });
        }

        var salesData = await loadSalesData();

        Array.prototype.unique = function() {
            return this.filter(function(value, index, self) {
                return self.indexOf(value) === index;
            });
        }

        var totals = 0,
            credits = 0,
            orders = 0, expectedSellingPrice = 0,
            productsCostPrice = 0,
            customers = 0, row = 0;
        var customerArray = new Array();
        var salesArray = new Array();
        var salesFigures = new Array();
        var creditSalesArray = new Array();
        var paidSalesArray = new Array();

        var creditBadge = ``, hourAndValue = new Array(), vHours = new Array();

        $.each(salesData, function(i, e) {

            if ((companyVariables._hi != 1)) {
                if ((e.branchId == companyVariables._clb) && (e.recorded_by == companyVariables._ud)) {
                    orders += 1;
                    customerArray.push(e.customer_id);
                    totals += parseFloat(e.order_amount_paid);
                    productsCostPrice += parseFloat(e.total_cost_price);
                    expectedSellingPrice += parseFloat(e.total_expected_selling_price);
                    salesFigures.push(parseFloat(e.order_amount_paid));
                    
                    vHours.push(parseFloat(e.hour_of_day));

                    if(e.credit_sales == 1) {
                        creditSalesArray.push(parseFloat(e.order_amount_paid));
                    } else if(e.credit_sales == 0) {
                        paidSalesArray.push(parseFloat(e.order_amount_paid));
                    }
                }
            } else {
                orders += 1;
                if(e.credit_sales == 1) {
                    creditSalesArray.push(parseFloat(e.order_amount_paid));
                } else if(e.credit_sales == 0) {
                    paidSalesArray.push(parseFloat(e.order_amount_paid));
                }
               
                productsCostPrice += parseFloat(e.total_cost_price);
                customerArray.push(e.customer_id);
                totals += parseFloat(e.order_amount_paid);
                salesFigures.push(parseFloat(e.order_amount_paid));
                expectedSellingPrice += parseFloat(e.total_expected_selling_price);

                vHours.push(parseFloat(e.hour_of_day));
                
            }

            if (e.credit_sales == 1) {
                credits += parseFloat(e.order_amount_paid);
            }

            if (e.payment_type == 'cash') {
                creditBadge = `<span class="text-gray">Cash Sale</span>`;
            } else if (e.payment_type == 'momo') {
                creditBadge = `<span class="text-gray">Mobile Money</span>`;
            } else if (e.payment_type == 'card') {
                creditBadge = `<span class="text-gray">Card Payment</span>`;
            } else if (e.payment_type == 'credit') {
                creditBadge = `<span class="text-gray">Credit</span>`;
            }

            if ((companyVariables._hi != 1)) {
                if (e.recorded_by == companyVariables._ud) {
                    salesArray.push({
                        row: orders,
                        order_id: `${e.order_id} <br> ${creditBadge}`,
                        fullname: `<a href="javascript:void(0)" type="button" class="get-sales-details text-success" data-sales-id="${e.order_id}" onclick="return getSalesDetails('${e.order_id}')">${e.customer_fullname}</a>`,
                        phone: e.customer_contact,
                        date: e.order_date,
                        amount: e.order_amount_paid,
                        action: `<a href="javascript:void(0)" type="button" class="get-sales-details text-success" data-sales-id="${e.order_id}" onclick="return getSalesDetails('${e.order_id}')">
			            	<i class="fa fa-eye"></i>
			            </a>`
                    });
                }
            } else {
                salesArray.push({
                    row: orders,
                    order_id: `${e.order_id} <br> ${creditBadge}`,
                    fullname: `<a href="javascript:void(0)" type="button" class="get-sales-details text-success" data-sales-id="${e.order_id}" onclick="return getSalesDetails('${e.order_id}')">${e.customer_fullname}</a>`,
                    phone: e.customer_contact,
                    date: e.order_date,
                    amount: e.order_amount_paid,
                    action: `<a href="javascript:void(0)" type="button" class="get-sales-details text-success" data-sales-id="${e.order_id}" onclick="return getSalesDetails('${e.order_id}')">
		            	<i class="fa fa-eye"></i>
		            </a>`
                });
            }
        });

        var lowestSale = `${companyVariables.cur} ${formatCurrency(Math.min(...salesFigures))}`;
        var highestSale = `${companyVariables.cur} ${formatCurrency(Math.max(...salesFigures))}`;
        var totalDiscount = `${companyVariables.cur} (${formatCurrency(expectedSellingPrice - totals)})`;
        var creditTotal = `${companyVariables.cur} ${formatCurrency(credits)}`;
        var salesTotal = `${companyVariables.cur} ${formatCurrency(totals)}`;
        var totalCost = `${companyVariables.cur} ${formatCurrency(productsCostPrice)}`;
        var totalProfit = `${companyVariables.cur} ${formatCurrency(totals-productsCostPrice)}`;
        var creditPercent = `<span class='text-danger'>${companyVariables.cur} ${parseFloat((credits/totals)*100).toFixed(2)}% of Total Sales</span>`;
        var average = `${companyVariables.cur} ${formatCurrency(totals/orders)}`;

        var hValues = new Array();
        for(var i = 0; i < vHours.length; i++) {
            if(hValues[vHours[i]] !== undefined) {
                hValues[vHours[i]] += parseFloat(salesFigures[i]);
            } else if(typeof vHours[i] !== undefined) {
                hValues[vHours[i]] = parseFloat(salesFigures[i]);
            }
        }

        var hourOfTheDay = {0:"12AM",1:"1AM",2:"2AM",3:"3AM",4:"4AM",5:"5AM",6:"6AM",7:"7AM",8:"8AM",9:"9AM",10:"10AM",11:"11AM",12:"12PM",13:"1PM",14:"2PM",15:"3PM",16:"4PM",17:"5PM",18:"6PM",19:"7PM",20:"8PM",21:"9PM",22:"10PM",23:"11PM"};

        var goodHour = new Array(), goodValues = new Array();
        $.each(hValues, function(i, e) {
            if(e != undefined) {
                goodValues.push(e);
                goodHour.push(hourOfTheDay[i]);
            }
        });

        var data = {
            total: salesTotal,
            credit: creditTotal,
            orders: orders,
            customers: customerArray.unique(),
            creditPercent: creditPercent,
            salesHistory: salesArray,
            averageSale: average,
            profit: totalProfit,
            selling: salesTotal,
            cost: totalCost,
            highest: highestSale,
            lowest: lowestSale,
            analitics: {
                hourValues: goodValues,
                goodHour: goodHour
            }
        };

        return data;
    }

    $(`span[class~="switch-button"]`).on('click', async function(e) {

        let show = $(this).attr('data-show-content');
        let hide = $(this).attr('data-hide-content');

        $(`div[class~="${show}"]`).removeClass('hidden').fadeIn('slow');
        $(`div[class~="${hide}"]`).addClass('hidden').fadeOut('slow');

        await $.ajax({
            url: `${baseUrl}doprocess_branches/saveReportsRecord`,
            data: { saveReportsRecord: true, attendantPerformance: show },
            type: "POST",
            dataType: "JSON",
            success: function(resp) {
            }
        });
    });

    if ($(`div[class~="dashboard-reports"], div[class~="overallSalesHistory"], div[class~="pos-reporting"]`).length) {

        var offline = true;

        async function loadDashboardInformation() {

            await dOC().then((itResp) => {
                if (itResp == 1) {
                    offline = false;
                    $(`div[class="connection"]`).css('display', 'none');
                } else {
                    offline = true;
                    $(`div[class="connection"]`).css('display', 'block');
                }
            }).catch((err) => {
                offline = true;
                $(`div[class="connection"]`).css('display', 'block');
            });

            if (offline) {

                $(`select[name="periodSelected"], select[name="periodSelect"]`).prop('disabled', true);
                $(`div[class~="offline-placeholder"]`).css({ 'display': 'flex' });
                $(`div[class~="offline-placeholder"] button[type="button"]`).html(`Reconnect`).css({ 'display': 'inline-flex' });

                dashboardAnalitics().then(async (dashboardInsights) => {

                    if($(`table[class~="salesLists"]`).length) {
                        
                        $(`table[class~="salesLists"]`).dataTable().fnDestroy();

                        $(`table[class~="salesLists"]`).dataTable({
                            "aaData": dashboardInsights.salesHistory,
                            "iDisplayLength": 10,
                            "columns": [
                                { "data": 'row' },
                                { "data": 'order_id' },
                                { "data": 'fullname' },
                                { "data": 'phone' },
                                { "data": 'date' },
                                { "data": 'amount' },
                                { "data": 'action' }
                            ]
                        });


                    }

                    if ($(`div[class~="dashboard-reports"]`).length) {
                        $(".total-sales").html(dashboardInsights.total);
                        $(".total-sales-trend").html(`<span class="text-success"><i class="mdi mdi-trending-up"></i> Total Sales Today</span>`);
                        $(".total-served").html(dashboardInsights.orders);
                        $(".total-served-trend").html(`<span class="text-success"><i class="mdi mdi-trending-up"></i> Customers Served</span>`);
                        $(".total-products").html(dashboardInsights.averageSale);
                        $(".total-products-worth").html(`<span class="text-success"><i class="mdi mdi-trending-up"></i> Sold today</span>`);
                        $(".total-credit-sales").html(dashboardInsights.credit);
                        $(".total-credit-sales-trend").html(dashboardInsights.creditPercent);

                        $(".total-profit").html(dashboardInsights.profit);
                        $(".total-profit-trend").html(`<span class="text-success"><i class="mdi mdi-trending-up"></i> Profits made from sale</span>`);

                        $(".total-selling").html(dashboardInsights.selling);
                        $(".total-selling-trend").html(`<span class="text-success"><i class="mdi mdi-trending-up"></i> Revenue less Discount</span>`);

                        $(".total-cost").html(dashboardInsights.cost);
                        $(".total-cost-trend").html(`<span class="text-success"><i class="mdi mdi-trending-up"></i> Costs of Items sold</span>`);
                    }

                    if($(`div[class~="pos-reporting"]`).length) {

                        await gIDBR('reports', "branch_performance").then((branchInsight) => {
                            delete branchInsight.reports_id;
                            var branchData = new Array();
                            populateBranchData(Object.values(branchInsight));
                        }).then((resp) => {
                            gIDBR('reports', "sales_attendant_performance").then((attendantInsight) => {
                                populateSalesTeamData(Object.values(attendantInsight.list), Object.values(attendantInsight.names), Object.values(attendantInsight.sales))
                            });
                        }).then((res) => {
                            gIDBR('reports', "products_performance").then((productsInsight) => {
                                delete productsInsight.reports_id;
                                populateProductsPerformance(Object.values(productsInsight));
                            });
                        });

                        $(`p[data-model="highest-sale"] span`).html(dashboardInsights.highest);
                        $(`p[data-model="lowest-sale"] span`).html(dashboardInsights.lowest);
                       
                        $(`div[data-report="total-sales"] h3[class="my-3"]`).html(dashboardInsights.total);
                        $(`div[data-report="total-sales"] p[class~="text-truncate"]`).html(`<span class="text-success"><i class="mdi mdi-trending-up"></i> Total Sales Today</span>`);

                        $(`div[data-report="average-sales"] h3[class="my-3"]`).html(dashboardInsights.averageSale);
                        $(`div[data-report="average-sales"] p[class~="text-truncate"]`).html(`<span class="text-success"><i class="mdi mdi-trending-up"></i> Average Sales Today</span>`);

                        $(`div[data-report="highest-sales"] h3[class="my-3"]`).html(dashboardInsights.highest);
                        $(`div[data-report="highest-sales"] p[class~="text-truncate"]`).html(`<span class="text-success"><i class="mdi mdi-trending-up"></i> highest Sales Today</span>`);

                        $(`div[data-report="total-orders"] h3[class="my-3"]`).html(dashboardInsights.orders);
                        $(`div[data-report="total-orders"] p[class~="text-truncate"]`).html(`<span class="text-success"><i class="mdi mdi-trending-up"></i> Total Sales Today</span>`);

                        var thisOpts = {
                            chart: {
                                height: 374,
                                type: 'line',
                                shadow: {
                                    enabled: false,
                                    color: '#bbb',
                                    top: 3,
                                    left: 2,
                                    blur: 3,
                                    opacity: 1
                                },
                            },
                            stroke: {
                                width: 5,
                                curve: 'smooth'
                            },
                            series: [{
                                name: 'Total Sales',
                                data: dashboardInsights.analitics.hourValues
                            }],
                            xaxis: {
                                type: 'datetime',
                                categories: dashboardInsights.analitics.goodHour,
                                axisBorder: {
                                    show: true,
                                    color: '#bec7e0',
                                },
                                axisTicks: {
                                    show: true,
                                    color: '#bec7e0',
                                },
                            },
                            fill: {
                                type: 'gradient',
                                gradient: {
                                    shade: 'dark',
                                    gradientToColors: ['#43cea2'],
                                    shadeIntensity: 1,
                                    type: 'horizontal',
                                    opacityFrom: 1,
                                    opacityTo: 1,
                                    stops: [0, 100, 100, 100]
                                },
                            },
                            markers: {
                                size: 4,
                                opacity: 0.9,
                                colors: ["#ffbc00"],
                                strokeColor: "#fff",
                                strokeWidth: 2,
                                style: 'hollow',
                                hover: {
                                    size: 7,
                                }
                            },
                            yaxis: {
                                title: {
                                    text: 'Sales Values',
                                },
                            },
                            tooltip: {
                                shared: true,
                                intersect: false,
                                y: {
                                    formatter: function(y) {
                                        if (typeof y !== "undefined") {
                                            return companyVariables.cur + formatCurrency(y);
                                        }
                                        return y;

                                    }
                                }
                            },
                            grid: {
                                row: {
                                    colors: ['transparent', 'transparent'],
                                    opacity: 0.2
                                },
                                borderColor: '#185a9d'
                            },
                            responsive: [{
                                breakpoint: 600,
                                options: {
                                    chart: {
                                        toolbar: {
                                            show: false
                                        }
                                    },
                                    legend: {
                                        show: false
                                    },
                                }
                            }]
                        }

                        delete thisOpts.xaxis.type;
                        
                        var chart = new ApexCharts(
                            document.querySelector("#sales-overview-chart"),
                            thisOpts
                        );

                        chart.render();
                       
                    }
                });
                
                hL();

                return false;
            }

            $(`div[class~="offline-placeholder"]`).css({ 'display': 'none' });
            $(`div[class~="offline-placeholder"] button[type="button"]`).prop('disabled', false);

            if ($(`div[class~="dashboard-reports"], div[class~="overallSalesHistory"]`).length) {
                fetchInventoryRecords();
                fetchSalesRecords();
                fetchProductThresholds();

                if($(`div[class~="sales-overview-data"]`).length) {
                    salesOverview('this-week');
                }

            } else if($(`div[class~="pos-reporting"]`).length) {

                var period = $(`select[name="periodSelect"]`).val();
                salesOverview(period);
                
                if ($(`div[class~="reports-summary"]`).length) {
                    summaryItems(period);
                    salesOverview(period);
                    salesAttendantPerformance(period);
                    if($(`div[id="dash_spark_1"]`).length) {
                        ordersCount(period);
                    }
                    topContactsPerformance(period);
                    branchPerformance(period);
                }

                $(`select[name="periodSelect"]`).on('change', function() {
                    sL();
                    var periodSelected = $(this).val();
                    summaryItems(periodSelected);
                    salesOverview(periodSelected);
                    if($(`div[id="dash_spark_1"]`).length) {
                        ordersCount(periodSelected);
                    }
                    salesAttendantPerformance(periodSelected);
                    topContactsPerformance(periodSelected);
                    branchPerformance(periodSelected);
                });

            }

            $(`select[name="periodSelected"]`).on('change', function() {
                fetchSalesRecords();
            });

        }

        loadDashboardInformation();
    }

});