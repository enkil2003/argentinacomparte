var ResultsPoll={
    loadConfirmationModal:function(title, modalId,o1,o2,o3,o4,r1,r2,r3,r4){
    	var chartContainer = 'results-'+modalId;
    	var total = r1+r2+r3+r4; 
        var chartdata = [
                      [o1, r1*1.0],
                      [o2, r2*1.0],
                      [o3, r3*1.0],
                      [o4, r4*1.0]
                  ];
        new Highcharts.Chart({
            chart: {
                renderTo: 'chartHolder',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: title
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage}%</b>',
                percentageDecimals: 1
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        formatter: function() {
                            return '<b>'+ this.point.name +'</b>: '+ this.percentage +' %';
                        }
                    }
                }
            },
            series: [{
                type: 'pie',
                name: title,
                data: chartdata
            }]
        });
        $modal=jQuery('#chartHolder');
        $modal.dialog({width: '960px'});
        return false;
    }
};
