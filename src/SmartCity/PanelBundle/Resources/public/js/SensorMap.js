SensorMap = {

    map: null,
    markers: null,
    imagesSource: '/bundles/smartcitypanel/images/map/',

	init: function(){
        this.initMap();
        // this.initSidebar();
        this.search();
    },
    
    initMap: function(){
        
        // init map and tiles
        SensorMap.map = L.map('sensormap', {
            center: [32.4356, 53.9209],
            zoom: 12, // 5
            minZoom: 4,
            maxzoom: 12
        });

        // http://{s}.tile.osm.org/{z}/{x}/{y}.png
        var tiles = L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoicG9vdHJpYSIsImEiOiJjajZiOGJ1amsxZXgzMndvOThvZTJ1bnY3In0.hcwyouLNbyr6Qsl4-nmpsg', {
            id: 'mapbox.streets',
        }).addTo(SensorMap.map);
        

        // init map listeners
        SensorMap.map.on('load', function(e){SensorMap.search()});
        SensorMap.map.on('zoomend dragend',function(e){SensorMap.search();});


        // init marker clustering
        SensorMap.markers = L.markerClusterGroup({
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
            return new L.DivIcon({ html: '<div class=cluster-icon leaflet-zoom-animated leaflet-clickable" tabindex="0"><div><span>'+markerCount+'</span></div></div>' });
            }
        });
        SensorMap.map.addLayer(SensorMap.markers);
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
        var bound = SensorMap.map.getBounds();

        var boundObject = {
            "top_left_lat": bound.getNorthWest().wrap().lat,
            "top_left_lon": bound.getNorthWest().wrap().lng,
            "bottom_right_lat": bound.getSouthEast().wrap().lat,
            "bottom_right_lon": bound.getSouthEast().wrap().lng,
        }

        var mapZoom = SensorMap.map.getZoom();        
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

    search: function() {
        map_params = this.getMapParams();

        $.ajax({
            url: Routing.generate('panel_sensor_map_cluster'),
            method: "POST",
            data: map_params,
        })
	    .done(function(response) {
            SensorMap.markers.clearLayers();
	        SensorMap.makePoints(response.markers.buckets);
    	})
    	.fail(function(error) {
        	BackendFramework.showNotif('error')
    	});
    },

    /* This will add all the clusters as returned by the elastic server.*/ 
    makePoints: function(clusters){
        points = {};
        var markerList = [];
        clusters.forEach(function(cluster, index){
            
            var center = geohash.decode(cluster.key);//elastic return a geohas so need to change it into lat/lon
            var specs = cluster.device.buckets[0].key.split('|');
            var marker_html = '<div data-id="'+specs[0]+'" class=cluster-icon leaflet-zoom-animated leaflet-clickable" tabindex="0"><div><span>'+cluster.doc_count+'</span></div></div>';


            if (cluster.doc_count == 1){                
                marker_html = '<div data-id="'+specs[0]+'" class="marker-image"><img width="32px" src="'+SensorMap.imagesSource + specs[1]+'.png"></div>';
            }
            
            
            var marker_icon = L.divIcon({ html: marker_html });
            var marker = L.marker(new L.LatLng(center.latitude, center.longitude), { 
                icon: marker_icon 
            });

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
                SensorMap.getSensorLog(sensorId, btn);
            });

            marker.bindPopup(popupContainer[0]);

            marker.count = cluster.doc_count;
            markerList.push(marker);
        });
        SensorMap.markers.addLayers(markerList);
    },

    getSensorLog: function(sensorId, btn){
        var btn_label = btn.text();
        btn.html(BackendFramework.loadingGif);

        $.ajax({
            url: Routing.generate('panel_sensor_log'),
            method: "POST",
            data: {
                sensorId: sensorId
            },
        })
	    .done(function(response) {
            console.log(response);
    	})
    	.fail(function(error) {
        	BackendFramework.showNotif('error')
        })
        .always(function(){
            setTimeout(function(){
                btn.html(btn_label)
            }, 2000)
        })
    }
}