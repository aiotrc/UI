Monitoring = {

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
        Monitoring.map = L.map('mapid', {
            center: [32.4356, 53.9209],
            zoom: 10, // 5
            minZoom: 4,
            maxzoom: 12
        });

        // http://{s}.tile.osm.org/{z}/{x}/{y}.png
        var tiles = L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoicG9vdHJpYSIsImEiOiJjajZiOGJ1amsxZXgzMndvOThvZTJ1bnY3In0.hcwyouLNbyr6Qsl4-nmpsg', {
            id: 'mapbox.streets',
        }).addTo(Monitoring.map);
        

        // init map listeners
        Monitoring.map.on('load', function(e){Monitoring.search()});
        Monitoring.map.on('zoomend dragend',function(e){Monitoring.search();});


        // init marker clustering
        Monitoring.markers = L.markerClusterGroup({
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
        Monitoring.map.addLayer(Monitoring.markers);
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
        var bound = Monitoring.map.getBounds();

        var bound_object = {
            "top_left_lat": bound.getNorthWest().wrap().lat,
            "top_left_lon": bound.getNorthWest().wrap().lng,
            "bottom_right_lat": bound.getSouthEast().wrap().lat,
            "bottom_right_lon": bound.getSouthEast().wrap().lng,
        }

        var mapZoom = Monitoring.map.getZoom();        
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
            'bound': bound_object,
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
            Monitoring.markers.clearLayers();
	        Monitoring.makePoints(response.markers.buckets);
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
                marker_html = '<div data-id="'+specs[0]+'" class="marker-image"><img width="32px" src="'+Monitoring.imagesSource + specs[1]+'.png"></div>';
            }
            
            
            var marker_icon = L.divIcon({ html: marker_html });
            var marker = L.marker(new L.LatLng(center.latitude, center.longitude), { 
                icon: marker_icon 
            });

            if (cluster.doc_count == 1){                
                marker.on('click', function(){ Monitoring.getSensorDetail(specs[0]) });
            }

            marker.bindPopup(''+cluster.doc_count);

            marker.count = cluster.doc_count;
            markerList.push(marker);
        });
        Monitoring.markers.addLayers(markerList);
    },

    getSensorDetail: function(sensorId){
        $.ajax({
            url: Routing.generate('panel_sensor_spec'),
            method: "POST",
            data: {
                sensorId: sensorId
            },
        })
	    .done(function(response) {
            var sensor = response
            $('#sensor-detail').empty().addClass('visible');

            detail_body = 
                '<div class="body">' +
                    '<img class="icon" src="'+Monitoring.imagesSource + sensor.type+'.png">' +
                    '<div>شناسه : ' + sensor.device_id + '</div>' +
                    '<div>نوع دستگاه : ' + sensor.type + '</div>' +
                    '<div>شرکت سازنده : ' + sensor.brand + '</div>' +
                    '<a class="btn blue-hoki">مشاهده اطلاعات</a>'
                '</div>'
            ;

            $('#sensor-detail').append(detail_body);

            console.log(response);
    	})
    	.fail(function(error) {
        	BackendFramework.showNotif('error')
    	});
    }
}