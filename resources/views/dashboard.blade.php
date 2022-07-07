@extends('layouts.app')

@section('content')

<div class="section-header">
    <h1>{{ $title }}</h1>
</div>
<div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                  <i class="far fa-user"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Total Admin</h4>
                  </div>
                  <div class="card-body">
                    10
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                  <i class="far fa-newspaper"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>News</h4>
                  </div>
                  <div class="card-body">
                    42
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                  <i class="far fa-file"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Reports</h4>
                  </div>
                  <div class="card-body">
                    1,201
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                  <i class="fas fa-circle"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Online Users</h4>
                  </div>
                  <div class="card-body">
                    47
                  </div>
                </div>
              </div>
            </div>                  
          </div>
<div class="row">
    <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4>Data Anggota Berdasarkan Team</h4>
                <button type="button" class="btn" data-toggle="tooltip" title="Date range" id="button-covid19">
                    <i class="fa fa-calendar"></i>
                </button>
            </div>
            <div class="card-body">
                <div id="team"></div>
            </div>
            <div class="card-footer">
                Footer Card
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4>Data Anggota Berdasarkan Desa</h4>
                <button type="button" class="btn" data-toggle="tooltip" title="Date range" id="button-desa">
                    <i class="fa fa-calendar"></i>
                </button>
            </div>
            <div class="card-body">
                <div id="desaPie"></div>
            </div>
            <div class="card-footer bg-whitesmoke">
                Footer Card
            </div>
        </div>
    </div>
</div>

<script>
    Highcharts.chart('team', {
                chart: {
                    type: 'spline'
                }
                , title: {
                    text: 'Chart Berdasarkan Team'
                }
                , subtitle: {
                    text: '-'
                }
                , xAxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'
                        , 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                    ]
                }
                , yAxis: {
                    title: {
                        text: 'Jumlah'
                    }
                    , labels: {
                        formatter: function() {
                            return this.value + '';
                        }
                    }
                }
                , tooltip: {
                    crosshairs: true
                    , shared: true
                }
                , plotOptions: {
                    spline: {
                        marker: {
                            radius: 4
                            , lineColor: '#666666'
                            , lineWidth: 1
                        }
                    }
                }
                , series: [{
                            name: 'Team A'
                            , marker: {
                                symbol: 'square'
                            }
                            , data: [<?= $a_str; ?>]

        }, {
            name: 'Team B',
            marker: {
                symbol: 'diamond'
            },
            data: [<?= $b_str; ?>]
        }]
    });

    // Pie Chart Desa

    $('button[id="button-desa"]').daterangepicker({
        maxDate: moment(),
        opens: 'left'
    }, function(start, end, label) {
        $.ajax({
            url: 'team/filter-desa/' + start.format('YYYY-MM-DD') + '/' + end.format('YYYY-MM-DD'),
            type: 'GET',
            async: true,
            //dataType: 'json',
            success: function(data) {
                var obj = data;
                // console.log(obj);

                var top_chart_wilayah = $('#desaPie').highcharts();
                myarray = [];
                $.each(obj, function(index, val) {
                    myarray[index] = [val.name, val.count];
                });
                dataFIlter = options.series[0].data = myarray;
                console.log(dataFIlter)

                top_chart_wilayah.series[0].update({
                    data: dataFIlter
                }, false)
                top_chart_wilayah.redraw();

            }
        });
        console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
    });

    var desa = <?php echo json_encode($pieDesa); ?>;
    var options = {
        chart: {
            renderTo: 'desaPie',
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: 'Percentage of desa Courses'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage}%</b>',
                                percentageDecimals: 1
                            }
                            , plotOptions: {
                                pie: {
                                    allowPointSelect: true
                                    , cursor: 'pointer'
                                    , dataLabels: {
                                        enabled: true
                                        , format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                                    }
                                }
                            }
                            , series: [{
                                type: 'pie'
                                , name: 'Desa'
                                , slice: true
                            }]
                        }
                        myarray = [];
                        $.each(desa, function(index, val) {
                            myarray[index] = [val.name, val.count];
                        });
                        options.series[0].data = myarray;
                        console.log(desa);
                        console.log(myarray)
                        chart = new Highcharts.Chart(options);
                        // End Pie Chart Desa

</script>

@endsection
