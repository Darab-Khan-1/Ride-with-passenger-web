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

                <div class="card-body flex-wrap border-0 pt-6 pb-0 ">

                <div class="row gy-5 g-xl-10">
                    {{-- <div class="col-xl-5 mb-xl-10">
                        <div class="card mb-12 h-md-100" dir="ltr" style="height: 450px;">
                            <div class="card-body d-flex flex-column flex-center">
                                <div class="mb-2">
                                    <p class="m-5" style="font-weight: bold;font-size: 17px;">
                                        Driver Status</p>
                                    <div class="py-18 text-left">
                                        <div id="driverChart"></div>
                                        <h3>Total Drivers: {{ $data['drivers'] }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-xl-5 mb-xl-10">
                        <div class="card mb-12 h-md-100" dir="ltr" style="height: 450px;">
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
    $("#dashboard_nav").addClass("menu-item-active");

    var data = {!! json_encode($data) !!}
    console.log(data);
    // var driverChart = new ApexCharts(document.querySelector("#driverChart"), {
    //     series: [data.offline, data.online],
    //     chart: {
    //         width: 510,
    //         height: 240,
    //         type: "donut",
    //     },
    //     colors: ["#ff0000", "#00ff00"],

    //     labels: [
    //         '<div class="d-inline-block"><span class="fs-5 bold-20 d-inline-block w-100">' + data.offline +
    //         ' Offline</span></div>',
    //         '<div class="d-inline-block"><span class="fs-5 bold-20 d-inline-block w-100">' + data.online +
    //         ' Online</span></div>',
    //     ],
    //     tooltip: {
    //         // your tooltip options here
    //     },
    //     legend: {
    //         show: true,
    //     }
    // });
    // driverChart.render();

    // var tripChart = new ApexCharts(document.querySelector("#tripChart"), {
    //     series: [data.active, data.completed],
    //     chart: {
    //         width: 510,
    //         height: 240,
    //         type: "donut",
    //     },
    //     colors: ["#FFA800", "#1BC5BD"],

    //     labels: [
    //         '<div class="d-inline-block"><span class="fs-5 bold-20 d-inline-block w-100">' + data.active +
    //         ' Active</span></div>',
    //         '<div class="d-inline-block"><span class="fs-5 bold-20 d-inline-block w-100">' + data
    //         .completed +
    //         ' Completed</span></div>',
    //     ],
    //     tooltip: {
    //         // your tooltip options here
    //     },
    //     legend: {
    //         show: true,
    //     }
    // });
    // tripChart.render();
    // getStatus();
    // setInterval(getStatus, 5000);
</script>
