@include('includes/header')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Entry-->
    <div>
        <!--begin::Container-->
        <div class="px-5">
            <div class="card card-custom">
                @if (session('success'))
                    <div class="alert alert-success m-2">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger m-2">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Dashboard
                            {{-- <span class="d-block text-muted pt-2 font-size-sm">Companies made easy</span> --}}
                        </h3>
                    </div>
                </div>

                <div class="card-body flex-wrap border-0 pt-6 pb-0 "
                    style="box-shadow: inset 1px 1px 10px 1px #c9c9c9;">

                    <div class="row gy-5 g-xl-10">
                        <div class="col-xl-5 mb-xl-10">
                            <div class="card mb-12 h-md-100" dir="ltr"
                                style="height: 450px;box-shadow: inset 1px 1px 10px 1px #c9c9c9;">
                                <div class="card-body d-flex flex-column flex-center">
                                    <div class="mb-2">
                                        <p class="m-5" style="font-weight: bold;font-size: 17px;">
                                            Users</p>
                                        <div class="py-18 text-left">
                                            <div id="driverChart"></div>
                                            <h3>Total Users: {{ $data['drivers'] }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-5 mb-xl-10">
                            <div class="card mb-12 h-md-100" dir="ltr"
                                style="height: 450px;box-shadow: inset 1px 1px 10px 1px #c9c9c9;">
                                <div class="card-body d-flex flex-column flex-center">
                                    <div class="mb-2">
                                        <p class="m-5" style="font-weight: bold;font-size: 17px;">
                                            Trips Status</p>
                                        <div class="py-18 text-left">
                                            <div id="tripChart"></div>
                                            <h3>Total Trips: {{ $data['trips'] }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-5 mb-xl-10">
                            <div class="card mb-12 h-md-100" dir="ltr"
                                style="height: 450px;box-shadow: inset 1px 1px 10px 1px #c9c9c9;">
                                <div class="card-body d-flex flex-column flex-center">
                                    <div class="mb-2">
                                        <p class="m-5" style="font-weight: bold;font-size: 17px;">
                                            Roles</p>
                                        <div class="py-18 text-left">
                                            <div id="roleChart"></div>
                                            <h3>Total Roles: {{ count($data['roles']) }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="card-footer">
                </div> --}}
            </div>

        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>

@include('includes/footer')
<script>
    $(".home-nav").click()
    $(".dashboard-nav").addClass("menu-item-active");

    var data = {!! json_encode($data) !!}
    console.log(data);
    var driverChart = new ApexCharts(document.querySelector("#driverChart"), {
        series: [data.drivers, data.employees],
        chart: {
            width: 510,
            height: 240,
            type: "donut",
        },
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: 'bottom'
            }
          }
        }],
        colors: ['#3699FF', "#FFA800"],

        labels: [
            '<div class="d-inline-block"><span class="fs-5 bold-20 d-inline-block w-100">' + data.drivers +
            ' Drivers</span></div>',
            '<div class="d-inline-block"><span class="fs-5 bold-20 d-inline-block w-100">' + data
            .employees +
            ' Employees</span></div>',
        ],
        tooltip: {
            // your tooltip options here
        },
        legend: {
            show: true,
        }
    });
    driverChart.render();

    var tripChart = new ApexCharts(document.querySelector("#tripChart"), {
        series: [data.incomplete, data.available, data.active, data.completed],
        chart: {
            width: 510,
            height: 240,
            type: "donut",
        },
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: 'bottom'
            }
          }
        }],
        colors: ["#F64E60", '#3699FF', "#FFA800", "#1BC5BD"],

        labels: [
            '<div class="d-inline-block"><span class="fs-5 bold-20 d-inline-block w-100">' + data
            .incomplete +
            ' Incomplete</span></div>',
            '<div class="d-inline-block"><span class="fs-5 bold-20 d-inline-block w-100">' + data
            .available +
            ' Available</span></div>',
            '<div class="d-inline-block"><span class="fs-5 bold-20 d-inline-block w-100">' + data.active +
            ' Active</span></div>',
            '<div class="d-inline-block"><span class="fs-5 bold-20 d-inline-block w-100">' + data
            .completed +
            ' Completed</span></div>',
        ],
        tooltip: {
            // your tooltip options here
        },
        legend: {
            show: true,
        }
    });
    tripChart.render();




    function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }


    var series = []
    var labels = []
    var colors = []
    data.roles.forEach(element => {
        series.push(element.employee_count)
        colors.push(getRandomColor())
        labels.push('<div class="d-inline-block"><span class="fs-5 bold-20 d-inline-block w-100">' + element
            .name + ' </span></div>')
    });

    var roleChart = new ApexCharts(document.querySelector("#roleChart"), {
        series: series,
        chart: {
            width: 510,
            height: 240,
            type: "donut",
        },
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: 'bottom'
            }
          }
        }],
        colors: colors,

        labels: labels,
        tooltip: {
            // your tooltip options here
        },
        legend: {
            show: true,
        }
    });
    roleChart.render();
</script>
