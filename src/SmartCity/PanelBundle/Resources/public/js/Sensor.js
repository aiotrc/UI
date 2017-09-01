Sensor = {

    map: null,
    markers: null,
    imagesSource: '/bundles/smartcitypanel/images/map/',
    google_API_key: 'AIzaSyCp6Nywq_dpJkIeRVrprpE7D8pMDj3X5BQ',
    labels: {
        no_result: 'اطلاعاتی برای نمایش وجود ندارد',
    },
    aggregationLogChart: null,
    months: ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'],

	init: function(){
        window['moment-range'].extendMoment(moment);
        this.initMap();
        this.getSensorCluster();
        moment.locale('fa')

        $("#metric-value-start-time").persianDatepicker({
            timePicker: {
                enabled: false
            },
            autoClose: true,
            format: 'YYYY-MM-DD',
            altField: '#metric-value-start-time_alt',
            altFormat: "g",
            position: [-270, -30],
            navigator: {
                text: {
                    btnNextText: '>',
                    btnPrevText: '<'
                }
            }
        })

        $("#metric-value-end-time").persianDatepicker({
            timePicker: {
                enabled: false
            },
            autoClose: true,
            format: 'YYYY-MM-DD',
            altField: '#metric-value-end-time_alt',
            altFormat: "g",
            position: [-270, -30],
            navigator: {
                text: {
                    btnNextText: '>',
                    btnPrevText: '<'
                }
            }
        });

        // $("#aggregation-log-start-time").persianDatepicker({
        //     timePicker: {
        //         enabled: false
        //     },
        //     autoClose: true,
        //     format: 'YYYY-MM-DD',
        //     altField: '#aggregation-log-start-time_alt',
        //     altFormat: "g",
        //     position: [-270, -30],
        //     navigator: {
        //         text: {
        //             btnNextText: '>',
        //             btnPrevText: '<'
        //         }
        //     }
        // })

        // $("#aggregation-log-end-time").persianDatepicker({
        //     timePicker: {
        //         enabled: false
        //     },
        //     autoClose: true,
        //     format: 'YYYY-MM-DD',
        //     altField: '#aggregation-log-end-time_alt',
        //     altFormat: "g",
        //     position: [-270, -30],
        //     navigator: {
        //         text: {
        //             btnNextText: '>',
        //             btnPrevText: '<'
        //         }
        //     }
        // });

        $('#sensor-metric-value-btn').click(function(){
            var filters = $(this).parents('.filters')

            var sensorId = filters.find('input[name=sensorId]').val();
            var func = filters.find('select[name=func]').val();
            var termName = filters.find('select[name=termName]').val();
            var startTime = filters.find('#metric-value-start-time_alt').val();
            var endTime = filters.find('#metric-value-end-time_alt').val();

            startTime = moment(startTime).format('YYYY-MM-DDTHH:mm:ss');
            endTime = moment(endTime).format('YYYY-MM-DDTHH:mm:ss');

            if(sensorId == ''){
                BackendFramework.showNotif('warning', 'ابتدا سنسور مورد نظر را انتخاب نمایید');
                return false;
            }

            if(func == '' || termName == '' || startTime == '' || endTime == ''){
                BackendFramework.showNotif('warning', 'فیلتر مورد نظر را انتخاب نمایید');
                return false;
            }
            
            Sensor.getSensorMetricValue(sensorId, func, termName, startTime, endTime)
        })

        $('#sensor-aggregation-log-btn').click(function(){
            var filters = $(this).parents('.filters')
            var sensorId = filters.find('input[name=sensorId]').val();
            var func = filters.find('select[name=func]').val();
            var termName = filters.find('select[name=termName]').val();
            var startTime = filters.find('#metric-value-start-time_alt').val();
            var endTime = filters.find('#metric-value-end-time_alt').val();
            var timeRange = filters.find('select[name=timeRange]').val();

            timeRangeObj = Sensor.makeTimeRanges(timeRange);

            startTime = timeRangeObj.startTime;
            endTime = timeRangeObj.endTime;
            interval = timeRangeObj.ranges;

            if(sensorId == ''){
                BackendFramework.showNotif('warning', 'ابتدا سنسور مورد نظر را انتخاب نمایید');
                return false;
            }
            if(func == '' || termName == '' || startTime == '' || endTime == ''){
                BackendFramework.showNotif('warning', 'فیلتر مورد نظر را انتخاب نمایید');
                return false;
            }

            Sensor.getSensorAggregationLog(sensorId, func, termName, startTime, endTime, interval);
        })
    },

    makeTimeRanges: function(timeRange){
        setting = {};

        switch (timeRange){
            case 'DAY_SOFAR':
                setting = {
                    startTimeConf: 'day',
                    endTimeConf: 'ww', // endOf argumant can't be null,
                    rangeBy: 'hours',
                    rangekeyConf: 'hour',
                    rangekeyFormat: 'HH',
                    rangeObjectFrom: 'hour',
                    rangeObjectTo: 'hour',
                };
                break;

            case 'WEEK_SOFAR':
                setting = {
                    startTimeConf: 'week',
                    endTimeConf: 'day',
                    rangeBy: 'days',
                    rangekeyConf: 'day',
                    rangekeyFormat: 'dddd',
                    rangeObjectFrom: 'day',
                    rangeObjectTo: 'day',
                };
                break;

            case 'MONTH_SOFAR':
                setting = {
                    startTimeConf: 'jMonth',
                    endTimeConf: 'day',
                    rangeBy: 'days',
                    rangekeyConf: 'day',
                    rangekeyFormat: 'jD',
                    rangeObjectFrom: 'day',
                    rangeObjectTo: 'day',
                };
                break;

                case 'YEAR_SOFAR':
                setting = {
                    startTimeConf: 'jYear',
                    endTimeConf: 'jMonth',
                    rangeBy: 'months',
                    rangekeyConf: 'ww',
                    rangekeyFormat: 'jMMMM',
                    rangeObjectFrom: 'jMonth',
                    rangeObjectTo: 'jMonth',
                };
                break;

            default: break;
        }
        
        
        startTime = moment().startOf(setting.startTimeConf).format('YYYY-MM-DDTHH:mm:ss');
        endTime = moment().endOf(setting.endTimeConf).format('YYYY-MM-DDTHH:mm:ss');

        var range = moment.range(startTime, endTime);
        var intervals = Array.from(range.by(setting.rangeBy));
        rangeObject = {};

        i = 0
        intervals.map((m, index) => {
            rangeKey = m.startOf(setting.rangekeyConf).format(setting.rangekeyFormat);

            if(rangeObject[i-1] != undefined){
                if(rangeObject[i-1].key == rangeKey){
                    return;
                }
            }

            rangeObject[i] = {
                from: m.startOf(setting.rangeObjectFrom).format('YYYY-MM-DDTHH:mm:ss'),
                to: m.endOf(setting.rangeObjectTo).format('YYYY-MM-DDTHH:mm:ss'),
                key: rangeKey,
            }
            i++
        });

        return {
            'startTime': startTime,
            'endTime': endTime,
            'ranges': rangeObject,
        }
    },
    
    initMap: function(){
        
        // init map and tiles
        Sensor.map = L.map('sensormap', {
            center: [32.4356, 53.9209],
            zoom: 12, //5,
            minZoom: 4,
            maxzoom: 12
        });
        Sensor.map.scrollWheelZoom.disable();

        // http://{s}.tile.osm.org/{z}/{x}/{y}.png
        var tiles = L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoicG9vdHJpYSIsImEiOiJjajZiOGJ1amsxZXgzMndvOThvZTJ1bnY3In0.hcwyouLNbyr6Qsl4-nmpsg', {
            id: 'mapbox.streets',
        }).addTo(Sensor.map);
        

        // init map listeners
        Sensor.map.on('load', function(e){Sensor.getSensorCluster()});
        Sensor.map.on('zoomend dragend',function(e){Sensor.getSensorCluster();});


        // init marker clustering
        Sensor.markers = L.markerClusterGroup({
        zoomToBoundsOnClick: true,
        removeOutsideVisibleBounds: true,
        animate: false,
        animateAddingMarkers: true,
        chunkedLoading: false,
        spiderfyOnMaxZoom: true,//spiderify effect when many points have same lat/lon
        showCoverageOnHover: false,
        iconCreateFunction: function(cluster) {
            //Grouping the cluster returned by the server, if
            var markers = cluster.getAllChildMarkers();
            var markerCount = 0;
            markers.forEach(function(m){markerCount = markerCount + m.count;});
            return new L.DivIcon({ html: '<div class="cluster-icon leaflet-zoom-animated leaflet-clickable" tabindex="0"><div><span>'+markerCount+'</span></div></div>' });
            }
        });
        Sensor.map.addLayer(Sensor.markers);
    },

    initSidebar: function(){
        var sidebar = $('<div id="sensor-detail"></div>');
        sidebar.appendTo('.page-content')

        $(document).mouseup(function(e) {
            
            if (!sidebar.is(e.target) && sidebar.has(e.target).length === 0) {
                sidebar.removeClass('visible');
            }
        });
    },

    getMapParams: function(){

        // Get map bound
        var bound = Sensor.map.getBounds();
        var boundObject = {
            "top_left_lat": bound.getNorthWest().wrap().lat,
            "top_left_lon": bound.getNorthWest().wrap().lng,
            "bottom_right_lat": bound.getSouthEast().wrap().lat,
            "bottom_right_lon": bound.getSouthEast().wrap().lng,
        }

        var mapZoom = Sensor.map.getZoom();        
        var zoom = 2;

        if(mapZoom >= 5 && mapZoom <= 8){
            zoom = 3;
        }
        else if(mapZoom >= 9 && mapZoom <= 11){
            zoom = 8;
        }
        else if(mapZoom >= 12 && mapZoom <= 13){
            zoom = 9;
        }
        else if(mapZoom >= 14){
            zoom = 10;
        }

        return {
            'bound': boundObject,
            'zoomLevel': zoom
        }
    },

    /* This will add all the clusters as returned by the elastic server.*/ 
    makePoints: function(clusters){
        points = {};
        var markerList = [];
        clusters.forEach(function(cluster, index){
            
            var center = geohash.decode(cluster.key);//elastic return a geohas so need to change it into lat/lon
            var specs = cluster.device.buckets[0].key.split('|');
            
            var marker_html = '<div data-id="'+specs[0]+'" class="cluster-icon leaflet-zoom-animated leaflet-clickable" tabindex="0"><div><span>'+cluster.doc_count+'</span></div></div>';
            if (cluster.doc_count == 1){            
                marker_html = '<div data-id="'+specs[0]+'" class="marker-image"><img width="32px" src="'+Sensor.imagesSource + specs[1]+'.png"></div>';
            }
            
            var marker_icon = L.divIcon({ html: marker_html });
            var marker = L.marker(new L.LatLng(center.latitude, center.longitude), { 
                icon: marker_icon 
            });

            if (cluster.doc_count == 1){
                var popup_content = 
                    '<div class="custom-content">' +
                        '<table class="table table-bordered table-striped">' +
                            '<tr>' +
                                '<td>شناسه</td>' +
                                '<td>'+specs[0]+'</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td>نوع دستگاه</td>' +
                                '<td>'+specs[1]+'</td>' +
                            '</tr>' +
                            '<tr>' +
                                '<td>شرکت سازنده</td>' +
                                '<td>'+specs[2]+'</td>' +
                            '</tr>' +
                        '</table>' +
                        '<a data-id="'+specs[0]+'" class="get-log btn btn-sm blue-hoki">مشاهده گزارشات</a>' +
                    '</div>'
                ;
                var popupContainer = $(popup_content);

                popupContainer.on('click', '.get-log', function() {
                    sensorId = $(this).attr('data-id');
                    btn = $(this)
                    Sensor.getSensorLatestLog(sensorId, btn);
                    Sensor.getSensorSpec(sensorId);
                    Sensor.getSensorGeoCodingInfo(center.latitude, center.longitude);
                });

                marker.bindPopup(popupContainer[0]);
            }

            marker.count = cluster.doc_count;
            markerList.push(marker);
        });
        Sensor.markers.addLayers(markerList);
    },



    // -------------------------------------------- API call
    getSensorCluster: function() {
        map_params = this.getMapParams();

        $.ajax({
            url: Routing.generate('panel_sensor_map_cluster'),
            method: "POST",
            data: map_params,
        })
	    .done(function(response) {
            if(!response.data){
                BackendFramework.showNotif('info', 'داده ای یافت نشد');
                return;
            }
            Sensor.markers.clearLayers();
	        Sensor.makePoints(response.data);
    	})
    	.fail(function(error) {
        	BackendFramework.showNotif('error')
    	});
    },

    getSensorLatestLog: function(sensorId, btn){
        var btn_label = btn.text();
        btn.html(BackendFramework.loadingGif);
        
        $.ajax({
            url: Routing.generate('panel_sensor_latest_log'),
            method: "GET",
            data: {
                sensorId: sensorId
            },
        })
	    .done(function(response) {
            if(!response.data){
                BackendFramework.showNotif('info', 'گزارشی یافت نشد');
                return;
            }

            Sensor.updateLatesLogView(response.data);
            Sensor.updateStateFilter(response.data);
    	})
    	.fail(function(error) {
        	BackendFramework.showNotif('error')
        })
        .always(function(){
            setTimeout(function(){
                btn.html(btn_label)
            }, 2000)
        })
    },

    getSensorSpec: function(sensorId){
        $.ajax({
            url: Routing.generate('panel_sensor_spec'),
            method: "GET",
            data: {
                sensorId: sensorId
            },
        })
	    .done(function(response) {
            if(!response.data){
                BackendFramework.showNotif('info', 'داده ای یافت نشد');
                return;
            }
            Sensor.updateSpecView(response.data);
    	})
    	.fail(function(error) {
        	BackendFramework.showNotif('error')
        })
    },

    getSensorMetricValue: function(sensorId, func, termName, startTime, endTime){
        $.ajax({
            url: Routing.generate('panel_sensor_metric_value'),
            method: "GET",
            data: {
                sensorId: sensorId,
                func: func,
                termName: termName,
                startTime: startTime,
                endTime: endTime,
            },
        })
	    .done(function(response) {
            if(!response.data){
                BackendFramework.showNotif('info', 'داده ای یافت نشد');
            }
            Sensor.updateMetricValueView(response.data);
    	})
    	.fail(function(error) {
        	BackendFramework.showNotif('error')
        })
    },

    getSensorGeoCodingInfo: function(lat, lon){
        $.ajax({
            url: 'https://maps.googleapis.com/maps/api/geocode/json',
            method: "GET",
            data: {
                latlng: lat + ',' + lon,
                key: Sensor.google_API_key,
                language: 'en'
            }
        })
        .done(function(response){
            // console.log(response)
            // var province_name = '';
            // var city_name = '';

            // for (var i = 0; i < response.results[1].address_components.length; i++) {
            //     switch(response.results[1].address_components[i].types[0]){
            //         case "administrative_area_level_1":
            //             province_name = response.results[1].address_components[i].long_name;
            //             break;

            //         case "administrative_area_level_2":
            //             city_name = response.results[1].address_components[i].long_name;
            //             break;

            //         default: break;
            //     }
            // }
            
            console.log(response.results[1].formatted_address);
        })
        .fail(function(error) {
        	BackendFramework.showNotif('error')
        })
    },

    getSensorAggregationLog: function(sensorId, func, termName, startTime, endTime, ranges){

        $.ajax({
            url: Routing.generate('panel_sensor_aggregation_log'),
            method: "POST",
            data: {
                sensorId: sensorId,
                func: func,
                termName: termName,
                startTime: startTime,
                endTime: endTime,
                ranges: ranges,
            },
        })
	    .done(function(response) {
            if(!response.data){
                BackendFramework.showNotif('info', 'داده ای یافت نشد');
            }
            Sensor.updateAggregationLog(response.data, termName);
    	})
    	.fail(function(error) {
        	BackendFramework.showNotif('error')
        })
    },



    // -------------------------------------------- update views
    updateStateFilter: function(data){
        $('.filters select[name=termName]').empty().append('<option value="">مشخصه</option>')
        $('.filters input[name=sensorId]').val(data.device_id);

        for ( property in data.state ) {
            $('.filters select[name=termName]').append('<option value="'+property+'">'+Translator.trans("label."+property, {}, "labels")+'</>')
        }
    },

    updateLatesLogView: function(data){
        
        $('#sensor-latest-log').empty();

        date = moment(data.time).format('jYYYY/jM/jD HH:mm:ss');
        date_element = '<div style="direction:ltr">'+ date +'</div>'
        $('#sensor-latest-log').parents('.portlet').find('.portlet-title .actions').empty().append(date_element);

        for ( property in data.state ) {
            $('#sensor-metric-value-filters select[name=termName]').append('<option <option value="'+property+'">'+Translator.trans("label."+property, {}, "labels")+'</option>>')
            var stateItem = 
                '<div class="state-item">' +
                    '<div class="key">'+ Translator.trans("label."+property, {}, "labels") +'</div>'+
                    '<div class="value">'+ BackendFramework.toFaNumber(data.state[property]) +'</div>'+
                '</div>'
            ;

            $('#sensor-latest-log').append(stateItem);
        }
    },

    updateSpecView: function(data){
        $('#sensor-spec .row').empty();
        var specs_element = 
            '<div class="col-md-6">' +
                '<table class="table table-bordered table-striped"><tbody>' +
                    '<tr>'+
                        '<td>'+Translator.trans("label.sensor.device_id", {}, "labels")+'</td>'+
                        '<td>'+data.device_id+'</td>'+
                    '</tr>'+
                        '<tr>'+
                        '<td>'+Translator.trans("label.sensor.type", {}, "labels")+'</td>'+
                        '<td>'+data.type+'</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td>'+Translator.trans("label.sensor.brand", {}, "labels")+'</td>'+
                        '<td>'+data.brand+'</td>'+
                    '</tr>'+
                '</tbody></table>'+
            '</div>'

        var spec_attributes_element = 
            '<div class="col-md-6">' +
                '<table class="table table-bordered table-striped"><tbody>'
        ;

        for ( property in data.attributes ) { 
            spec_attributes_element += 
                '<tr>'+
                    '<td>'+Translator.trans("label."+property, {}, "labels")+'</td>'+
                    '<td>'+data.attributes[property]+'</td>'+
                '</tr>'
            ;
        }

        spec_attributes_element += '</tbody></table></div>'
        
        $('#sensor-spec .row').append(specs_element);
        $('#sensor-spec .row').append(spec_attributes_element);
        
    },

    updateMetricValueView: function(data){
        var key = $('select[name=termName]').val();
        var stateItem

        if(typeof data == 'undefined'){
            stateItem = Sensor.labels.no_result;
        }
        else{
            stateItem = 
                '<div class="state-item large">' +
                    '<div class="key">'+ Translator.trans("label."+key, {}, "labels") +'</div>'+
                    '<div class="value">'+ (data).toFixed(2) +'</div>'+
                '</div>'
            ;
        }
        
        $('#sensor-metric-value .result').empty().append(stateItem);
    },

    updateAggregationLog: function(data, termName){
        var labels = data.map(item => {return item.key})
        var values = data.map(item => {return item.value.value})

        if(Sensor.aggregationLogChart != null){
            Sensor.aggregationLogChart.destroy();
        }

        var ctx = $("#aggregation_log");
        Sensor.aggregationLogChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: Translator.trans("label."+termName, {}, "labels"),
                    data: values,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    }
}